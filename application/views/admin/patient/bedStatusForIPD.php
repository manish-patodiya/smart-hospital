<style>
.card {
    position: relative;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 1px solid rgba(0, 0, 0, .125);
    border-radius: 1rem;
    box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
    margin-bottom: 2rem;
}

.card-header {
    padding: 0.75rem 1.25rem;
    margin-bottom: 0;
    background-color: rgba(0, 0, 0, .03);
    border-bottom: 1px solid rgba(0, 0, 0, .125);
}

.card-header:first-child {
    border-radius: calc(0.25rem - 1px) calc(0.25rem - 1px) 0 0;
}

.card-body {
    -webkit-box-flex: 1;
    -ms-flex: 1 1 auto;
    flex: 1 1 auto;
    padding: 1.25rem;
}

.m-5 {}
</style>
<div class="row">
    <?php foreach ($floor_list as $key => $floor) {?>
    <?php foreach ($bedgroup_list as $key => $bedgroup) {
    if ($bedgroup["fid"] == $floor["id"]) {?>
    <div class="col-md-4">
        <div class="card">
            <div class='card-header'>
                <h5><?php echo $floor["name"] . ' (' . $bedgroup["name"] . ')' ?></h5>
            </div>
            <div class="card-body">
                <?php foreach ($bedlist as $key => $beds) {
        if ($beds["bedgroupid"] == $bedgroup["id"]) {
            if ($beds["is_active"] == 'no' && $beds["pid"]) {
                $name = $beds["patient_name"];
                ?>
                <div class="col-md-3 col-xs-3 col-lg-3 col-sm-3">
                    <a data-toggle="popover" class="beddetail_popover"
                        href="<?php echo base_url() . "admin/patient/ipdprofile/" . $beds["ipd_details_id"] ?>">
                        <div class="relative">
                            <div class="<?php if ($beds["is_active"] == "yes") {
                    echo "bedgreen";
                } else {
                    echo "bedred";
                }
                ?>">
                                <i class="fas fa-bed"></i>
                                <div class="bedtpmiuns6"><?php echo $name ?></div>
                            </div>
                        </div>
                        <div class="bed_detail_popover" style="display: none">
                            <?php echo $this->lang->line('bed_no') . " : " . $beds["name"] . "<br/>" . $this->lang->line('patient_id') . " : " . $beds["patient_unique_id"] . "<br/>" . $this->lang->line('admission_date') . " : " . date($this->customlib->getHospitalDateFormat(true, true), strtotime($beds['date'])) . "<br/>" . $this->lang->line('phone') . " : " . $beds["mobileno"] . "<br/>" . $this->lang->line('gender') . " : " . $beds["gender"] . "<br/>" . $this->lang->line('guardian_name') . " : " . $beds["guardian_name"] . "<br/>" . $this->lang->line('address') . " : " . $beds["address"] . "<br/>" . $this->lang->line('consultant') . " : " . $beds["staff"] . " " . $beds["surname"]; ?>
                        </div>
                    </a>
                </div>
                <!--./col-md-2-->
                <?php }
            if ($beds["is_active"] == 'yes') {
                $name = $beds["name"];
                $dataarr = array($beds["id"], $bedgroup["id"]);
                ?>
                <div class="col-md-3 col-xs-3 col-lg-3 col-sm-3">
                    <a
                        href="<?php echo base_url() . "admin/patient/ipdsearch/" . $beds["id"] . "/" . $bedgroup["id"] ?>">
                        <div class="relative">
                            <div class="<?php if ($beds["is_active"] == "yes") {
                    echo "bedgreen";
                } else {
                    echo "bedred";
                }
                ?>"><i class="fas fa-bed"></i>
                                <div class="bedtpmiuns6"><?php echo $name ?></div>
                            </div>
                        </div>
                    </a>
                </div>
                <?php
}
            if ($beds["is_active"] == 'unused') {
                $name = $beds["name"];
                $dataarr = array($beds["id"], $bedgroup["id"]);
                ?>
                <div class="col-md-3 col-xs-3 col-lg-3 col-sm-3">
                    <a data-toggle="popover" class="beddetail_popover"
                        href="<?php echo base_url() . "admin/patient/ipdsearch/" . $beds["id"] . "/" . $bedgroup["id"] ?>">
                        <div class="relative">
                            <div class="<?php if ($beds["is_active"] == "unused") {
                    echo "bed-unused";
                }
                ?>"><i class="fas fa-bed"></i>
                                <div class="bedtpmiuns6"><?php echo $name ?></div>
                            </div>
                        </div>
                        <div class="bed_detail_popover" style="display: none">
                            <?php echo $this->lang->line('unused') ?></div>
                    </a>
                </div>
                <?php
}
        }?>
                <?php }?>
            </div>
        </div>
    </div>
    <?php }}}?>
