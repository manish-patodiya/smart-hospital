<div class="modal fade" id="myModal" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close pt4" data-dismiss="modal">&times;</button>
                <h3 class="text-white" style="margin:0"><?php echo $this->lang->line('add_appointment') ?></h3>
                <!-- <div class="row">
                    <div class="col-sm-8 col-xs-8">
                        <div class="row">
                            <div class="col-lg-2 col-md-2 col-sm-3 col-xs-1">
                                <div class="p-2">
                                    <?php if ($this->rbac->hasPrivilege('patient', 'can_add')) {?>
                                    <a data-toggle="modal" id="add" onclick="holdModal('myModalpa')"
                                        class="modalbtnpatient"><i class="fa fa-plus"></i>
                                        <span><?php echo $this->lang->line('new_patient'); ?></span></a>
                                    <?php }?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
            <form id="formadd" accept-charset="utf-8" method="post">
                <div class="">
                    <div class="modal-body pb0">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="row">
                                    <div class="col-sm-12 col-xs-8">
                                        <div class="col-sm-3">
                                            <div class="p-2 select2-full-width">
                                                <label><?php echo $this->lang->line('patient_name'); ?></label>
                                                <input class="form-control" list="patient-datalist" id="patient_name"
                                                    name="patient_name" placeholder="Enter Patient Name">
                                                <input type="hidden" class="form-control" id="patient_id"
                                                    name="patient_id" placeholder="Enter Patient Name">

                                                <datalist id="patient-datalist"></datalist>
                                                <span class="text-danger"><?php echo form_error('name'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label> <?php echo $this->lang->line('gender'); ?></label>
                                                </label><small class="req"> *</small>
                                                <select class="form-control" name="gender" id="addformgender">
                                                    <option value="">
                                                        <?php echo $this->lang->line('select'); ?>
                                                    </option>
                                                    <?php foreach ($genderList as $key => $value) {?><option
                                                        value="<?php echo $key; ?>"
                                                        <?php if (set_value('gender') == $key) {echo "selected";}?>>
                                                        <?php echo $value; ?></option>
                                                    <?php }?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-3" id="calculate">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('age') . ' (Y/M/D)'; ?>
                                                </label><small class="req"> *</small>
                                                <!-- <div style="clear: both;overflow: hidden;">
                                                    <input type="text"
                                                        placeholder="<?php echo $this->lang->line('year'); ?>"
                                                        name="age" id="age_year" value=""
                                                        class="form-control patient_age_year"
                                                        style="width: 95%; float: left;">
                                                </div> -->
                                                <div style="clear: both;overflow: hidden;">
                                                    <input type="number"
                                                        placeholder="<?php echo $this->lang->line('year'); ?>"
                                                        name="age[year]" id="age_year" value=""
                                                        class="form-control patient_age_year"
                                                        style="width: 30%; float: left;">

                                                    <input type="number" id="age_month"
                                                        placeholder="<?php echo $this->lang->line('month'); ?>"
                                                        name="age[month]" value="0"
                                                        class="form-control patient_age_month"
                                                        style="width: 36%;float: left; margin-left: 4px;">
                                                    <input type="number" id="age_day"
                                                        placeholder="<?php echo $this->lang->line('day'); ?>"
                                                        name="age[day]" value="0" class="form-control patient_age_day"
                                                        style="width: 26%;float: left; margin-left: 4px;">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <lable>
                                                <label><?php echo $this->lang->line('phone'); ?></label>
                                            </lable>
                                            <input type="text" name="mobileno"
                                                onkeypress="return (this.value.length < 10) && (this.value == +this.value)"
                                                id="patient-phone" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <!--./col-sm-12-->
                                <hr>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="exampleInputFile"><?php echo $this->lang->line('doctor'); ?></label>
                                        <small class="req"> *</small>
                                        <div>
                                            <select class="form-control select2 doctor_select2" name="doctorid"
                                                onchange="getDoctorShift(this);getDoctorFees(this)" <?php
if ((isset($disable_option)) && ($disable_option == true)) {
    echo 'disabled';
}
?> name='doctor' id="doctorid" style="width:100%">
                                                <option value="<?php echo set_value('doctor'); ?>">
                                                    <?php echo $this->lang->line('select') ?></option>
                                                <?php foreach ($doctors as $dkey => $dvalue) {
    ?>
                                                <option value="<?php echo $dvalue["id"]; ?>" <?php
if ($doctor_select == $dvalue['id']) {
        echo 'selected';
    }
    ?>><?php echo $dvalue["name"] . " " . $dvalue["surname"] ?></option>
                                                <?php }?>
                                            </select>
                                            <input type="hidden" name="charge_id" value="" id="charge_id" />
                                        </div>
                                        <span class="text-danger"><?php echo form_error('doctor'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group" style="position: relative; overflow:visible !important">
                                        <label><?php echo $this->lang->line('appointment_date'); ?></label>
                                        <small class="req"> *</small>
                                        <input type="text" id="datetimepicker" name="date"
                                            class="form-control datetime">
                                        <span class="text-danger"><?php echo form_error('date'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('shift'); ?></label><span
                                            class="req">
                                            *</span>
                                        <select name="global_shift" id="global_shift" class="select2" style="width:100%"
                                            onchange="getShift();">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="slot"><?php echo $this->lang->line('slot'); ?></label>
                                        <span class="req"> *</span>
                                        <select name="slot" id="slot" onchange="validateTime(this)"
                                            class="form-control">
                                        </select>
                                        <span class="text-danger"><?php echo form_error('slot'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="doctor_fees"><?php echo $this->lang->line("doctor_fees"); ?></label>
                                        <small class="req"> *</small>
                                        <div>
                                            <input type="text" name="amount" id="doctor_fees" class="form-control">
                                        </div>
                                        <span class="text-danger"><?php echo form_error('doctor_fees'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('payment_mode'); ?></label>
                                        <select class="form-control payment_mode" name="payment_mode">
                                            <?php foreach ($payment_mode as $key => $value) {?>
                                            <option value="<?php echo $key ?>"><?php echo $value ?></option>
                                            <?php }?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('apply_charge'); ?></span>
                                    </div>
                                </div>
                                <div class="cheque_div" style="display: none;">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('cheque_no'); ?></label><small
                                                class="req">
                                                *</small>
                                            <input type="text" name="cheque_no" id="cheque_no" class="form-control">
                                            <span class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('cheque_date'); ?></label><small
                                                class="req"> *</small>
                                            <input type="text" name="cheque_date" id="cheque_date"
                                                class="form-control date">
                                            <span class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('attach_document'); ?></label>
                                            <input type="file" class="filestyle form-control" name="document">
                                            <span class="text-danger"><?php echo form_error('document'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <label for="message"><?php echo $this->lang->line('message'); ?></label>
                                        <!-- <small class="req">*</small> -->
                                        <textarea name="message" id="note" class="form-control"></textarea>
                                        <span class="text-danger"><?php echo form_error('message'); ?></span>
                                    </div>
                                </div>
                                <?php if ($this->module_lib->hasActive('live_consultation')) {?>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label
                                            for="exampleInputFile"><?php echo $this->lang->line('live_consultant_on_video_conference'); ?></label>
                                        <small class="req">*</small>
                                        <div>
                                            <select name="live_consult" id="live_consult" class="form-control">
                                                <?php foreach ($yesno_condition as $yesno_key => $yesno_value) {
    ?>
                                                <option value="<?php echo $yesno_key ?>" <?php
if ($yesno_key == 'no') {
        echo "selected";
    }
    ?>><?php echo $yesno_value ?>
                                                </option>
                                                <?php }?>
                                            </select>
                                        </div>
                                        <span class="text-danger"><?php echo form_error('live_consult'); ?></span>
                                    </div>
                                </div>
                                <?php }?>
                                <div class="">
                                    <?php echo display_custom_fields('appointment'); ?>
                                </div>
                            </div>
                            <!--./row-->
                        </div>
                        <!--./col-md-12-->
                    </div>
                    <!--./row-->
                </div>
                <!--./modal-body-->
                <div class="modal-footer">
                    <div class="pull-right">
                        <button type="submit" id="formaddbtn" name="save"
                            data-loading-text="<?php echo $this->lang->line('processing') ?>" class="btn btn-info"><i
                                class="fa fa-check-circle"></i>
                            <?php echo $this->lang->line('save'); ?></button>
                    </div>
                    <div class="pull-right" style="margin-right: 10px; ">
                        <button type="submit" data-loading-text="<?php echo $this->lang->line('processing') ?>"
                            name="save_print" class="btn btn-info pull-right printsavebtn"><i class="fa fa-print"></i>
                            <?php echo $this->lang->line('save_print'); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function holdModal(modalId) {
    $('#' + modalId).modal({
        backdrop: 'static',
        keyboard: false,
        show: true
    });
}

function getDoctorShift(obj, doctor_id = null, global_shift_id = null) {
    if (doctor_id == null) {
        var doctor_id = obj.value;
    }
    var select = "";
    var select_box = "<option value=''><?php echo $this->lang->line('select'); ?></option> ";
    $.ajax({
        type: 'POST',
        url: base_url + "admin/onlineappointment/doctorshiftbyid",
        data: {
            doctor_id: doctor_id
        },
        dataType: 'json',
        success: function(res) {
            $.each(res, function(i, list) {
                select_box += "<option value='" + list.id + "'>" + list.name + "</option>";
            });
            $("#global_shift").html(select_box);
            $("#global_shift_edit").html(select_box);
            $("#rglobal_shift_edit").html(select_box);
            if (global_shift_id != null) {
                $("#global_shift_edit").val(global_shift_id).trigger('change');
                $("#rglobal_shift_edit").val(global_shift_id).trigger('change');
            }
        }
    });
}

function getDoctorFees(object) {
    let doctor_id = object.value;
    $.ajax({
        url: baseurl + 'admin/appointment/getDoctorFees/',
        type: "POST",
        data: {
            doctor_id: doctor_id
        },
        dataType: 'json',
        success: function(res) {
            $("#doctor_fees").val(res.fees);
            $("#charge_id").val(res.charge_id);
        }
    })
}

function getShift() {

    var div_data = "";
    var date = $("#datetimepicker").val();
    var doctor = $("#doctorid").val();
    var global_shift = $("#global_shift").val();

    $.ajax({
        url: baseurl + 'admin/onlineappointment/getShift',
        type: "POST",
        data: {
            doctor: doctor,
            date: date,
            global_shift: global_shift
        },
        dataType: 'json',
        success: function(res) {
            div_data += "<option value=''></option>"
            $.each(res, function(i, obj) {
                div_data += "<option value=" + obj.id + ">" + obj.start_time + " - " + obj
                    .end_time + "</option>";
            });
            $("#slot").html('');
            $('#slot').append(div_data);
        }
    });
}

function validateTime(obj) {
    let id = obj.value;
    let date = $("#datetimepicker").val();
    if (id) {
        $.ajax({
            url: baseurl + 'admin/onlineappointment/getshiftbyid',
            type: "POST",
            data: {
                id: id,
                date: date
            },
            dataType: 'json',
            success: function(res) {
                if (res.status) {
                    alert("<?php echo $this->lang->line("appointment_time_is_expired"); ?>");
                }
            }
        });
    }
}

$(document).ready(function(e) {


    $("form#formadd button[type=submit]").click(function() {
        $("button[type=submit]", $(this).parents("form")).removeAttr("clicked");
        $(this).attr("clicked", "true");
    });

    $("#formadd").on('submit', (function(e) {
        var did = $("#doctorid").val();
        $("#doctorinputid").val(did);

        var sub_btn_clicked = $("button[type=submit][clicked=true]");
        var sub_btn_clicked_name = sub_btn_clicked.attr('name');
        console.log(sub_btn_clicked_name);
        e.preventDefault();
        $.ajax({
            url: baseurl + 'admin/appointment/add',
            type: "POST",
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
                sub_btn_clicked.button('loading');
            },
            success: function(data) {
                if (data.status == "fail") {
                    var message = "";
                    $.each(data.error, function(index, value) {
                        message += value;
                    });
                    errorMsg(message);
                } else {
                    successMsg(data.message);
                    $('.ajaxlist').DataTable().ajax.reload();
                    $('#myModal').modal('hide');
                    if (sub_btn_clicked_name === "save_print") {
                        printAppointment(data.appointment_id);
                    }
                }
                sub_btn_clicked.button('reset');
            },
            error: function() {
                sub_btn_clicked.button('reset');
            },
            complete: function() {
                sub_btn_clicked.button('reset');
            }
        });
    }));

    $('#myModal').on('hidden.bs.modal', function() {
        $(".doctor_select2").select2("val", "");
        $("#global_shift").select2("val", "");
        $('#formadd').find('input:text, input:password, input:file, textarea').val('');
        $('#formadd').find('select option:selected').removeAttr('selected');
        $('#formadd').find('input:checkbox, input:radio').removeAttr('checked');
    });

    $('#myModal').modal({
        backdrop: 'static',
        keyboard: false,
        show: false
    });

    function printAppointment(id) {
        $.ajax({
            url: base_url + 'admin/appointment/printAppointmentBill',
            type: "POST",
            data: {
                'appointment_id': id
            },
            dataType: 'json',
            beforeSend: function() {

            },
            success: function(data) {
                popup(data.page);
            },

            error: function(xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
            },
            complete: function() {}
        });
    }

    function popup(data) {
        var base_url = '<?php echo base_url() ?>';
        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        frame1.css({
            "position": "absolute",
            "top": "-1000000px"
        });
        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ?
            frame1[0]
            .contentDocument.document : frame1[0].contentDocument;
        frameDoc.document.open();
        //Create a new HTML document.
        frameDoc.document.write('<html>');
        frameDoc.document.write('<head>');
        frameDoc.document.write('<title></title>');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url +
            'backend/bootstrap/css/bootstrap.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url +
            'backend/dist/css/font-awesome.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url +
            'backend/dist/css/ionicons.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url +
            'backend/dist/css/AdminLTE.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url +
            'backend/dist/css/skins/_all-skins.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url +
            'backend/plugins/iCheck/flat/blue.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url +
            'backend/plugins/morris/morris.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url +
            'backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url +
            'backend/plugins/datepicker/datepicker3.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url +
            'backend/plugins/daterangepicker/daterangepicker-bs3.css">');
        frameDoc.document.write('</head>');
        frameDoc.document.write('<body>');
        frameDoc.document.write(data);
        frameDoc.document.write('</body>');
        frameDoc.document.write('</html>');
        frameDoc.document.close();
        setTimeout(function() {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            frame1.remove();

        }, 500);

        return true;
    }


    $("#datetimepicker").on("dp.change", function(e) {
        if ($("#global_shift").val() != '') {
            getShift();
        }
    });

    $('#patient_name').on("input", function() {
        $search_val = $(this).val();
        if (checkPatiendID() == false) {
            $('#patient_id').val('');
            $('#addformgender').val('');
            $('#age_year').val('');
            $('#patient-phone').val('');
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
});

function checkPatiendID() {
    var val = document.getElementById("patient_name").value.trim();
    var opts = document.getElementById('patient-datalist').childNodes;
    for (var i = 0; i < opts.length; i++) {
        if (opts[i].value === val) {
            $('#patient_id').val($(opts[i]).attr('pid'));
            get_patient($(opts[i]).attr('pid'));
            return true;
        }
    }
    return false;
}

function get_patient(id) {
    console.log(id)
    $.ajax({
        url: '<?php echo base_url(); ?>admin/patient/patientDetails',
        type: "POST",
        data: {
            id: id
        },
        dataType: 'json',
        success: function(res) {
            let gender = res.gender.charAt(0).toUpperCase() + res.gender.slice(1);
            $('#addformgender').val(gender);
            $('#patient_name').val(res.patient_name);
            $('#age_year').val(res.age);
            $('#age_month').val(res.month);
            $('#age_day').val(res.day);
            $('#patient-phone').val(res.mobileno);
        }
    })
}
</script>
<!-- dd -->