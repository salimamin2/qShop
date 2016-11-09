<div class="box table-wrapper products-table section">
	<div class="head well">
    <h3>
        <i class="icon-th-list"></i> <?php echo $heading_title; ?>
			<div class="pull-right">
      	<a onclick="location = '<?php echo $insert; ?>'" class="btn-flat success"><span>+ <?php echo $button_insert; ?></span></a> <a onclick="$('form').submit();" class="btn-flat btn-danger btn-sm"><span><?php echo $button_delete; ?></span></a>
			</div>
    </h3>
	</div>
    <?php if ($error_warning) { ?>
      <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    <?php if ($success) { ?>
      <div class="success"><?php echo $success; ?></div>
    <?php } ?>
  <div class="content">

    <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">

     <div class="table-responsive">

		<table class="table table-hover" data-rel="data-grid">

        <thead>

          <tr>

            <th width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></th>

            <th class="left"><span class="line"></span><?php if ($sort == 'title') { ?>

              <a href="<?php echo $sort_title; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_title; ?></a>

              <?php } else { ?>

              <a href="<?php echo $sort_title; ?>"><?php echo $column_title; ?></a>

              <?php } ?></th>

            <th class="right"><span class="line"></span><?php echo $column_action; ?></th>

          </tr>

        </thead>

        <tbody>

          <?php if ($tax_classes) { ?>

          <?php foreach ($tax_classes as $tax_class) { ?>

          <tr>

            <td style="text-align: center;"><?php if ($tax_class['selected']) { ?>

              <input type="checkbox" name="selected[]" value="<?php echo $tax_class['tax_class_id']; ?>" checked="checked" />

              <?php } else { ?>

              <input type="checkbox" name="selected[]" value="<?php echo $tax_class['tax_class_id']; ?>" />

              <?php } ?></td>

            <td class="left"><?php echo $tax_class['title']; ?></td>

            <td class="right"><?php foreach ($tax_class['action'] as $action) { ?>

              <a class="btn <?php echo $action['class'] ?> btn-sm" data-toggle="tooltip" href="<?php echo $action['href']; ?>" title="<?php echo $action['text']; ?>"><i class="<?php echo $action['icon']; ?>"></i></a>

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

    <div class="pagination" style="display:none"><?php echo $pagination; ?></div>

  </div>

</div>

