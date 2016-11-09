<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
<div class="box table-wrapper products-table section">
  <div class="head well">
    <h3><i class="icon-user"></i> <?php echo $heading_title; ?>
			<div class="pull-right">
        <a href="<?php echo $insert; ?>" class="btn-flat success"><span>+ <?php echo $button_insert; ?></span></a> <button type="submit" class="btn-flat btn-danger btn-sm"><span><?php echo $button_delete; ?></span></button>
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

                <th width="1">&nbsp;</th>

                <th class="left"><?php echo $column_name; ?></th>

                <th class="right"><?php echo $column_action; ?></th>

            </tr>

        </thead>

        <tbody>

          <?php if ($customer_groups) { ?>

          <?php foreach ($customer_groups as $customer_group) { ?>

          <tr>

            <td style="text-align: center;"><?php if ($customer_group['selected']) { ?>

              <input type="checkbox" name="selected[]" value="<?php echo $customer_group['customer_group_id']; ?>" checked="checked" />

              <?php } else { ?>

              <input type="checkbox" name="selected[]" value="<?php echo $customer_group['customer_group_id']; ?>" />

              <?php } ?></td>

            <td class="left"><?php echo $customer_group['name']; ?></td>

            <td class="right"><?php foreach ($customer_group['action'] as $action) { ?>

              <a class="btn <?php echo $action['class'] ?> btn-sm" data-toggle="tooltip " href="<?php echo $action['href']; ?>" title="<?php echo $action['text']; ?>"><i class="<?php echo $action['icon']; ?>"></i></a>

              <?php } ?></td>

          </tr>

          <?php } ?>

          <?php } ?>

        </tbody>

      </table>
	    </div>



    </form>
    <div class="pagination" style="display: none;"><?php echo $pagination; ?></div>

  </div>

</div>

