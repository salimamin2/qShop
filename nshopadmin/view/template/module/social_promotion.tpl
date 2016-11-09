<div class="box">
<div class="head well">
        <h3>
        <i class="icon-th-list"></i> <?php echo $heading_title; ?>
            <div class="pull-right">
            <a onclick="$('#form').submit();" class="btn btn-success"><span><?php echo $button_save; ?></span></a>
            <a onclick="location = '<?php echo $cancel; ?>';" class="btn btn-default"><span><?php echo $button_cancel; ?></span></a>
            </div>
        </h3>
    </div>
<?php if ($error_warning) { ?>
    <div class="alert alert-danger"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php } ?>
    <div class="content">

        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">

            <table class="form">

                <tr>

                    <td><?php echo $entry_title; ?> <span class="required">*</span></td>

                    <td>

                        <input name="social_promotion_title" value="<?php echo $social_promotion_title ?>" />

                        <?php if($error_title): ?>

                            <span class="error"><?php echo $error_title; ?></span>

                        <?php endif; ?>

                    </td>

                </tr>

                <tr>

                    <td><?php echo $entry_promo; ?> <span class="required">*</span></td>

                    <td>

                        <select name="social_promotion_promo">

                            <option value=""><?php echo __('text_select'); ?></option>

                            <?php foreach($coupons as $coupon): ?>

                                <option value="<?php echo $coupon['code']; ?>" <?php echo ($coupon['code'] == $social_promotion_promo ? 'selected' : ''); ?> ><?php echo $coupon['code']; ?></option>

                            <?php endforeach; ?>

                        </select>

                        <?php if($error_promo): ?>

                            <span class="error"><?php echo $error_promo; ?></span>

                                <?php endif; ?>

                    </td>

                </tr>

                <tr>

                    <td><?php echo $entry_description; ?></td>

                    <td><textarea name="social_promotion_description" cols="100" rows="10" data-rel="wyswyg"><?php echo $social_promotion_description; ?></textarea></td>

                </tr>

                <tr>

                    <td><?php echo $entry_box_title; ?></td>

                    <td><input name="social_promotion_box_title" value="<?php echo $social_promotion_box_title ?>" /></td>

                </tr>        

                <tr>

                    <td><?php echo $entry_box_desc; ?></td>

                    <td><textarea name="social_promotion_box_desc" cols="100" rows="10" data-rel="wyswyg"><?php echo $social_promotion_box_desc; ?></textarea></td>

                </tr>

                <tr>

                    <td><?php echo $entry_fb_link; ?></td>

                    <td><input name="social_promotion_fb_link" value="<?php echo $social_promotion_fb_link; ?>" /></td>

                </tr>

                <tr>

                    <td><?php echo $entry_tt_link; ?></td>

                    <td><input name="social_promotion_tt_link" value="<?php echo $social_promotion_tt_link; ?>" /></td>

                </tr>

                <tr>

                    <td><?php echo $entry_gp_link; ?></td>

                    <td><input name="social_promotion_gp_link" value="<?php echo $social_promotion_gp_link; ?>" /></td>

                </tr>

                <tr>

                    <td><?php echo $entry_status; ?> <span class="required">*</span></td>

                    <td>

                        <select name="social_promotion_status">

                            <option value="1" <?php echo ($social_promotion_status == 1 ? 'selected' : ''); ?> ><?php echo $text_enabled; ?></option>

                            <option value="0" <?php echo ($social_promotion_status == 1 ? '' : 'selected'); ?> ><?php echo $text_disabled; ?></option>

                        </select>

                    </td>

                </tr>

            </table>

        </form>

    </div>

</div>

