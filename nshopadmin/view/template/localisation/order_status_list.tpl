<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">

<div class="box table-wrapper products-table section">
	<div class="head well">
    <h3><i class="icon-folder-open"></i> <?php echo $heading_title; ?>
			<div class="pull-right">
        <a onclick="location = '<?php echo $insert; ?>'" class="btn-flat success"><span>+ <?php echo $button_insert; ?></span></a>
        <a onclick="$('form').submit();" class="btn-flat btn-danger btn-sm"><span><?php echo $button_delete; ?></span></a>
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

    <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">

     <div class="table-responsive">

		<table class="table table-hover" data-rel="data-grid">

        <thead>

          <tr>

            <th width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></th>

            <th class="left"><span class="left"></span><?php if ($sort == 'name') { ?>

              <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>

              <?php } else { ?>

              <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>

              <?php } ?></th>

            <th class="right"><span class="left"></span><?php echo $column_action; ?></th>

          </tr>

        </thead>

        <tbody>

          <?php if ($order_statuses) { ?>

          <?php foreach ($order_statuses as $order_status) { ?>

          <tr>

            <td style="text-align: center;"><?php if ($order_status['selected']) { ?>

              <input type="checkbox" name="selected[]" value="<?php echo $order_status['order_status_id']; ?>" checked="checked" />

              <?php } else { ?>

              <input type="checkbox" name="selected[]" value="<?php echo $order_status['order_status_id']; ?>" />

              <?php } ?></td>

            <td class="left"><?php echo $order_status['name']; ?></td>

            <td class="right"><?php foreach ($order_status['action'] as $action) { ?>

              <a class="btn <?php echo $action['class'] ?> btn-sm" data-toggle="tooltip " href="<?php echo $action['href']; ?>" title="<?php echo $action['text']; ?>"><i class="<?php echo $action['icon']; ?>"></i></a>

              <?php } ?></td>

          </tr>

          <?php } ?>

          <?php } else { ?>

          <tr>

            <td class="center" colspan="3"><?php echo $text_no_results; ?></td>

          </tr>

          <?php } ?>

        </tbody>

		</table>

	    </div>



    </form>

    <div class="pagination" style="display: none;"><?php echo $pagination; ?></div>

  </div>

</div>

