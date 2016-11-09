<div class="box">
    <form action="<?php echo $backup; ?>" method="post" enctype="multipart/form-data" id="backup">
  <div class="head well">
    <h3><i class="icon-folder-close"></i> <?php echo $heading_title; ?>
			<div class="pull-right">
        <button type="submit" class="btn-flat btn-primary btn-sm"><span><?php echo $button_backup; ?></span></button>
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

      <table class="form">

        <tr>

          <td><?php echo $entry_backup; ?></td>

          <td><div class="scrollbox" style="margin-bottom: 5px;">

              <?php $class = 'odd'; ?>

              <?php foreach ($tables as $table) { ?>

              <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>

              <div class="<?php echo $class; ?>">

                <input type="checkbox" name="backup[]" value="<?php echo $table; ?>" checked="checked" />

                <?php echo $table; ?> </div>

              <?php } ?>

            </div>

            <a onclick="$('input[name*=\'backup\']').attr('checked', 'checked');"><u><?php echo $text_select_all; ?></u></a> / <a onclick="$('input[name*=\'backup\']').attr('checked',false);"><u><?php echo $text_unselect_all; ?></u></a></td>

        </tr>

      </table>



  </div>

    </form>

</div>