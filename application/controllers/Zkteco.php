<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Zkteco extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->helper('html');
        $this->load->database();
        $this->load->library('form_validation');
        $this->load->model('Timesheet_model');
        $this->load->model('Employees_model');
        $this->load->model('Xin_model');
        $this->load->library('Zkteco_lib');

        // Check if user is logged in
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('');
        }

        // Auto-create table if not exists (for simplicity)
        $this->_check_db();
    }

    private function _check_db()
    {
        if (!$this->db->table_exists('xin_zkteco_settings')) {
            $sql = "CREATE TABLE `xin_zkteco_settings` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `device_ip` varchar(50) NOT NULL,
              `device_port` int(11) NOT NULL DEFAULT '4370',
              `last_sync` datetime DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
            $this->db->query($sql);

            // Insert default record
            $this->db->query("INSERT INTO `xin_zkteco_settings` (`device_ip`, `device_port`) VALUES ('192.168.1.201', '4370')");
        }
    }

    public function index()
    {
        $data['title'] = 'ZKTeco Integration';
        $data['breadcrumbs'] = 'ZKTeco Integration';
        $data['path_url'] = 'zkteco';

        $data['settings'] = $this->db->get('xin_zkteco_settings')->row();

        $data['subview'] = $this->load->view('zkteco/zkteco_list', $data, TRUE);
        $this->load->view('layout_main', $data); // Assuming layout_main is the main layout
    }

    public function save_settings()
    {
        if ($this->input->post('save_type') == 'edit') {
            $id = $this->input->post('id');
            $data = array(
                'device_ip' => $this->input->post('device_ip'),
                'device_port' => $this->input->post('device_port')
            );

            $this->db->where('id', $id);
            $this->db->update('xin_zkteco_settings', $data);

            $this->session->set_flashdata('message', 'Settings updated successfully.');
            redirect('zkteco');
        }
    }

    public function test_connection()
    {
        $ip = $this->input->post('device_ip');
        $port = $this->input->post('device_port');

        if ($this->zkteco_lib->connect($ip, $port)) {
            $this->zkteco_lib->disconnect();
            echo json_encode(array('status' => 'success', 'msg' => 'Connection Successful!'));
        } else {
            $error = $this->zkteco_lib->getError();
            echo json_encode(array('status' => 'error', 'msg' => 'Connection Failed! ' . $error));
        }
    }

    public function sync_attendance()
    {
        // Prevent timeout and memory issues for large data sync
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        try {
            $settings = $this->db->get('xin_zkteco_settings')->row();

            if ($this->zkteco_lib->connect($settings->device_ip, $settings->device_port)) {
                $attendance = $this->zkteco_lib->getAttendance();
                $this->zkteco_lib->disconnect();

                $count = 0;
                $new_records = [];
                if (!empty($attendance)) {
                    foreach ($attendance as $log) {
                        // $log should have 'id', 'state', 'timestamp'
                        $employee_id = $log['id'];
                        $timestamp = $log['timestamp'];

                        // Validate timestamp to prevent 1970 dates
                        if (strtotime($timestamp) < strtotime('2000-01-01')) {
                            continue;
                        }

                        $date = date('Y-m-d', strtotime($timestamp));
                        $time = date('H:i:s', strtotime($timestamp));

                        // Check if exists
                        $check = $this->Timesheet_model->check_existing_attendance($employee_id, $date, $time);
                        if ($check->num_rows() == 0) {
                            $new_records[] = array(
                                'employee_id' => $employee_id,
                                'attendance_date' => $date,
                                'clock_in' => $time,
                                'clock_out' => '',
                                'time_attendance_id' => '',
                                'attendance_status' => 'Present',
                                'clock_in_out' => '0'
                            );
                            $count++;
                        }

                        // Batch insert every 100 records
                        if (count($new_records) >= 100) {
                            $this->Timesheet_model->add_employee_attendance_batch($new_records);
                            $new_records = [];
                        }
                    }

                    // Insert remaining records
                    if (!empty($new_records)) {
                        $this->Timesheet_model->add_employee_attendance_batch($new_records);
                    }
                }

                // Update last sync
                $this->db->where('id', $settings->id);
                $this->db->update('xin_zkteco_settings', array('last_sync' => date('Y-m-d H:i:s')));

                echo json_encode(array('status' => 'success', 'msg' => "Synced $count records."));
            } else {
                $error = $this->zkteco_lib->getError();
                echo json_encode(array('status' => 'error', 'msg' => 'Connection Failed during sync. ' . $error));
            }
        } catch (Exception $e) {
            echo json_encode(array('status' => 'error', 'msg' => 'Sync Error: ' . $e->getMessage()));
        }
    }

    public function generate_attendance_report()
    {
        log_message('error', 'Zkteco: generate_attendance_report called');

        // Prevent timeout
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        // Start output buffering
        ob_start();

        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');

        log_message('error', "Zkteco: Dates received - Start: $start_date, End: $end_date");

        if (empty($start_date) || empty($end_date)) {
            ob_end_clean();
            echo json_encode(['status' => 'error', 'msg' => 'Please select both start and end dates.']);
            return;
        }

        // Create uploads directory if not exists
        $upload_path = FCPATH . 'uploads/attendance/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }

        $filename = 'attendance_report_' . $start_date . '_to_' . $end_date . '_' . time() . '.csv';
        $file_path = $upload_path . $filename;
        $file_url = base_url() . 'uploads/attendance/' . $filename;

        try {
            $settings = $this->db->get('xin_zkteco_settings')->row();
            log_message('error', "Zkteco: Settings loaded. IP: " . $settings->device_ip);

            // 1. Connect and Fetch Data using Zkteco_lib (Pure PHP)
            log_message('error', "Zkteco: Attempting connection...");
            if ($this->zkteco_lib->connect($settings->device_ip, $settings->device_port)) {
                log_message('error', "Zkteco: Connected.");

                // Fetch Users
                $zk_users = $this->zkteco_lib->getUser();
                log_message('error', "Zkteco: Users fetched. Count: " . count($zk_users));

                // Fetch Attendance
                $attendance = $this->zkteco_lib->getAttendance();
                log_message('error', "Zkteco: Attendance fetched. Count: " . count($attendance));

                $this->zkteco_lib->disconnect();

                if (empty($attendance)) {
                    ob_end_clean();
                    echo json_encode(['status' => 'error', 'msg' => 'No attendance data found on device.']);
                    return;
                }

                // Build User Map [id => name]
                $user_map = [];
                if (!empty($zk_users)) {
                    foreach ($zk_users as $uid => $name) {
                        // Zkteco_lib returns [uid => name] directly
                        $user_map[$uid] = $name;
                    }
                }

                // Process Attendance Data (User's Logic)
                $daily_data = [];

                foreach ($attendance as $log) {
                    // $log structure from Zkteco_lib: ['id' => ..., 'timestamp' => ..., 'state' => ...]
                    $timestamp_str = $log['timestamp'];
                    $timestamp = strtotime($timestamp_str);
                    $uid = $log['id'];

                    // Filter by Date Range
                    $log_date = date('Y-m-d', $timestamp);

                    if ($log_date >= $start_date && $log_date <= $end_date) {

                        $group_key = $uid . "_" . $log_date;

                        if (!isset($daily_data[$group_key])) {
                            // Try to find name, default to Unknown
                            $name = isset($user_map[$uid]) ? $user_map[$uid] : (isset($user_map[(int) $uid]) ? $user_map[(int) $uid] : "Unknown");

                            $daily_data[$group_key] = [
                                'id' => $uid,
                                'name' => $name,
                                'date' => $log_date,
                                'day' => date('l', $timestamp),
                                'punches' => []
                            ];
                        }
                        $daily_data[$group_key]['punches'][] = $timestamp;
                    }
                }

                log_message('error', "Zkteco: Processing done. Daily data count: " . count($daily_data));

                if (empty($daily_data)) {
                    ob_end_clean();
                    echo json_encode(['status' => 'error', 'msg' => 'No records found for the selected dates.']);
                    return;
                }

                $fp = fopen($file_path, 'w');
                if (!$fp) {
                    throw new Exception("Could not open file for writing: $file_path");
                }

                // CSV Header
                fputcsv($fp, array('User ID', 'Name', 'Date', 'Day', 'Check-In', 'Check-Out', 'All Punches', 'Status'));

                $final_rows = [];

                foreach ($daily_data as $data) {
                    sort($data['punches']);
                    $punches = $data['punches'];

                    $check_in = date('H:i:s', $punches[0]);
                    $check_out = (count($punches) > 1) ? date('H:i:s', end($punches)) : "";

                    $all_punches_str = implode(', ', array_map(function ($t) {
                        return date('H:i', $t); }, $punches));

                    $status = 'Present';
                    if (empty($check_out)) {
                        $status = 'Missing Out-Punch';
                    }

                    $final_rows[] = [
                        'User ID' => $data['id'],
                        'Name' => $data['name'],
                        'Date' => $data['date'],
                        'Day' => $data['day'],
                        'Check-In' => $check_in,
                        'Check-Out' => $check_out,
                        'All Punches' => $all_punches_str,
                        'Status' => $status
                    ];
                }

                // Sort by Name then Date (User's preference from Python script)
                usort($final_rows, function ($a, $b) {
                    $name_cmp = strcmp($a['Name'], $b['Name']);
                    if ($name_cmp === 0) {
                        return strcmp($a['Date'], $b['Date']);
                    }
                    return $name_cmp;
                });

                $record_count = 0;
                foreach ($final_rows as $row) {
                    fputcsv($fp, $row);
                    $record_count++;
                }

                fclose($fp);
                ob_end_clean();

                log_message('error', "Zkteco: Report generated successfully.");

                echo json_encode([
                    'status' => 'success',
                    'msg' => 'Report generated successfully.',
                    'file_url' => $file_url,
                    'count' => $record_count
                ]);

            } else {
                $error = $this->zkteco_lib->getError();
                log_message('error', "Zkteco: Connection failed. Error: $error");
                ob_end_clean();
                echo json_encode(['status' => 'error', 'msg' => 'Connection Failed: ' . $error]);
            }

        } catch (Exception $e) {
            log_message('error', "Zkteco: Exception: " . $e->getMessage());
            ob_end_clean();
            echo json_encode(['status' => 'error', 'msg' => 'Error: ' . $e->getMessage()]);
        }
    }
}
