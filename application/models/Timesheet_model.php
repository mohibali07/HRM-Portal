<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Timesheet_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_attendance_by_date($attendance_date)
    {
        $condition = "attendance_date =" . "'" . $attendance_date . "'";
        $this->db->select('*');
        $this->db->from('xin_attendance_time');
        $this->db->where($condition);
        return $this->db->get();
    }

    public function add_employee_attendance($data)
    {
        $this->db->insert('xin_attendance_time', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function all_leave_types()
    {
        return $this->db->get("xin_leave_type");
    }

    public function read_leave_information($id)
    {
        $condition = "leave_id =" . "'" . $id . "'";
        $this->db->select('*');
        $this->db->from('xin_leave_applications');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function read_leave_type_information($id)
    {
        $condition = "leave_type_id =" . "'" . $id . "'";
        $this->db->select('*');
        $this->db->from('xin_leave_type');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function read_task_information($id)
    {
        $condition = "task_id =" . "'" . $id . "'";
        $this->db->select('*');
        $this->db->from('xin_tasks');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function get_tasks()
    {
        return $this->db->get("xin_tasks");
    }

    public function assign_task_user($data, $id)
    {
        $this->db->where('task_id', $id);
        if ($this->db->update('xin_tasks', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function update_task_record($data, $id)
    {
        $this->db->where('task_id', $id);
        if ($this->db->update('xin_tasks', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function get_project_tasks($id)
    {
        return $this->db->get_where('xin_tasks', array('project_id' => $id));
    }

    public function get_comments($id)
    {
        return $this->db->get_where('xin_tasks_comments', array('task_id' => $id));
    }

    public function add_comment($data)
    {
        $this->db->insert('xin_tasks_comments', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function delete_comment_record($id)
    {
        $this->db->where('comment_id', $id);
        $this->db->delete('xin_tasks_comments');
    }

    public function add_new_attachment($data)
    {
        $this->db->insert('xin_tasks_attachment', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_attachments($id)
    {
        return $this->db->get_where('xin_tasks_attachment', array('task_id' => $id));
    }

    public function delete_attachment_record($id)
    {
        $this->db->where('task_attachment_id', $id);
        $this->db->delete('xin_tasks_attachment');
    }

    public function read_office_shift_information($id)
    {
        $condition = "office_shift_id =" . "'" . $id . "'";
        $this->db->select('*');
        $this->db->from('xin_office_shift');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function attendance_first_in_check($employee_id, $attendance_date)
    {
        $condition = "employee_id =" . "'" . $employee_id . "' AND attendance_date =" . "'" . $attendance_date . "'";
        $this->db->select('*');
        $this->db->from('xin_attendance_time');
        $this->db->where($condition);
        $this->db->limit(1);
        return $this->db->get();
    }

    public function attendance_first_in($employee_id, $attendance_date)
    {
        $condition = "employee_id =" . "'" . $employee_id . "' AND attendance_date =" . "'" . $attendance_date . "'";
        $this->db->select('*');
        $this->db->from('xin_attendance_time');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->result();
    }

    public function total_hours_worked_attendance($employee_id, $attendance_date)
    {
        return $this->db->query("SELECT * FROM xin_attendance_time WHERE employee_id = '" . $employee_id . "' AND attendance_date = '" . $attendance_date . "'");
    }

    public function total_rest_attendance($employee_id, $attendance_date)
    {
        return $this->db->query("SELECT * FROM xin_attendance_time WHERE employee_id = '" . $employee_id . "' AND attendance_date = '" . $attendance_date . "'");
    }

    public function holiday_date_check($attendance_date)
    {
        $condition = "start_date <=" . "'" . $attendance_date . "' AND end_date >=" . "'" . $attendance_date . "'";
        $this->db->select('*');
        $this->db->from('xin_holidays');
        $this->db->where($condition);
        $this->db->limit(1);
        return $this->db->get();
    }

    public function holiday_date($attendance_date)
    {
        $condition = "start_date <=" . "'" . $attendance_date . "' AND end_date >=" . "'" . $attendance_date . "'";
        $this->db->select('*');
        $this->db->from('xin_holidays');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->result();
    }

    public function leave_date_check($employee_id, $attendance_date)
    {
        $condition = "from_date <=" . "'" . $attendance_date . "' AND to_date >=" . "'" . $attendance_date . "' AND employee_id = '" . $employee_id . "'";
        $this->db->select('*');
        $this->db->from('xin_leave_applications');
        $this->db->where($condition);
        $this->db->limit(1);
        return $this->db->get();
    }

    public function leave_date($employee_id, $attendance_date)
    {
        $condition = "from_date <=" . "'" . $attendance_date . "' AND to_date >=" . "'" . $attendance_date . "' AND employee_id = '" . $employee_id . "'";
        $this->db->select('*');
        $this->db->from('xin_leave_applications');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->result();
    }

    public function attendance_first_out_check($employee_id, $attendance_date)
    {
        $condition = "employee_id =" . "'" . $employee_id . "' AND attendance_date =" . "'" . $attendance_date . "'";
        $this->db->select('*');
        $this->db->from('xin_attendance_time');
        $this->db->where($condition);
        $this->db->limit(1);
        return $this->db->get();
    }

    public function attendance_first_out($employee_id, $attendance_date)
    {
        $condition = "employee_id =" . "'" . $employee_id . "' AND attendance_date =" . "'" . $attendance_date . "'";
        $this->db->select('*');
        $this->db->from('xin_attendance_time');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->result();
    }

    public function attendance_employee_with_date($employee_id, $attendance_date)
    {
        return $this->db->query("SELECT * FROM xin_attendance_time WHERE employee_id = '" . $employee_id . "' AND attendance_date = '" . $attendance_date . "'");
    }

    public function get_office_shifts()
    {
        return $this->db->get("xin_office_shift");
    }

    public function get_holidays()
    {
        return $this->db->get("xin_holidays");
    }

    public function get_leaves()
    {
        return $this->db->get("xin_leave_applications");
    }

    public function add_task_record($data)
    {
        $this->db->insert('xin_tasks', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function add_leave_record($data)
    {
        $this->db->insert('xin_leave_applications', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function add_holiday_record($data)
    {
        $this->db->insert('xin_holidays', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function update_holiday_record($data, $id)
    {
        $this->db->where('holiday_id', $id);
        if ($this->db->update('xin_holidays', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function update_leave_record($data, $id)
    {
        $this->db->where('leave_id', $id);
        if ($this->db->update('xin_leave_applications', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function read_attendance_information($id)
    {
        $condition = "time_attendance_id =" . "'" . $id . "'";
        $this->db->select('*');
        $this->db->from('xin_attendance_time');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function read_holiday_information($id)
    {
        $condition = "holiday_id =" . "'" . $id . "'";
        $this->db->select('*');
        $this->db->from('xin_holidays');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function update_attendance_record($data, $id)
    {
        $this->db->where('time_attendance_id', $id);
        if ($this->db->update('xin_attendance_time', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function update_default_shift_zero($data)
    {
        $this->db->where('default_shift', 1);
        if ($this->db->update('xin_office_shift', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function update_default_shift_record($data, $id)
    {
        $this->db->where('office_shift_id', $id);
        if ($this->db->update('xin_office_shift', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function add_office_shift_record($data)
    {
        $this->db->insert('xin_office_shift', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function update_shift_record($data, $id)
    {
        $this->db->where('office_shift_id', $id);
        if ($this->db->update('xin_office_shift', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function delete_attendance_record($id)
    {
        $this->db->where('time_attendance_id', $id);
        $this->db->delete('xin_attendance_time');
    }

    public function delete_holiday_record($id)
    {
        $this->db->where('holiday_id', $id);
        $this->db->delete('xin_holidays');
    }

    public function delete_shift_record($id)
    {
        $this->db->where('office_shift_id', $id);
        $this->db->delete('xin_office_shift');
    }

    public function delete_leave_record($id)
    {
        $this->db->where('leave_id', $id);
        $this->db->delete('xin_leave_applications');
    }

    public function delete_task_record($id)
    {
        $this->db->where('task_id', $id);
        $this->db->delete('xin_tasks');
    }

    public function check_user_attendance()
    {
        $today_date = date('Y-m-d');
        $session = $this->session->userdata('username');
        return $this->db->query("SELECT * FROM xin_attendance_time WHERE employee_id = '" . $session['user_id'] . "' AND attendance_date = '" . $today_date . "'");
    }

    public function add_new_attendance($data)
    {
        $this->db->insert('xin_attendance_time', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function check_user_attendance_clockout()
    {
        $today_date = date('Y-m-d');
        $session = $this->session->userdata('username');
        return $this->db->query("SELECT * FROM xin_attendance_time WHERE employee_id = '" . $session['user_id'] . "' AND attendance_date = '" . $today_date . "' ORDER BY time_attendance_id DESC LIMIT 1");
    }

    public function update_attendance_clockedout($data, $id)
    {
        $this->db->where('time_attendance_id', $id);
        if ($this->db->update('xin_attendance_time', $data)) {
            return true;
        } else {
            return false;
        }
    }
}
