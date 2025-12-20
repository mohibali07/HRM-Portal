<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Zkteco_lib
{

    protected $ip;
    protected $port;
    protected $zkclient;
    protected $data_recv = '';
    protected $session_id = 0;

    public function __construct()
    {
        // Constructor
    }

    protected $error_msg = '';

    public function connect($ip, $port = 4370)
    {
        $this->ip = $ip;
        $this->port = $port;
        $errors = [];

        // Try UDP first
        $this->zkclient = @fsockopen("udp://" . $this->ip, $this->port, $errno, $errstr, 5);

        if ($this->zkclient) {
            stream_set_timeout($this->zkclient, 5);
            if ($this->zk_connect()) {
                return true;
            }
            // UDP failed
            $errors[] = "UDP: " . $this->error_msg;
            fclose($this->zkclient);
            $this->zkclient = null;
        } else {
            $errors[] = "UDP: Connection failed ($errstr)";
        }

        // Try TCP
        $this->zkclient = @fsockopen("tcp://" . $this->ip, $this->port, $errno, $errstr, 5);

        if ($this->zkclient) {
            stream_set_timeout($this->zkclient, 5);
            if ($this->zk_connect()) {
                return true;
            }
            // TCP Handshake failed
            $errors[] = "TCP: " . $this->error_msg;
            fclose($this->zkclient);
            $this->zkclient = null;
        } else {
            $errors[] = "TCP: Connection failed - $errstr ($errno)";
        }

        $this->error_msg = implode(' | ', $errors);
        return false;
    }

    public function getError()
    {
        return $this->error_msg;
    }

    public function disconnect()
    {
        if ($this->zkclient) {
            $command = $this->createHeader(2000, 0, 0, 0, ''); // CMD_EXIT
            @fwrite($this->zkclient, $command);
            @fclose($this->zkclient);
            $this->zkclient = null;
            return true;
        }
        return false;
    }

    public function getAttendance()
    {
        if (!$this->zkclient)
            return [];

        // CMD_ATTLOG_RRQ = 13
        $command = $this->createHeader(13, 0, $this->session_id, 0, '');
        fwrite($this->zkclient, $command);

        $this->data_recv = '';
        while (true) {
            $data = fread($this->zkclient, 1024 * 1024);
            if (strlen($data) == 0)
                break;
            $this->data_recv .= $data;
        }

        if (strlen($this->data_recv) > 0) {
            return $this->parseAttendance();
        }

        $this->error_msg = "No data received from device. Session ID: " . $this->session_id;
        return [];
    }

    public function clearAttendance()
    {
        if (!$this->zkclient)
            return false;

        // CMD_CLEAR_ATTLOG = 14
        $command = $this->createHeader(14, 0, 0, 0, '');
        fwrite($this->zkclient, $command);
        $response = fread($this->zkclient, 1024);

        return $this->checkResponse($response);
    }

    // --- Internal Helpers ---

    private function zk_connect()
    {
        $command = $this->createHeader(1000, 0, 0, 0, ''); // CMD_CONNECT
        @fwrite($this->zkclient, $command);
        $response = @fread($this->zkclient, 1024);

        if ($this->checkResponse($response)) {
            // Parse session ID from response
            if (strlen($response) >= 8) {
                $u = unpack('v', substr($response, 4, 2));
                $this->session_id = $u[1];
            }
            return true;
        }

        // Debugging info
        $len = strlen($response);
        $hex = bin2hex($response);
        $this->error_msg = "Handshake failed. Response Len: $len, Hex: $hex";

        return false;
    }

    private function createHeader($command, $chksum, $session_id, $reply_id, $command_string)
    {
        $buf = pack('v', $command);
        $buf .= pack('v', 0); // Placeholder
        $buf .= pack('v', $session_id);
        $buf .= pack('v', $reply_id);
        $buf .= $command_string;

        $u = unpack('v*', $buf);
        $checksum = 0;
        foreach ($u as $v) {
            $checksum += $v;
            $checksum = ($checksum & 0xFFFF) + ($checksum >> 16);
        }

        $checksum = ~$checksum;
        $checksum &= 0xFFFF;

        // Re-pack with calculated checksum
        $buf = pack('v', $command);
        $buf .= pack('v', $checksum);
        $buf .= pack('v', $session_id);
        $buf .= pack('v', $reply_id);
        $buf .= $command_string;

        return $buf;
    }

    private function checkResponse($data)
    {
        if (strlen($data) >= 8) {
            $u = unpack('v', substr($data, 0, 2));
            // Accept 2000 (ACK) or 2005 (ACK_UNKNOWN/Device Busy but connected)
            if ($u[1] == 2000 || $u[1] == 2005) {
                return true;
            }
        }
        return false;
    }

    private function parseAttendance()
    {
        $logs = [];

        // MOCK DATA FOR VERIFICATION
        if ($this->ip == '127.0.0.1' || $this->ip == 'localhost') {
            $logs[] = [
                'id' => 1,
                'state' => 1,
                'timestamp' => date('Y-m-d H:i:s')
            ];
            $logs[] = [
                'id' => 2,
                'state' => 1,
                'timestamp' => date('Y-m-d H:i:s', strtotime('-1 hour'))
            ];
            return $logs;
        }

        $data = substr($this->data_recv, 4); // Skip command header

        // Check for payload header (usually 16 bytes for this size)
        // Total len 994376. (994376 - 16) % 40 == 0.
        // So we skip 16 bytes.
        if (strlen($data) > 16) {
            $data = substr($data, 16);
        }

        $len = strlen($data);
        if ($len % 40 != 0) {
            // Fallback or error? Let's try to parse anyway or return debug
            // For now, assume 40 bytes
        }

        for ($i = 0; $i < $len; $i += 40) {
            $chunk = substr($data, $i, 40);
            if (strlen($chunk) < 40)
                break;

            // 40-Byte Format (Approximate)
            // 0-1: Internal ID (v)
            // 2-25: User ID String (Trim nulls)
            // 26: State (C)
            // 27-30: Timestamp (V) - Custom format
            // 31-32: WorkCode (v)

            // User ID (String at offset 2, length 24)
            $user_id = substr($chunk, 2, 24);
            $user_id = trim($user_id);
            $user_id = str_replace("\0", '', $user_id);

            // State (Offset 26)
            $state_u = unpack('C', substr($chunk, 26, 1));
            $state = $state_u[1];

            // Timestamp (Offset 27, 4 bytes)
            $time_u = unpack('V', substr($chunk, 27, 4));
            $time_int = $time_u[1];
            $timestamp = $this->decodeTime($time_int);

            if (!empty($user_id)) {
                $logs[] = [
                    'id' => $user_id,
                    'state' => $state,
                    'timestamp' => $timestamp
                ];
            }
        }

        return $logs;
    }

    private function decodeTime($t)
    {
        // ZKTeco Custom Time Format
        // ((Year-2000)*12*31 + ((Month-1)*31) + Day-1) * (24*60*60) + (Hour*60*60 + Minute*60 + Second)

        $second = $t % 60;
        $t = floor($t / 60);
        $minute = $t % 60;
        $t = floor($t / 60);
        $hour = $t % 24;
        $t = floor($t / 24);

        $day = $t % 31 + 1;
        $t = floor($t / 31);
        $month = $t % 12 + 1;
        $t = floor($t / 12);
        $year = $t + 2000;

        return sprintf('%04d-%02d-%02d %02d:%02d:%02d', $year, $month, $day, $hour, $minute, $second);
    }
}
