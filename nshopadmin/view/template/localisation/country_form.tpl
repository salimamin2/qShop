<div class="box">
  <div class="head well">
    <h3><i class="icon-globe"></i> <?php echo $heading_title; ?>
			<div class="pull-right">
        <a onclick="$('#form').submit();" class="btn-flat btn-success btn-sm"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="btn-flat btn-default btn-sm"><span><?php echo $button_cancel; ?></span></a>
			</div>
    </h3>
	</div>
    <?php if ($error_warning) { ?>
      <div class="alert alert-danger"><?php echo $error_warning; ?></div>
    <?php } ?>
  <div class="content">

    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">

      <table class="form">

        <tr>

          <td> <?php echo $entry_name; ?><span class="required">*</span></td>

          <td><input class="form-control" type="text" name="name" value="<?php echo $name; ?>" />

            <?php if ($error_name) { ?>

            <span class="error"><?php echo $error_name; ?></span>

            <?php } ?></td>

        </tr>

        <tr>

          <td><?php echo $entry_iso_code_2; ?></td>

          <td><input class="form-control" type="text" name="iso_code_2" value="<?php echo $iso_code_2; ?>" /></td>

        </tr>

        <tr>

          <td><?php echo $entry_iso_code_3; ?></td>

          <td><input class="form-control" type="text" name="iso_code_3" value="<?php echo $iso_code_3; ?>" /></td>

        </tr>

        <tr>

          <td><?php echo $entry_address_format; ?></td>

          <td><textarea class="form-control" name="address_format" cols="40" rows="5"><?php echo $address_format; ?></textarea></td>

        </tr>

		<tr>

          <td><?php echo $entry_status; ?></td>

          <td><div class="ui-select"><select name="status">

              <?php if ($status) { ?>

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
</div>

