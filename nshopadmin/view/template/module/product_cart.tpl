<div class="box">
    <div class="head well">
        <h3>
            <i class="icon-th-list"></i> <?php echo $heading_title; ?>
            <div class="pull-right">
                <button  type="button" id="add_option" class="btn-flat btn-sm btn-warning"><span><?php echo __('Add'); ?></span></button>
                <a onclick="$('#form').submit();" class="btn-flat btn-success btn-sm"><span><?php echo $button_save; ?></span></a>
                <a onclick="location = '<?php echo $cancel; ?>';" class="btn-flat btn-default btn-sm"><span><?php echo $button_cancel; ?></span></a>
            </div>
        </h3>
    </div>
            
<?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php } ?>
    <div class="content">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
            <table class="form">
                <tr>
                    <td>Status</td>
                    <td><div class="ui-select">
                            <select name="know_status">
                                <?php if ($know_status) { ?>
                                <option value="1" selected="selected">Enabled</option>
                                <option value="0">Disabled</option>
                                <?php } else { ?>
                                <option value="1">Enabled</option>
                                <option value="0" selected="selected">Disabled</option>
                                <?php } ?>
                            </select>
                        </div></td>
                </tr>
                <tr>
                    <td>Order</td>
                    <td><input type="text" name="know_sort_order" value="<?php echo $know_sort_order; ?>" size="1" /></td>
                </tr>
                <?php foreach($aData as $key=>$value) { ?>

                <tr>
                    <td>Comments</td>
                    <td><input class="form-control" type="text" name="comments[]" value="<?php echo $key; ?>" size="1" /></td>
                </tr>
                <tr>
                    <td>Information Pages</td>
                    <td>
                        <div class="ui-select">
                            <select name="information_page[]">
                                <?php foreach ($informations as $position) { ?>
                                <?php if ($value == $position['information_id']) { ?>
                                <option value="<?php echo $position['information_id']; ?>" selected="selected"><?php echo $position['title']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $position['information_id']; ?>"><?php echo $position['title']; ?></option>
                                <?php } ?>
                                <?php } ?>
                            </select>
                        </div></td>
                </tr>
                <tr><td><br></td></tr>
                <?php } ?>
                <script type="text/javascript">
                    $(document).ready(function() {


                    });

                </script>
            </table>
        </form>
    </div>
</div>