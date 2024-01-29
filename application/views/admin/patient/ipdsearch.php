<?php
$currency_symbol = $this->customlib->getHospitalCurrencyFormat();
$genderList = $this->customlib->getGender();
?>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title titlefix"> <?php echo $this->lang->line('ipd_patient'); ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('ipd_patient', 'can_add')) {?>
                            <a data-toggle="modal" onclick="holdModal('myModal')" id="addp"
                                class="btn btn-primary btn-sm addpatient"><i class="fa fa-plus"></i>
                                <?php echo 'New Admission' ?></a>
                            <?php }?>
                            <?php if ($this->rbac->hasPrivilege('discharged_patients', 'can_view')) {?>
                            <a href="<?php echo base_url() ?>admin/patient/discharged_patients"
                                class="btn btn-primary btn-sm"><i class="fa fa-reorder"></i>
                                <?php echo $this->lang->line('discharged_patient'); ?></a>
                            <?php }?>
                        </div>
                    </div><!-- /.box-header -->


                    <div class="box-body" id="box-body">
                        <div class="download_label"><?php echo $this->lang->line('ipd_patient'); ?></div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover ajaxlist" cellspacing="0"
                                width="100%" data-export-title="<?php echo $this->lang->line('ipd_patient'); ?>">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('ipd_no'); ?></th>
                                        <th><?php echo $this->lang->line('case_id'); ?></th>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('gender'); ?></th>
                                        <th><?php echo $this->lang->line('phone'); ?></th>
                                        <th><?php echo $this->lang->line('consultant'); ?></th>
                                        <th><?php echo $this->lang->line('bed'); ?></th>
                                        <?php if (!empty($fields)) {foreach ($fields as $fields_key => $fields_value) {?>
                                        <th><?php echo $fields_value->name; ?></th>
                                        <?php }}?>
                                        <th class="text-right">
                                            <?php echo $this->lang->line('credit_limit') . " (" . $currency_symbol . ")"; ?>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg modalfullmobile" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close pt4" data-dismiss="modal">&times;</button>
                <div class="row">
                    <h3 class="text-white" style="margin:0"> <?php echo 'New Admission' ?></h3>
                    <!-- <div class="col-sm-5 col-xs-5">
                        <div class="form-group15">
                            <div>
                                <select onchange="get_PatientDetails(this.value)" class="form-control patient_list_ajax"
                                    style="width:100%" id="addpatient_id" name=''>
                                </select>
                            </div>
                            <span class="text-danger"><?php echo form_error('refference'); ?></span>
                        </div>
                    </div> -->
                    <!--./col-sm-6 col-xs-8 -->
                    <!-- <div class="col-sm-4 col-xs-4">
                        <div class="form-group15">
                            <?php if ($this->rbac->hasPrivilege('patient', 'can_add')) {?>
                            <a data-toggle="modal" id="addpip" onclick="holdModal('myModalpa')"
                                class="modalbtnpatient"><i class="fa fa-plus"></i>
                                <span><?php echo $this->lang->line('new_patient'); ?></span></a>
                            <?php }?>
                        </div>
                    </div> -->
                </div>
                <!--./row-->
            </div>
            <form id="formadd" accept-charset="utf-8" action="<?php echo base_url("admin/patient/add_inpatient") ?>"
                enctype="multipart/form-data" method="post" style="padding:1.5rem">
                <div class="row" style="margin-bottom:1.5rem">
                    <input id="patientuniqueid" name="patientunique_id" placeholder="" type="hidden"
                        class="form-control" value="" />
                    <input name="patient_id" id="patient_id" type="hidden" class="form-control" />
                    <input name="email" id="pemail" type="hidden" class="form-control" />
                    <input type="hidden" class="form-control" id="password" name="password">

                    <div class="col-md-3">
                        <label><?php echo $this->lang->line('patient_name'); ?></label><small class="req"> *</small>
                        <input name="patient_name" id="patient_name" list="patient-datalist" type="text"
                            class="form-control" />
                        <datalist id="patient-datalist"></datalist>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('age') . ' (Y/M/D)'; ?>
                            </label><small class="req"> *</small>
                            <div style="clear: both;overflow: hidden;">
                                <input type="number" placeholder="<?php echo $this->lang->line('year'); ?>"
                                    name="age[year]" id="age_year" value="" class="form-control patient_age_year"
                                    style="width: 30%; float: left;">
                                <input type="number" id="age_month"
                                    placeholder="<?php echo $this->lang->line('month'); ?>" name="age[month]" value="0"
                                    class="form-control patient_age_month"
                                    style="width: 30%;float: left; margin-left: 4px;">
                                <input type="number" id="age_day" placeholder="<?php echo $this->lang->line('day'); ?>"
                                    name="age[day]" value="0" class="form-control patient_age_day"
                                    style="width: 30%;float: left; margin-left: 4px;">
                            </div>
                        </div>

                    </div>
                    <div class="col-md-2">
                        <label><?php echo $this->lang->line('gender'); ?></label><small class="req"> *</small>
                        <select class="form-control" id="p_gender" name="gender">
                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                            <?php
