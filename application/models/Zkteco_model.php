<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Zkteco_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get ZKTeco device settings
     */
    public function get_settings()
    {
        // Check if table exists first
        if (!$this->db->table_exists('xin_zkteco_settings')) {
            return null;
        }
        
        $query = $this->db->get('xin_zkteco_settings');
        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return null;
    }

    /**
     * Save or update ZKTeco device settings
     */
    public function save_settings($data)
    {
        // Check if table exists first
        if (!$this->db->table_exists('xin_zkteco_settings')) {
            return false;
        }
        
        $existing = $this->get_settings();
        if ($existing) {
            $this->db->where('id', $existing->id);
            return $this->db->update('xin_zkteco_settings', $data);
        } else {
            return $this->db->insert('xin_zkteco_settings', $data);
        }
    }

    /**
     * Get employee mapping (ZK user ID to HRM employee ID)
     */
    public function get_employee_mapping($zk_user_id = null)
    {
        // Check if table exists first
        if (!$this->db->table_exists('xin_zkteco_employee_mapping')) {
            return $this->db->get('xin_zkteco_employee_mapping'); // Returns empty result
        }
        
        if ($zk_user_id) {
            $this->db->where('zk_user_id', $zk_user_id);
        }
        return $this->db->get('xin_zkteco_employee_mapping');
    }

    /**
     * Save employee mapping
     */
    public function save_employee_mapping($zk_user_id, $employee_id, $zk_name = '')
    {
        // Check if mapping exists
        $existing = $this->db->get_where('xin_zkteco_employee_mapping', array('zk_user_id' => $zk_user_id));
        
        $data = array(
            'zk_user_id' => $zk_user_id,
            'employee_id' => $employee_id,
            'zk_name' => $zk_name,
            'updated_at' => date('Y-m-d H:i:s')
        );

        if ($existing->num_rows() > 0) {
            $this->db->where('zk_user_id', $zk_user_id);
            return $this->db->update('xin_zkteco_employee_mapping', $data);
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            return $this->db->insert('xin_zkteco_employee_mapping', $data);
        }
    }

    /**
     * Delete employee mapping
     */
    public function delete_employee_mapping($zk_user_id)
    {
        $this->db->where('zk_user_id', $zk_user_id);
        return $this->db->delete('xin_zkteco_employee_mapping');
    }

    /**
     * Check if attendance record already exists
     */
    public function attendance_exists($employee_id, $attendance_date, $clock_in)
    {
        $this->db->where('employee_id', $employee_id);
        $this->db->where('attendance_date', $attendance_date);
        $this->db->where('clock_in', $clock_in);
        $query = $this->db->get('xin_attendance_time');
        return $query->num_rows() > 0;
    }

    /**
     * Get sync log
     */
    public function get_sync_logs($limit = 50)
    {
        // Check if table exists first
        if (!$this->db->table_exists('xin_zkteco_sync_log')) {
            return $this->db->get('xin_zkteco_sync_log'); // Returns empty result
        }
        
        $this->db->order_by('sync_date', 'DESC');
        $this->db->limit($limit);
        return $this->db->get('xin_zkteco_sync_log');
    }

    /**
     * Log sync operation
     */
    public function log_sync($status, $message, $records_synced = 0, $errors = '')
    {
        $data = array(
            'status' => $status,
            'message' => $message,
            'records_synced' => $records_synced,
            'errors' => $errors,
            'sync_date' => date('Y-m-d H:i:s')
        );
        return $this->db->insert('xin_zkteco_sync_log', $data);
    }

    /**
     * Update last sync time
     */
    public function update_last_sync($datetime = null)
    {
        if ($datetime === null) {
            $datetime = date('Y-m-d H:i:s');
        }
        $existing = $this->get_settings();
        if ($existing) {
            $this->db->where('id', $existing->id);
            return $this->db->update('xin_zkteco_settings', array('last_sync' => $datetime));
        }
        return false;
    }
}

