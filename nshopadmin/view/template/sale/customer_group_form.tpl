<div class="box">
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div class="head well">
    <h3><i class="icon-user"></i> <?php echo $heading_title; ?>
			<div class="pull-right">
            <button tyoe="submit" class="btn-flat btn-success btn-sm"><span><?php echo $button_save; ?></span></button>
            <a href="<?php echo $cancel; ?>" class="btn-flat btn-default btn-sm"><span><?php echo $button_cancel; ?></span></a>
      </div>
    </h3>
  </div>
    <?php if ($error_warning) { ?>
          <div class="alert alert-danger"><?php echo $error_warning; ?></div>
    <?php } ?>
  <div class="content">

      <table class="form">

        <tr>

          <td> <?php echo $entry_name; ?><span class="required">*</span></td>

          <td><input class="form-control" type="text" name="name" value="<?php echo $name; ?>" />

            <?php if ($error_name) { ?>

            <span class="error"><?php echo $error_name; ?></span>

            <?php  } ?></td>

        </tr>

      </table>

  </div>

</form>

</div>

