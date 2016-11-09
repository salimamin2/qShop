<div class="box">
  <div class="head well">
    <h3 style="background-image: url('view/image/module.png');"><?php echo $heading_title; ?>
  
			<div class="pull-right">
				<div class="buttons">  	    
	<a onclick="$('#form').submit();" class="btn-flat btn-success btn-sm"><span><?php echo $button_save; ?></span></a> <a onclick="location = '<?php echo $cancel; ?>';" class="btn-flat btn-default btn-sm"><span><?php echo $button_cancel; ?></span></a>
				</div>
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
          <td><?php echo $entry_ajax; ?></td>
          <td><div class="ui-select"><select name="cart_ajax">
              <?php if ($cart_ajax) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select></div></td>
        </tr>
        <tr>
          <td><?php echo $entry_position; ?></td>
          <td><div class="ui-select"><select name="cart_position">
              <?php if ($cart_position == 'left') { ?>
              <option value="left" selected="selected"><?php echo $text_left; ?></option>
              <?php } else { ?>
              <option value="left"><?php echo $text_left; ?></option>
              <?php } ?>
              <?php if ($cart_position == 'top') { ?>
              <option value="top" selected="selected"><?php echo $text_top; ?></option>
              <?php } else { ?>
              <option value="top"><?php echo $text_top; ?></option>
              <?php } ?>
              <?php if ($cart_position == 'right') { ?>
              <option value="right" selected="selected"><?php echo $text_right; ?></option>
              <?php } else { ?>
              <option value="right"><?php echo $text_right; ?></option>
              <?php } ?>
            </select></div></td>
        </tr>
        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><div class="ui-select"><select name="cart_status">
              <?php if ($cart_status) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select></div></td>
        </tr>
        <tr>
          <td><?php echo $entry_sort_order; ?></td>
          <td><input type="text" name="cart_sort_order" value="<?php echo $cart_sort_order; ?>" size="1" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
