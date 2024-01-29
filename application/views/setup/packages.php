<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <?php if ($this->rbac->hasPrivilege('package', 'can_view')) {?>
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title titlefix"><?php echo 'Packages List' ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('package', 'can_add')) {?>
                            <a data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-sm addpackage"><i
                                    class="fa fa-plus"></i> <?php echo 'Add Package'; ?></a>
                            <?php }?>
                        </div><!-- /.box-tools -->
                    </div>
                    <div class="box-body">
                        <div class="download_label"><?php echo $this->lang->line('bed_list'); ?></div>
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-hover table-striped table-bordered example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('amount'); ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($package_list)) {?>
                                    <?php } else {foreach ($package_list as $key => $value) {?>
                                    <tr>
                                        <td class="mailbox-name">
                                            <?php echo $value->package_name ?>
                                        </td>

                                        <td><?php echo $value->package_charge; ?></td>

                                        <td class="mailbox-date pull-right">
                                            <?php if ($this->rbac->hasPrivilege('package', 'can_edit')) {?>
                                            <a href="#" onclick="getRecord('<?php echo $value->id ?>')"
                                                class="btn btn-default btn-xs" data-target="#myModalEdit"
                                                data-toggle="tooltip" title=""
                                                data-original-title="<?php echo $this->lang->line('edit'); ?>">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                            <?php }?>
                                            <?php if ($this->rbac->hasPrivilege('package', 'can_delete')) {?>
                                            <a class="btn btn-default btn-xs" data-toggle="tooltip" title=""
                                                onclick="delete_recordByIdReload('admin/packages/delete/<?php echo $value->id; ?>', '')"
                                                data-original-title="<?php echo $this->lang->line('delete') ?>">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            <?php }?>
                                        </td>
                                    </tr>
                                    <?php }}?>
                                </tbody>
                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
                <?php } else {?>
                <div class="alert alert-danger">You don't have permission for view this page</div>
                <?php }?>
            </div>
            <!--/.col (left) -->
            <!-- right column -->
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<!-- new END -->
</div><!-- /.content-wrapper -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm400" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo 'Add Package' ?></h4>
            </div>
            <form id="addpackage" class="ptt10" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <div class="modal-body pt0 pb0">
                    <div class="row" id="">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('name'); ?></label>
                                <span class="req"> *</span>
                                <input name="name" placeholder="" type="text" class="form-control"
                                    value="<?php echo set_value('name'); ?>" />

                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('amount'); ?></label>
                                <span class="req"> *</span>
                                <input name="amount" placeholder="" type="text" class="form-control"
                                    value="<?php echo set_value('amount'); ?>" />

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="pull-right">
                        <button type="submit" id="addpackagebtn"
                            data-loading-text="<?php echo $this->lang->line('processing'); ?>" class="btn btn-info"><i
                                class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>
</div>

<div class="modal fade" id="myModalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm400" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('edit_bed'); ?></h4>
            </div>

            <form id="editpackage" class="ptt10" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <div class="modal-body pt0 pb0">
                    <div class="row" id="">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('name'); ?></label>
                                <span class="req"> *</span>
                                <input id="name" name="name" placeholder="" type="text" class="form-control"
                                    value="<?php echo set_value('name'); ?>" />
                                <input id="packageid" name="packageid" placeholder="" type="hidden"
                                    class="form-control" />
                            </div>
                            <div class="form-group">
                                <label><?php echo $this->lang->line('amount'); ?></label>
                                <span class="req"> *</span>
                                <input id="amount" name="amount" placeholder="" type="text" class="form-control"
                                    value="<?php echo set_value('amount'); ?>" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="pull-right">
                        <button type="submit" data-loading-text="<?php echo $this->lang->line('processing'); ?>"
                            id="editpackagebtn" class="btn btn-info "><i class="fa fa-check-circle"></i>
                            <?php echo $this->lang->line('save'); ?></button>
                    </div>
                </div>
            </form>
        </div>
        </form>
    </div>
</div>
<script>
$(document).ready(function() {
    $('.detail_popover').popover({
        placement: 'right',
        trigger: 'hover',
        container: 'body',
        html: true,
        content: function() {
            return $(this).closest('td').find('.fee_detail_popover').html();
        }
    });
});


$(document).ready(function(e) { // e mai functiojn ki defination aa gye
    $('#addpackage').on('submit', (function(e) {
        $("#addpackagebtn").button('loading');
        e.preventDefault();
        $.ajax({
            url: '<?php echo base_url(); ?>admin/packages/add',
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
                        $('.' + index).html(value);
                        message += value;
                    });

                    errorMsg(message);
                } else {
                    successMsg(data.message);
                    window.location.reload(true);
                }
                $("#addpackagebtn").button('reset');

            },
            error: function() {
                alert("<?php echo $this->lang->line('fail'); ?>")
                $("#addpackagebtn").button('reset');
            }
        });
    }));
});

$(document).ready(function(e) { // e mai functiojn ki defination aa gye
    $('#editpackage').on('submit', (function(e) {
        $("#editpackagebtn").button('loading');

        e.preventDefault();
        $.ajax({
            url: '<?php echo base_url(); ?>admin/packages/update',
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
                $("#editpackagebtn").button('reset');
            },
            error: function() {
                alert("Fail");
                $("#editpackagebtn").button('reset');
            }
        });
    }));
});

function getRecord(id) {
    $('#myModalEdit').modal('show');
    $.ajax({
        url: '<?php echo base_url(); ?>admin/packages/get/' + id,
        type: "POST",
        dataType: "json",
        success: function(data) {
            $("#name").val(data.package_name);
            $("#packageid").val(id);
            $("#amount").val(data.package_charge);
            if (data.is_active == 'unused') {
                $('#mark_as_unused').attr('checked', 'checked');
            }
        },
        error: function() {
            alert("Fail")
        }

    })
}

$(document).ready(function(e) {
    $('#myModal,#myModalEdit').modal({
        backdrop: 'static',
        keyboard: false,
        show: false
    });
});

$(".addpackage").click(function() {
    $('#addbed').trigger("reset");

});
</script>