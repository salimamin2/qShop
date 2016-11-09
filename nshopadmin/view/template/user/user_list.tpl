<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
<div class="box table-wrapper products-table section">
	<div class="head well">
    <h3><i class="icon-user"></i> <?php echo $heading_title; ?>
			<div class="pull-right">
        <a href="<?php echo $insert; ?>" class="btn-flat success"><span> <?php echo $button_insert; ?></span></a>

        <a type="submit" class="btn-flat btn-danger btn-sm"><span><?php echo $button_delete; ?></span></a>
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

      <div class="table-responsive">

		<table class="table table-hover" data-rel="data-grid">

        <thead>

          <tr>

              <th class="col-1 text-center" ><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></th>

              <th><span class="line"></span><?php echo __('column_username'); ?></th>

              <th><span class="line"></span><?php echo __('column_status'); ?></th>

              <th><span class="line"></span><?php echo __('column_date_added'); ?></th>

              <th><span class="line"></span><?php echo __('column_action'); ?></th>

          </tr>

        </thead>

        <tbody>

          <?php if ($users) { ?>

          <?php foreach ($users as $user) { ?>

          <tr>

            <td style="text-align: center;"><?php if ($user['selected']) { ?>

              <input type="checkbox" name="selected[]" value="<?php echo $user['user_id']; ?>" checked="checked" />

              <?php } else { ?>

              <input type="checkbox" name="selected[]" value="<?php echo $user['user_id']; ?>" />

              <?php } ?></td>

            <td class="left"><?php echo $user['username']; ?></td>

            <td class="left"><?php echo $user['status']; ?></td>

            <td class="left"><?php echo $user['date_added']; ?></td>

            <td class="right"><?php foreach ($user['action'] as $action) { ?>

              <a class="btn <?php echo $action['class'] ?> btn-sm" data-toggle="tooltip" href="<?php echo $action['href']; ?>" title="<?php echo $action['text']; ?>"><i class="<?php echo $action['icon']; ?>"></i></a>

              <?php } ?></td>

          </tr>

          <?php } ?>

          <?php } ?>

        </tbody>

      </table>

      </div>

  </div>

</div>

</form>