</div>


<?php /* foreach ($floor_list as $key => $floor) {?>
<fieldset class="floormain">
    <legend>
        <h4><?php echo $floor["name"] ?></h4>
    </legend>
    <div class="row">
        <?php foreach ($bedgroup_list as $key => $bedgroup) {
if ($bedgroup["fid"] == $floor["id"]) {?>
        <div class="col-md-12">
            <fieldset style="background-color:<?php echo $bedgroup['color']; ?>">
                <!-- /class="bedgroups"/ -->
                <legend class="text-center floorwardbg">
                    <h4><?php echo $bedgroup["name"] ?></h4>
                </legend>
                <div class="row">
                    <?php foreach ($bedlist as $key => $beds) {
if ($beds["bedgroupid"] == $bedgroup["id"]) {
if ($beds["is_active"] == 'no' && $beds["pid"]) {
$name = $beds["patient_name"];
?>
                    <div class="col-md-1 col-xs-6 col-lg-1 col-sm-4">
                        <a data-toggle="popover" class="beddetail_popover"
                            href="<?php echo base_url() . "admin/patient/ipdprofile/" . $beds["ipd_details_id"] ?>">
                            <div class="relative">
                                <div class="<?php if ($beds["is_active"] == "yes") {
echo "bedgreen";
} else {
echo "bedred";
}
?>">
                                    <i class="fas fa-bed"></i>
                                    <div class="bedtpmiuns6"><?php echo $name ?></div>
                                </div>
                            </div>
                            <div class="bed_detail_popover" style="display: none">
                                <?php echo $this->lang->line('bed_no') . " : " . $beds["name"] . "<br/>" . $this->lang->line('patient_id') . " : " . $beds["patient_unique_id"] . "<br/>" . $this->lang->line('admission_date') . " : " . date($this->customlib->getHospitalDateFormat(true, true), strtotime($beds['date'])) . "<br/>" . $this->lang->line('phone') . " : " . $beds["mobileno"] . "<br/>" . $this->lang->line('gender') . " : " . $beds["gender"] . "<br/>" . $this->lang->line('guardian_name') . " : " . $beds["guardian_name"] . "<br/>" . $this->lang->line('consultant') . " : " . $beds["staff"] . " " . $beds["surname"]; ?>
                            </div>
                        </a>
                    </div>
                    <!--./col-md-2-->
                    <?php }
if ($beds["is_active"] == 'yes') {
$name = $beds["name"];
$dataarr = array($beds["id"], $bedgroup["id"]);
?>
                    <div class="col-md-1 col-xs-6 col-lg-1 col-sm-4">
                        <a
                            href="<?php echo base_url() . "admin/patient/ipdsearch/" . $beds["id"] . "/" . $bedgroup["id"] ?>">
                            <div class="relative">
                                <div class="<?php if ($beds["is_active"] == "yes") {
echo "bedgreen";
} else {
echo "bedred";
}
?>"><i class="fas fa-bed"></i>
                                    <div class="bedtpmiuns6"><?php echo $name ?></div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php
}
if ($beds["is_active"] == 'unused') {
$name = $beds["name"];
$dataarr = array($beds["id"], $bedgroup["id"]);
?>
                    <div class="col-md-1 col-xs-6 col-lg-1 col-sm-4">
                        <a data-toggle="popover" class="beddetail_popover"
                            href="<?php echo base_url() . "admin/patient/ipdsearch/" . $beds["id"] . "/" . $bedgroup["id"] ?>">
                            <div class="relative">
                                <div class="<?php if ($beds["is_active"] == "unused") {
echo "bed-unused";
}
?>"><i class="fas fa-bed"></i>
                                    <div class="bedtpmiuns6"><?php echo $name ?></div>
                                </div>
                            </div>
                            <div class="bed_detail_popover" style="display: none">
                                <?php echo $this->lang->line('unused') ?></div>
                        </a>
                    </div>
                    <?php
}
}
}
?>
                </div>
            </fieldset>
        </div>
        <?php }}?>
    </div>
</fieldset>
<?php }*/?>
<script type="text/javascript">
$(document).ready(function() {
    $('.beddetail_popover').popover({
        placement: 'right',
        trigger: 'hover',
        container: 'body',
        html: true,
        content: function() {

            return $(this).closest('div').find('.bed_detail_popover').html();
        }
    });
});
</script>