<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Package_model extends MY_Model
{

    public function getPackages()
    {
        $this->db->select('*');
        $query = $this->db->get('packages');
        return $query->result();
    }

    public function getPackageDetails($id)
    {
        $this->db->select('*');
        $this->db->where('id', $id);
        $query = $this->db->get('packages');
        return $query->row_array();
    }

    public function valid_package($str)
    {
        $name = $this->input->post('name');
        if ($this->check_floor_exists($name)) {
            $this->form_validation->set_message('check_exists', 'Package already exists');
            return false;
        } else {
            return true;
        }
    }

    public function check_floor_exists($name)
    {
        $packageid = $this->input->post("packageid");
        if ($packageid) {
            $data = array('package_name' => $name, 'id !=' => $packageid);
            $query = $this->db->where($data)->get('packages');

            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            $this->db->where('package_name', $name);
            $query = $this->db->get('packages');
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function savepackage($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data["id"])) {
            $this->db->where("id", $data["id"])->update("packages", $data);
            $message = UPDATE_RECORD_CONSTANT . " On Package id " . $data['id'];
            $action = "Update";
            $record_id = $data['id'];
            $this->log($message, $record_id, $action);
            //======================Code End==============================

            $this->db->trans_complete(); # Completing transaction
            /* Optional */

            if ($this->db->trans_status() === false) {
                # Something went wrong.
                $this->db->trans_rollback();
                return false;
            } else {
                return $record_id;
            }
        } else {
            $this->db->insert("packages", $data);
            $insert_id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On Package id " . $insert_id;
            $action = "Insert";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);
            //======================Code End==============================

            $this->db->trans_complete(); # Completing transaction
            /* Optional */

            if ($this->db->trans_status() === false) {
                # Something went wrong.
                $this->db->trans_rollback();
                return false;
            } else {
                return $record_id;
            }
            return $insert_id;
        }
    }

    public function delete($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where("id", $id)->delete("packages");
        $message = DELETE_RECORD_CONSTANT . " On Package id " . $id;
        $action = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $record_id;
        }
    }
}
