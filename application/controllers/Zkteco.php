<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Zkteco extends MY_Controller
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
        $this->load->model("Xin_model");
        $this->load->model("Zkteco_model");
        $this->load->model("Timesheet_model");
        $this->load->model("Employees_model");
        $this->load->library('Zkteco_lib');
    }

    public function index()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('');
        }

        $data['title'] = $this->Xin_model->site_title();
        $data['breadcrumbs'] = 'ZKTeco Settings';
        $data['path_url'] = 'zkteco_settings';

        // Get current settings
        $data['settings'] = $this->Zkteco_model->get_settings();

        // Get ZK attendance data (latest 100 records, if table exists)
        if ($this->db->table_exists('xin_zkteco_attendance')) {
            $this->db->order_by('attendance_date', 'DESC');
            $this->db->order_by('zk_user_id', 'ASC');
            $this->db->limit(100);
            $data['zk_attendance'] = $this->db->get('xin_zkteco_attendance')->result();
        } else {
            $data['zk_attendance'] = array();
        }

        $data['subview'] = $this->load->view("zkteco/settings", $data, TRUE);
        $this->load->view('layout_main', $data);
    }

    /**
     * Settings page - Redirect to index
     */
    public function settings()
    {
        redirect('zkteco');
    }

    public function fetch_users()
    {
        // Try to get IP from POST first, then from settings
        $ip = $this->input->post('ip_address') ?: $this->input->post('device_ip');
        $port = $this->input->post('device_port') ?: 4370;

        // If no IP in POST, get from settings
        if (empty($ip)) {
            $settings = $this->Zkteco_model->get_settings();
            if ($settings) {
                $ip = $settings->device_ip;
                $port = $settings->device_port ?: 4370;
            }
        }

        if (empty($ip)) {
            $this->output(['status' => 'error', 'message' => 'Device IP address is required. Please configure device settings first.']);
            return;
        }

        $zk = new Zkteco_lib($ip, $port);
        $connected = $zk->connect();

        if (!$connected) {
            $error_msg = $zk->error ?: 'Connection failed. Please check device IP and network connectivity.';
            $this->output([
                'status' => 'error',
                'message' => 'Could not connect to device. ' . $error_msg,
                'debug' => $zk->debug_logs
            ]);
            return;
        }

        // Get users from device
        $users = $zk->getUser();
        $zk->disconnect();

        if (empty($users) || !is_array($users)) {
            $this->output([
                'status' => 'warning',
                'data' => [],
                'message' => 'No users found on device. Make sure users are enrolled on the device.',
                'debug' => $zk->debug_logs
            ]);
            return;
        }

        $user_list = [];
        foreach ($users as $uid => $user) {
            if (isset($user['userid']) && isset($user['name'])) {
                $user_list[] = [
                    'user_id' => $user['userid'],
                    'name' => $user['name']
                ];
            }
        }

        if (empty($user_list)) {
            $this->output([
                'status' => 'warning',
                'data' => [],
                'message' => 'Users found but could not parse user data. Debug info available.',
                'debug' => $zk->debug_logs
            ]);
            return;
        }

        $this->output([
            'status' => 'success',
            'data' => $user_list,
            'message' => 'Successfully loaded ' . count($user_list) . ' user(s) from device.',
            'debug' => $zk->debug_logs
        ]);
    }

    public function generate_report()
    {
        $ip = $this->input->post('ip_address');
        $user_id = $this->input->post('employee_id');
        $year = $this->input->post('year');
        $month = $this->input->post('month');

        $result = $this->_get_report_data($ip, $user_id, $year, $month);

        if ($result === false || (!empty($result['error']) && empty($result['rows']))) {
            $msg = isset($result['error']) ? $result['error'] : 'Could not connect to device.';
            $this->output(['status' => 'error', 'message' => $msg, 'debug' => isset($result['debug']) ? $result['debug'] : []]);
            return;
        }

        $this->output(['status' => 'success', 'data' => $result['rows'], 'debug' => $result['debug']]);
    }

    public function export_report()
    {
        $ip = $this->input->post('ip_address');
        $user_id = $this->input->post('employee_id');
        $year = $this->input->post('year');
        $month = $this->input->post('month');

        // If accessed via GET (e.g. direct link), try GET params
        if (!$ip)
            $ip = $this->input->get('ip_address');
        if (!$user_id)
            $user_id = $this->input->get('employee_id');
        if (!$year)
            $year = $this->input->get('year');
        if (!$month)
            $month = $this->input->get('month');

        $result = $this->_get_report_data($ip, $user_id, $year, $month);
        $data = isset($result['rows']) ? $result['rows'] : [];

        if (empty($data)) {
            echo "Error generating report or no data.<br>";
            if (!empty($result['debug'])) {
                echo "<pre>" . print_r($result['debug'], true) . "</pre>";
            }
            if (!empty($result['error'])) {
                echo "Error: " . $result['error'];
            }
            return;
        }

        $filename = "Attendance_Report_{$year}_{$month}.csv";
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $fp = fopen('php://output', 'w');
        fputcsv($fp, ['User ID', 'Name', 'Date', 'Day', 'Check-In', 'Check-Out', 'All Punches', 'Status']);

        foreach ($data as $row) {
            fputcsv($fp, [
                $row['user_id'],
                $row['name'],
                $row['date'],
                $row['day'],
                $row['check_in'],
                $row['check_out'],
                $row['all_punches'],
                $row['status']
            ]);
        }
        fclose($fp);
    }

    private function _get_report_data($ip, $user_id, $year, $month)
    {
        $zk = new Zkteco_lib($ip, 4370);
        if (!$zk->connect()) {
            return false;
        }

        $attendance = $zk->getAttendance();
        // Don't fetch users - use attendance user IDs directly as requested
        $zk->disconnect();

        $daily_data = [];
        $month_idx = (int) $month;
        $selected_year = (int) $year;

        foreach ($attendance as $record) {
            if (!isset($record['timestamp']))
                continue;

            $timestamp = strtotime($record['timestamp']);
            $r_year = date('Y', $timestamp);
            $r_month = date('n', $timestamp);

            if ($r_year == $selected_year && $r_month == $month_idx) {
                $uid = $record['id'];
                if ($user_id != 'All' && $uid != $user_id)
                    continue;

                $date_str = date('Y-m-d', $timestamp);
                $key = $uid . '_' . $date_str;

                if (!isset($daily_data[$key])) {
                    $daily_data[$key] = [
                        'User ID' => $uid,
                        'Name' => $uid, // Use ID as name since we're not linking to HRM
                        'Date' => $date_str,
                        'Day' => date('l', $timestamp),
                        'Punches' => []
                    ];
                }
                $daily_data[$key]['Punches'][] = $timestamp;
            }
        }

        $final_rows = [];
        foreach ($daily_data as $key => $data) {
            $times = $data['Punches'];
            sort($times);
            $check_in = date('H:i:s', $times[0]);
            $check_out = (count($times) > 1) ? date('H:i:s', end($times)) : '';
            $all_punches = implode(', ', array_map(function ($t) {
                return date('H:i', $t);
            }, $times));
            $status = (count($times) == 1) ? 'Missing Out-Punch' : 'Present';

            $final_rows[] = [
                'user_id' => $data['User ID'],
                'name' => $data['Name'],
                'date' => $data['Date'],
                'day' => $data['Day'],
                'check_in' => $check_in,
                'check_out' => $check_out,
                'all_punches' => $all_punches,
                'status' => $status
            ];
        }

        usort($final_rows, function ($a, $b) {
            $c = strcmp($a['name'], $b['name']);
            return ($c === 0) ? strcmp($a['date'], $b['date']) : $c;
        });

        return ['rows' => $final_rows, 'debug' => $zk->debug_logs, 'error' => $zk->error];
    }



    /**
     * Save device settings
     */
    public function save_settings()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            $this->output(['status' => 'error', 'message' => 'Unauthorized']);
            return;
        }

        $device_ip = $this->input->post('device_ip');
        $device_port = $this->input->post('device_port') ?: 4370;

        if (empty($device_ip)) {
            $this->output(['status' => 'error', 'message' => 'Device IP is required']);
            return;
        }

        $data = array(
            'device_ip' => $device_ip,
            'device_port' => (int) $device_port,
            'updated_at' => date('Y-m-d H:i:s')
        );

        if ($this->Zkteco_model->save_settings($data)) {
            $this->output(['status' => 'success', 'message' => 'Settings saved successfully']);
        } else {
            $this->output(['status' => 'error', 'message' => 'Failed to save settings']);
        }
    }

    /**
     * Test connection to ZK device
     */
    public function test_connection()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            $this->output(['status' => 'error', 'message' => 'Unauthorized']);
            return;
        }

        $ip = $this->input->post('device_ip') ?: $this->input->get('device_ip');
        $port = $this->input->post('device_port') ?: $this->input->get('device_port') ?: 4370;

        if (empty($ip)) {
            $this->output(['status' => 'error', 'message' => 'Device IP is required']);
            return;
        }

        $zk = new Zkteco_lib($ip, $port);
        $connected = $zk->connect();

        if ($connected) {
            // Try to get device info or users to verify connection
            $users = $zk->getUser();
            $zk->disconnect();

            $this->output([
                'status' => 'success',
                'message' => 'Connection successful! Found ' . count($users) . ' users on device.'
            ]);
        } else {
            $this->output([
                'status' => 'error',
                'message' => 'Connection failed: ' . ($zk->error ?: 'Unable to connect to device')
            ]);
        }
    }

    /**
     * Sync attendance from ZK device to HRM database
     */
    public function sync_attendance()
    {
        // Increase execution time and memory limit for large syncs
        ini_set('max_execution_time', 0); // Unlimited time
        ini_set('memory_limit', '512M');

        $session = $this->session->userdata('username');
        if (empty($session)) {
            $this->output(['status' => 'error', 'message' => 'Unauthorized']);
            return;
        }

        // Get device settings
        $settings = $this->Zkteco_model->get_settings();
        if (!$settings) {
            $this->output(['status' => 'error', 'message' => 'Device settings not configured. Please configure device first.']);
            return;
        }

        $ip = $settings->device_ip;
        $port = $settings->device_port ?: 4370;

        // Optional: Get date range from POST and convert to Y-m-d format
        $start_date_raw = $this->input->post('start_date');
        $end_date_raw = $this->input->post('end_date');

        // Convert date format (handle MM/DD/YYYY or YYYY-MM-DD)
        $start_date = null;
        $end_date = null;

        if (!empty($start_date_raw)) {
            // Try to detect format and convert
            if (strpos($start_date_raw, '/') !== false) {
                // MM/DD/YYYY format
                $parts = explode('/', $start_date_raw);
                if (count($parts) == 3) {
                    $start_date = $parts[2] . '-' . str_pad($parts[0], 2, '0', STR_PAD_LEFT) . '-' . str_pad($parts[1], 2, '0', STR_PAD_LEFT);
                }
            } else {
                // Already YYYY-MM-DD format
                $start_date = $start_date_raw;
            }
        }

        if (!empty($end_date_raw)) {
            // Try to detect format and convert
            if (strpos($end_date_raw, '/') !== false) {
                // MM/DD/YYYY format
                $parts = explode('/', $end_date_raw);
                if (count($parts) == 3) {
                    $end_date = $parts[2] . '-' . str_pad($parts[0], 2, '0', STR_PAD_LEFT) . '-' . str_pad($parts[1], 2, '0', STR_PAD_LEFT);
                }
            } else {
                // Already YYYY-MM-DD format
                $end_date = $end_date_raw;
            }
        }

        $zk = new Zkteco_lib($ip, $port);
        $connected = $zk->connect();

        if (!$connected) {
            $this->Zkteco_model->log_sync('error', 'Connection failed: ' . $zk->error, 0);
            $this->output(['status' => 'error', 'message' => 'Connection failed: ' . $zk->error]);
            return;
        }

        // Get attendance data
        $attendance = $zk->getAttendance();

        // Get users in the same connection to save time
        $users = $zk->getUser();

        $debug_info = $zk->debug_logs;
        $zk->disconnect();

        if (empty($attendance) || !is_array($attendance)) {
            $error_msg = 'No attendance data found on device. ';
            if (!empty($zk->error)) {
                $error_msg .= 'Error: ' . $zk->error;
            }
            $this->Zkteco_model->log_sync('warning', $error_msg, 0);
            $this->output([
                'status' => 'warning',
                'message' => $error_msg,
                'debug' => $debug_info,
                'attendance_count' => is_array($attendance) ? count($attendance) : 0
            ]);
            return;
        }

        $total_attendance_records = count($attendance);

        // Check if table exists
        if (!$this->db->table_exists('xin_zkteco_attendance')) {
            $this->Zkteco_model->log_sync('error', 'Table xin_zkteco_attendance does not exist. Please run migration SQL.', 0);
            $this->output([
                'status' => 'error',
                'message' => 'Database table does not exist. Please run zkteco_attendance_table.sql first.',
                'debug' => $debug_info
            ]);
            return;
        }

        // Map users
        $zk_users = array();
        foreach ($users as $uid => $user) {
            $zk_users[$user['userid']] = $user['name'];
            // Also map by uid in case userid is different
            $zk_users[$uid] = $user['name'];
        }

        // Optimization: Get the latest sync date from DB to avoid processing old records
        // Only if no specific date range is requested
        $last_sync_timestamp = 0;
        if (empty($start_date)) {
            $this->db->select_max('sync_date');
            $query = $this->db->get('xin_zkteco_attendance');
            if ($query->num_rows() > 0) {
                $row = $query->row();
                if (!empty($row->sync_date)) {
                    $last_sync_timestamp = strtotime($row->sync_date);
                }
            }
        }

        // Group attendance by user and date
        $daily_data = array();
        $processed_count = 0;
        $skipped_no_timestamp = 0;
        $skipped_no_userid = 0;
        $skipped_date_filter = 0;
        $skipped_old_data = 0;

        foreach ($attendance as $record) {
            if (!isset($record['timestamp']) || empty($record['timestamp'])) {
                $skipped_no_timestamp++;
                continue;
            }

            $timestamp = strtotime($record['timestamp']);
            if ($timestamp === false) {
                $skipped_no_timestamp++;
                continue;
            }

            $attendance_date = date('Y-m-d', $timestamp);
            $clock_time = date('H:i:s', $timestamp);

            // Apply date filter if provided (use string comparison for dates in Y-m-d format)
            if (!empty($start_date)) {
                if ($attendance_date < $start_date) {
                    $skipped_date_filter++;
                    continue;
                }
            }

            // Optimization: If no date range, skip records older than 30 days by default
            // to prevent processing years of history every time
            if (empty($start_date) && $timestamp < strtotime('-30 days')) {
                $skipped_old_data++;
                continue;
            }
            if (!empty($end_date)) {
                if ($attendance_date > $end_date) {
                    $skipped_date_filter++;
                    continue;
                }
            }

            // Get user ID - try multiple ways
            $zk_user_id = null;

            // Method 1: Use 'id' field (string user ID)
            if (isset($record['id'])) {
                $zk_user_id = trim($record['id']);
                if ($zk_user_id === '' || $zk_user_id === '0') {
                    $zk_user_id = null;
                }
            }

            // Method 2: Use 'uid' field (internal numeric ID)
            if (empty($zk_user_id) && isset($record['uid'])) {
                $zk_user_id = trim((string) $record['uid']);
                if ($zk_user_id === '' || $zk_user_id === '0') {
                    $zk_user_id = null;
                }
            }

            if (empty($zk_user_id)) {
                $skipped_no_userid++;
                continue;
            }

            $key = $zk_user_id . '_' . $attendance_date;

            if (!isset($daily_data[$key])) {
                // Try to get name from users array
                $zk_name = $zk_user_id; // Default to user ID
                if (isset($zk_users[$zk_user_id])) {
                    $zk_name = $zk_users[$zk_user_id];
                } elseif (isset($record['id']) && isset($zk_users[$record['id']])) {
                    $zk_name = $zk_users[$record['id']];
                }

                $daily_data[$key] = array(
                    'zk_user_id' => $zk_user_id,
                    'zk_name' => $zk_name,
                    'attendance_date' => $attendance_date,
                    'punches' => array(),
                    'clock_in' => null,
                    'clock_out' => null
                );
            }

            $daily_data[$key]['punches'][] = $clock_time;
            $processed_count++;
        }

        // Process and insert attendance records
        $synced_count = 0;
        $skipped_count = 0;
        $error_count = 0;
        $errors = array();
        $debug_details = array();

        $debug_details[] = "Total records from device: {$total_attendance_records}";
        $debug_details[] = "Date filter - Start: " . ($start_date ?: 'None') . ", End: " . ($end_date ?: 'None');
        $debug_details[] = "Processed records: {$processed_count}";
        $debug_details[] = "Grouped into daily records: " . count($daily_data);
        $debug_details[] = "Skipped (no timestamp): {$skipped_no_timestamp}";
        $debug_details[] = "Skipped (no user ID): {$skipped_no_userid}";
        $debug_details[] = "Skipped (date filter): {$skipped_date_filter}";

        // Show sample dates from records for debugging
        if ($total_attendance_records > 0 && $skipped_date_filter > 0) {
            $sample_dates = array();
            $count = 0;
            foreach ($attendance as $record) {
                if (isset($record['timestamp']) && $count < 5) {
                    $ts = strtotime($record['timestamp']);
                    if ($ts !== false) {
                        $sample_dates[] = date('Y-m-d', $ts);
                        $count++;
                    }
                }
            }
            if (!empty($sample_dates)) {
                $debug_details[] = "Sample dates from device: " . implode(', ', array_unique($sample_dates));
            }
        }

        if (empty($daily_data)) {
            $this->Zkteco_model->log_sync('warning', "No valid records to sync after processing. Total: {$total_attendance_records}, Processed: {$processed_count}", 0);
            $this->output([
                'status' => 'warning',
                'message' => "No valid records to sync. Total records: {$total_attendance_records}, but none matched criteria.",
                'synced' => 0,
                'skipped' => $total_attendance_records,
                'total_from_device' => $total_attendance_records,
                'debug' => array_merge($debug_info, $debug_details)
            ]);
            return;
        }

        // BATCH PROCESSING
        // Convert daily_data to array for chunking
        $all_records = array_values($daily_data);
        $chunk_size = 100;
        $chunks = array_chunk($all_records, $chunk_size);

        foreach ($chunks as $chunk) {
            $zk_user_ids = [];
            $attendance_dates = [];
            $records_map = [];

            foreach ($chunk as $data) {
                $zk_user_ids[] = $data['zk_user_id'];
                $attendance_dates[] = $data['attendance_date'];
                $key = $data['zk_user_id'] . '_' . $data['attendance_date'];

                // Sort punches
                sort($data['punches']);
                $data['clock_in'] = !empty($data['punches']) ? $data['punches'][0] : null;
                $data['clock_out'] = count($data['punches']) > 1 ? end($data['punches']) : null;
                $data['all_punches'] = implode(', ', $data['punches']);
                $data['status'] = count($data['punches']) == 1 ? 'Missing Out-Punch' : 'Present';

                $records_map[$key] = $data;
            }

            // Fetch existing records for this chunk
            $this->db->where_in('zk_user_id', $zk_user_ids);
            $this->db->where_in('attendance_date', $attendance_dates);
            $existing_query = $this->db->get('xin_zkteco_attendance');

            $existing_map = [];
            foreach ($existing_query->result() as $row) {
                $key = $row->zk_user_id . '_' . $row->attendance_date;
                $existing_map[$key] = $row;
            }

            $insert_batch = [];

            foreach ($records_map as $key => $data) {
                if (isset($existing_map[$key])) {
                    // Update existing
                    $update_data = array(
                        'clock_in' => $data['clock_in'],
                        'clock_out' => $data['clock_out'],
                        'all_punches' => $data['all_punches'],
                        'status' => $data['status'],
                        'sync_date' => date('Y-m-d H:i:s')
                    );

                    $this->db->where('zk_user_id', $data['zk_user_id']);
                    $this->db->where('attendance_date', $data['attendance_date']);
                    if ($this->db->update('xin_zkteco_attendance', $update_data)) {
                        $synced_count++;
                    } else {
                        $error_count++;
                        $errors[] = "Failed to update: {$key}";
                    }
                } else {
                    // Prepare for insert
                    $insert_batch[] = array(
                        'zk_user_id' => $data['zk_user_id'],
                        'zk_name' => $data['zk_name'],
                        'attendance_date' => $data['attendance_date'],
                        'clock_in' => $data['clock_in'],
                        'clock_out' => $data['clock_out'],
                        'all_punches' => $data['all_punches'],
                        'status' => $data['status'],
                        'device_ip' => $ip,
                        'sync_date' => date('Y-m-d H:i:s')
                    );
                }
            }

            // Execute batch insert
            if (!empty($insert_batch)) {
                if ($this->db->insert_batch('xin_zkteco_attendance', $insert_batch)) {
                    $synced_count += count($insert_batch);
                } else {
                    $error_count += count($insert_batch);
                    $errors[] = "Failed to batch insert " . count($insert_batch) . " records";
                }
            }
        }

        // Update last sync time
        $this->Zkteco_model->update_last_sync();

        // Log sync operation
        $error_msg = !empty($errors) ? implode('; ', array_slice($errors, 0, 10)) : '';
        $log_message = "Total records from device: {$total_attendance_records}. Processed: {$processed_count}, Grouped: " . count($daily_data) . ", Synced: {$synced_count}";
        if ($error_count > 0) {
            $log_message .= ", Errors: {$error_count}";
        }

        $this->Zkteco_model->log_sync(($synced_count > 0) ? 'success' : 'warning', $log_message, $synced_count, $error_msg);

        $message = "Sync completed! ";
        $message .= "Total records from device: {$total_attendance_records}. ";
        $message .= "Processed: {$processed_count}, Grouped into: " . count($daily_data) . " daily records. ";
        $message .= "Synced: {$synced_count}";

        if ($skipped_no_userid > 0 || $skipped_date_filter > 0) {
            $message .= ", Skipped: " . ($skipped_no_userid + $skipped_date_filter);
        }

        if ($error_count > 0) {
            $message .= ", Errors: {$error_count}";
        }

        $this->output([
            'status' => ($synced_count > 0) ? 'success' : 'warning',
            'message' => $message,
            'synced' => $synced_count,
            'skipped' => $skipped_no_userid + $skipped_date_filter,
            'errors' => $error_count,
            'total_from_device' => $total_attendance_records,
            'processed' => $processed_count,
            'grouped' => count($daily_data),
            'debug' => array_merge($debug_info, $debug_details, !empty($errors) ? array('db_errors' => array_slice($errors, 0, 5)) : array())
        ]);
    }

    /**
     * Get ZK attendance data
     */
    public function get_zk_attendance()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            $this->output(['status' => 'error', 'message' => 'Unauthorized']);
            return;
        }

        if (!$this->db->table_exists('xin_zkteco_attendance')) {
            $this->output(['status' => 'success', 'data' => []]);
            return;
        }

        $limit = $this->input->get('limit') ?: 100;
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');

        $this->db->order_by('attendance_date', 'DESC');
        $this->db->order_by('zk_user_id', 'ASC');

        if ($start_date) {
            $this->db->where('attendance_date >=', $start_date);
        }
        if ($end_date) {
            $this->db->where('attendance_date <=', $end_date);
        }

        $this->db->limit($limit);
        $attendance = $this->db->get('xin_zkteco_attendance')->result();

        $data = array();
        foreach ($attendance as $record) {
            $data[] = array(
                'zk_user_id' => $record->zk_user_id,
                'zk_name' => $record->zk_name,
                'attendance_date' => $record->attendance_date,
                'clock_in' => $record->clock_in,
                'clock_out' => $record->clock_out,
                'all_punches' => $record->all_punches,
                'status' => $record->status,
                'sync_date' => $record->sync_date
            );
        }

        $this->output(['status' => 'success', 'data' => $data]);
    }

    /**
     * Export ZK attendance data to CSV (Excel)
     */
    public function export_zk_attendance()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            show_error('Unauthorized', 401);
            return;
        }

        if (!$this->db->table_exists('xin_zkteco_attendance')) {
            show_error('ZK attendance table does not exist.', 500);
            return;
        }

        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');

        if ($start_date) {
            $this->db->where('attendance_date >=', $start_date);
        }
        if ($end_date) {
            $this->db->where('attendance_date <=', $end_date);
        }

        $this->db->order_by('attendance_date', 'DESC');
        $this->db->order_by('zk_user_id', 'ASC');
        $attendance = $this->db->get('xin_zkteco_attendance')->result_array();

        $filename = 'zk_attendance_' . date('Ymd_His') . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);

        $output = fopen('php://output', 'w');

        // Add UTF-8 BOM for Excel
        fputs($output, "\xEF\xBB\xBF");

        // Header row
        fputcsv($output, array('ZK User ID', 'Name', 'Date', 'Clock In', 'Clock Out', 'All Punches', 'Status', 'Device IP', 'Sync Date'));

        foreach ($attendance as $row) {
            fputcsv($output, array(
                $row['zk_user_id'],
                $row['zk_name'],
                $row['attendance_date'],
                $row['clock_in'],
                $row['clock_out'],
                $row['all_punches'],
                $row['status'],
                $row['device_ip'],
                $row['sync_date']
            ));
        }

        fclose($output);
        exit;
    }

    /**
     * Get sync logs
     */
    public function get_sync_logs()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            $this->output(['status' => 'error', 'message' => 'Unauthorized']);
            return;
        }

        $logs = $this->Zkteco_model->get_sync_logs(50);
        $log_data = array();
        foreach ($logs->result() as $log) {
            $log_data[] = array(
                'id' => $log->id,
                'status' => $log->status,
                'message' => $log->message,
                'records_synced' => $log->records_synced,
                'sync_date' => $log->sync_date
            );
        }

        $this->output(['status' => 'success', 'data' => $log_data]);
    }

    public function output($Return = array())
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        exit(json_encode($Return));
    }
}
