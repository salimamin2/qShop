
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
    <h1 style="background-image: url('view/image/product.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="location = '<?php echo $insert; ?>'" class="button"><span><?php echo $button_insert; ?></span></a><a onclick="$('form').submit();" class="button"><span><?php echo $button_delete; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="list">
        <thead>
          <tr>
            <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
            <td class="left"><?php if ($sort == 'title') { ?>
              <a href="<?php echo $sort_title; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_title; ?>"><?php echo $column_name; ?></a>
              <?php } ?></td>
            <td class="left"><?php if ($sort == 'status') { ?>
              <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
              <?php } ?></td>
            <td class="right"><?php echo $column_total; ?></td>
            <td class="right"><?php echo $column_action; ?></td>
          </tr>
        </thead>
        <tbody>
          <tr class="filter">
            <td></td>
            <td><input type="text" name="filter_title" value="<?php echo $filter_title; ?>" /></td>
            <td><select name="filter_status">
                <option value="*"></option>
                <?php if ($filter_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <?php } ?>
                <?php if (!is_null($filter_status) && !$filter_status) { ?>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
            <td></td>
            <td align="right"><a onclick="filter();" class="button"><span><?php echo $button_filter; ?></span></a></td>
          </tr>
          <?php if ($product_types) { ?>
          <?php foreach ($product_types as $product_type) { ?>
          <tr>
            <td style="text-align: center;">
              <?php if ($product['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $product_type['product_type_id']; ?>" checked="checked" />
              <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $product_type['product_type_id']; ?>" />
              <?php } ?>
            </td>
            <td class="left"><?php echo $product_type['title']; ?></td>
            <td class="left"><?php echo $product_type['status']; ?></td>
            <td class="right"><?php echo $product_type['total']; ?></td>
            <td class="right">
            <?php foreach ($product_type['action'] as $action) { ?>
              [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
            <?php } ?>
            </td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="5"><?php echo $text_no_results; ?></td>
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
	url = 'catalog/product_type&token=<?php echo $token; ?>';
	
	var filter_title = $('input[name=\'filter_title\']').attr('value');
	
	if (filter_title) {
		url += '&filter_title=' + encodeURIComponent(filter_title);
	}
	
	var filter_status = $('select[name=\'filter_status\']').attr('value');
	
	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}	

	location = url;
}
//--></script>

<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
//--></script>
