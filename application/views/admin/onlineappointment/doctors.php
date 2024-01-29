 <?php

$currency_symbol = $this->customlib->getHospitalCurrencyFormat();
$genderList = $this->customlib->getGender_Patient();
$this->load->view('layout/header');
?>
 <style>
.hideextra {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.boxes {
    width: 13%;
    height: auto;
    margin-right: 10px;
    /* margin: 2px;
    margin-left: 12px; */
}

.box-header {
    padding: 0px;
}

.box-header h3 {
    padding: 5px;
    margin: 0px;
    font-size: 19px;
    /* font-weight: bold; */
}
 </style>
 <div class="content-wrapper">
     <!-- Main content -->
     <section class="content">
         <div class="row">
             <div class="col-md-12">
                 <div class="box box-primary">
                     <div class="box-header with-border">
                         <h3 class="box-title titlefix">Patients list</h3>
                         <div class="box-tools pull-right">
                             <?php if ($this->rbac->hasPrivilege('appointment', 'can_add')) {?>
                             <a data-toggle="modal" data-target="#myModal"
                                 class="btn btn-primary btn-sm addappointment">
                                 <i class="fa fa-plus"></i> <?php echo $this->lang->line('add_appointment'); ?></a>
                             <?php }?>
                             <a href="<?php echo base_url("admin/onlineappointment/patientschedule"); ?>"
                                 class="btn btn-primary btn-sm"><i class="fa fa-reorder"></i>
                                 Search Doctor Wise Appointment</a>
                             <!-- <a href="<?php echo base_url("admin/onlineappointment/patientqueue"); ?>"
                                 class="btn btn-primary btn-sm"><i class="fa fa-reorder"></i>
                                 <?php echo $this->lang->line('queue'); ?></a> -->
                         </div>
                     </div><!-- /.box-header -->
                     <div class="box-body p-5" style="margin-left:30px;">
                         <div class="row">
                             <div class="row" id="boxes">
                             </div>
                         </div>
                     </div>

                 </div>
             </div>
         </div>
     </section>
 </div>
 <?php
$data = [
    // "disable_option" => $disable_option,
    "doctors" => $doctors,
    "doctor_select" => $doctor_select,
    "appoint_priority_list" => $appoint_priority_list,
    "payment_mode" => $payment_mode,
    // "yesno_condition" => $yesno_condition,
];

$this->load->view("admin/modals/add_appointment_mdl", $data);
$this->load->view('admin/patient/patientaddmodal');
?>
 <script>
$(function() {

    let appointments = {
        url: base_url + "/admin/Onlineappointment/appointments",
        dataType: "json",
        success: function(res) {
            if (res.status == 1) {
                $.each(res.data, function(doctor, value) {
                    var trs = ``;
                    $.each(value, function(i, v) {

                        var check = v.done == "1" ? "fas fa-check-circle" :
                            "far fa-check-circle";
                        trs +=
                            `<tr><td style="padding-left: 5px;">${v.token}` + "." +
                            `<td><td>${v.patient_name}</td><td><input type="checkbox" style="display:none" value="0" /><i class="` +
                            check +
                            ` fa-lg"
                        style="cursor:pointer"val="${v.done}" app_id="${v.patient_id}"></i></td></tr>`
                    })
                    $("#boxes").append(`<div  class=" col-md-6 box boxes"style="padding:0px;">
                 <div class="box-header with-border">
                 <h3 class="hideextra"style="padding:5px;" title=${doctor}>${doctor}</h3>
                  </div>
                  <div class=" p-5">
                 <table class="table mb0 table-striped table-bordered examples" id=table-doc-` + value
                        .id + `>
                 ` + trs + `
                 </table>
                 </div>
                 </div>`);
                })

            }
        }
    }
    $.ajax(appointments);


    function done() {
        $(document).on("click", ".fa-check-circle", function() {
            let check_val = $(this).attr("val");
            let app_id = $(this).attr("app_id");
            if (check_val == '0') {
                $(this).attr('val', '1');
                check_val = $(this).attr('val');
                $(this).toggleClass("far fa-check-circle").toggleClass(
                    "fas fa-check-circle");
            } else {
                $(this).attr('val', '0');
                check_val = $(this).attr('val');
                $(this).toggleClass("far fa-check-circle").toggleClass(
                    "fas fa-check-circle");
            }
            let done = {
                url: base_url + "admin/Onlineappointment/checkBox",
                data: {
                    check_val: check_val,
                    app_id: app_id,
                },
                dataType: "json",
                success: function(res) {
                    if (res.status == "1") {
                        console.log("success");
                    }
                }
            }
            $.ajax(done);
        })
    }
    done()

})
$("#formaddbtn").click(function() {
    window.location.reload();
})
 </script>
 <?php
$this->load->view('layout/footer');
?>