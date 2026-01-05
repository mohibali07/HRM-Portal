<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ZkAttendance extends MY_Controller
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
        //load the models
        $this->load->model('Xin_model');
    }

    public function index()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('');
        }
        $data['title'] = $this->Xin_model->site_title();
        $data['breadcrumbs'] = 'Attendance App';
        $data['path_url'] = 'zk_attendance';
        $role_resources_ids = $this->Xin_model->user_role_resource();

        // Check if user has access - for now allowing all logged in users or restrict as needed
        // if(in_array('53',$role_resources_ids)) { // Example permission check
        $data['subview'] = $this->load->view('zk_attendance/index', $data, TRUE);
        $this->load->view('layout_main', $data); //page load
        // } else {
        // 	redirect('dashboard/');
        // }
    }
}
