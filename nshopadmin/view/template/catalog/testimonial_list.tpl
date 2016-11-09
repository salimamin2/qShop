

<?php if ($error_warning) { ?>

<div class="alert alert-danger"><?php echo $error_warning; ?></div>

<?php } ?>

<?php if ($success) { ?>

<div class="alert alert-success"><?php echo $success; ?></div>

<?php } ?>

<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">

<div class="box table-wrapper products-table section">

    <div class="head">
		<h4><i class="icon-tags"></i> <?php echo $heading_title; ?></h4>
	</div>
	
    <div class="row filter-block">
		<div class="col-md-12">
			<div class="pull-right">
				<div class="buttons">
					<a href="<?php echo $insert; ?>" class="btn-flat success">+ <?php echo $button_insert; ?></a>
					<button type="submit" class="btn-flat btn-danger btn-sm"><?php echo $button_delete; ?></button>
				</div>
			</div>
		</div>
	</div>

  <div class="content">

      <table class="table table-hover" data-rel="data-grid">

        <thead>

          <tr>

            <th width="1" style="align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></th>

            <th class="left"><span class="line"></span><?php echo $column_title; ?></th>

            <th class="right"><span class="line"></span><?php echo $column_sort_order; ?></th>

            <th class="right"><span class="line"></span><?php echo $column_status; ?></th>

            <th class="right"><span class="line"></span><?php echo $column_action; ?></th>

          </tr>

        </thead>

        <tbody>

          <?php if ($testimonials) { ?>

          <?php foreach ($testimonials as $testimonial) { ?>

          <tr>

            <td style="align: center;"><?php if ($testimonial['selected']) { ?>

              <input type="checkbox" name="selected[]" value="<?php echo $testimonial['testimonial_id']; ?>" checked="checked" />

              <?php } else { ?>

              <input type="checkbox" name="selected[]" value="<?php echo $testimonial['testimonial_id']; ?>" />

              <?php } ?></td>

            <td class="left"><?php echo $testimonial['title']; ?></td>

            <td class="right"><?php echo $testimonial['sort_order']; ?></td>

            <td class="right"><?php echo $testimonial['status']; ?></td>

            <td class="right"><?php foreach ($testimonial['action'] as $action) { ?>

              [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]

              <?php } ?></td>

          </tr>

          <?php } ?>

        <?php } ?>

        </tbody>

      </table>



    <div class="pagination"><?php echo $pagination; ?></div>

  </div>

  </form>

</div>

