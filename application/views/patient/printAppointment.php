<?php
$currency_symbol = $this->customlib->getHospitalCurrencyFormat();
?>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title><?php echo $this->lang->line('bill'); ?></title>
    <style type="text/css">
    .printablea4 {
        width: 100%;
    }

    .printablea4>tbody>tr>th,
    .printablea4>tbody>tr>td {
        padding: 2px 0;
        line-height: 1.42857143;
        vertical-align: top;
        font-size: 15px;
    }

    @media print {
        @page {
            margin: 0;
        }


    }
    </style>
</head>
<div id="html-2-pdfwrapper">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <div class="">
                <div style="border:1px solid; margin:10px; height:50vh;">
                    <div style="padding: 0 10px; height:50vh; ">
                        <address>
                            <center>
                                <h3><b><?php echo strtoupper("$res->name"); ?></h3>
                                <h4><?php echo $res->address; ?></h4>
                                <h4>Phone no: <?php echo $res->phone; ?></h4>
                            </center>
                        </address>
                        <div class="table-responsive">
                            <table class="table mb0 " style="text-align:left; width:100%">
                                <tr>
                                    <th width="15%"><?php echo $this->lang->line('patient'); ?></th>
                                    <td width="40%"><span
                                            id='patient_name_view'><?php echo ": " . $result['patients_name'] ?></span>
                                <tr>
                                <tr>
                                    <th width="15%">
                                        <?php echo $this->lang->line('gender') . ' & ' . $this->lang->line('age'); ?>
                                    </th>
                                    <td width="40%"><span
                                            id='patient_name_view'><?php echo ": " . $result['patients_gender'] . " (" . $result['age'] ?></span>
                                <tr>

                                    <?php if ($result['patient_mobileno']) {?>
                                <tr>
                                    <th width="15%"><?php echo $this->lang->line('phone'); ?></th>
                                    <td width="40%"><span
                                            id="phones_view"><?php echo ": " . $result['patient_mobileno'] ?></span>
                                    </td>
                                </tr>
                                <?php }?>
                                <tr>
                                    <th width="15%"><?php echo 'App No' ?></th>
                                    <td width="40%"><span
                                            id="appointment_no"><?php echo ": " . $result['appointment_no'] ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="15%"><?php echo $this->lang->line('doctor'); ?></th>
                                    <td width="40%"><?php echo ": " . $result['name'] . " " . $result['surname']; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="15%"><?php echo 'App Slot' ?></th>
                                    <td width="40%"><span
                                            id='dating'><?php echo ": " . $result['date'] . " (" . $result['global_shift_name'] . ")" ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="15%"></th>
                                    <td width="40%"><span
                                            id='dating'><?php echo "&nbsp;&nbsp;(" . $result["doctor_shift_name"] . ")" ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="15%"><?php echo $this->lang->line('amount'); ?></th>
                                    <td width="40%"><span
                                            id='pay_amount'><?php echo ": " . $currency_symbol . $result['amount'] ?><?php echo $result['payment_mode'] == "" ? "" : " (" . $result['payment_mode'] . ")" ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="15%"><?php echo $this->lang->line('date'); ?></th>
                                    <td width="40%"><span
                                            id='pay_amount'><?php echo ": " . date('d-m-Y H:m:s') ?></span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-sm-4">
                            <div
                                style="float:right;width:40%;text-align:center;border-top:1px solid;margin-top: 80px;font-weight: bold;">
                                <?='Received By'?>
                            </div>
                        </div>
                    </div>
                    <!-- <div style="padding: 0  10px; width:50%; height:50vh; float:left;">
                        <address>
                            <center>
                                <h3><b><?php echo strtoupper("$res->name"); ?></h3>
                                <h4><?php echo $res->address; ?></h4>
                                <h4>Phone no: <?php echo $res->phone; ?></h4>
                            </center>
                        </address>
                        <div class="table-responsive">
                            <table class="table mb0" style="text-align:left; width:100%">
                                <tr>
                                    <th width="15%"><?php echo $this->lang->line('patient'); ?></th>
                                    <td width="40%"><span
                                            id='patient_name_view'><?php echo ": " . $result['patients_name'] ?></span>
                                <tr>
                                <tr>
                                    <th width="15%">
                                        <?php echo $this->lang->line('gender') . ' & ' . $this->lang->line('age'); ?>
                                    </th>
                                    <td width="40%"><span
                                            id='patient_name_view'><?php echo ": " . $result['patients_gender'] . " (" . $result['age'] . ' Years)' ?></span>
                                <tr>
                                    <?php if ($result['patient_mobileno']) {?>
                                <tr>
                                    <th width="15%"><?php echo $this->lang->line('phone'); ?></th>
                                    <td width="40%"><span
                                            id="phones_view"><?php echo ": " . $result['patient_mobileno'] ?></span>
                                    </td>
                                </tr>
                                <?php }?>
                                <tr>
                                    <th width="15%"><?php echo 'App No' ?></th>
                                    <td width="40%"><span
                                            id="appointment_no"><?php echo ": " . $result['appointment_no'] ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="15%"><?php echo $this->lang->line('doctor'); ?></th>
                                    <td width="40%"><?php echo ": " . $result['name'] . " " . $result['surname']; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="15%"><?php echo 'App Slot' ?></th>
                                    <td width="40%"><span
                                            id='dating'><?php echo ": " . $result['date'] . " (" . $result['global_shift_name'] . ")" ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="15%"></th>
                                    <td width="40%"><span
                                            id='dating'><?php echo "&nbsp;&nbsp;(" . $result["doctor_shift_name"] . ")" ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="15%"><?php echo $this->lang->line('amount'); ?></th>
                                    <td width="40%"><span
                                            id='pay_amount'><?php echo ": " . $currency_symbol . $result['amount'] ?><?php echo $result['payment_mode'] == "" ? "" : " (" . $result['payment_mode'] . ")" ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="15%"><?php echo $this->lang->line('date'); ?></th>
                                    <td width="40%"><span
                                            id='pay_amount'><?php echo ": " . date('d-m-Y H:m:s') ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="15%"><?php echo 'app priority' ?></th>
                                    <td width="40%"><span
                                            id="priority"><?php echo ": " . $result['appoint_priority'] ?></span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div> -->
                    <!-- <div style="padding: 10px; margin-left:10px; width:calc(50% - 10px); height:50vh; float:left; ">
                        <address>
                            <h3><?php echo $res->name; ?></h3>
                            <h4><?php echo $res->address; ?></h4>
                            <h4>Phone no: <?php echo $res->phone; ?></h4>
                        </address>

                        <div style="display:flex;">
                            <label for=""><?php echo $this->lang->line('patient_name'); ?></label>
                            <span
                                id='patient_name_view'><?php echo ": " . $result['patients_name'] . "/" . $result['patients_gender'] . "/ " . $result['age'] . ' year' ?></span>
                        </div>
                        <div style="display:flex;">
                            <label for=""><?php echo $this->lang->line('phone'); ?></label>
                            <span id="phones_view"><?php echo ": " . $result['patient_mobileno'] ?></span>
                        </div>
                        <div style="display:flex;">
                            <label for=""><?php echo $this->lang->line('appointment_no'); ?></label>
                            <span id="appointment_no"><?php echo ": " . $result['appointment_no'] ?>
                        </div>
                        <div style="display:flex;">
                            <label for=""><?php echo $this->lang->line('doctor'); ?></label>
                            <span id='doctors'><?php echo ": " . $result['name'] . " " . $result['surname']; ?></span>
                        </div>
                        <div style="display:flex;">
                            <label for=""><?php echo $this->lang->line('appointment_date'); ?></label>
                            <span
                                id='dating'><?php echo ": " . $result['date'] . " (" . $result['global_shift_name'] . ")" ?></span>
                        </div>
                        <div style="display:flex;">
                            <label for=""><?php echo $this->lang->line('amount'); ?></label>
                            <span
                                id='pay_amount'><?php echo ": " . $currency_symbol . $result['amount'] ?><?php echo $result['payment_mode'] == "" ? "" : " (" . $result['payment_mode'] . ")" ?></span>
                        </div>
                        <div style="display:flex;">
                            <label for=""><?php echo $this->lang->line('appointment_priority'); ?></label>
                            <span id="priority"><?php echo ": " . $result['appoint_priority'] ?></span>
                        </div>
                    </div> -->
                </div>
                <!-- <div style="border:1px solid; margin:10px;  margin-top:-1%;border-top:0px dashed; height:45vh;">
                    <div style="padding: 10px; margin-left:10px; width:calc(50% - 10px); height:50vh; float:left;">
                        <address>
                            <h3><b><?php echo strtoupper("$res->name"); ?></h3>
                            <h4><?php echo $res->address; ?></h4>
                            <h4>Phone no: <?php echo $res->phone; ?></h4>
                        </address>
                        <div class="table-responsive">
                            <table class="table mb0" style="text-align:left; width:100%">
                                <tr>
                                    <th width="15%"><?php echo $this->lang->line('patient_name'); ?></th>
                                    <td width="40%"><span
                                            id='patient_name_view'><?php echo ": " . $result['patients_name'] . "/" ?><?php echo $result['patients_gender'] = "Male" ? "M" : "F" ?><?php echo "/" . $result['age'] . ' year' ?></span>
                                </tr>
                                <?php if ($result['patient_mobileno']) {?>
                                <tr>
                                    <th width="15%"><?php echo $this->lang->line('phone'); ?></th>
                                    <td width="40%"><span
                                            id="phones_view"><?php echo ": " . $result['patient_mobileno'] ?></span>
                                    </td>
                                </tr>
                                <?php }?>
                                <tr>
                                    <th width="15%"><?php echo 'app no' ?></th>
                                    <td width="40%"><span
                                            id="appointment_no"><?php echo ": " . $result['appointment_no'] ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="15%"><?php echo $this->lang->line('doctor'); ?></th>
                                    <td width="40%"><?php echo ": " . $result['name'] . " " . $result['surname']; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="15%"><?php echo 'app date' ?></th>
                                    <td width="40%"><span
                                            id='dating'><?php echo ": " . $result['date'] . " (" . $result['global_shift_name'] . ")" ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="15%"><?php echo $this->lang->line('amount'); ?></th>
                                    <td width="40%"><span
                                            id='pay_amount'><?php echo ": " . $currency_symbol . $result['amount'] ?><?php echo $result['payment_mode'] == "" ? "" : " (" . $result['payment_mode'] . ")" ?></span>
                                    </td>
                                </tr>
                                 <tr>
                                    <th width="15%"><?php echo 'app priority' ?></th>
                                    <td width="40%"><span
                                            id="priority"><?php echo ": " . $result['appoint_priority'] ?></span>
                                    </td>
                                </tr>

                            </table>
                        </div>
                    </div>
                </div> -->

                <!-- <hr style="height: 1px; clear: both;margin-bottom: 10px; margin-top: 10px">
                <form id="view" accept-charset="utf-8" method="get" class="pt5 pb5">
                    <div class="table-responsive col-6">

                        <table class="table mb0 table-striped table-bordered examples" id="field_data">
                            <?php if (!empty($fields)) {foreach ($fields as $fields_key => $fields_value) {?>
                            <tr>
                                <th width="20%"><?php echo $fields_value->name . ': '; ?></th>
                                <td width="35%"> <?php echo $result["$fields_value->name"]; ?></td>
                                <th width="20%"></th>
                                <td width="35%"></td>
                            </tr>
                            <?php }}?>
                        </table>
                    </div>
                </form>
                <hr style="height: 1px; clear: both;margin-bottom: 10px; margin-top: 10px">
                <p><?php if (!empty($print_details[0]['print_footer'])) {echo $print_details[0]['print_footer'];}?>
                </p> -->
            </div>
        </div>

        <!--/.col (left) -->
    </div>
</div>

</html>