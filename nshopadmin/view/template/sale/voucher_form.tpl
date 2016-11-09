
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <div class="box">
    <div class="head well">
        <h3>
        <i class="icon-th-list"></i> <?php echo $heading_title; ?>
          <div class="pull-right">
                  <a onclick="$('#form').submit();" class="btn btn-success"><span><?php echo $button_save; ?></span></a>
                  <a onclick="location = '<?php echo $cancel; ?>';" class="btn btn-default"><span><?php echo $button_cancel; ?></span></a>
          </div>
        </h3>
    </div>
      <?php if ($error_warning) { ?>
          <div class="warning"><?php echo $error_warning; ?></div>
      <?php } ?>
    <div class="content">
     <ul id="tabs" class="nav nav-tabs">
         <li class="active">
          <a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a>
         </li>
         <li class="tab-pane" data-toggle="tab">
        <?php if ($voucher_id) { ?>

        <a href="#tab-history">Voucher History</a>
         </li>
        <?php } ?>
         </ul>

      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <div id="tab-general" class="tab-pane active">
          <table class="form">
            <tr>
              <td><span class="required">*</span> <?php echo $entry_code; ?></td>
              <td><input type="text" name="code" value="<?php echo $code; ?>" />
                <?php if ($error_code) { ?>
                <span class="error"><?php echo $error_code; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_from_name; ?></td>
              <td><input type="text" name="from_name" value="<?php echo $from_name; ?>" />
                <?php if ($error_from_name) { ?>
                <span class="error"><?php echo $error_from_name; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_from_email; ?></td>
              <td><input type="text" name="from_email" value="<?php echo $from_email; ?>" />
                <?php if ($error_from_email) { ?>
                <span class="error"><?php echo $error_from_email; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_to_name; ?></td>
              <td><input type="text" name="to_name" value="<?php echo $to_name; ?>" />
                <?php if ($error_to_name) { ?>
                <span class="error"><?php echo $error_to_name; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_to_email; ?></td>
              <td><input type="text" name="to_email" value="<?php echo $to_email; ?>" />
                <?php if ($error_to_email) { ?>
                <span class="error"><?php echo $error_to_email; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_theme; ?></td>
              <td><select name="voucher_theme_id">
                  <?php foreach ($voucher_themes as $voucher_theme) { ?>
                  <?php if ($voucher_theme['voucher_theme_id'] == $voucher_theme_id) { ?>
                  <option value="<?php echo $voucher_theme['voucher_theme_id']; ?>" selected="selected"><?php echo $voucher_theme['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $voucher_theme['voucher_theme_id']; ?>"><?php echo $voucher_theme['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_message; ?></td>
              <td><textarea name="message" cols="40" rows="5"><?php echo $message; ?></textarea></td>
            </tr>
            <tr>
              <td><?php echo $entry_amount; ?></td>
              <td><input type="text" name="amount" value="<?php echo $amount; ?>" />
                <?php if ($error_amount) { ?>
                <span class="error"><?php echo $error_amount; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_status; ?></td>
              <td><select name="status">
                  <?php if ($status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
          </table>
        </div>
        <?php if ($voucher_id) { ?>
        <div id="tab-history">
          <div id="historyo">
              <table class="list">
                  <thead>
                  <tr align="left">
                      <td class="right"><b>Order ID</b></td>
                      <td class="left"><b>Customer</b></td>
                      <td class="right"><b>Amount Added</b></td>
                      <td class="left"><b>Date </b></td>
                  </tr>
                  </thead>
                  <tbody>
                  <?php if ($histories) { ?>
                  <?php foreach ($histories as $history) { ?>

                  <tr>
                      <td class="right"><?php echo $history['order_id']; ?></td>
                      <td class="left"><?php echo $history['customer']; ?></td>
                      <td class="right"><?php echo $history['amount']; ?></td>
                      <td class="left"><?php echo $history['date_added']; ?></td>
                  </tr>
                  <?php } ?>
                  <?php } else { ?>
                  <tr>
                      <td class="center" colspan="4"><?php echo $text_no_results; ?></td>
                  </tr>
                  <?php } ?>
                  </tbody>
              </table>
              <div class="pagination"><?php echo $pagination; ?></div>


          </div>
        </div>
        <?php } ?>
    </div>
      </form>
    </div>
  </div>

<?php if ($voucher_id) { ?>

<?php } ?>
<script type="text/javascript"><!--
$('#tabs a').tabs(); 
//--></script> 
<?php echo $footer; ?>