<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_projects()
    {
        return $this->db->get("xin_projects");
    }

    public function get_all_projects()
    {
        $query = $this->db->get("xin_projects");
        return $query->result();
    }

    public function read_project_information($id)
    {

        $condition = "project_id =" . "'" . $id . "'";
        $this->db->select('*');
        $this->db->from('xin_projects');
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
        $this->db->insert('xin_projects', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function delete_record($id)
    {
        $this->db->where('project_id', $id);
        $this->db->delete('xin_projects');

    }

    public function update_record($data, $id)
    {
        $this->db->where('project_id', $id);
        if ($this->db->update('xin_projects', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function get_discussion($id)
    {
        return $this->db->get_where('xin_projects_discussion', array('project_id' => $id));
    }

    public function add_discussion($data)
    {
        $this->db->insert('xin_projects_discussion', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_bug($id)
    {
        return $this->db->get_where('xin_projects_bugs', array('project_id' => $id));
    }

    public function add_bug($data)
    {
        $this->db->insert('xin_projects_bugs', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function read_bug_information($id)
    {
        $condition = "bug_id =" . "'" . $id . "'";
        $this->db->select('*');
        $this->db->from('xin_projects_bugs');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function update_bug($data, $id)
    {
        $this->db->where('bug_id', $id);
        if ($this->db->update('xin_projects_bugs', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function delete_bug_record($id)
    {
        $this->db->where('bug_id', $id);
        $this->db->delete('xin_projects_bugs');
    }

    public function add_new_attachment($data)
    {
        $this->db->insert('xin_projects_attachment', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_attachments($id)
    {
        return $this->db->get_where('xin_projects_attachment', array('project_id' => $id));
    }

    public function delete_attachment_record($id)
    {
        $this->db->where('project_attachment_id', $id);
        $this->db->delete('xin_projects_attachment');
    }
}
