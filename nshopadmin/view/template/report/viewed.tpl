<div class="box table-wrapper products-table section">
	<div class="head well">
    <h3>
        <i class="icon-th-list"></i> <?php echo $heading_title; ?>
			<div class="pull-right">
        <a onclick="<?php echo $reset; ?>" class="btn-flat btn-primary btn-sm"><span><?php echo $button_reset; ?></span></a>
			</div>
    </h3>
	</div>
  <?php if ($success) { ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="content">
   <div class="table-responsive">
    <table class="table table-hover">

      <thead>

        <tr>

          <th class="left"><?php echo $column_name; ?></th>

          <th class="left"><span class="line"></span><?php echo $column_model; ?></th>

          <th class="left"><span class="line"></span><?php echo $column_viewed; ?></th>

          <th class="left"><span class="line"></span><?php echo $column_percent; ?></th>

        </tr>

      </thead>

      <tbody>

        <?php if ($products) { ?>

        <?php foreach ($products as $product) { ?>

        <tr>

          <td class="left"><?php echo $product['name']; ?></td>

          <td class="left"><?php echo $product['model']; ?></td>

          <td class="right"><?php echo $product['viewed']; ?></td>

          <td class="right"><?php echo $product['percent']; ?></td>

        </tr>

        <?php } ?>

        <?php } else { ?>

        <tr>

          <td class="center" colspan="4"><?php echo $text_no_results; ?></td>

        </tr>

        <?php } ?>

      </tbody>

    </table>
   </div>
    <div class="pagination"><?php echo $pagination; ?></div>

  </div>

</div>

