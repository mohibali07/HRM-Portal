<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Location_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_locations()
    {
        return $this->db->get("xin_office_location");
    }

    public function read_location_information($id)
    {

        $condition = "location_id =" . "'" . $id . "'";
        $this->db->select('*');
        $this->db->from('xin_office_location');
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
        $this->db->insert('xin_office_location', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function delete_record($id)
    {
        $this->db->where('location_id', $id);
        $this->db->delete('xin_office_location');

    }

    public function update_record($data, $id)
    {
        $this->db->where('location_id', $id);
        if ($this->db->update('xin_office_location', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function all_locations()
    {
        $query = $this->db->get("xin_office_location");
        return $query->result();
    }

    public function all_office_locations()
    {
        return $this->all_locations();
    }
}
