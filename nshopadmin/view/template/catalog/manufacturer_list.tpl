<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
<div class="box">
    <div class="head well">
		    <h3><i class="icon-list"></i> <?php echo $heading_title; ?>
            <div class="pull-right">
                <a href="<?php echo $insert; ?>" class="btn-flat success"><span>+ <?php echo $button_insert; ?></span></a>
                <button type="submit" class="btn-flat btn-danger btn-sm"><span><?php echo $button_delete; ?></span></button>
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
      <table class="table table-hover" data-rel="data-grid">
        <thead>
          <tr>
            <th data-bSortable="false" width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></th>
            <th class="left"><span class="line"></span><?php echo __('column_id'); ?></th>
            <th class="left"><span class="line"></span><?php echo __('column_name'); ?></th>
            <th class="left"><span class="line"></span><?php echo __('column_email'); ?></th>
            <th class="right"><span class="line"></span><?php echo __('column_sort_order') ?></th>
            <th class="right"><span class="line"></span><?php echo __('column_action') ?></th>
          </tr>
        </thead>
        <tbody>
          <?php if ($manufacturers) { ?>
          <?php foreach ($manufacturers as $manufacturer) { ?>
          <tr>
            <td style="text-align: center;"><?php if ($manufacturer['selected']) { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $manufacturer['manufacturer_id']; ?>" checked="checked" />
              <?php } else { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $manufacturer['manufacturer_id']; ?>" />
              <?php } ?></td>
            <td class="left"><?php echo $manufacturer['manufacturer_id']; ?></td>  
            <td class="left"><?php echo $manufacturer['name']; ?></td>
            <td class="left"><?php echo $manufacturer['email']; ?></td>
            <td class="right"><?php echo $manufacturer['sort_order']; ?></td>
            <td class="right"><?php foreach ($manufacturer['action'] as $action) { ?>
              <a class="btn <?php echo $action['class'] ?> btn-sm" data-toggle="tooltip" href="<?php echo $action['href']; ?>" title="<?php echo $action['text']; ?>"><i class="<?php echo $action['icon']; ?>"></i></a>
              <?php } ?>
            </td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="4"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    <div class="pagination"><?php echo $pagination; ?></div>
  </div>
</div>
</form>
