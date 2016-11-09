

<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php } ?>

<div class="box">

  <div class="left"></div>

  <div class="right"></div>

  <div class="heading">

    <h1 style="background-image: url('view/image/module.png');"><?php echo $heading_title; ?></h1>

    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>

  </div>

  <div class="content">

    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">

      <table class="form">

        <tr>

          <td><?php echo $entry_position; ?></td>

          <td><select name="account_position">

              <?php if ($account_position == 'left') { ?>

              <option value="left" selected="selected"><?php echo $text_left; ?></option>

              <?php } else { ?>

              <option value="left"><?php echo $text_left; ?></option>

              <?php } ?>

              <?php if ($account_position == 'right') { ?>

              <option value="right" selected="selected"><?php echo $text_right; ?></option>

              <?php } else { ?>

              <option value="right"><?php echo $text_right; ?></option>

              <?php } ?>

              <?php if ($account_position == 'top') { ?>

              <option value="top" selected="selected"><?php echo $text_top; ?></option>

              <?php } else { ?>

              <option value="top"><?php echo $text_top; ?></option>

              <?php } ?>

              <?php if ($account_position == 'bottom') { ?>

              <option value="bottom" selected="selected"><?php echo $text_bottom; ?></option>

              <?php } else { ?>

              <option value="bottom"><?php echo $text_bottom; ?></option>

              <?php } ?>

            </select></td>

        </tr>

        <tr>

          <td><?php echo $entry_status; ?></td>

          <td><select name="account_status">

              <?php if ($account_status) { ?>

              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>

              <option value="0"><?php echo $text_disabled; ?></option>

              <?php } else { ?>

              <option value="1"><?php echo $text_enabled; ?></option>

              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>

              <?php } ?>

            </select></td>

        </tr>

        <tr>

          <td><?php echo $entry_sort_order; ?></td>

          <td><input type="text" name="account_sort_order" value="<?php echo $account_sort_order; ?>" size="1" /></td>

        </tr>

      </table>

    </form>

  </div>

</div>

