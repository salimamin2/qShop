
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/shipping.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <tr>
          <td><span class="required">*</span> <?php echo $entry_name; ?></td>
          <td><input name="name" value="<?php echo $name; ?>" size="100" />
            <?php if ($error_name) { ?>
            <span class="error"><?php echo $error_name; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_hex_code; ?></td>
          <td><input name="hex_code" value="<?php echo $hex_code; ?>" size="100" class="color_picker"/>
            <?php if ($error_hex_code) { ?>
            <span class="error"><?php echo $error_hex_code; ?></span>
            <?php } ?></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<script type="text/javascript" src="view/javascript/jquery/jpicker-1.1.6.min.js"></script>
<script type="text/javascript">        
  $(document).ready(function(){
      $('.color_picker').jPicker({
        images: {clientPath: '<?php echo HTTP_SERVER; ?>view/image/'}
      });
    });
</script>
