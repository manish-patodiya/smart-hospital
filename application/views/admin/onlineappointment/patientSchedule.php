<style>
.bootstrap-datetimepicker-widget {
    overflow: visible !important
}
</style>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('doctor_wise_appointment'); ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('appointment', 'can_add')) {?>
                            <a data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-sm addappointment">
                                <i class="fa fa-plus"></i> <?php echo $this->lang->line('add_appointment'); ?></a>
                            <?php }?>
                            <!-- <a href="<?php echo base_url("admin/onlineappointment/patientschedule"); ?>"
                                class="btn btn-primary btn-sm"><i class="fa fa-reorder"></i>
                                <?php echo $this->lang->line('doctor_wise'); ?></a> -->
                            <!-- <a href="<?php echo base_url("admin/onlineappointment/patientqueue"); ?>"
                                class="btn btn-primary btn-sm"><i class="fa fa-reorder"></i>
                                <?php echo $this->lang->line('queue'); ?></a> -->
                        </div><!-- /.box-header -->
                    </div>
                    <div class="box-body">
                        <form action="<?php echo site_url("admin/onlineappointment/patientschedule"); ?>"
                            id="form-doctorwise-appo" method="post" accept-charset="utf-8">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('doctor') ?></label>
                                        <span class="req"> *</span>
                                        <select name="doctor" id="doctor" class="form-control select2">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php foreach ($doctors as $doctor_key => $doctor_value) {?>
                                            <option value="<?php echo $doctor_value['id']; ?>"
                                                <?php echo $doctor_value["id"] == set_value("doctor") ? "selected" : ""; ?>>
                                                <?php echo $doctor_value['name'] . " " . $doctor_value['surname']; ?>
                                            </option>
                                            <?php }?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('doctor'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="date"><?php echo $this->lang->line('date') . " " ?></label>
                                        <span class="req"> *</span>
                                        <div class='input-group'>
                                            <input type='text' id="date" value="<?php echo set_value('date'); ?>"
                                                class="form-control date" name="date" /><span
                                                class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        </div>
                                        <span class="text-danger"><?php echo form_error('date'); ?></span>
                                    </div>
                                </div>
                            </div>
                            <button type="submit"
                                class="btn btn-primary btn-sm pull-right"><?php echo $this->lang->line('search'); ?></button>
                        </form>
                    </div>

                    <?php if (isset($doctor_id)) {

    ?>
                    <div class="box-body">
                        <div class="download_label"><?php echo $this->lang->line('doctor_wise_appointment'); ?></div>
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-hover table-striped table-bordered dt-list"
                                data-export-title="<?php echo $this->lang->line('doctor_wise_appointment'); ?>">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('patient_name'); ?></th>
                                        <th><?php echo $this->lang->line('phone'); ?></th>
                                        <th><?php echo $this->lang->line('time'); ?></th>
                                        <th><?php echo $this->lang->line('email'); ?></th>
                                        <th><?php echo $this->lang->line('date'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line("source"); ?></th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php }?>
                </div>
            </div>
        </div>
    </section>
</div>


<?php
$data = [
    "disable_option" => $disable_option,
    "doctors" => $doctors,
    "doctor_select" => $doctor_select,
    "appoint_priority_list" => $appoint_priority_list,
    "payment_mode" => $payment_mode,
    "yesno_condition" => $yesno_condition,
];
$this->load->view("admin/modals/add_appointment_mdl", $data);
$this->load->view('admin/patient/patientaddmodal');
?>

<!-- //========datatable start===== -->
<script type="text/javascript">
(function($) {
    'use strict';
    $(document).ready(function() {
        $(".select2").select2();
        initDatatable('dt-list',
            'admin/onlineappointment/getpatientschedule/?doctor=<?php echo isset($doctor_id) ? $doctor_id : ""; ?>&date=<?php echo isset($date) ? $date : ""; ?>'
        );


        $("#doctor").change(function(e) {
            e.preventDefault();
            if ($("#date").val()) {
                $("#form-doctorwise-appo").submit();
            }
        });

        $("#date").change(function(e) {
            e.preventDefault();
            if ($("#doctor").val()) {
                $("#form-doctorwise-appo").submit();
            }
        });
    });
}(jQuery))
</script>
<!-- //========datatable end===== -->