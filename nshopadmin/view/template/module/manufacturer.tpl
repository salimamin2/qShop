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
    	<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
	<?php if ($success) { ?>
	    <div class="alert alert-success"><?php echo $success; ?></div>
	<?php } ?>
    <div class="content">
	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
	    <table class="form">
		<tr>
		    <td><?php echo $entry_position; ?></td>
		    <td><select name="manufacturer_position">
			    <?php if ($manufacturer_position == 'left') { ?>
    			    <option value="left" selected="selected"><?php echo $text_left; ?></option>
			    <?php } else { ?>
    			    <option value="left"><?php echo $text_left; ?></option>
			    <?php } ?>
			    <?php if ($manufacturer_position == 'right') { ?>
    			    <option value="right" selected="selected"><?php echo $text_right; ?></option>
			    <?php } else { ?>
    			    <option value="right"><?php echo $text_right; ?></option>
			    <?php } ?>
			    <?php if ($manufacturer_position == 'home') { ?>
    			    <option value="home" selected="selected"><?php echo __('Home'); ?></option>
			    <?php } else { ?>
    			    <option value="home"><?php echo __('Home'); ?></option>
			    <?php } ?>
			    <?php if ($manufacturer_position == 'bottom') { ?>
    			    <option value="bottom" selected="selected"><?php echo __('Bottom'); ?></option>
			    <?php } else { ?>
    			    <option value="bottom"><?php echo __('Bottom'); ?></option>
			    <?php } ?>
			</select></td>
		</tr>
		<tr>
		    <td><?php echo $entry_status; ?></td>
		    <td><select name="manufacturer_status">
			    <?php if ($manufacturer_status) { ?>
    			    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
    			    <option value="0"><?php echo $text_disabled; ?></option>
			    <?php } else { ?>
    			    <option value="1"><?php echo $text_enabled; ?></option>
    			    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
			    <?php } ?>
			</select></td>
		</tr>
		<tr>
		    <td><?php echo $entry_sort_order; ?></td>
		    <td><input type="text" name="manufacturer_sort_order" value="<?php echo $manufacturer_sort_order; ?>" size="1" /></td>
		</tr>
	    </table>
	</form>
    </div>
</div>
