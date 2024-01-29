<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Packages extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("staff_model");
        $this->load->model(array("onlineappointment_model", "charge_category_model", "appoint_priority_model"));
        $this->load->library("datatables");
        $this->time_format = $this->customlib->getHospitalTimeFormat();
        $this->load->library("customlib");
        $this->load->helper('customfield_helper');
        $res = $this->config->load("payroll");
        $this->payment_mode = $this->config->item('payment_mode');

    }

    public function index()
    {
        $this->session->set_userdata('top_menu', 'setup');
        $this->session->set_userdata('sub_menu', 'packages');
        $data['package_list'] = $this->package_model->getPackages();
        $this->load->view('layout/header');
        $this->load->view('setup/packages', $data);
        $this->load->view('layout/footer');
    }

    public function add()
    {
        $this->form_validation->set_rules(
            'name', $this->lang->line('name'), array('required',
                array('check_exists', array($this->package_model, 'valid_package')),
            )
        );
        $this->form_validation->set_rules('amount', $this->lang->line('amount'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $msg = array(
                'name' => form_error('name'),
                'amount' => form_error('amount'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {

            $package = array(
                'package_name' => $this->input->post('name'),
                'package_charge' => $this->input->post('amount'),
            );

            $this->package_model->savepackage($package);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function update()
    {

        $this->form_validation->set_rules(
            'name', $this->lang->line('name'), array('required',
                array('check_exists', array($this->package_model, 'valid_package')),
            )
        );
        $this->form_validation->set_rules('amount', $this->lang->line('amount'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {

            $msg = array(
                'name' => form_error('name'),
                'amount' => form_error('amount'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $package = array(
                'id' => $this->input->post('packageid'),
                'package_name' => $this->input->post('name'),
                'package_charge' => $this->input->post('amount'),
            );

            $this->package_model->savepackage($package);
            $msg = "Package Updated Successfully";
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('update_message'));
        }
        echo json_encode($array);
    }

    public function get($id)
    {
        $result = $this->package_model->getPackageDetails($id);
        echo json_encode($result);
    }

    public function delete($id)
    {
        if (!empty($id)) {
            $this->package_model->delete($id);
        }
        echo json_encode(array("status" => 1, "msg" => $this->lang->line("delete_message")));
    }

}