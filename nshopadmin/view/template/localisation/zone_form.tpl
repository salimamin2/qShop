

<div class="box table-wrapper products-table section">
	<div class="head well">
    <h3><i class="icon-globe"></i> <?php echo $heading_title; ?>
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

          <td> <?php echo $entry_name; ?><span class="required">*</span></td>

          <td><input class="form-control" type="text" name="name" value="<?php echo $name; ?>" />

            <?php if ($error_name) { ?>

            <span class="error"><?php echo $error_name; ?></span>

            <?php } ?></td>

        </tr>

        <tr>

          <td><?php echo $entry_code; ?></td>

          <td><input class="form-control" type="text" name="code" value="<?php echo $code; ?>" /></td>

        </tr>

        <tr>

          <td><?php echo $entry_country; ?></td>

          <td><div class="ui-select"><select name="country_id">

              <?php foreach ($countries as $country) { ?>

              <?php if ($country['country_id'] == $country_id) { ?>

              <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>

              <?php } else { ?>

              <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>

              <?php } ?>

              <?php } ?>

            </select></div></td>

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

