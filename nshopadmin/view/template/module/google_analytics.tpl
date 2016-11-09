<div class="box">
  <div class="head well">
    <h3 style="background-image: url('view/image/module.png');"><?php echo $heading_title; ?>
      <div class="pull-right">
            <a onclick="$('#form').submit();" class="btn-flat btn-success btn-sm"><span><?php echo $button_save; ?></span></a> <a onclick="location = '<?php echo $cancel; ?>';" class="btn-flat btn-default btn-sm"><span><?php echo $button_cancel; ?></span></a>
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
          <td> <?php echo $entry_code; ?><span class="required">*</span></td>
          <td><textarea class="form-control" name="google_analytics_code" cols="40" rows="5"><?php echo $google_analytics_code; ?></textarea>
            <?php if ($error_code) { ?>
            <span class="error"><?php echo $error_code; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><div class="ui-select"><select name="google_analytics_status">
              <?php if ($google_analytics_status) { ?>
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