foreach ($genderList as $key => $value) {
    ?>
                            <option value="<?php echo $key; ?>" <?php if (set_value('gender') == $key) {
        echo "selected";
    }
    ?>><?php echo $value; ?></option>
                            <?php
}
?>
                        </select>
                        <span class="text-danger"><?php echo form_error('gender'); ?></span>
                    </div>
                    <div class="col-md-2">
                        <label><?php echo $this->lang->line('phone'); ?></label><small class="req"> *</small>
                        <input name="mobileno" id="pmobileno"
                            onkeypress="return (this.value.length < 10) && (this.value == +this.value)" type="text"
                            class="form-control" />
                    </div>
                    <div class="col-md-3">
                        <label><?php echo $this->lang->line('guardian'); ?></label><small class="req"> *</small>
                        <input name="guardian" id="p_guardian" type="text" class="form-control" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="pwd"><?php echo $this->lang->line('address'); ?></label>
                            <textarea name="address" rows="2" class="form-control"
                                id='p_address'><?php echo set_value('address'); ?></textarea>
                        </div>
                    </div>
                </div>
                <hr style="margin:0">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="exampleInputFile">
                                <?php echo 'Package' ?></label>
                            <div>
                                <select class="form-control" name='package_id'>
                                    <option value=""><?php echo $this->lang->line('select') ?></option>
                                    <?php foreach ($packages as $key => $pack) {?>
                                    <?php $package = $pack->package_name . " (" . $pack->package_charge . ")"?>
                                    <option value="<?php echo $pack->id ?>"><?php echo $package ?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="exampleInputFile">
                                <?php echo $this->lang->line('bed_group'); ?></label>
                            <div>
                                <select class="form-control" name='bed_group_id' onchange="getBed(this.value)">
                                    <option value=""><?php echo $this->lang->line('select') ?></option>
                                    <?php foreach ($bedgroup_list as $key => $bedgroup) {?>
                                    <option value="<?php echo $bedgroup["id"] ?>">
                                        <?php echo $bedgroup["name"] . " - " . $bedgroup["floor_name"] ?>
                                    </option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="exampleInputFile">
                                <?php echo $this->lang->line('bed_number'); ?></label><small class="req">
                                *</small>
                            <div><select class="form-control select2" style="width: 100%" name='bed_no' id='bed_no'>
                                    <option value=""><?php echo $this->lang->line('select') ?></option>

                                </select>
                            </div>
                            <span class="text-danger"><?php echo form_error('bed_no'); ?></span>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('admission_date'); ?></label><small class="req">
                                *</small>
                            <input id="admission_date" name="appointment_date" placeholder="" type="text"
                                class="form-control datetime" />
                            <span class="text-danger"><?php echo form_error('appointment_date'); ?></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="exampleInputFile">
                                <?php echo $this->lang->line('consultant_doctor'); ?><small class="req">
                                    *</small></label>
                            <div>
                                <select class="form-control select2"
                                    <?php echo $disable_option == true ? "disabled" : "" ?> style="width:100%"
                                    id='consultant_doctor' name='consultant_doctor'>
                                    <option value=""><?php echo $this->lang->line('select') ?></option>
                                    <?php foreach ($doctors as $dkey => $dvalue) {?>
                                    <option value="<?php echo $dvalue["id"]; ?>"
                                        <?php echo isset($doctor_select) && $doctor_select == $dvalue["id"] ? "selected" : ""; ?>>
                                        <?php echo $dvalue["name"] . " " . $dvalue["surname"] . " (" . $dvalue["employee_id"] . ")" ?>
                                    </option>
                                    <?php }?>
                                </select>
                                <?php if ($disable_option == true) {?>
                                <input type="hidden" name="consultant_doctor" value="<?php echo $doctor_select ?>">
                                <?php }?>
                            </div>
                            <span class="text-danger"><?php echo form_error('refference'); ?></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <label for="exampleInputFile">
                            <?php echo 'Admission Charge' ?><small class="req">
                                *</small></label>
                        <select name="charge_id" id="charge_id" class="form-control">
                            <option value=""><?php echo $this->lang->line('select') ?></option>
                            <?php foreach ($admission_list as $key => $value) {?>
                            <option value="<?php echo $value->id ?>" charge='<?php echo $value->standard_charge ?>'>
                                <?php echo $value->name . ' (' . (int) $value->standard_charge . ')' ?></option>
                            <?php }?>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('amount'); ?><small class="req">
                                    *</small></label>
                            <input name="admission_fees" type='text' class="form-control" id="admission_fees" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('payment_mode'); ?></label>
                            <select class="form-control payment_mode" name="payment_mode">
                                <?php foreach ($payment_mode as $key => $value) {?>
                                <option value="<?php echo $key ?>" <?php if ($key == 'cash') {echo "selected";}?>>
                                    <?php echo $value ?></option>
                                <?php }?>
                            </select>
                            <span class="text-danger"><?php echo form_error('apply_charge'); ?></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="pwd"><?php echo $this->lang->line('note'); ?></label>
                            <textarea name="note" rows="2" class="form-control"
                                id="p_notes"><?php echo set_value('note'); ?></textarea>
                        </div>
                    </div>
                </div>


                <div class="box-footer">
                    <div class="pull-right">
                        <button type="submit" id="formaddbtn"
                            data-loading-text="<?php echo $this->lang->line('processing'); ?>"
                            class="btn btn-info pull-right"><i class="fa fa-check-circle"></i>
                            <?php echo $this->lang->line('save'); ?></button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>



<!-- revisit -->
<div class="modal fade" id="revisitModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title">
                    <?php echo $this->lang->line('patient') . " " . $this->lang->line('information'); ?></h4>
            </div>

            <div class="modal-body pt0 pb0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 paddlr">
                        <form id="formrevisit" accept-charset="utf-8" enctype="multipart/form-data" method="post"
                            class="ptt10">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>
                                            <?php echo $this->lang->line('patient') . " " . $this->lang->line('id'); ?></label>
                                        <input id="revisit_id" disabled name="patient_id" placeholder="" type="text"
                                            class="form-control" value="<?php echo set_value('roll_no'); ?>" />
                                        <span class="text-danger"><?php echo form_error('patient_id'); ?></span>
                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('name'); ?></label><small class="req">
                                            *</small>
                                        <input id="revisit_name" name="name" placeholder="" type="text"
                                            class="form-control" value="<?php echo set_value('name'); ?>" />
                                        <input type="hidden" name="id" id="pid">
                                        <span class="text-danger"><?php echo form_error('name'); ?></span>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('phone'); ?></label>
                                        <input id="revisit_contact" autocomplete="off" name="contact" placeholder=""
                                            type="text" class="form-control"
                                            value="<?php echo set_value('contact'); ?>" />

                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('appointment') . " " . $this->lang->line('date'); ?></label>
                                        <input id="revisit_date" name="appointment_date" placeholder="" type="text"
                                            class="form-control" />
                                        <span class="text-danger"><?php echo form_error('appointment_date'); ?></span>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="exampleInputFile">
                                            <?php echo $this->lang->line('case'); ?></label>
                                        <div><input class="form-control" type='text' id="revisit_case"
                                                name='revisit_case' />
                                        </div>
                                        <span class="text-danger"><?php echo form_error('case'); ?></span>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="exampleInputFile">
                                            <?php echo $this->lang->line('casualty'); ?></label>
                                        <div>
                                            <select name="casualty" id="revisit_casualty" class="form-control">
                                                <option value=""><?php echo $this->lang->line('select') ?></option>
                                                <option value="yes"><?php echo $this->lang->line('yes') ?></option>
                                                <option value="no"><?php echo $this->lang->line('no') ?></option>
                                            </select>
                                        </div>
                                        <span class="text-danger"><?php echo form_error('case'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="exampleInputFile">
                                            <?php echo $this->lang->line('old') . " " . $this->lang->line('patient'); ?></label>
                                        <div>
                                            <select name="old_patient" class="form-control">
                                                <option value=""><?php echo $this->lang->line('select') ?></option>
                                                <option value="yes"><?php echo $this->lang->line('yes') ?></option>
                                                <option value="no"><?php echo $this->lang->line('no') ?></option>
                                            </select>
                                        </div>
                                        <span class="text-danger"><?php echo form_error('case'); ?></span>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="email"><?php echo $this->lang->line('symtoms'); ?></label>
                                        <textarea name="symptoms" id="revisit_symptoms"
                                            class="form-control"><?php echo set_value('address'); ?></textarea>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label
                                            for="email"><?php echo $this->lang->line('any_known_allergies'); ?></label>
                                        <textarea name="known_allergies" id="revisit_allergies"
                                            class="form-control"><?php echo set_value('address'); ?></textarea>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="email"><?php echo $this->lang->line('address'); ?></label>
                                        <textarea name="address" id="revisit_address"
                                            class="form-control"><?php echo set_value('address'); ?></textarea>
                                    </div>
                                </div>


                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('note'); ?></label>
                                        <textarea name="note" id="revisit_note"
                                            class="form-control"><?php echo set_value('note'); ?></textarea>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="exampleInputFile">
                                            <?php echo $this->lang->line('refference'); ?></label>
                                        <div><input class="form-control" id="revisit_refference" type='text'
                                                name='refference' />
                                        </div>
                                        <span class="text-danger"><?php echo form_error('refference'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="exampleInputFile">
                                            <?php echo $this->lang->line('consultant') . " " . $this->lang->line('doctor'); ?></label>
                                        <div><select class="form-control select2" <?php
if ($disable_option == true) {
    echo "disabled";
}
?> name='consultant_doctor' id="revisit_doctor">
                                                <option value=""><?php echo $this->lang->line('select') ?></option>
                                                <?php foreach ($doctors as $dkey => $dvalue) {
    ?>
                                                <option value="<?php echo $dvalue["id"]; ?>" <?php
if ((isset($doctor_select)) && ($doctor_select == $dvalue["id"])) {
        echo "selected";
    }
    ?>><?php echo $dvalue["name"] . " " . $dvalue["surname"] ?></option>
                                                <?php }?>
                                            </select>
                                            <?php if ($disable_option == true) {?>
                                            <input type="hidden" name="consultant_doctor"
                                                value="<?php echo $doctor_select ?>">
                                            <?php }?>
                                        </div>
                                        <span class="text-danger"><?php echo form_error('refference'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('amount'); ?></label>
                                        <input name="amount" type="text" class="form-control" id="revisit_amount" />
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('tax'); ?></label>
                                        <input type="text" name="tax" id="revisi_tax" class="form-control" />
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label
                                            for="pwd"><?php echo $this->lang->line('payment') . " " . $this->lang->line('mode'); ?></label>
                                        <select name="payment_mode" id="revisit_payment" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select') ?></option>
                                            <?php foreach ($payment_mode as $payment_key => $payment_value) {
    ?>
                                            <option value="<?php echo $payment_key ?>"><?php echo $payment_value ?>
                                            </option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <!--./row-->
                            <button type="submit"
                                class="btn btn-info pull-right"><?php $this->lang->line('save');?></button>
                        </form>
                    </div>
                    <!--./col-md-12-->

                </div>
                <!--./row-->

            </div>
            <div class="box-footer">
                <div class="pull-right paddA10">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- dd -->
<div class="modal fade" id="myModaledit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title"><?php $this->lang->line('patient') . " " . $this->lang->line('information');?>
                </h4>
            </div>

            <div class="modal-body pt0 pb0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 paddlr">
                        <form id="formedit" accept-charset="utf-8" enctype="multipart/form-data" method="post"
                            class="ptt10">
                            <div class="row">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('name'); ?></label><small class="req red">
                                            *</small>
                                        <input id="patient_name" name="name" placeholder="" type="text"
                                            class="form-control" value="<?php echo set_value('name'); ?>" />
                                        <input type="hidden" id="updateid" name="updateid">
                                        <input type="hidden" id="opdid" name="opdid">
                                        <span class="text-danger"><?php echo form_error('name'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('guardian_name'); ?></label>
                                        <input type="text" id="guardian_name" name="guardian_name" value=""
                                            class="form-control">

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label> <?php echo $this->lang->line('gender'); ?></label><small class="req">
                                            *</small>
                                        <select class="form-control" id="gender" name="gender">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
foreach ($genderList as $key => $value) {
    ?>
                                            <option value="<?php echo $key; ?>" <?php if (set_value('gender') == $key) {
        echo "selected";
    }
    ?>><?php echo $value; ?></option>
                                            <?php
}
?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('gender'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('marital_status'); ?></label>
                                        <select name="marital_status" id="marital_status" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select') ?></option>
                                            <?php foreach ($marital_status as $mkey => $mvalue) {
    ?>
                                            <option value="<?php echo $mkey ?>"><?php echo $mvalue ?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('phone'); ?></label>
                                        <input id="contact" autocomplete="off" name="contact" placeholder="" type="text"
                                            class="form-control" value="<?php echo set_value('contact'); ?>" />
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="exampleInputFile">
                                            <?php echo $this->lang->line('patient') . " " . $this->lang->line('photo'); ?></label>
                                        <div><input class="filestyle form-control" type='file' name='file' id="file"
                                                size='20' />
                                            <input type="hidden" name="patient_photo" id="patient_photo">
                                        </div>
                                        <span class="text-danger"><?php echo form_error('file'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('email'); ?></label>
                                        <input type="text" id="email" value="<?php echo set_value('email'); ?>"
                                            name="email" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label> <?php echo $this->lang->line('blood_group'); ?></label><small
                                            class="req"> *</small>
                                        <select class="form-control" id="bloodgroup" name="blood_group">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
foreach ($bloodgroup as $key => $value) {
    ?>
                                            <option value="<?php echo $value; ?>" <?php if (set_value('gender') == $key) {
        echo "selected";
    }
    ?>><?php echo $value; ?></option>
                                            <?php
}
?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('gender'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('age'); ?></label>

                                        <div style="clear: both;overflow: hidden;">
                                            <input type="text" placeholder="<?php echo $this->lang->line('year') ?>"
                                                name="age" id="age" class="form-control"
                                                value="<?php echo set_value('age'); ?>"
                                                style="width: 40%; float: left;">
                                            <input type="text" placeholder="Month" name="month" id="month"
                                                value="<?php echo set_value('month'); ?>" class="form-control"
                                                style="width: 56%;float: left; margin-left: 5px;">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('height'); ?></label>
                                        <input type="text" id="height" name="height"
                                            value="<?php echo set_value('height'); ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('weight'); ?></label>
                                        <input type="text" id="weight" name="weight"
                                            value="<?php echo set_value('weight'); ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="exampleInputFile">
                                            <?php echo $this->lang->line('organisation'); ?></label>
                                        <div><select class="form-control" name='organisation'>
                                                <option value=""><?php echo $this->lang->line('select') ?></option>
                                                <?php foreach ($organisation as $orgkey => $orgvalue) {
    ?>
                                                <option value="<?php echo $orgvalue["id"]; ?>">
                                                    <?php echo $orgvalue["organisation_name"] ?></option>
                                                <?php }?>
                                            </select>
                                        </div>
                                        <span class="text-danger"><?php echo form_error('refference'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="exampleInputFile">
                                            <?php echo $this->lang->line('credit_limit'); ?></label>
                                        <div><input type="text" name="credit_limit" id="credit_limit"
                                                class="form-control">
                                        </div>
                                        <span class="text-danger"><?php echo form_error('refference'); ?></span>
                                    </div>
                                </div>
                            </div>
                            <!--./row-->
                            <button type="submit"
                                class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                        </form>
                    </div>
                    <!--./col-md-12-->
                </div>
                <!--./row-->
            </div>
            <div class="box-footer">
                <div class="pull-right paddA10">
                </div>
            </div>
        </div>
    </div>
</div>

<script>
var rows = 2;


var link = 1;
$(document).on('click', '.custom-select', function() {
    var currents = $(this);

    if (currents.parent().find('div.section_checkboxs').is(":visible")) {
        currents.parent().find('div.section_checkboxs').hide();
    } else {
        currents.parent().find('div.section_checkboxs').show();
    }

});

function toggleFillColor(obj) {
    if ($(obj).prop('checked') == true) {
        console.log($(obj).closest('li'));
        $(obj).closest('li').css("background-color", '#ddd');
    } else {
        $(obj).closest('li').css("background-color", '#FFF');
    }
}

$(document).on("click", ".checkbox", function(e) {
    var checkboxObj = $(this).children("input");
    toggleFillColor(checkboxObj);
});

$(document).click(function(e) {
    e.stopPropagation();
    var container = $(".a");

    //check if the clicked area is dropDown or not
    if (container.has(e.target).length === 0) {
        $("div.section_checkboxs").hide();
    }
})
</script>
<script type="text/javascript">
$(document).on('click', '.add-btn', function() {
    var s = "";
    s += "<div class='row'>";
    s += "<input name='rows[]' type='hidden' value='" + rows + "'>";
    s += "<div class='col-md-6'>";
    s += "<div class='form-group'>";
    s += "<label for='act'>Act</label>";
    s += "<select class='form-control act select2' id='act' name='act" + rows + "' data-row_id='" + rows + "'>";
    s += "<option value=''>--Select--</option>";
    s += $('#act-template').html();
    s += "</select>";
    s += "<small class='text text-danger help-inline'></small>";
    s += "</div>";
    s += "</div>";
    s += "<div class='col-md-5'>";
    s += "<label for='validationDefault02'>Section</label>";
    s += "<div id='dd' class='wrapper-dropdown-3'>";
    s += "<input class='form-control filterinput' type='text'>";
    s += "<ul class='dropdown scroll150 section_ul'>";
    s += "<li><label class='checkbox'>--Select--</label></li>";
    s += "</ul>";
    s += "</div>";
    s += "</div>";
    s += "<div class='col-md-1'>";
    s += "<div class='form-group'>";
    s += "<label for='removebtn'>&nbsp;</label>";
    s +=
        "<button type='button' class='form-control btn btn-sm btn-danger remove_row'><i class='fa fa-remove'></i></button>";
    s += "</div>";
    s += "</div>";
    s += "</div>";
    $(".multirow").append(s);
    $('.select2').select2();
    link = 2;
    rows++;
});
</script>

<script type="text/html" id="act-template">
<?php foreach ($symptomsresulttype as $dkey => $dvalue) {
    ?>
<option value="<?php echo $dvalue["id"]; ?>"><?php echo $dvalue["symptoms_type"]; ?></option>
<?php
}
?>
</script>

<script>
$(document).on('change', '.act', function() {
    $this = $(this);
    var sys_val = $(this).val();
    //console.log(sys_val);
    var row_id = $this.data('row_id');
    var section_ul = $(this).closest('div.row').find('ul.section_ul');

    var sel_option = "";
    $.ajax({
        type: 'POST',
        url: base_url + 'admin/patient/getPartialsymptoms',
        data: {
            'sys_id': sys_val,
            'row_id': row_id
        },
        dataType: 'JSON',
        beforeSend: function() {
            // setting a timeout
            $('ul.section_ul').find('li:not(:first-child)').remove();
            $("div.wrapper-dropdown-3").removeClass('active');

        },
        success: function(data) {

            section_ul.append(data.record);

        },
        error: function(xhr) { // if error occured
            alert("Error occured.please try again");

        },
        complete: function() {

        }
    });

});
</script>
<script type="text/javascript">
$(document).on('click', '.remove_row', function() {
    $this = $(this);
    $this.closest('.row').remove();

});
$(document).mouseup(function(e) {
    var container = $(".wrapper-dropdown-3"); // YOUR CONTAINER SELECTOR

    if (!container.is(e.target) // if the target of the click isn't the container...
        &&
        container.has(e.target).length === 0) // ... nor a descendant of the container
    {
        $("div.wrapper-dropdown-3").removeClass('active');
    }
});

$(document).on('click', '.filterinput', function() {

    if (!$(this).closest('.wrapper-dropdown-3').hasClass("active")) {
        $(".wrapper-dropdown-3").not($(this)).removeClass('active');
        $(this).closest("div.wrapper-dropdown-3").addClass('active');
    }


});

$(document).on('click', 'input[name="section[]"]', function() {
    $(this).closest('label').toggleClass('active_section');
});

$(document).on('keyup', '.filterinput', function() {

    var valThis = $(this).val().toLowerCase();
    var closer_section = $(this).closest('div').find('.section_ul > li');

    var noresult = 0;
    if (valThis == "") {
        closer_section.show();
        noresult = 1;
        $('.no-results-found').remove();
    } else {
        closer_section.each(function() {
            var text = $(this).text().toLowerCase();
            var match = text.indexOf(valThis);
            if (match >= 0) {
                $(this).show();
                noresult = 1;
                $('.no-results-found').remove();
            } else {
                $(this).hide();
            }
        });
    };
    if (noresult == 0) {
        closer_section.append('<li class="no-results-found">No results found.</li>');
    }
});
</script>


<script type="text/javascript">
$(function() {
    //Initialize Select2 Elements
    $('.select2').select2()
});
$(function() {
    $('#easySelectable').easySelectable();
});

function add_more() {

    var table = document.getElementById("tableID");
    var table_len = (table.rows.length);
    var id = parseInt(table_len);

    var div =
        "<td><input type='text' name='date[]' class='form-control datetime'></td><td><select name='doctor[]' class='select2' style='width:100%'><option value=''><?php echo $this->lang->line('select') ?></option><?php foreach ($doctors as $key => $value) {?><option value='<?php echo $value["id"] ?>'><?php echo $value["name"] . ' ' . $value["surname"] ?></option><?php }?></select></td><td><textarea name='instruction[]' style='height:28px;' class='form-control'></textarea></td><td><input type='text' name='insdate[]' class='form-control date'></td>";

    var row = table.insertRow(table_len).outerHTML = "<tr id='row" + id + "'>" + div +
        "<td><button type='button' onclick='delete_row(" + id +
        ")' class='closebtn'><i class='fa fa-remove'></i></button></td></tr>";

    $('.select2').select2();


}

function delete_row(id) {
    var table = document.getElementById("tableID");
    var rowCount = table.rows.length;
    $("#row" + id).html("");
    //table.deleteRow(id);
}
</script>
<script type="text/javascript">
/*
     Author: mee4dy@gmail.com
     */
(function($) {
    //selectable html elements
    $.fn.easySelectable = function(options) {
        var el = $(this);
        var options = $.extend({
            'item': 'li',
            'state': true,
            onSelecting: function(el) {

            },
            onSelected: function(el) {

            },
            onUnSelected: function(el) {

            }
        }, options);
        el.on('dragstart', function(event) {
            event.preventDefault();
        });
        el.off('mouseover');
        el.addClass('easySelectable');
        if (options.state) {
            el.find(options.item).addClass('es-selectable');
            el.on('mousedown', options.item, function(e) {
                $(this).trigger('start_select');
                var offset = $(this).offset();
                var hasClass = $(this).hasClass('es-selected');
                var prev_el = false;
                el.on('mouseover', options.item, function(e) {
                    if (prev_el == $(this).index())
                        return true;
                    prev_el = $(this).index();
                    var hasClass2 = $(this).hasClass('es-selected');
                    if (!hasClass2) {
                        $(this).addClass('es-selected').trigger('selected');
                        el.trigger('selected');
                        options.onSelecting($(this));
                        options.onSelected($(this));
                    } else {
                        $(this).removeClass('es-selected').trigger('unselected');
                        el.trigger('unselected');
                        options.onSelecting($(this))
                        options.onUnSelected($(this));
                    }
                });
                if (!hasClass) {
                    $(this).addClass('es-selected').trigger('selected');
                    el.trigger('selected');
                    options.onSelecting($(this));
                    options.onSelected($(this));
                } else {
                    $(this).removeClass('es-selected').trigger('unselected');
                    el.trigger('unselected');
                    options.onSelecting($(this));
                    options.onUnSelected($(this));
                }
                var relativeX = (e.pageX - offset.left);
                var relativeY = (e.pageY - offset.top);
            });
            $(document).on('mouseup', function() {
                el.off('mouseover');
            });
        } else {
            el.off('mousedown');
        }
    };
})(jQuery);
</script>

<script type="text/javascript">
$(document).ready(function(e) {
    $("#formadd").on('submit', (function(e) {
        $("#formaddbtn").button('loading');
        e.preventDefault();
        $.ajax({
            url: '<?php echo base_url(); ?>admin/patient/add_inpatient',
            type: "POST",
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {

                if (data.status == "fail") {

                    var message = "";
                    $.each(data.error, function(index, value) {

                        message += value;
                    });
                    errorMsg(message);
                } else {

                    successMsg(data.message);
                    $('#myModal').modal('hide');
                    $('.ajaxlist').DataTable().ajax.reload();
                    window.location =
                        '<?php echo base_url(); ?>/admin/patient/ipdsearch';
                }
                $("#formaddbtn").button('reset');
            },
            error: function() {}
        });


    }));
});


$(document).ready(function(e) {
    $("#formrevisit").on('submit', (function(e) {

        e.preventDefault();
        $.ajax({
            url: '<?php echo base_url(); ?>admin/patient/add_revisit',
            type: "POST",
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {

                if (data.status == "fail") {

                    var message = "";
                    $.each(data.error, function(index, value) {

                        message += value;
                    });
                    errorMsg(message);
                } else {

                    successMsg(data.message);
                    window.location.reload(true);
                }

            },
            error: function() {}
        });


    }));
});
/**/

$(document).ready(function(e) {
    $("#formedit").on('submit', (function(e) {

        e.preventDefault();
        $.ajax({
            url: '<?php echo base_url(); ?>admin/patient/update',
            type: "POST",
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {

                if (data.status == "fail") {

                    var message = "";
                    $.each(data.error, function(index, value) {

                        message += value;
                    });
                    errorMsg(message);
                } else {

                    successMsg(data.message);
                    window.location.reload(true);
                }

            },
            error: function() {}
        });


    }));
});

/**/
$(document).ready(function(e) {
    $("#formaddip").on('submit', (function(e) {
        $("#formaddipbtn").button('loading');
        e.preventDefault();
        $.ajax({
            url: '<?php echo base_url(); ?>admin/patient/addpatient',
            type: "POST",
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                if (data.status == "fail") {
                    var message = "";
                    $.each(data.error, function(index, value) {
                        message += value;
                    });
                    errorMsg(message);
                } else {
                    successMsg(data.message);
                    window.location.reload(true);
                }
                $("#formaddipbtn").button('reset');
            },
            error: function() {}
        });
    }));
});

function makeid(length) {
    var result = '';
    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    for (var i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}

function get_PatientDetails(id) {

    if (id == '') {
        $("#ajax_load").html("");
        $("#patientDetails").hide();
    } else {
        var base_url = "<?php echo base_url(); ?>backend/images/loading.gif";
        $("#ajax_load").html("<center><img src='" + base_url + "'/>");
        var password = makeid(5);
        $.ajax({
            url: '<?php echo base_url(); ?>admin/patient/patientDetails',
            type: "POST",
            data: {
                id: id
            },
            dataType: 'json',
            success: function(res) {

                if (res) {
                    $("#ajax_load").html("");
                    $('#patientuniqueid').val(res.patient_unique_id);
                    $('#patient_id').val(res.id);
                    $('#password').val(password);
                    $('#patient_name').val(res.patient_name);
                    $('#pemail').val(res.email);
                    $('#pmobileno').val(parseInt(res.mobileno));
                    $('#p_guardian').val(res.guardian_name);
                    $("#p_address").val(res.address);
                    $("#age_year").val(res.age);
                    $("#age_month").val(res.month);
                    $("#age_day").val(res.day);
                    $("#p_notes").val(res.note);
                    $("#p_gender").val(res.gender);
                } else {
                    $("#ajax_load").html("");
                    $("#patientDetails").hide();
                }
            }
        });
    }
}

function getRecord(id) {

    $.ajax({
        url: '<?php echo base_url(); ?>admin/patient/getIpdDetails',
        type: "POST",
        data: {
            recordid: id
        },
        dataType: 'json',
        success: function(data) {
            $("#patientid").val(data.patient_unique_id);
            $("#patient_name").val(data.patient_name);
            $("#contact").val(data.mobileno);
            $("#email").val(data.email);
            $("#age").val(data.age);
            $("#bloodgroup").val(data.blood_group);
            $("#guardian_name").val(data.guardian_name);
            $("#appointment_date").val(data.appointment_date);
            $("#case").val(data.case_type);
            $("#symptoms").val(data.symptoms);
            $("#known_allergies").val(data.known_allergies);
            $("#refference").val(data.refference);
            $("#credit_limit").val(data.credit_limit);
            $("#amount").val(data.amount);
            $("#tax").val(data.tax);
            $("#opdid").val(data.opdid);
            $("#address").val(data.address);
            $("#note").val(data.note);
            $("#height").val(data.height);
            $("#weight").val(data.weight);
            $("#updateid").val(id);
            $('select[id="gender"] option[value="' + data.gender + '"]').attr("selected", "selected");
            $('select[id="marital_status"] option[value="' + data.marital_status + '"]').attr("selected",
                "selected");
            $('select[id="consultant_doctor"] option[value="' + data.cons_doctor + '"]').attr("selected",
                "selected");
            $(".select2").select2().select2('val', data.cons_doctor);
            $('select[id="payment_mode"] option[value="' + data.payment_mode + '"]').attr("selected",
                "selected");
            $('select[id="casualty"] option[value="' + data.casualty + '"]').attr("selected", "selected");
        },

    })
}

function get_symptoms(id) {


    $.ajax({
        url: '<?php echo base_url(); ?>admin/patient/getsymptoms',
        type: "POST",
        data: {
            id: id
        },
        dataType: 'json',
        success: function(res) {
            if (res) {

                $('#symptoms_description').val(res.description);

            } else {
                $('#symptoms_description').val("");
            }
        }
    });
}

function getRevisitRecord(id) {

    $.ajax({
        url: '<?php echo base_url(); ?>admin/patient/getDetails',
        type: "POST",
        data: {
            recordid: id
        },
        dataType: 'json',
        success: function(data) {
            $("#revisit_id").val(data.patient_unique_id);
            $("#revisit_name").val(data.patient_name);
            $("#revisit_contact").val(data.mobileno);
            $("#revisit_date").val(data.appointment_date);
            $("#revisit_case").val(data.case_type);
            $("#pid").val(id);
            $("#revisit_allergies").val(data.known_allergies);
            $("#revisit_refference").val(data.refference);
            $("#revisit_amount").val(data.amount);
            $("#revisit_symptoms").val(data.symptoms);
            $("#revisi_tax").val(data.tax);
            $("#revisit_address").val(data.address);
            $("#revisit_note").val(data.note);
            $('select[id="revisit_doctor"] option[value="' + data.cons_doctor + '"]').attr("selected",
                "selected");
            $('select[id="revisit_payment"] option[value="' + data.payment_mode + '"]').attr("selected",
                "selected");
            $('select[id="revisit_casualty"] option[value="' + data.casualty + '"]').attr("selected",
                "selected");
        },

    })
}

$(document).ready(function(e) {
    $("#consultant_register").on('submit', (function(e) {

        var doctor_id = $("#doctor_field").val();

        $("#doctor_set").val(doctor_id);
        $("#consultant_registerbtn").button('loading');
        e.preventDefault();
        $.ajax({
            url: '<?php echo base_url(); ?>admin/patient/add_consultant_instruction',
            type: "POST",
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {

                if (data.status == "fail") {

                    var message = "";
                    $.each(data.error, function(index, value) {

                        message += value;
                    });
                    errorMsg(message);
                } else {

                    successMsg(data.message);
                    window.location.reload(true);

                }
                $("#consultant_registerbtn").button('reset');
            },
            error: function() {}
        });


    }));
});

function getBed(bed_group, bed = '') {
    var div_data = "";
    $('#bed_no').html("<option value='l'><?php echo $this->lang->line('loading') ?></option>");

    $.ajax({
        url: '<?php echo base_url(); ?>admin/setup/bed/getbedbybedgroup',
        type: "POST",
        data: {
            bed_group: bed_group,
            active: 'yes'
        },
        dataType: 'json',
        success: function(res) {
            $.each(res, function(i, obj) {
                var sel = "";
                if ((bed != '') && (bed == obj.id)) {
                    sel = "selected";
                }
                div_data += "<option value=" + obj.id + " " + sel + ">" + obj.name + "</option>";
            });
            $("#bed_no").html("<option value=''>Select</option>");
            $('#bed_no').append(div_data);
            $("#bed_no").select2().select2('val', bed);
        }
    });
}

function add_inpatient(bed, bedgroup) {

    $('select[name="bed_group_id"] option[value="' + bedgroup + '"]').attr("selected", "selected");
    getBed(bedgroup, bed);
    holdModal('myModal');
}

function holdModal(modalId) {
    $('#' + modalId).modal({
        backdrop: 'static',
        keyboard: false,
        show: true
    });
}

$(document).ready(function() {
    $('.detail_popover').popover({
        placement: 'right',
        trigger: 'hover',
        container: 'body',
        html: true,
        content: function() {
            return $(this).closest('a').find('.fee_detail_popover').html();
        }
    });
});
</script>
<script type="text/javascript">
$('#myModal').on('hidden.bs.modal', function() {
    $('#formadd').trigger('reset');
});


$(".modalbtnpatient").click(function() {
    $('#formaddpa').trigger("reset");
    $(".dropify-clear").trigger("click");
});

function refreshmodal() {
    $('#formaddpa').trigger("reset");
    var table = document.getElementById("tableID");
    var table_len = (table.rows.length);
    for (i = 1; i < table_len; i++) {
        delete_row(i);
    }
}
</script>

<!-- //========datatable start===== -->
<!-- <script type="text/javascript">
(function($) {

    'use strict';
    $(document).ready(function() {
        initDatatable('ajaxlist', 'admin/patient/getipddatatable', [], [], 100);
    });
}(jQuery))
</script> -->

<!-- //========datatable end===== -->
<?php $this->load->view('admin/patient/patientaddmodal')?><script>
$.ajax({
    url: '<?php echo base_url(); ?>admin/patient/getBedStatusIPD/',
    type: 'POST',
    data: '',
    success: function(res) {
        $("#box-body").html(res);
    }
})
$('#patient_name').on("input", function() {
    $search_val = $(this).val();
    if (checkPatiendID() == false) {
        $("#ajax_load").html("");
        $('#patientuniqueid').val('');
        $('#patient_id').val('');
        $('#password').val('');
        $('#pemail').val('');
        $('#pmobileno').val('');
        $('#p_guardian').val('');
        $("#p_address").val('');
        $("#p_age").val('');
        $("#p_notes").val('');
        $("#p_gender").val('');
        $.ajax({
            url: "http://localhost/sgh/admin/patient/getPatientListAjax",
            type: "post",
            dataType: 'json',
            delay: 300,
            data: {
                'searchTerm': $search_val // search term
            },
            success: function(res) {
                if (res.length) {
                    $('#patient-datalist').html('');
                    res.map(function(ele) {
                        $('#patient-datalist').append(`
                    <option value="${ele.text}" pid='${ele.id}'></option>
                    `)
                    });
                }
            }
        })
    }
})

$('#charge_id').change(function() {
    var charge = $(this).find('option:selected').attr('charge');
    $('#admission_fees').val(charge);
})

function checkPatiendID() {
    var val = document.getElementById("patient_name").value.trim();
    var opts = document.getElementById('patient-datalist').childNodes;
    for (var i = 0; i < opts.length; i++) {
        if (opts[i].value === val) {
            $('#patient_id').val($(opts[i]).attr('pid'));
            get_PatientDetails($(opts[i]).attr('pid'));
            return true;
        }
    }
    return false;
}
</script>