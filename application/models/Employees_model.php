<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employees_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_employees()
    {
        return $this->db->get("xin_employees");
    }

    public function all_office_shifts()
    {
        return $this->db->get("xin_office_shift");
    }

    public function all_document_types()
    {
        return $this->db->get("xin_document_type");
    }

    public function all_education_level()
    {
        return $this->db->get("xin_qualification_education_level");
    }

    public function all_qualification_language()
    {
        return $this->db->get("xin_qualification_language");
    }

    public function all_qualification_skill()
    {
        return $this->db->get("xin_qualification_skill");
    }

    public function all_contract_types()
    {
        return $this->db->get("xin_contract_type");
    }

    public function all_contracts()
    {
        return $this->db->get("xin_employee_contract");
    }

    public function read_employee_information($id)
    {
        $condition = "user_id =" . "'" . $id . "'";
        $this->db->select('*');
        $this->db->from('xin_employees');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function read_contact_information($id)
    {
        $condition = "contact_id =" . "'" . $id . "'";
        $this->db->select('*');
        $this->db->from('xin_employee_contacts');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function read_document_information($id)
    {
        $condition = "document_id =" . "'" . $id . "'";
        $this->db->select('*');
        $this->db->from('xin_employee_documents');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function read_imgdocument_information($id)
    {
        $condition = "immigration_id =" . "'" . $id . "'";
        $this->db->select('*');
        $this->db->from('xin_employee_immigration');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function read_qualification_information($id)
    {
        $condition = "qualification_id =" . "'" . $id . "'";
        $this->db->select('*');
        $this->db->from('xin_employee_qualification');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function read_work_experience_information($id)
    {
        $condition = "work_experience_id =" . "'" . $id . "'";
        $this->db->select('*');
        $this->db->from('xin_employee_work_experience');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function read_bank_account_information($id)
    {
        $condition = "bankaccount_id =" . "'" . $id . "'";
        $this->db->select('*');
        $this->db->from('xin_employee_bankaccount');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function read_contract_information($id)
    {
        $condition = "contract_id =" . "'" . $id . "'";
        $this->db->select('*');
        $this->db->from('xin_employee_contract');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function read_leave_information($id)
    {
        $condition = "leave_id =" . "'" . $id . "'";
        $this->db->select('*');
        $this->db->from('xin_employee_leave');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function read_emp_shift_information($id)
    {
        $condition = "emp_shift_id =" . "'" . $id . "'";
        $this->db->select('*');
        $this->db->from('xin_employee_shift');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function read_location_information($id)
    {
        $condition = "office_location_id =" . "'" . $id . "'";
        $this->db->select('*');
        $this->db->from('xin_employee_location');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function add($data)
    {
        $this->db->insert('xin_employees', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function basic_info($data, $id)
    {
        $this->db->where('user_id', $id);
        if ($this->db->update('xin_employees', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function profile_picture($data, $id)
    {
        $this->db->where('user_id', $id);
        if ($this->db->update('xin_employees', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function social_info($data, $id)
    {
        $this->db->where('user_id', $id);
        if ($this->db->update('xin_employees', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function check_employee_contact_permanent($id)
    {
        $condition = "employee_id =" . "'" . $id . "' AND contact_type = 'permanent'";
        $this->db->select('*');
        $this->db->from('xin_employee_contacts');
        $this->db->where($condition);
        $this->db->limit(1);
        return $this->db->get();
    }

    public function check_employee_contact_current($id)
    {
        $condition = "employee_id =" . "'" . $id . "' AND contact_type = 'current'";
        $this->db->select('*');
        $this->db->from('xin_employee_contacts');
        $this->db->where($condition);
        $this->db->limit(1);
        return $this->db->get();
    }

    public function contact_info_add($data)
    {
        $this->db->insert('xin_employee_contacts', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function contact_info_update($data, $id)
    {
        $this->db->where('contact_id', $id);
        if ($this->db->update('xin_employee_contacts', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function document_info_add($data)
    {
        $this->db->insert('xin_employee_documents', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function document_info_update($data, $id)
    {
        $this->db->where('document_id', $id);
        if ($this->db->update('xin_employee_documents', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function immigration_info_add($data)
    {
        $this->db->insert('xin_employee_immigration', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function img_document_info_update($data, $id)
    {
        $this->db->where('immigration_id', $id);
        if ($this->db->update('xin_employee_immigration', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function qualification_info_add($data)
    {
        $this->db->insert('xin_employee_qualification', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function qualification_info_update($data, $id)
    {
        $this->db->where('qualification_id', $id);
        if ($this->db->update('xin_employee_qualification', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function work_experience_info_add($data)
    {
        $this->db->insert('xin_employee_work_experience', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function work_experience_info_update($data, $id)
    {
        $this->db->where('work_experience_id', $id);
        if ($this->db->update('xin_employee_work_experience', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function bank_account_info_add($data)
    {
        $this->db->insert('xin_employee_bankaccount', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function bank_account_info_update($data, $id)
    {
        $this->db->where('bankaccount_id', $id);
        if ($this->db->update('xin_employee_bankaccount', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function contract_info_add($data)
    {
        $this->db->insert('xin_employee_contract', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function contract_info_update($data, $id)
    {
        $this->db->where('contract_id', $id);
        if ($this->db->update('xin_employee_contract', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function leave_info_add($data)
    {
        $this->db->insert('xin_employee_leave', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function leave_info_update($data, $id)
    {
        $this->db->where('leave_id', $id);
        if ($this->db->update('xin_employee_leave', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function shift_info_add($data)
    {
        $this->db->insert('xin_employee_shift', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function shift_info_update($data, $id)
    {
        $this->db->where('emp_shift_id', $id);
        if ($this->db->update('xin_employee_shift', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function location_info_add($data)
    {
        $this->db->insert('xin_employee_location', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function location_info_update($data, $id)
    {
        $this->db->where('office_location_id', $id);
        if ($this->db->update('xin_employee_location', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function change_password($data, $id)
    {
        $this->db->where('user_id', $id);
        if ($this->db->update('xin_employees', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function set_employee_contacts($id)
    {
        return $this->db->get_where('xin_employee_contacts', array('employee_id' => $id));
    }

    public function set_employee_documents($id)
    {
        return $this->db->get_where('xin_employee_documents', array('employee_id' => $id));
    }

    public function read_document_type_information($id)
    {
        $condition = "document_type_id =" . "'" . $id . "'";
        $this->db->select('*');
        $this->db->from('xin_document_type');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function set_employee_immigration($id)
    {
        return $this->db->get_where('xin_employee_immigration', array('employee_id' => $id));
    }

    public function set_employee_qualification($id)
    {
        return $this->db->get_where('xin_employee_qualification', array('employee_id' => $id));
    }

    public function read_education_information($id)
    {
        $condition = "education_level_id =" . "'" . $id . "'";
        $this->db->select('*');
        $this->db->from('xin_qualification_education_level');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function set_employee_experience($id)
    {
        return $this->db->get_where('xin_employee_work_experience', array('employee_id' => $id));
    }

    public function set_employee_bank_account($id)
    {
        return $this->db->get_where('xin_employee_bankaccount', array('employee_id' => $id));
    }

    public function set_employee_contract($id)
    {
        return $this->db->get_where('xin_employee_contract', array('employee_id' => $id));
    }

    public function read_contract_type_information($id)
    {
        $condition = "contract_type_id =" . "'" . $id . "'";
        $this->db->select('*');
        $this->db->from('xin_contract_type');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function set_employee_leave($id)
    {
        return $this->db->get_where('xin_employee_leave', array('employee_id' => $id));
    }

    public function read_shift_information($id)
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

    public function set_employee_shift($id)
    {
        return $this->db->get_where('xin_employee_shift', array('employee_id' => $id));
    }

    public function set_employee_location($id)
    {
        return $this->db->get_where('xin_employee_location', array('employee_id' => $id));
    }

    public function delete_contact_record($id)
    {
        $this->db->where('contact_id', $id);
        $this->db->delete('xin_employee_contacts');
    }

    public function delete_document_record($id)
    {
        $this->db->where('document_id', $id);
        $this->db->delete('xin_employee_documents');
    }

    public function delete_imgdocument_record($id)
    {
        $this->db->where('immigration_id', $id);
        $this->db->delete('xin_employee_immigration');
    }

    public function delete_qualification_record($id)
    {
        $this->db->where('qualification_id', $id);
        $this->db->delete('xin_employee_qualification');
    }

    public function delete_work_experience_record($id)
    {
        $this->db->where('work_experience_id', $id);
        $this->db->delete('xin_employee_work_experience');
    }

    public function delete_bank_account_record($id)
    {
        $this->db->where('bankaccount_id', $id);
        $this->db->delete('xin_employee_bankaccount');
    }

    public function delete_contract_record($id)
    {
        $this->db->where('contract_id', $id);
        $this->db->delete('xin_employee_contract');
    }

    public function delete_leave_record($id)
    {
        $this->db->where('leave_id', $id);
        $this->db->delete('xin_employee_leave');
    }

    public function delete_shift_record($id)
    {
        $this->db->where('emp_shift_id', $id);
        $this->db->delete('xin_employee_shift');
    }

    public function delete_location_record($id)
    {
        $this->db->where('office_location_id', $id);
        $this->db->delete('xin_employee_location');
    }

    public function delete_record($id)
    {
        $this->db->where('user_id', $id);
        $this->db->delete('xin_employees');
    }
}
