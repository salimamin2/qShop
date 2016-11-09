<div class="box">
  <div class="head well">
    <h3 style="background-image: url('view/image/module.png');"><?php echo $heading_title; ?>
      <div class="pull-right">
        <div class="buttons"> 
            <a onclick="$('#form').submit();" class="btn-flat btn-success btn-sm"><span><?php echo $button_save; ?></span></a>
            <a onclick="location = '<?php echo $cancel; ?>';" class="btn-flat btn-default btn-sm"><span><?php echo $button_cancel; ?></span></a>
        </div>
      </div>
    </h3>
  </div>
 				
<?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php } ?>
  <div class="content">

    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">

      <table class="form">

        <tr>

          <td><?php echo $entry_limit; ?></td>

          <td><input type="text" name="forthcoming_limit" value="<?php echo $forthcoming_limit; ?>" size="1" /></td>

        </tr>

        <tr>

          <td><?php echo $entry_position; ?></td>

          <td><div class="ui-select"><select name="forthcoming_position">

              <?php foreach ($positions as $position) { ?>

              <?php if ($forthcoming_position == $position['position']) { ?>

              <option value="<?php echo $position['position']; ?>" selected="selected"><?php echo $position['title']; ?></option>

              <?php } else { ?>

              <option value="<?php echo $position['position']; ?>"><?php echo $position['title']; ?></option>

              <?php } ?>

              <?php } ?>

            </select></div></td>

        </tr>

        <tr>

          <td><?php echo $entry_status; ?></td>

          <td><div class="ui-select"><select name="forthcoming_status">

              <?php if ($forthcoming_status) { ?>

              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>

              <option value="0"><?php echo $text_disabled; ?></option>

              <?php } else { ?>

              <option value="1"><?php echo $text_enabled; ?></option>

              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>

              <?php } ?>

            </select></div></td>

        </tr>

        <tr>

          <td><?php echo $entry_sort_order; ?></td>

          <td><input type="text" name="forthcoming_sort_order" value="<?php echo $forthcoming_sort_order; ?>" size="1" /></td>

        </tr>

		<tr>

        <td><?php echo $entry_product; ?></td>

        <td><div class="scrollbox">

            <?php $class = 'odd'; ?>

            <?php foreach ($products as $product) { ?>

            <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>

            <div class="<?php echo $class; ?>">

              <?php if (in_array($product['product_id'], $forthcoming_product)) { ?>

              <input type="checkbox" name="forthcoming_product[]" value="<?php echo $product['product_id']; ?>" checked="checked" />

              <?php echo $product['name']; ?>

              <?php } else { ?>

              <input type="checkbox" name="forthcoming_product[]" value="<?php echo $product['product_id']; ?>" />

              <?php echo $product['name']; ?>

              <?php } ?>

            </div>

            <?php } ?>

          </div></td>

      </tr>

      </table>

    </form>

  </div>

</div>

