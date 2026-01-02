<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Zkteco_lib
{

    public $ip;
    public $port;
    public $socket;
    public $session_id = 0;
    public $reply_id = 0;
    public $data_recv = '';
    public $userdata = array();
    public $attendancedata = array();
    public $error = '';
    public $last_error_code = 0;

    // Constants from ZK library
    const USHRT_MAX = 65535;
    const CMD_CONNECT = 1000;
    const CMD_EXIT = 1001;
    const CMD_AUTH = 1102;
    const CMD_USERTEMP_RRQ = 9;
    const CMD_ATTLOG_RRQ = 13;
    const CMD_PREPARE_DATA = 1500;
    const CMD_DATA = 1501;
    const CMD_REC_RRQ = 1503;
    const CMD_ACK_OK = 2000;
    const CMD_ACK_UNAUTH = 2005;

    const MACHINE_PREPARE_DATA_1 = 0x5050;
    const MACHINE_PREPARE_DATA_2 = 0x7d82;

    public function __construct($ip = '192.168.1.201', $port = 4370)
    {
        if (is_array($ip)) {
            $this->ip = isset($ip['ip']) ? $ip['ip'] : '192.168.1.201';
            $this->port = isset($ip['port']) ? $ip['port'] : 4370;
        } else {
            $this->ip = $ip;
            $this->port = $port;
        }
    }

    public function connect()
    {
        // Reset error state
        $this->error = '';
        $this->last_error_code = 0;
        $this->debug_logs = [];

        // Validate IP address
        if (!filter_var($this->ip, FILTER_VALIDATE_IP)) {
            $this->error = "Invalid IP address: {$this->ip}";
            return false;
        }

        // Validate port
        if ($this->port < 1 || $this->port > 65535) {
            $this->error = "Invalid port number: {$this->port}";
            return false;
        }

        // Set socket timeout
        $timeout = 10; // Increased timeout for better reliability

        $this->socket = @fsockopen($this->ip, $this->port, $errno, $errstr, $timeout);

        if (!$this->socket) {
            $this->error = "Connection failed: $errstr ($errno)";
            $this->debug_logs[] = "Failed to open socket to {$this->ip}:{$this->port}";
            return false;
        }

        // Set stream timeout
        stream_set_timeout($this->socket, $timeout);

        $this->session_id = 0;
        $this->reply_id = self::USHRT_MAX - 1;
        $this->data_recv = '';

        $this->debug_logs[] = "Socket connected to {$this->ip}:{$this->port}";

        // CMD_CONNECT = 1000
        $result = $this->command(self::CMD_CONNECT, '', 5);

        if ($result === false && $this->last_error_code == self::CMD_ACK_UNAUTH) {
            // Device requires authentication
            $this->debug_logs[] = "Device requires authentication, attempting auth...";
            $command_string = $this->makeCommKey(0, $this->session_id, 50);
            $auth_result = $this->command(self::CMD_AUTH, $command_string, 5);

            if ($auth_result) {
                $this->debug_logs[] = "Authentication successful";
            } else {
                $this->debug_logs[] = "Authentication failed";
            }

            return $auth_result;
        }

        if ($result) {
            $this->debug_logs[] = "Connection established successfully";
        } else {
            $this->debug_logs[] = "Connection handshake failed";
        }

        return $result;
    }

    public function disconnect()
    {
        if ($this->socket) {
            try {
                $this->command(self::CMD_EXIT, '', 5);
            } catch (Exception $e) {
                $this->debug_logs[] = "Error during disconnect command: " . $e->getMessage();
            }

            @fclose($this->socket);
            $this->socket = null;
            $this->debug_logs[] = "Disconnected from device";
        }
        return true;
    }

    private function cleanString($str)
    {
        $original_hex = bin2hex($str);

        // FIX: Truncate at first null byte to remove binary garbage/padding
        $null_pos = strpos($str, "\0");
        if ($null_pos !== false) {
            $str = substr($str, 0, $null_pos);
        }

        // Convert encoding
        $converted = false;
        if (function_exists('mb_convert_encoding')) {
            // Try to detect or force GBK/GB2312 to UTF-8
            $str = @mb_convert_encoding($str, 'UTF-8', 'GBK, GB2312, ASCII');
            $converted = true;
        } elseif (function_exists('iconv')) {
            $str = @iconv('GBK', 'UTF-8//IGNORE', $str);
            $converted = true;
        }

        // Trim whitespace and non-printable characters
        // Remove control characters except spaces
        $str = preg_replace('/[\x00-\x1F\x7F]/', '', $str);

        $final = trim($str);

        // Log interesting cases for debugging
        // if (count($this->debug_logs) < 20 && $original_hex !== bin2hex($final) && $final !== '') {
        //     $this->debug_logs[] = "CleanString: Hex: $original_hex -> Final: $final";
        // }

        return $final;
    }

    private function cleanUserID($str)
    {
        // First clean standard stuff
        $str = $this->cleanString($str);

        // For User ID, we enforce alphanumeric only to remove garbage
        // This fixes issues like "??. ( 46" becoming "46"
        $cleaned = preg_replace('/[^a-zA-Z0-9]/', '', $str);

        if ($str !== $cleaned && count($this->debug_logs) < 25) {
            // $this->debug_logs[] = "CleanUserID: '$str' -> '$cleaned'";
        }

        return $cleaned;
    }

    public function getUser()
    {
        $this->debug_logs[] = "Fetching users from device...";

        // Try buffered read first (more reliable for some devices)
        $data = $this->readWithBuffer(self::CMD_USERTEMP_RRQ);

        // If buffered read fails or returns empty, try direct command
        if ($data === false || strlen($data) == 0) {
            $this->debug_logs[] = "Buffered read failed or empty, trying direct command...";
            $result = $this->command(self::CMD_USERTEMP_RRQ, '', 1024 * 1024);
            if ($result && strlen($this->data_recv) > 0) {
                $data = $this->data_recv;
            } else {
                $this->debug_logs[] = "Direct command also failed. Error: " . $this->error;
                return [];
            }
        }

        $users = [];
        $this->debug_logs[] = "Received " . strlen($data) . " bytes of user data";

        if (strlen($data) < 72) {
            $this->debug_logs[] = "Data too short (" . strlen($data) . " bytes), expected at least 72 bytes";
            return $users;
        }

        // Skip size header if present
        if (strlen($data) > 4) {
            $size_check = unpack('V', substr($data, 0, 4))[1];

            // FIX: Determine header size based on alignment
            $header_size = 0;
            if ((strlen($data) - 8) % 72 == 0) {
                $header_size = 8;
            } elseif ((strlen($data) - 4) % 72 == 0) {
                $header_size = 4;
            } elseif ($size_check > 0 && $size_check < strlen($data)) {
                // Fallback to old logic
                $header_size = 4;
            }

            if ($header_size > 0) {
                $this->debug_logs[] = "Skipping size header: " . $header_size;
                $data = substr($data, $header_size);
            }
        }

        $record_size = 72;
        $len = strlen($data);
        $this->debug_logs[] = "Parsing user data, length: " . $len . ", record size: " . $record_size;

        for ($i = 0; $i < $len; $i += $record_size) {
            $u = substr($data, $i, $record_size);
            if (strlen($u) < $record_size)
                break;

            try {
                $uid_internal = unpack('v', substr($u, 0, 2))[1];
                $name = substr($u, 11, 24);
                $userid_string = substr($u, 48, 10);

                $name = $this->cleanString($name);
                $userid_string = $this->cleanUserID($userid_string);

                if (empty($userid_string))
                    $userid_string = (string) $uid_internal;

                if ($uid_internal > 0) {
                    $users[$uid_internal] = [
                        'userid' => $userid_string,
                        'name' => $name ?: 'User ' . $uid_internal,
                        'cardno' => 0
                    ];
                }
            } catch (Exception $e) {
                $this->debug_logs[] = "Error parsing user record at offset $i: " . $e->getMessage();
                continue;
            }
        }

        $this->debug_logs[] = "Successfully parsed " . count($users) . " users";
        return $users;
    }

    public $debug_logs = [];

    public function getAttendance()
    {
        // Use buffered read for attendance logs
        $data = $this->readWithBuffer(self::CMD_ATTLOG_RRQ);

        if ($data === false) {
            $this->debug_logs[] = "readWithBuffer failed: " . $this->error;
            return [];
        }

        $logs = [];
        $this->debug_logs[] = "Received " . strlen($data) . " bytes from readWithBuffer";

        if (strlen($data) > 4) {
            // First 4 bytes are total size
            $total_size = unpack('V', substr($data, 0, 4))[1];
            $this->debug_logs[] = "Total size from header: " . $total_size;

            // FIX: Determine header size based on alignment
            // If removing 8 bytes makes it divisible by 40, then header is 8 bytes.
            $header_size = 4;
            if ((strlen($data) - 8) % 40 == 0) {
                $header_size = 8;
            } elseif ((strlen($data) - 4) % 40 == 0) {
                $header_size = 4;
            }

            $this->debug_logs[] = "Detected header size: " . $header_size;
            $data = substr($data, $header_size);

            $len = strlen($data);
            $record_size = 40;

            // Simple heuristic
            if ($len > 0) {
                if ($len % 40 == 0) {
                    $record_size = 40;
                } elseif ($len % 16 == 0) {
                    $record_size = 16;
                } elseif ($len % 8 == 0) {
                    $record_size = 8;
                }
            }
            $this->debug_logs[] = "Detected record size: " . $record_size;

            for ($i = 0; $i < $len; $i += $record_size) {
                $u = substr($data, $i, $record_size);
                if (strlen($u) < $record_size)
                    break;

                $row = [];

                if ($record_size == 40) {
                    // Format: vuid(2) + a24user_id(24) + Cstatus(1) + a4time(4) + Cpunch(1) + a8space(8) = 40
                    $rec = unpack('vuid/a24user_id/Cstatus/a4time/Cpunch/a8space', $u);
                    $row['uid'] = $rec['uid'];
                    $row['id'] = $this->cleanUserID($rec['user_id']);

                    // Fallback: If ID is empty, use the internal UID
                    if (empty($row['id']) && $rec['uid'] > 0) {
                        $row['id'] = (string) $rec['uid'];
                    }

                    $row['state'] = $rec['status'];

                    // FIX: Timestamp is at offset 32, not 27
                    // Format seems to be: uid(2) + user_id(24) + status(1) + reserved(4) + punch(1) + time(4) + reserved(4)
                    $time_bytes = substr($u, 32, 4);
                    $row['timestamp'] = $this->decodeTime($time_bytes);

                    // Debug: Log if timestamp is invalid (null)
                    if ($row['timestamp'] === null && count($this->debug_logs) < 20) {
                        $this->debug_logs[] = "Invalid Timestamp Record " . count($logs) . " Hex: " . bin2hex($u);
                    }

                    $row['type'] = $rec['punch'];
                } elseif ($record_size == 16) {
                    // Format: Vuser_id(4) + a4time(4) + Cstatus(1) + Cpunch(1) + a2reserved(2) + Vworkcode(4) = 16
                    $rec = unpack('Vuser_id/a4time/Cstatus/Cpunch/a2reserved/Vworkcode', $u);
                    $row['uid'] = $rec['user_id'];
                    $row['id'] = $rec['user_id']; // Usually numeric here
                    $row['state'] = $rec['status'];
                    // Extract time bytes directly (4 bytes starting at offset 4)
                    $time_bytes = substr($u, 4, 4);
                    $row['timestamp'] = $this->decodeTime($time_bytes);
                    $row['type'] = $rec['punch'];
                } elseif ($record_size == 8) {
                    // Format: vuid(2) + Cstatus(1) + a4time(4) + Cpunch(1) = 8
                    $rec = unpack('vuid/Cstatus/a4time/Cpunch', $u);
                    $row['uid'] = $rec['uid'];
                    $row['id'] = $rec['uid']; // Usually numeric here
                    $row['state'] = $rec['status'];
                    // Extract time bytes directly (4 bytes starting at offset 3: 2+1)
                    $time_bytes = substr($u, 3, 4);
                    $row['timestamp'] = $this->decodeTime($time_bytes);
                    $row['type'] = $rec['punch'];
                }

                $logs[] = $row;
            }
        }
        $this->debug_logs[] = "Parsed " . count($logs) . " attendance records";
        return $logs;
    }

    private function readWithBuffer($command, $fct = 0, $ext = 0)
    {
        // Command 1503 (CMD_REC_RRQ) - buffered read
        $command_string = pack('cvVV', 1, $command, $fct, $ext);
        $result = $this->command(self::CMD_REC_RRQ, $command_string, 1024);

        if (!$result) {
            return false;
        }

        // If response is CMD_DATA, return the data directly
        if ($this->last_error_code == self::CMD_DATA) {
            $this->debug_logs[] = "Got CMD_DATA directly with " . strlen($this->data_recv) . " bytes";
            // Skip the first 4 bytes (size header) if present
            if (strlen($this->data_recv) > 4) {
                $size_check = unpack('V', substr($this->data_recv, 0, 4))[1];
                if ($size_check > 0 && $size_check < strlen($this->data_recv)) {
                    // Hassize header, skip it
                    return substr($this->data_recv, 4);
                }
            }
            return $this->data_recv;
        }

        // Otherwise, expect CMD_PREPARE_DATA with size info
        // Then read chunks
        if (strlen($this->data_recv) < 4) {
            $this->error = "Invalid response from readWithBuffer";
            return false;
        }

        $size = unpack('V', substr($this->data_recv, 1, 4))[1];
        $this->debug_logs[] = "Total data size to read: " . $size;

        $MAX_CHUNK = 0xFFc0;
        $remain = $size % $MAX_CHUNK;
        $packets = ($size - $remain) / $MAX_CHUNK;

        $data = [];
        $start = 0;

        for ($i = 0; $i < $packets; $i++) {
            $chunk = $this->readChunk($start, $MAX_CHUNK);
            if ($chunk === false) {
                $this->debug_logs[] = "Failed reading chunk $i, will return partial data";
                break; // Stop reading but keep what we have
            }
            $data[] = $chunk;
            $start += $MAX_CHUNK;
        }

        if ($remain > 0) {
            $chunk = $this->readChunk($start, $remain);
            if ($chunk !== false) {
                $data[] = $chunk;
            } else {
                $this->debug_logs[] = "Failed reading final chunk";
            }
        }

        @$this->freeData();

        return implode('', $data);
    }

    private function readChunk($start, $size)
    {
        // CMD_READ_BUFFER (1504) - read a chunk of data
        $command_string = pack('VV', $start, $size);
        $result = $this->command(1504, $command_string, $size + 1024);

        if (!$result) {
            return false;
        }

        return $this->data_recv;
    }

    private function freeData()
    {
        // CMD_FREE_DATA (1502) - tell device we're done reading
        $this->command(1502, '', 1024);
    }

    private function decodeTime($t)
    {
        // Ensure we have exactly 4 bytes
        if (strlen($t) < 4) {
            // $this->debug_logs[] = "Warning: Time field has less than 4 bytes: " . strlen($t);
            return null;
        }

        // Unpack as little-endian unsigned long (V)
        $unpacked = unpack('V', $t);
        if (!isset($unpacked[1])) {
            // $this->debug_logs[] = "Warning: Failed to unpack time field";
            return null;
        }

        $t = $unpacked[1];

        // Handle zero or invalid timestamp
        if ($t == 0 || $t > 4294967295) {
            // Too many logs, suppress unless needed
            // $this->debug_logs[] = "Warning: Invalid timestamp value: " . $t;
            return null;
        }

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

        // Validate decoded date
        if ($year < 2000 || $year > 2100 || $month < 1 || $month > 12 || $day < 1 || $day > 31) {
            // $this->debug_logs[] = "Warning: Invalid decoded date: {$year}-{$month}-{$day}";
            return null;
        }

        return sprintf('%04d-%02d-%02d %02d:%02d:%02d', $year, $month, $day, $hour, $minute, $second);
    }

    // --- Protocol Helpers ---

    private function command($command, $command_string, $read_size)
    {
        if (!$this->socket) {
            $this->error = "Socket not connected";
            return false;
        }

        $chksum = 0;
        $session_id = $this->session_id;
        $reply_id = $this->reply_id;

        $buf = $this->createHeader($command, $command_string, $session_id, $reply_id);

        // Wrap in TCP Header
        $tcp_buf = $this->createTCPHeader($buf);

        // Set timeout
        stream_set_timeout($this->socket, 10);

        // Write command
        $written = @fwrite($this->socket, $tcp_buf);
        if ($written === false) {
            $this->error = "Failed to write to socket";
            return false;
        }

        $this->data_recv = '';

        // Read TCP Header first (8 bytes)
        $tcp_header = @fread($this->socket, 8);
        if ($tcp_header === false || strlen($tcp_header) < 8) {
            $this->error = "Failed to read TCP header";
            $this->debug_logs[] = "TCP header read failed or incomplete";
            return false;
        }

        // Unpack TCP Header to get length
        $tcp_h = unpack('v2code/Vlength', $tcp_header);
        $packet_length = $tcp_h['length'];

        if ($packet_length > 10 * 1024 * 1024) { // 10MB limit
            $this->error = "Packet size too large: {$packet_length} bytes";
            return false;
        }

        // Read the rest of the packet
        $total_read = 0;
        $max_attempts = 1000; // Prevent infinite loop
        $attempts = 0;

        while ($total_read < $packet_length && $attempts < $max_attempts) {
            $chunk = @fread($this->socket, min($packet_length - $total_read, 8192));
            if ($chunk === false || strlen($chunk) == 0) {
                // Check for timeout
                $meta = stream_get_meta_data($this->socket);
                if ($meta['timed_out']) {
                    $this->error = "Read timeout while receiving data";
                    $this->debug_logs[] = "Timeout after reading {$total_read} of {$packet_length} bytes";
                    return false;
                }
                break;
            }
            $this->data_recv .= $chunk;
            $total_read += strlen($chunk);
            $attempts++;
        }

        if (strlen($this->data_recv) >= 8) {
            // Unpack ZK Header
            $u = unpack('v4', substr($this->data_recv, 0, 8));

            $this->session_id = $u[3];
            $this->reply_id = $u[4];
            $response_code = $u[1];
            $this->last_error_code = $response_code;

            // Update data_recv to contain only the payload (strip 8 byte header)
            $this->data_recv = substr($this->data_recv, 8);

            if ($response_code == self::CMD_ACK_OK || $response_code == self::CMD_DATA || $response_code == self::CMD_PREPARE_DATA) {
                return true;
            } else {
                $error_msg = "Device responded with error code: {$response_code}";
                $this->error = $error_msg;
                $this->debug_logs[] = $error_msg;
                return false;
            }
        }

        $this->error = "No valid ZK data received. Received " . strlen($this->data_recv) . " bytes";
        $this->debug_logs[] = "Invalid response: expected at least 8 bytes, got " . strlen($this->data_recv);
        return false;
    }

    private function createHeader($command, $command_string, $session_id, $reply_id)
    {
        $buf = pack('vvvv', $command, 0, $session_id, $reply_id) . $command_string;

        $checksum = $this->createChecksum($buf);

        $reply_id += 1;
        if ($reply_id >= self::USHRT_MAX)
            $reply_id -= self::USHRT_MAX;
        $this->reply_id = $reply_id;

        return pack('vvvv', $command, $checksum, $session_id, $reply_id) . $command_string;
    }

    private function createTCPHeader($packet)
    {
        $length = strlen($packet);
        return pack('vvV', self::MACHINE_PREPARE_DATA_1, self::MACHINE_PREPARE_DATA_2, $length) . $packet;
    }

    private function createChecksum($p)
    {
        $l = strlen($p);
        $checksum = 0;
        $i = 0;

        while ($l > 1) {
            $u = unpack('v', substr($p, $i, 2));
            $checksum += $u[1];

            $i += 2;
            $l -= 2;

            if ($checksum > self::USHRT_MAX) {
                $checksum -= self::USHRT_MAX;
            }
        }

        if ($l > 0) {
            $checksum += ord($p[$i]);
        }

        while ($checksum > self::USHRT_MAX) {
            $checksum -= self::USHRT_MAX;
        }

        $checksum = ~$checksum;

        while ($checksum < 0) {
            $checksum += self::USHRT_MAX;
        }

        return $checksum;
    }

    private function makeCommKey($key, $session_id, $ticks = 50)
    {
        $key = intval($key);
        $session_id = intval($session_id);
        $k = 0;
        for ($i = 0; $i < 32; $i++) {
            if (($key & (1 << $i))) {
                $k = ($k << 1) | 1;
            } else {
                $k = $k << 1;
            }
        }
        $k += $session_id;

        $b = pack('V', $k);
        $b = array_values(unpack('C*', $b));

        $b[0] = $b[0] ^ ord('Z');
        $b[1] = $b[1] ^ ord('K');
        $b[2] = $b[2] ^ ord('S');
        $b[3] = $b[3] ^ ord('O');

        $k_str = pack('C*', ...$b);

        $shorts = array_values(unpack('v*', $k_str));

        $k_str = pack('vv', $shorts[1], $shorts[0]);

        $B = 0xff & $ticks;
        $b = array_values(unpack('C*', $k_str));

        $b[0] = $b[0] ^ $B;
        $b[1] = $b[1] ^ $B;
        $b[2] = $B;
        $b[3] = $b[3] ^ $B;

        return pack('C*', ...$b);
    }
}
