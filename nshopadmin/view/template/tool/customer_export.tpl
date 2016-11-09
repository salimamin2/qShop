<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
  <h1 style="background-image: url('view/image/customer.png');"><?php echo $heading_title?></h1>
  <div class="buttons"><a onclick="return $('#form').submit();" class="button"><span><?php echo $button_export; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table border="0" align="center">
        <tr>
          <td colspan="3"><?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?></td>
        </tr>
      </table>
    </form>
  </div>
</div>
