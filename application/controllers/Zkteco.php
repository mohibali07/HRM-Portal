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
}
