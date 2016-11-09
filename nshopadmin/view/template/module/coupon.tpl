

<div class="box">
    <div class="head well">
        <h3>
            <i class="icon-th-list"></i> <?php echo $heading_title; ?>
            <div class="pull-right">
                    <a onclick="$('#form').submit();" class="btn btn-success"><span><?php echo $button_save; ?></span></a>  <a onclick="location = '<?php echo $cancel; ?>';" class="btn btn-default"><span><?php echo $button_cancel; ?></span></a>
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
                    <td><?php echo $entry_title; ?></td>
                    <td><input name="coupon_title" value="<?php echo $coupon_title ?>" /></td>
                </tr>
                <tr>
                    <td><?php echo $entry_promo; ?></td>
                    <td><input name="coupon_promo" value="<?php echo $coupon_promo ?>" /></td>
                </tr>
                <tr>
                    <td><?php echo $entry_description; ?></td>
                    <td><textarea name="coupon_description" cols="100" rows="10"><?php echo $coupon_description; ?></textarea></td>
                </tr>
                <tr>
                    <td><?php echo $entry_box_title; ?></td>
                    <td><input name="coupon_box_title" value="<?php echo $coupon_box_title ?>" /></td>
                </tr>        
                <tr>
                    <td><?php echo $entry_box_desc; ?></td>
                    <td><textarea name="coupon_box_desc" cols="100" rows="10"><?php echo $coupon_box_desc; ?></textarea></td>
                </tr>
                <tr>
                    <td><?php echo $entry_fb_link; ?></td>
                    <td><input name="coupon_fb_link" value="<?php echo $coupon_fb_link; ?>" /></td>
                </tr>
                <tr>
                    <td><?php echo $entry_tt_link; ?></td>
                    <td><input name="coupon_tt_link" value="<?php echo $coupon_tt_link; ?>" /></td>
                </tr>
                <tr>
                    <td><?php echo $entry_gp_link; ?></td>
                    <td><input name="coupon_gp_link" value="<?php echo $coupon_gp_link; ?>" /></td>
                </tr>
                <tr>
                    <td><?php echo $entry_status; ?></td>
                    <td><select name="coupon_status">
                            <?php if ($coupon_status) { ?>
                                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                <option value="0"><?php echo $text_disabled; ?></option>
                            <?php } else { ?>
                                <option value="1"><?php echo $text_enabled; ?></option>
                                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                            <?php } ?>
                        </select></td>
                </tr>
            </table>
        </form>
    </div>
</div>
