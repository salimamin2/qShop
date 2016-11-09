
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
    <h1 style="background-image: url('view/image/shipping.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons">
        <a onclick="location = '<?php echo $insert; ?>'" class="button"><span><?php echo $button_insert; ?></span></a>
        <a onclick="$('#form').attr('action','<?php echo $action_update; ?>');$('#form').submit();" class="button"><span><?php echo $button_update; ?></span></a>
        <a onclick="$('form').submit();" class="button"><span><?php echo $button_delete; ?></span></a>
    </div>
  </div>
  <div class="content">
    <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="list">
        <thead>
          <tr>
            <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
            <td class="left"><?php if ($sort == 'name') { ?>
              <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
              <?php } ?></td>
            <td class="left"><?php if ($sort == 'hex_code') { ?>
              <a href="<?php echo $sort_hex_code; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_hex_code; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_hex_code; ?>"><?php echo $column_hex_code; ?></a>
              <?php } ?></td>
            <td class="right"><?php echo $column_action; ?></td>
          </tr>
        </thead>
        <tbody>
            <tr class="filter">
                <td></td>
                <td><input type="text" name="filter_name" value="<?php echo $filter_name; ?>" /></td>
                <td><input type="text" name="filter_hex_code" value="<?php echo $filter_hex_code; ?>" /></td>
                <td align="right"><a onclick="filter();" class="button"><span><?php echo $button_filter; ?></span></a></td>
            </tr>
          <?php if ($colors) { $row =0; ?>
          <?php foreach ($colors as $color) { ?>
          <tr>
            <td style="text-align: center;"><?php if ($color['selected']) { ?>
              <input type="checkbox" name="selected[<?php echo $row; ?>]" value="<?php echo $color['color_id']; ?>" checked="checked" />
              <?php } else { ?>
              <input type="checkbox" name="selected[<?php echo $row; ?>]" value="<?php echo $color['color_id']; ?>" />
              <?php } ?></td>
            <td class="left"><?php echo $color['name']; ?></td>
            <td class="left"><input type="text" name="hex_code[<?php echo $row; ?>]" value="<?php echo $color['hex_code']; ?>" class="color_picker" /></td>
            <td class="right"><?php foreach ($color['action'] as $action) { ?>
              [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
              <?php } ?></td>
          </tr>
          <?php $row++;} ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="4"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </form>
    <div class="pagination"><?php echo $pagination; ?></div>
  </div>
</div>
<script type="text/javascript"><!--
function filter() {
	url = 'catalog/color&token=<?php echo $this->session->data['token']; ?>';
	
	var filter_name = $('input[name=\'filter_name\']').attr('value');
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
	
	var filter_hex_code = $('input[name=\'filter_hex_code\']').attr('value');
	if (filter_hex_code) {
		url += '&filter_hex_code=' + encodeURIComponent(filter_hex_code);
	}
	
	location = url;
}
//--></script>
<script type="text/javascript" src="view/javascript/jquery/jpicker-1.1.6.min.js"></script>
<script type="text/javascript">        
  $(document).ready(function(){
      $('.color_picker').jPicker({
        images: {clientPath: '<?php echo HTTP_SERVER; ?>view/image/'}
      });
    });
</script>
