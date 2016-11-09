
<?php if ($error_warning) { ?>
  <div class="alert alert-danger"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
  <div class="alert alert-success"><?php echo $success; ?></div>
<?php } ?>
<form action="<?php echo $delete; ?>" method="post" id="form">
<div class="box">
  <div class="head well">
    <h3>
      <i class="icon-tags"></i> <?php echo $heading_title; ?>
      <div class="pull-right">
        <button type="button" onclick="location = '<?php echo $insert; ?>'" class="btn btn-success btn-sm"><i class="icon-plus"></i> <?php echo $button_insert; ?></button>
        <button type="submit" class="btn btn-danger btn-sm"><i class="icon-trash"></i> <?php echo $button_delete; ?></button>
      </div>
    </h3>
  </div>
  <div class="content">
      <table class="table table-hover" data-rel="data-grid">
        <thead>
          <tr>
              <th width="1"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></th>
              <th><?php echo $column_name; ?></th>
              <th><?php echo $column_discount; ?></th>
              <th><?php echo $column_customer_group_id; ?></th>
              <th><?php echo $column_sort_order; ?></th>
              <th><?php echo $column_date_start; ?></th>
              <th><?php echo $column_date_end; ?></th>
              <th><?php echo $column_status; ?></th>
              <th><?php echo $column_action; ?></th>
          </tr>
        </thead>
        <tbody>
            <?php if($discounts): ?>
              <?php foreach ($discounts as $discount): ?>
                <tr>
                  <td style="text-align: center;"><?php if ($discount['selected']) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $discount['discount_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $discount['discount_id']; ?>" />
                    <?php } ?></td>
                  <td class="left"><?php echo $discount['name']; ?></td>
          
                  <td class="right"><?php echo $discount['discount']; ?></td>
                  <td class="right"><?php echo ($discount['cgname'] == '' ? 'All Customers' : $discount['cgname'] ); ?></td>
                  
                  
                  <td class="right"><?php echo $discount['sort_order']; ?></td>
                  <td class="left"><?php echo $discount['date_start']; ?></td>
                  <td class="left"><?php echo $discount['date_end']; ?></td>
                  <td class="left"><?php echo $discount['status']; ?></td>
                  <td class="right"><a class="btn btn-info btn-sm" href="<?php echo $discount['edit']; ?>"><i class="icon-edit"></i></a></td>
                </tr>
              <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
  </div>
</div>
</form>
