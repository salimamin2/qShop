	

<?php if ($error_warning) { ?>

<div class="warning"><?php echo $error_warning; ?></div>

<?php } ?>

<?php if ($success) { ?>

<div class="success"><?php echo $success; ?></div>

<?php } ?>

<div class="box table-wrapper products-table section">

  <div class="head">

    <h4 style="background-image: url('view/image/shipping.png');"><?php echo $heading_title; ?></h4>
	
  </div>
   	<div class="row filter-block">
		<div class="col-md-12">
			<div class="pull-right">
				<div class="buttons">   
  <a onclick="location = '<?php echo $insert; ?>'" class="btn-flat success"><span>+ <?php echo $button_insert; ?></span></a> <a onclick="$('form').submit();" class="btn-flat btn-danger btn-sm"><span><?php echo $button_delete; ?></span></a>
				</div>
			</div>
		</div>
	</div>

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

            <th class="left"><span class="line"></span><?php if ($sort == 'unit') { ?>

              <a href="<?php echo $sort_unit; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_unit; ?></a>

              <?php } else { ?>

              <a href="<?php echo $sort_unit; ?>"><?php echo $column_unit; ?></a>

              <?php } ?></th>

            <th class="right"><span class="line"></span><?php if ($sort == 'value') { ?>

              <a href="<?php echo $sort_value; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_value; ?></a>

              <?php } else { ?>

              <a href="<?php echo $sort_value; ?>"><?php echo $column_value; ?></a>

              <?php } ?></th>              

            <th class="right"><span class="line"></span><?php echo $column_action; ?></th>

          </tr>

        </thead>

        <tbody>

          <?php if ($weight_classes) { ?>

          <?php foreach ($weight_classes as $weight_class) { ?>

          <tr>

            <td style="text-align: center;"><?php if ($weight_class['selected']) { ?>

              <input type="checkbox" name="selected[]" value="<?php echo $weight_class['weight_class_id']; ?>" checked="checked" />

              <?php } else { ?>

              <input type="checkbox" name="selected[]" value="<?php echo $weight_class['weight_class_id']; ?>" />

              <?php } ?></td>

            <td class="left"><?php echo $weight_class['title']; ?></td>

            <td class="left"><?php echo $weight_class['unit']; ?></td>

            <td class="right"><?php echo $weight_class['value']; ?></td>

            <td class="right"><?php foreach ($weight_class['action'] as $action) { ?>

              [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]

              <?php } ?></td>

          </tr>

          <?php } ?>

          <?php } else { ?>

          <tr>

            <td class="center" colspan="5"><?php echo $text_no_results; ?></td>

          </tr>

          <?php } ?>

        </tbody>

      </table>
      </div>
    </form>

    <div class="pagination" style="display:none;"><?php echo $pagination; ?></div>

  </div>

</div>

