<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
<div class="box table-wrapper products-table section">
    <div class="head well">
      <h3><i class="icon-tags"></i> <?php echo $heading_title; ?>
			  <div class="pull-right">
          <a href="<?php echo $insert; ?>" class="btn-flat success "><span>+ <?php echo $button_insert; ?></span></a>
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



	    <div class="table-responsive">

		<table class="table table-hover" data-rel="data-grid">

		    <thead>

          <tr>

              <th width="1">&nbsp;</th>

              <th class="left"><span class="line"></span><?php echo $column_name; ?></th>

              <th class="left"><span class="line"></span><?php echo $column_code; ?></th>

              <th class="right"><span class="line"></span><?php echo $column_discount; ?></th>

              <th class="left"><span class="line"></span><?php echo $column_date_start; ?></th>

              <th class="left"><span class="line"></span><?php echo $column_date_end; ?></th>

              <th class="left"><span class="line"></span><?php echo $column_status; ?></th>

              <th class="right"><span class="line"></span><?php echo $column_action; ?></th>

          </tr>

        </thead>

        <tbody>

          <?php if ($coupons) { ?>

          <?php foreach ($coupons as $coupon) { ?>

          <tr>

            <td style="text-align: center;"><?php if ($coupon['selected']) { ?>

              <input type="checkbox" name="selected[]" value="<?php echo $coupon['coupon_id']; ?>" checked="checked" />

              <?php } else { ?>

              <input type="checkbox" name="selected[]" value="<?php echo $coupon['coupon_id']; ?>" />

              <?php } ?></td>

            <td class="left"><?php echo $coupon['name']; ?></td>

            <td class="left"><?php echo $coupon['code']; ?></td>

            <td class="right"><?php echo $coupon['discount']; ?></td>

            <td class="left"><?php echo $coupon['date_start']; ?></td>

            <td class="left"><?php echo $coupon['date_end']; ?></td>

            <td class="left"><?php echo $coupon['status']; ?></td>

            <td class="right"><?php foreach ($coupon['action'] as $action) { ?>

              <a class="btn <?php echo $action['class'] ?> btn-sm" data-toggle="tooltip " href="<?php echo $action['href']; ?>" title="<?php echo $action['text']; ?>"><i class="<?php echo $action['icon']; ?>"></i></a> 

              <?php } ?></td>

          </tr>

          <?php } ?>

          <?php } ?>

        </tbody>

      </table>

  </div>

</form>

</div>
</div>
</div>

