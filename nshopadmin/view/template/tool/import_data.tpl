<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/import.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons">
        <a onclick="$('#import').submit();" class="button"><span><?php echo $button_import; ?></span></a>
    </div>
  </div>
  <div class="content">
    <form action="<?php echo $import; ?>" method="post" enctype="multipart/form-data" id="import">
      <table class="form">
        <tr>
          <td><?php echo $entry_import; ?></td>
          <td><input type="file" name="import" /></td>
          <td>&nbsp;</td>
          <td><a href="<?php echo $reference_file; ?>">Reference/Sample File<span class="help">Download Here</span></a></td>
        </tr>
      </table>
    </form>
  </div>
</div>