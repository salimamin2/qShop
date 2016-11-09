<div class="box table-wrapper products-table section">
   <div class="head well">
    <h3><i class="icon-folder-open"></i> <?php echo $heading_title; ?>
			<div class="pull-right">

        <a onclick="$('#form').submit();" class="btn-flat btn-success btn-sm"><span><?php echo $button_save; ?></span></a>

        <a onclick="location = '<?php echo $cancel; ?>';" class="btn-flat btn-default btn-sm"><span><?php echo $button_cancel; ?></span></a>

				</div>
		  </h3>
    </div>
    <?php if ($error_warning) { ?>
          <div class="alert alert-danger"><?php echo $error_warning; ?></div>
    <?php } ?>
  <div class="content">

    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">

      <table class="form">

        <?php foreach ($languages as $language) { ?>

        <tr>

          <td><span class="required">*</span> <?php echo $entry_name; ?></td>

          <td><input class="form-control" name="order_status[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($order_status[$language['language_id']]) ? $order_status[$language['language_id']]['name'] : ''; ?>" />

            <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />

            <?php if (isset($error_name[$language['language_id']])) { ?>

            <span class="error"><?php echo $error_name[$language['language_id']]; ?></span>

            <?php } ?></td>

        </tr>

        <?php } ?>

      </table>

    </form>

  </div>
</div>


