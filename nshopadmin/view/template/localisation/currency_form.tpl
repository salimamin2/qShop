<div class="box">
  <div class="head well">
    <h3>
        <i class="icon-th-list"></i> <?php echo $heading_title; ?>
			<div class="pull-right">
	       <a onclick="$('#form').submit();" class="btn-flat btn-success btn-sm"><span><?php echo $button_save; ?></span></a> <a onclick="location = '<?php echo $cancel; ?>';" class="btn-flat btn-default btn-sm"><span><?php echo $button_cancel; ?></span></a>
			</div>
		</h3>
  </div>
    <?php if ($error_warning) { ?>
        <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
  <div class="content">

    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">

      <table class="form">

        <tr>

          <td> <?php echo $entry_title; ?><span class="required">*</span></td>

          <td><input type="text" class="form-control" name="title" value="<?php echo $title; ?>" />

            <?php if ($error_title) { ?>

            <span class="error"><?php echo $error_title; ?></span>

            <?php } ?></td>

        </tr>

        <tr>

          <td> <?php echo $entry_code; ?><span class="required">*</span></td>

          <td><input type="text" class="form-control" name="code" value="<?php echo $code; ?>" />

            <?php if ($error_code) { ?>

            <span class="error"><?php echo $error_code; ?></span>

            <?php } ?></td>

        </tr>

        <tr>

          <td><?php echo $entry_symbol_left; ?></td>

          <td><input type="text" class="form-control" name="symbol_left" value="<?php echo $symbol_left; ?>" /></td>

        </tr>

        <tr>

          <td><?php echo $entry_symbol_right; ?></td>

          <td><input type="text" class="form-control" name="symbol_right" value="<?php echo $symbol_right; ?>" /></td>

        </tr>

        <tr>

          <td><?php echo $entry_decimal_place; ?></td>

          <td><input type="text" class="form-control" name="decimal_place" value="<?php echo $decimal_place; ?>" /></td>

        </tr>

        <tr>

          <td><?php echo $entry_value; ?></td>

          <td><input type="text" class="form-control" name="value" value="<?php echo $value; ?>" /></td>

        </tr>

        <tr>

          <td><?php echo $entry_status; ?></td>

          <td>
		  <div class="ui-select">
		  <select name="status">

              <?php if ($status) { ?>

              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>

              <option value="0"><?php echo $text_disabled; ?></option>

              <?php } else { ?>

              <option value="1"><?php echo $text_enabled; ?></option>

              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>

              <?php } ?>

            </select>
			</div></td>

        </tr>

      </table>

    </form>

  </div>

</div>
</div>
