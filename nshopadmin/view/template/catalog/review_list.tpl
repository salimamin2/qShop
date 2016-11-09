

<?php if ($error_warning) { ?>

<div class="alert alert-danger"><?php echo $error_warning; ?></div>

<?php } ?>

<?php if ($success) { ?>

<div class="alert alert-success"><?php echo $success; ?></div>

<?php } ?>

<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">

<div class="box table-wrapper products-table section">

    <div class="head">
		<h4><i class="icon-list"></i> <?php echo $heading_title; ?></h4>
	</div>

   <div class="row filter-block">
		<div class="col-md-12">
			<div class="pull-right">
				<div class="buttons">
					<a href="<?php echo $insert; ?>" class="btn-flat success"><span>+ <?php echo $button_insert; ?></span></a>
					<button type="submit" class="btn-flat btn-danger"><span><?php echo $button_delete; ?></span></button>
				</div>
			</div>
		</div>
	</div>

  <div class="content">

      <table class="table table-hover">

        <thead>

          <tr>

            <th width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></th>

            <th class="left"><span class="line"></span><?php if ($sort == 'pd.name') { ?>

              <a href="<?php echo $sort_product; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_product; ?></a>

              <?php } else { ?>

              <a href="<?php echo $sort_product; ?>"><?php echo $column_product; ?></a>

              <?php } ?></th>

            <th class="left"><span class="line"></span><?php if ($sort == 'r.author') { ?>

              <a href="<?php echo $sort_author; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_author; ?></a>

              <?php } else { ?>

              <a href="<?php echo $sort_author; ?>"><?php echo $column_author; ?></a>

              <?php } ?></th>

            <th class="right"><span class="line"></span><?php if ($sort == 'r.rating') { ?>

              <a href="<?php echo $sort_rating; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_rating; ?></a>

              <?php } else { ?>

              <a href="<?php echo $sort_rating; ?>"><?php echo $column_rating; ?></a>

              <?php } ?></th>

            <th class="left"><span class="line"></span><?php if ($sort == 'r.status') { ?>

              <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>

              <?php } else { ?>

              <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>

              <?php } ?></th>

            <th class="left"><span class="line"></span><?php if ($sort == 'r.date_added') { ?>

              <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>

              <?php } else { ?>

              <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>

              <?php } ?></th>

            <th class="right"><span class="line"></span><?php echo $column_action; ?></th>

          </tr>

        </thead>

        <tbody>

          <?php if ($reviews) { ?>

          <?php foreach ($reviews as $review) { ?>

          <tr>

            <td style="text-align: center;"><?php if ($review['selected']) { ?>

              <input type="checkbox" name="selected[]" value="<?php echo $review['review_id']; ?>" checked="checked" />

              <?php } else { ?>

              <input type="checkbox" name="selected[]" value="<?php echo $review['review_id']; ?>" />

              <?php } ?></td>

            <td class="left"><?php echo $review['name']; ?></td>

            <td class="left"><?php echo $review['author']; ?></td>

            <td class="right"><?php echo $review['rating']; ?></td>

            <td class="left"><?php echo $review['status']; ?></td>

            <td class="left"><?php echo $review['date_added']; ?></td>

            <td class="right"><?php foreach ($review['action'] as $action) { ?>

              [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]

              <?php } ?></td>

          </tr>

          <?php } ?>

          <?php } else { ?>

          <tr>

            <td class="center" colspan="7"><?php echo $text_no_results; ?></td>

          </tr>

          <?php } ?>

        </tbody>

      </table>

    <div class="pagination"><?php echo $pagination; ?></div>

  </div>

</div>

</form>

