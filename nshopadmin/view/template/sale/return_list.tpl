<div class="head well">
    <h3><i class="icon-list"></i> <?php echo $heading_title; ?>
    <div class="pull-right">
        <div class="buttons">
            <a href="<?php echo $return_add_link; ?>" class="btn-flat success"><span>+ Insert</span></a>
            <button type="button" class="btn-flat btn-danger btn-sm"><span>Delete</span></button>
        </div>
    </div>
    </h3>  
</div>
<form action="" method="post" enctype="multipart/form-data" id="form">
    <div class="box">
      <div class="content">
        <table class="table table-hover" data-rel="ajax-grid" data-grid-url="<?php echo $rReturnList ?>">
          <thead>
            <tr>
                <th data-searchable="false" width="1" style="text-align: center; width: 13px;" class="sorting_disabled" rowspan="1" colspan="1" aria-label=""><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);"></th>
                <th><?php echo _('Return ID'); ?></th>
                <th><?php echo _('Order ID'); ?></th>
                <th><?php echo _('Customer Name'); ?></th>
                <th><?php echo _('Product Name'); ?></th>
                <th><?php echo _('Model'); ?></th>
                <th><?php echo _('Status'); ?></th>
                <th><?php echo _('Date Added'); ?></th>
                <th><?php echo _('Date Modified'); ?></th>
                <th><?php echo _('Action'); ?></th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
</form>