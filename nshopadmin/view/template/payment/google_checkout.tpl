
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="head">
    <h4 style="background-image: url('view/image/payment.png');"><?php echo $heading_title; ?></h4>
    	<div class="row filter-block">
		 <div class="col-md-12">
			<div class="pull-right">
				<div class="buttons">      
	<a onclick="$('#form').submit();" class="btn-flat btn-success btn-sm"><span><?php echo $button_save; ?></span></a> <a onclick="location = '<?php echo $cancel; ?>';" class="btn-flat btn-default btn-sm"><span><?php echo $button_cancel; ?></span></a>
				</div>
			</div>
		</div>
	</div>
	<br />
	<br />
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <tr>
          <td> <?php echo $entry_merchant_id; ?><span class="required">*</span></td>
          <td><input class="form-control" type="text" name="google_checkout_merchant_id" value="<?php echo $google_checkout_merchant_id; ?>" />
            <?php if ($error_merchant_id) { ?>
            <span class="error"><?php echo $error_merchant_id; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td> <?php echo $entry_merchant_key; ?><span class="required">*</span></td>
          <td><input class="form-control" type="text" name="google_checkout_merchant_key" value="<?php echo $google_checkout_merchant_key; ?>" />
            <?php if ($error_merchant_key) { ?>
            <span class="error"><?php echo $error_merchant_key; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_test; ?></td>
          <td><?php if ($google_checkout_test) { ?>
            <input type="radio" name="google_checkout_test" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <input type="radio" name="google_checkout_test" value="0" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="google_checkout_test" value="1" />
            <?php echo $text_yes; ?>
            <input type="radio" name="google_checkout_test" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><div class="ui-select"><select name="google_checkout_status">
              <?php if ($google_checkout_status) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select></div></td>
        </tr>
      </table>
    </form>
  </div>
</div>
