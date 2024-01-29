<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Lab_test_model extends MY_Model
{
    public function add($data, $insert_parameter_array, $update_parameter_array, $deleted_parameter_array)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //======================Code Start==============================
        if (isset($data['id']) && $data['id'] > 0) {
            $this->db->where('id', $data['id']);
            $this->db->update('lab_test', $data);
            $insert_id = $data['id'];
            $message = UPDATE_RECORD_CONSTANT . " On Radio id " . $insert_id;
            $action = "Update";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);

        } else {
            $this->db->insert('lab_test', $data);
            $insert_id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On Radio id " . $insert_id;
            $action = "Insert";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);
        }

        if (!empty($insert_parameter_array)) {
            foreach ($insert_parameter_array as $params_key => $params_value) {
                $insert_parameter_array[$params_key]['radiology_id'] = $insert_id;
            }
            $this->db->insert_batch('lab_parameterdetails', $insert_parameter_array);
        }
        if (!empty($update_parameter_array)) {
            $this->db->update_batch('lab_parameterdetails', $update_parameter_array, 'id');
        }
        if (!empty($deleted_parameter_array)) {
            $this->db->where_in('id', $deleted_parameter_array);
            $this->db->delete('lab_parameterdetails');
        }

        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $insert_id;
        }

    }

    public function getpatientRadiologyYearCounter($patient_id, $year)
    {
        $sql = "SELECT count(*) as `total_visits`,Year(date) as `year` FROM `lab_billing` WHERE YEAR(date) >= " . $this->db->escape($year) . " AND patient_id=" . $this->db->escape($patient_id) . " GROUP BY  YEAR(date)";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getAllradiologyRecord()
    {
        $i = 1;
        $custom_fields = $this->customfield_model->get_custom_fields('radiology', 1);
        $custom_field_column_array = array();

        $field_var_array = array();
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($custom_field_column_array, 'table_custom_' . $i . '.field_value');
                array_push($field_var_array, 'table_custom_' . $i . '.field_value as ' . $custom_fields_value->name);
                $this->datatables->join('custom_field_values as ' . $tb_counter, 'lab_report.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, 'left');
                $i++;
            }
        }

        $field_variable = implode(',', $field_var_array);
        $custom_field_column = implode(',', $custom_field_column_array);

        $this->datatables
            ->select('lab_report.*, lab_test.id as rid,lab_test.test_name, lab_test.short_name,staff.name,staff.surname,charges.id as cid,charges.charge_category_id,charges.standard_charge,patients.patient_name,' . $field_variable)
            ->join('lab_test', 'lab_report.radiology_id = lab_test.id', 'inner')
            ->join('staff', 'staff.id = lab_report.consultant_doctor', "left")
            ->join('charges', 'charges.id = lab_test.charge_id')
            ->join('patients', 'patients.id = lab_report.patient_id')
            ->searchable('lab_report.bill_no,lab_report.reporting_date,patients.patient_name,lab_test.test_name,lab_test.short_name,staff.name,' . $custom_field_column)
            ->orderable('lab_report.bill_no,lab_report.reporting_date,lab_report.patient_id,lab_test.test_name,lab_test.short_name,staff.name,lab_report.description,' . $custom_field_column)
            ->sort('lab_report.id', 'desc')
            ->from('lab_report');
        return $this->datatables->generate('json');
    }

    public function getAlllabtest()
    {

        $i = 1;
        $custom_fields = $this->customfield_model->get_custom_fields('labtest', 1);
        $custom_field_column_array = array();
        $field_var_array = array();
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($custom_field_column_array, 'table_custom_' . $i . '.field_value');
                array_push($field_var_array, '`table_custom_' . $i . '`.`field_value` as `' . $custom_fields_value->name . '`');
                $this->datatables->join('custom_field_values as ' . $tb_counter, 'lab_test.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, "left");
                $i++;
            }
        }

        $field_variable = (empty($field_var_array)) ? "" : "," . implode(',', $field_var_array);
        $custom_field_column = (empty($custom_field_column_array)) ? "" : "," . implode(',', $custom_field_column_array);
        $this->datatables
            ->select('lab_test.*,lab.id as category_id,lab.lab_name,charges.standard_charge,tax_category.percentage' . $field_variable)
            ->join('lab', 'lab_test.radiology_category_id = lab.id', 'left')
            ->join('charges', 'lab_test.charge_id = charges.id', 'left')
            ->join('tax_category', 'tax_category.id = charges.tax_category_id', 'left')
            ->searchable('lab_test.test_name,lab_test.short_name,lab_test.test_type,lab.lab_name,lab_test.sub_category,lab_test.report_days' . $custom_field_column . ',tax_category.percentage,charges.standard_charge')
            ->orderable('lab_test.test_name,lab_test.short_name,lab_test.test_type,lab.lab_name,lab_test.sub_category,lab_test.report_days' . $custom_field_column . ',tax_category.percentage,charges.standard_charge')
            ->sort('lab_test.id', 'desc')
            ->where('lab_test.radiology_category_id = lab.id')
            ->from('lab_test');
        // prd($this->datatables->generate('json'));
        return $this->datatables->generate('json');
    }

    public function getAllradiologybillRecord()
    {
        $custom_fields = $this->customfield_model->get_custom_fields('radiology', 1);
        $custom_field_column_array = array();
        $field_var_array = array();
        $i = 1;
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($custom_field_column_array, 'table_custom_' . $i . '.field_value');
                array_push($field_var_array, '`table_custom_' . $i . '`.`field_value` as `' . $custom_fields_value->name . '`');
                $this->datatables->join('custom_field_values as ' . $tb_counter, 'lab_billing.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, "left");
                $i++;
            }
        }

        $field_variable = (empty($field_var_array)) ? "" : "," . implode(',', $field_var_array);
        $custom_field_column = (empty($custom_field_column_array)) ? "" : "," . implode(',', $custom_field_column_array);
        $this->datatables
            ->select('lab_billing.*,(SELECT IFNULL(SUM(transactions.amount),0) from transactions WHERE transactions.lab_billing_id=lab_billing.id ) as paid_amount,patients.patient_name,patients.id as pid,staff.name,staff.surname,staff.employee_id' . $field_variable)
            ->join('patients', 'patients.id = lab_billing.patient_id', 'left')
            ->join('transactions', 'transactions.lab_billing_id = lab_billing.id', 'left')
            ->join('staff', 'staff.id = lab_billing.doctor_id', 'left')
            ->searchable('lab_billing.id,lab_billing.case_reference_id,lab_billing.date,patients.patient_name,lab_billing.doctor_id,lab_billing.note' . $custom_field_column . ',lab_billing.net_amount,(SELECT SUM(transactions.amount) from transactions WHERE transactions.lab_billing_id=lab_billing.id ) as paid_amount')
            ->orderable('lab_billing.id,lab_billing.case_reference_id,lab_billing.date,patients.patient_name,lab_billing.doctor_id,lab_billing.note' . $custom_field_column . ',lab_billing.net_amount, paid_amount')
            ->sort('lab_billing.id', 'desc')
            ->from('lab_billing');
        return $this->datatables->generate('json');
    }

    public function getradiologybillByCaseId($case_id)
    {
        $this->datatables
            ->select('lab_billing.*,sum(transactions.amount) as paid_amount,patients.patient_name,patients.id as patient_unique_id,staff.name,staff.surname,staff.employee_id')
            ->join('patients', 'patients.id = lab_billing.patient_id', 'left')
            ->join('transactions', 'transactions.lab_billing_id = lab_billing.id', 'left')
            ->join('staff', 'staff.id = lab_billing.doctor_id', 'left')
            ->searchable('lab_billing.id,lab_billing.case_reference_id,lab_billing.date,patients.patient_name')
            ->orderable('lab_billing.id,lab_billing.case_reference_id,lab_billing.date,patients.patient_name,lab_billing.doctor_id,lab_billing.note,lab_billing.net_amount,paid_amount')
            ->group_by('transactions.lab_billing_id')
            ->sort('lab_billing.id', 'desc')
            ->where('lab_billing.case_reference_id', $case_id)
            ->from('lab_billing');
        return $this->datatables->generate('json');
    }

    public function getradiologyByCaseId($case_id)
    {
        $query = $this->db->select('lab_billing.*,IFNULL((SELECT sum(transactions.amount) from transactions WHERE transactions.lab_billing_id=lab_billing.id),0) as `amount_paid`,patients.patient_name,patients.id as patient_id')
            ->join('patients', 'patients.id = lab_billing.patient_id', 'left')
            ->where('lab_billing.case_reference_id', $case_id)
            ->get('lab_billing');
        return $query->result();
    }

    public function getradiotestDetails()
    {
        $this->db->select('lab_test.*,lab.id as category_id,lab.lab_name as category_name,charges.id as charge_id, charges.name, charges.charge_category_id, charges.standard_charge, charges.description');
        $this->db->join('lab', 'lab_test.radiology_category_id = lab.id', 'left');
        $this->db->join('charges', 'lab_test.charge_id = charges.id', 'left');
        $this->db->order_by('lab_test.id', 'desc');
        $query = $this->db->get('lab_test');
        return $query->result_array();
    }

    public function totalPatientRadiology($patient_id)
    {
        $query = $this->db->select('count(lab_billing.patient_id) as total')
            ->where('patient_id', $patient_id)
            ->get('lab_billing');
        return $query->row_array();
    }

    public function getRadiologyBillByID($id, $patient_panel = null)
    {
        if ($patient_panel == 'patient') {
            $custom_fields = $this->customfield_model->get_custom_fields('radiology', '', '', '', 1);
        } else {
            $custom_fields = $this->customfield_model->get_custom_fields('radiology');
        }

        $custom_field_column_array = array();
        $field_var_array = array();
        $i = 1;
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($custom_field_column_array, 'table_custom_' . $i . '.field_value');
                array_push($field_var_array, 'table_custom_' . $i . '.field_value as ' . $custom_fields_value->name);
                $this->datatables->join('custom_field_values as ' . $tb_counter, 'lab_billing.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, 'left');
                $i++;
            }
        }
        $field_variable = implode(',', $field_var_array);
        $query = $this->db->select('lab_billing.*,blood_bank_products.name as blood_group_name,IFNULL((SELECT SUM(amount) FROM transactions WHERE lab_billing_id=lab_billing.id),0) as total_deposit,patients.patient_name,patients.id as patient_unique_id,patients.age, patients.month, patients.day,patients.gender,patients.dob,patients.blood_group,patients.mobileno,patients.email,patients.address,staff.employee_id,staff.name,staff.surname,staff.employee_id,transactions.payment_mode,transactions.amount,transactions.cheque_no,transactions.cheque_date,transactions.note as `transaction_note`,' . $field_variable)
            ->join('patients', 'lab_billing.patient_id = patients.id')
            ->join('blood_bank_products', 'blood_bank_products.id = patients.blood_bank_product_id', 'left')
            ->join('staff', 'staff.id = lab_billing.generated_by')
            ->join('transactions', 'transactions.id = lab_billing.transaction_id', 'left')
            ->where("lab_billing.id", $id)
            ->get('lab_billing');
        if ($query->num_rows() > 0) {
            $result = $query->row();
            $result->{'lab_report'} = $this->getReportByBillId($result->id);
            return $result;
        }
        return false;
    }

    public function getReportByBillId($id)
    {
        $query = $this->db->select('lab_report.*,lab_test.test_name,lab_test.short_name,lab_test.report_days,lab_test.id as pid,lab_test.charge_id as cid,staff.name,staff.surname,charges.charge_category_id,charges.name,charges.standard_charge,collection_specialist_staff.name as `collection_specialist_staff_name`,collection_specialist_staff.surname as `collection_specialist_staff_surname`,collection_specialist_staff.employee_id as `collection_specialist_staff_employee_id`,approved_by_staff.name as `approved_by_staff_name`,approved_by_staff.surname as `approved_by_staff_surname`,approved_by_staff.employee_id as `approved_by_staff_employee_id`')
            ->join('lab_billing', 'lab_report.radiology_bill_id = lab_billing.id')
            ->join('lab_test', 'lab_report.radiology_id = lab_test.id')
            ->join('staff', 'staff.id = lab_report.consultant_doctor', "left")
            ->join('staff as collection_specialist_staff', 'collection_specialist_staff.id = lab_report.collection_specialist', "left")
            ->join('staff as approved_by_staff', 'approved_by_staff.id = lab_report.approved_by', "left")
            ->join('charges', 'lab_test.charge_id = charges.id')
            ->where('lab_report.radiology_bill_id', $id)
            ->get('lab_report');
        return $query->result();
    }

    public function getRadiologyReportByID($id)
    {
        $query = $this->db->select('lab_report.*,lab_test.id as pid,lab_test.charge_id as cid,charges.charge_category_id,charges.name,charges.standard_charge,patients.patient_name')
            ->join('patients', 'lab_report.patient_id = patients.id')
            ->join('lab_test', 'lab_report.radiology_id = lab_test.id')
            ->join('charges', 'lab_test.charge_id = charges.id')
            ->where("lab_report.id", $id)
            ->get('lab_report');
        return $query->row_array();
    }

    public function addBill($data, $addReports, $updateReports, $deleteReports, $pathology_billing_id, $transcation_data)
    {

        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //======================Code Start==============================

        if (isset($data['id']) && $data['id'] > 0) {
            $this->db->where('id', $data['id']);
            $this->db->update('lab_billing', $data);
            $id = $data['id'];
            $message = UPDATE_RECORD_CONSTANT . " On Radiology Billing id " . $id;
            $action = "Update";
            $record_id = $id;
            $this->log($message, $record_id, $action);

        } else {
            $this->db->insert("lab_billing", $data);
            $id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On Radiology Billing id " . $id;
            $action = "Insert";
            $record_id = $id;
            $this->log($message, $record_id, $action);
        }

        if (!empty($addReports)) {
            foreach ($addReports as $report_key => $report_value) {
                $addReports[$report_key]['radiology_bill_id'] = $id;
            }
            $this->db->insert_batch('lab_report', $addReports);

        }
        if (!empty($updateReports)) {
            $this->db->update_batch('lab_report', $updateReports, 'id');
        }

        if (!empty($deleteReports) && $pathology_billing_id > 0) {
            $this->db->where_not_in('id', $deleteReports);
            $this->db->where('radiology_bill_id', $pathology_billing_id);
            $this->db->delete('lab_report');
        }

        if (isset($transcation_data) && !empty($transcation_data)) {
            $transcation_data['lab_billing_id'] = $id;
            $this->transaction_model1->add($transcation_data);
        }
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return $id;
        }
    }

    public function getBillNo()
    {
        $query = $this->db->select("max(id) as id")->get('lab_billing');
        return $query->row_array();
    }

    public function addparameter($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('lab_parameterdetails', $data);
            $message = UPDATE_RECORD_CONSTANT . " On Radiology Parameter Details id " . $data['id'];
            $action = "Update";
            $record_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert_batch('lab_parameterdetails', $data);
            $insert_id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On Radiology Parameter Details id " . $insert_id;
            $action = "Insert";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);
        }
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

    public function delete_parameter($delete_arr)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        foreach ($delete_arr as $key => $value) {
            $id = $value["id"];
            $this->db->where("id", $value["id"])->delete("lab_parameterdetails");
            $message = DELETE_RECORD_CONSTANT . " On Radiology Paramete Details id " . $id;
            $action = "Delete";
            $record_id = $id;
            $this->log($message, $record_id, $action);
        }
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return true;
        }
    }

    public function getpathoparameter($id = null)
    {
        if (!empty($id)) {
            $this->db->select('radiolog_parameter.*,unit.unit_name');
            $this->db->from('radiolog_parameter');
            $this->db->join('unit', 'radiolog_parameter.unit = unit.id', 'left');
            $this->db->where("radiolog_parameter.id", $id);
            $query = $this->db->get();
            return $query->row_array();
        } else {
            $this->db->select('lab_parameter.*,unit.unit_name');
            $this->db->from('lab_parameter');
            $this->db->join('unit', 'lab_parameter.unit = unit.id', 'left');
            $this->db->join('lab_test', 'lab_parameter.id = lab_test.radiology_parameter_id', 'left');
            $query = $this->db->get();
            return $query->result_array();
        }
    }

    public function getparameterDetails($id)
    {
        $query = $this->db->select('lab_parameterdetails.*,lab_parameter.parameter_name,lab_parameter.reference_range,lab_parameter.unit,unit.unit_name')
            ->join('lab_parameter', 'lab_parameter.id = lab_parameterdetails.parameter_id')
            ->join('unit', 'unit.id = lab_parameter.unit')
            ->where('lab_parameterdetails.radiology_id', $id)
            ->get('lab_parameterdetails');
        return $query->result_array();
    }

    public function getparameterDetailsforpatient($report_id)
    {
        $query = $this->db->select('lab_report_parameterdetails.*,lab_parameter.parameter_name,lab_parameter.reference_range,lab_parameter.unit,unit.unit_name')
            ->join('lab_parameter', 'lab_parameter.id = lab_report_parameterdetails.parameter_id')
            ->join('unit', 'unit.id = lab_parameter.unit')
            ->where("lab_report_parameterdetails.radiology_report_id", $report_id)
            ->get('lab_report_parameterdetails');
        return $query->result_array();
        echo $this->db->last_query();
    }

    public function update($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id']) && $data['id'] != '') {
            $this->db->where('id', $data['id']);
            $this->db->update('lab_test', $data);
            $message = UPDATE_RECORD_CONSTANT . " On Radio id " . $data['id'];
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
        }

    }

    public function delete($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('lab_test');
        $this->db->where("radiology_id", $id)->delete('lab_parameterdetails');
        $message = DELETE_RECORD_CONSTANT . " On  Radiology Paramete Details where radiology id " . $id;
        $action = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        $this->customfield_model->delete_custom_fieldRecord($id, 'radiologytest');
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {

            //return $return_value;
        }
    }

    public function deleteRadiologyBill($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('lab_billing');

        $message = DELETE_RECORD_CONSTANT . " On Radiology Billing id " . $id;
        $action = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        $this->customfield_model->delete_custom_fieldRecord($id, 'radiology');
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {

            //return $return_value;
        }
    }

    public function getRadiology($id = null)
    {
        if (!empty($id)) {
            $this->db->where("lab_test.id", $id);
        }
        $query = $this->db->select('lab_test.*,charges.charge_category_id,charges.name,charges.standard_charge')->join('charges', 'lab_test.charge_id = charges.id')->order_by('lab_test.id', 'desc')->get('lab_test');
        if (!empty($id)) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getradiologytest($id = null)
    {
        if (!empty($id)) {
            $this->db->where("lab_test.id", $id);
        }
        $query = $this->db->select('lab_test.*,')->order_by('lab_test.id', 'desc')->get('lab_test');
        if (!empty($id)) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getBillDetails($id)
    {
        $this->db->select('lab_report.*,lab_test.test_name,lab_test.short_name,lab_test.report_days,patients.patient_name,patients.id,patients.age,patients.gender,patients.blood_group,patients.mobileno,patients.email,patients.address,staff.name as doctorname,staff.surname as doctorsurname');
        $this->db->where('lab_report.id', $id);
        $this->db->join('lab_test', 'lab_test.id = lab_report.radiology_id');
        $this->db->join('patients', 'patients.id = lab_report.patient_id');
        $this->db->join('staff', 'staff.id = lab_report.consultant_doctor', 'left');
        $query = $this->db->get('lab_report');
        $result = $query->row_array();
        $generated_by = $result["generated_by"];
        $staff_query = $this->db->select("staff.name,staff.surname")
            ->where("staff.id", $generated_by)
            ->get("staff");
        $staff_result = $staff_query->row_array();
        $result["generated_byname"] = $staff_result["name"] . $staff_result["surname"];
        return $result;
    }

    public function getDetails($id)
    {
        $i = 1;
        $custom_fields = $this->customfield_model->get_custom_fields('radiology', 1);
        $custom_field_column_array = array();

        $field_var_array = array();
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($custom_field_column_array, 'table_custom_' . $i . '.field_value');
                array_push($field_var_array, 'table_custom_' . $i . '.field_value as ' . $custom_fields_value->name);
                $this->datatables->join('custom_field_values as ' . $tb_counter, 'lab_test.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, 'left');
                $i++;
            }
        }

        $field_variable = implode(',', $field_var_array);
        $custom_field_column = implode(',', $custom_field_column_array);

        $this->db->select('lab_test.*,lab.id as category_id,lab.lab_name, charges.id as charge_id, charges.name, charges.charge_category_id, charges.standard_charge, charges.description,charges.name as `charge_name`,charge_categories.name as `charge_category_name`,tax_category.name as apply_tax,tax_category.percentage,' . $field_variable);
        $this->db->join('lab', 'lab_test.radiology_category_id = lab.id', 'left');
        $this->db->join('charges', 'lab_test.charge_id = charges.id', 'left');
        $this->db->join('charge_categories', 'charge_categories.id = charges.charge_category_id');
        $this->db->join('tax_category', 'tax_category.id = charges.tax_category_id');
        $this->db->join("charge_type_master", 'charge_categories.charge_type_id = charge_type_master.id');
        $this->db->where('lab_test.id', $id);
        $this->db->order_by('lab_test.id', 'desc');
        $query = $this->db->get('lab_test');
        if ($query->num_rows() > 0) {
            $result = $query->row();
            $result->{'lab_parameter'} = $this->getRadiologyParamsById($result->id);
            return $result;
        }
        return false;
    }

    public function getRadiologyParamsById($id)
    {
        $this->db->select('lab_parameterdetails.*,lab_parameter.parameter_name,lab_parameter.reference_range,lab_parameter.unit,unit.unit_name');
        $this->db->join('lab_parameter', 'lab_parameter.id = lab_parameterdetails.radiology_parameter_id');
        $this->db->join('unit', 'unit.id = lab_parameter.unit');
        $this->db->where('radiology_id', $id);
        $query = $this->db->get('lab_parameterdetails');
        return $query->result();
    }

    public function getradioBillDetails($id)
    {
        $this->db->select('lab_billing.*,sum(transactions.amount) as total_deposit,patients.patient_name,patients.patient_unique_id,patients.age,patients.gender,patients.blood_group,patients.mobileno,patients.email,patients.address');
        $this->db->where('lab_billing.id', $id);
        $this->db->join('patients', 'patients.id = lab_billing.patient_id', "left");
        $this->db->join('transactions', 'lab_billing.id = transactions.lab_billing_id', "left");
        $query = $this->db->get('lab_billing');
        $result = $query->row_array();
        $generated_by = $result["generated_by"];
        $staff_query = $this->db->select("staff.name,staff.surname")
            ->where("staff.id", $generated_by)
            ->get("staff");
        $staff_result = $staff_query->row_array();
        $result["generated_byname"] = $staff_result["name"] . $staff_result["surname"];
        return $result;
    }

    public function updateparameter($condition)
    {
        $SQL = "INSERT INTO lab_parameterdetails
                    (parameter_id, id)
                    VALUES
                    " . $condition . "
                    ON DUPLICATE KEY UPDATE
                    parameter_id=VALUES(parameter_id)";
        $query = $this->db->query($SQL);
    }

    public function getMaxId()
    {
        $query = $this->db->select('max(id) as bill_no')->get("lab_report");
        $result = $query->row_array();
        return $result["bill_no"];
    }

    public function getAllBillDetails($id)
    {
        $query = $this->db->select('lab_report.*,lab_test.test_name,lab_test.short_name,lab_test.report_days,lab_test.charge_id')
            ->join('lab_test', 'lab_test.id = lab_report.radiology_id')
            ->where('lab_report.id', $id)
            ->get('lab_report');
        return $query->result_array();
    }

    public function getAllradioBillDetails($id)
    {
        $query = $this->db->select('lab_report.*,lab_test.test_name,lab_test.short_name,lab_test.report_days,lab_test.charge_id')
            ->join('lab_test', 'v.id = lab_report.radiology_id')
            ->join('lab_billing', 'lab_report.radiology_bill_id = lab_billing.id', 'left')
            ->where('lab_report.radiology_bill_id', $id)
            ->get('lab_report');
        return $query->result_array();
    }

    public function testReportBatch($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id']) && $data['id'] != '') {
            $this->db->where('id', $data['id']);
            $this->db->update('lab_report', $data);
            $message = UPDATE_RECORD_CONSTANT . " On Radiology Report id " . $data['id'];
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
            $this->db->insert('lab_report', $data);

            $insert_id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On Radiology Report id " . $insert_id;
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
                //return $return_value;
            }
            return $insert_id;
        }
    }

    public function getRadiologyReport($id)
    {
        $query = $this->db->select('lab_report.*,v.id as pid,lab_test.charge_id as cid,staff.name,staff.surname,charges.charge_category_id,charges.name,charges.standard_charge')
            ->join('lab_test', 'lab_report.radiology_id = lab_test.id')
            ->join('charges', 'lab_test.charge_id = charges.id')
            ->join('staff', 'staff.id = lab_report.consultant_doctor', "left")
            ->where("lab_report.id", $id)
            ->get('lab_report');
        return $query->row_array();
    }

    public function updateTestReport($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id']) && $data['id'] != '') {
            $this->db->where('id', $data['id']);
            $this->db->update('lab_report', $data);
            $message = UPDATE_RECORD_CONSTANT . " On Radiology Report id " . $data['id'];
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
        }
    }

    public function addparametervalue($parametervalue)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($parametervalue["id"])) {
            $this->db->where("id", $parametervalue["id"])->update('lab_report_parameterdetails', $parametervalue);

            $message = UPDATE_RECORD_CONSTANT . " On Radiology Report Parameter Dtails id " . $parametervalue["id"];
            $action = "Update";
            $record_id = $parametervalue["id"];
            $this->log($message, $record_id, $action);

        } else {
            $this->db->insert('lab_parameterdetails', $parametervalue);
            $insert_id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On Radiology Parameter Details id " . $insert_id;
            $action = "Insert";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);
        }
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

    public function getTestReportBatch($radiology_id)
    {
        $doctor_restriction = $this->session->userdata['hospitaladmin']['doctor_restriction'];
        $disable_option = false;
        $userdata = $this->customlib->getUserData();
        $role_id = $userdata['role_id'];
        if ($doctor_restriction == 'enabled') {
            if ($role_id == 3) {
                $doctorid = $userdata['id'];
                $this->db->where("lab_report.consultant_doctor", $doctorid);
            }}

        $this->db->select('lab_report.*, lab_test.id as rid,v.test_name, lab_test.short_name,staff.name,staff.surname,charges.id as cid,charges.charge_category_id,charges.standard_charge,patients.patient_name');
        $this->db->join('lab_test', 'lab_report.radiology_id = lab_test.id', 'inner');
        $this->db->join('staff', 'staff.id = lab_report.consultant_doctor', "left");
        $this->db->join('charges', 'charges.id = lab_test.charge_id');
        $this->db->join('patients', 'patients.id = lab_report.patient_id');
        $this->db->where("patients.is_active", "yes");
        $this->db->order_by('lab_report.id', 'desc');
        $query = $this->db->get('lab_report');
        $result = $query->result();
        foreach ($result as $key => $value) {
            $generated_by = $value->generated_by;
            $staff_query = $this->db->select("staff.name,staff.surname")
                ->where("staff.id", $generated_by)
                ->get("staff");
            $staff_result = $staff_query->row_array();
            $result[$key]->generated_byname = $staff_result["name"] . $staff_result["surname"];
        }
        return $result;
    }

    public function getPatientRadiologyReportDetails($id)
    {
        $query = $this->db->select('lab_report.*,lab_test.test_name,lab_test.short_name,lab_test.report_days,lab_test.id as pid,lab_test.charge_id as charge_id,lab_report.radiology_bill_id,lab_billing.doctor_name,lab_billing.case_reference_id,lab_billing.patient_id,charges.charge_category_id,charges.name as `charge_name`,charges.standard_charge,patients.patient_name as `patient_name`,patients.id as patient_unique_id,patients.age,patients.month,patients.day,patients.gender,patients.blood_group,patients.mobileno,patients.email,patients.address,collection_specialist_staff.name as `collection_specialist_staff_name`,collection_specialist_staff.surname as `collection_specialist_staff_surname`,collection_specialist_staff.employee_id as `collection_specialist_staff_employee_id`,collection_specialist_staff.id as `collection_specialist_staff_id`')
            ->join('lab_billing', 'lab_report.radiology_bill_id = lab_billing.id')
            ->join('patients', 'lab_report.patient_id = patients.id')
            ->join('lab_test', 'lab_report.radiology_id = lab_test.id')
            ->join('staff as collection_specialist_staff', 'collection_specialist_staff.id = lab_report.collection_specialist', "left")
            ->join('charges', 'lab_test.charge_id = charges.id')
            ->where('lab_report.id', $id)
            ->get('lab_report');

        if ($query->num_rows() > 0) {
            $result = $query->row();
            $result->{'lab_parameter'} = $this->getPatientRadiologyReportParameterDetails($result->id);
            return $result;
        }
        return false;
    }

    public function getPatientRadiologyReportParameterDetails($radiology_report_id)
    {
        $sql = "SELECT lab_parameterdetails.*,lab_parameter.parameter_name,lab_parameter.description,lab_parameter.reference_range,unit.unit_name,IFNULL(lab_report_parameterdetails.id,0) as `radiology_report_parameterdetail_id`,lab_report_parameterdetails.radiology_report_id,lab_report_parameterdetails.radiology_parameterdetail_id,lab_report_parameterdetails.radiology_report_value FROM `lab_report` INNER join lab_parameterdetails on lab_parameterdetails.radiology_id=lab_report.radiology_id INNER JOIN lab_parameter on lab_parameterdetails.radiology_parameter_id=lab_parameter.id INNER JOIN unit on lab_parameter.unit=unit.id LEFT join lab_report_parameterdetails on lab_report_parameterdetails.radiology_parameterdetail_id=lab_parameterdetails.id and lab_report_parameterdetails.radiology_report_id=lab_report.id WHERE lab_report.id =" . $radiology_report_id;
        $query = $this->db->query($sql);
        $result = $query->result();
        return $result;
    }

    public function getTestReportBatchRadio($patient_id)
    {
        $i = 1;
        $custom_fields = $this->customfield_model->get_custom_fields('radiology', '', '', '', 1);
        $custom_field_column_array = array();
        $field_var_array = array();
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($custom_field_column_array, 'table_custom_' . $i . '.field_value');
                array_push($field_var_array, '`table_custom_' . $i . '`.`field_value` as `' . $custom_fields_value->name . '`');
                $this->datatables->join('custom_field_values as ' . $tb_counter, 'lab_billing.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, "left");
                $i++;
            }
        }

        $field_variable = (empty($field_var_array)) ? "" : "," . implode(',', $field_var_array);
        $custom_field_column = (empty($custom_field_column_array)) ? "" : "," . implode(',', $custom_field_column_array);

        $this->db->select('lab_billing.*,sum(transactions.amount)as paid_amount,patients.patient_name,patients.id as pid,staff.name,staff.surname,staff.employee_id' . $field_variable);
        $this->db->join('patients', 'patients.id = lab_billing.patient_id', 'left');
        $this->db->join('staff', 'staff.id = lab_billing.doctor_id', 'left');
        $this->db->join('transactions', 'transactions.lab_billing_id = lab_billing.id');
        $this->db->group_by('transactions.lab_billing_id');
        $this->db->where('lab_billing.patient_id', $patient_id);
        $this->db->order_by('lab_billing.id', 'desc');
        $query = $this->db->get('lab_billing');
        return $query->result();
    }

    public function deleteTestReport($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('lab_report');
        $message = DELETE_RECORD_CONSTANT . " On Radiology Report id " . $id;
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
            //return $return_value;
        }

    }

    public function deletetestbill($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id)->delete('lab_billing');
        $this->db->where('radiology_bill_id', $id)->delete('lab_report');
        $message = DELETE_RECORD_CONSTANT . " On Radiology Report where Radiology Bill Id " . $id;
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
            //return $return_value;
        }
    }

    public function getChargeCategory()
    {
        $query = $this->db->select('charge_categories.*')
            ->get('charge_categories');
        return $query->result_array();
    }

    public function getparameterBypathology($id)
    {
        $query = $this->db->select('lab_parameterdetails.parameter_id')
            ->where('radiology_id', $id)
            ->get('lab_parameterdetails');
        return $query->result_array();
    }

    public function addParameterforPatient($radiology_report_id, $approved_by, $approve_date, $insert_parameter_array, $update_parameter_array, $deleted_parameter_array)
    {

        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //======================Code Start==============================

        $this->db->where('id', $radiology_report_id);
        $this->db->update('lab_report', array('parameter_update' => $approve_date, 'approved_by' => $approved_by));

        if (!empty($deleted_parameter_array)) {
            $this->db->where_not_in('id', $deleted_parameter_array);
            $this->db->where('radiology_report_id', $radiology_report_id);
            $this->db->delete('lab_report_parameterdetails');

            $message = DELETE_RECORD_CONSTANT . " On Radiology Report Parameter Details where radiology report id " . $radiology_report_id;
            $action = "Delete";
            $record_id = $radiology_report_id;
            $this->log($message, $record_id, $action);

        }

        if (!empty($insert_parameter_array)) {
            $this->db->insert_batch('lab_report_parameterdetails', $insert_parameter_array);
        }

        if (!empty($update_parameter_array)) {
            $this->db->update_batch('lab_report_parameterdetails', $update_parameter_array, 'id');
        }

        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return true;
        }

    }

    public function test_uniqe($test_name, $short_name, $id)
    {
        if ($id != '') {
            $this->db->where_not_in('id', $id);
        }
        $query = $this->db->select('test_name')
            ->where("test_name", $test_name)
            ->where("short_name", $short_name)
            ->get('lab_test');

        return $query->num_rows();
    }

    public function validate_paymentamount()
    {
        $payment_amount = $this->input->post('amount');
        $net_amount = $this->input->post('net_amount');
        if ($payment_amount > $net_amount) {
            $this->form_validation->set_message('check_exists', 'Amount should not be greater than balance ' . $net_amount);
            return false;
        } else {
            return true;
        }
    }

    public function printtestparameterdetail($id)
    {
        $query = $this->db->select('lab_report.*,lab_report.id as test_id,lab_test.test_name,lab_test.short_name,lab_test.report_days,lab_test.id as pid,lab_test.charge_id as charge_id,lab_report.radiology_bill_id,lab_billing.doctor_name,lab_billing.case_reference_id,lab_billing.patient_id,charges.charge_category_id,charges.name as `charge_name`,charges.standard_charge,patients.patient_name as `patient_name`,patients.id as patient_unique_id,patients.age,patients.month,patients.day,patients.gender,patients.blood_group,patients.mobileno,patients.email,patients.address,collection_specialist_staff.name as `collection_specialist_staff_name`,collection_specialist_staff.surname as `collection_specialist_staff_surname`,collection_specialist_staff.employee_id as `collection_specialist_staff_employee_id`,collection_specialist_staff.id as `collection_specialist_staff_id`')
            ->join('lab_billing', 'lab_report.radiology_bill_id = lab_billing.id')
            ->join('patients', 'lab_report.patient_id = patients.id')
            ->join('lab_test', 'lab_report.radiology_id = lab_test.id')
            ->join('staff as collection_specialist_staff', 'collection_specialist_staff.id = lab_report.collection_specialist', "left")
            ->join('charges', 'lab_test.charge_id = charges.id')
            ->where('lab_billing.id', $id)
            ->get('lab_report');

        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            foreach ($result as $row) {
                $test_result[$row['id']] = $row;
                $test_result[$row['id']]['lab_parameter'] = $this->getPatientRadiologyReportParameterDetails($row['test_id']);
            }
            return $test_result;
        }
        return false;
    }

}