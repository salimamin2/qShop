<?php if ($error_warning) { ?>
  <div class="alert alert-danger"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($error) { ?>
  <div class="alert alert-danger"><?php echo $error; ?></div>
<?php } ?>

<form action="<?php echo $action; ?>" method="post" class="form-horizontal" id="form">
<div class="box">
    <div class="head well">
      <h3>
        <i class="icon-tags"></i> <?php echo $heading_title; ?>
        <div class="pull-right">
          <button type="submit" class="btn btn-primary btn-sm"><i class="icon-check"></i> <?php echo $button_save; ?></button>
          <button type="button" onclick="location = '<?php echo $cancel; ?>';" class="btn btn-default btn-sm"><i class="icon-remove"></i> <?php echo $button_cancel; ?></button>
        </div>
      </h3>
    </div>

  <div class="content">
      <?php foreach ($languages as $language): ?>
      <div id="language<?php echo $language['language_id']; ?>">
          <table class="form">
              <tr>
                <td><span class="required">*</span> <?php echo $entry_name; ?></td>
                <td>
                    <div class="col-sm-12">
                      <input name="discount_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($discount_description[$language['language_id']]) ? $discount_description[$language['language_id']]['name'] : ''; ?>" class="form-control" />
                    </div>
                    <?php if (isset($error_name[$language['language_id']])) { ?>
                      <span class="error"><?php echo $error_name[$language['language_id']]; ?></span>
                    <?php } ?>
                </td>
              </tr>

              <tr>
                <td><span class="required">*</span> <?php echo $entry_description; ?></td>
                <td>
                  <div class="col-sm-12">
                    <textarea name="discount_description[<?php echo $language['language_id']; ?>][description]" cols="40" rows="5" class="form-control"><?php echo isset($discount_description[$language['language_id']]) ? $discount_description[$language['language_id']]['description'] : ''; ?></textarea>
                  </div>

                  <?php if (isset($error_description[$language['language_id']])) { ?>
                      <span class="error"><?php echo $error_description[$language['language_id']]; ?></span>
                  <?php } ?></td>
              </tr>
          </table>
      </div>
      <?php endforeach; ?>
      
      <table class="form">
        <tr>
            <td><?php echo $entry_type; ?></td>
            <td>
              <div class="col-sm-3">
                <?php echo CHtml::dropDownList('type',$type,array('P' => $text_percent,'F' => $text_amount),array('class' => 'form-control')); ?>
              </div>
            </td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_discount; ?></td>
          <td>
            <div class="col-sm-3">
                <input type="number" step="0.0001" name="discount" value="<?php echo $discount; ?>" class="form-control" />
            </div>
          </td>
        </tr>

        <tr>
          <td><?php echo $entry_total; ?></td>
          <td>
            <div class="col-sm-3">
              <input type="number" name="total" value="<?php echo $total; ?>" class="form-control" />
            </div>
          </td>
        </tr>

       
        <tr>
          <td><?php echo $entry_shipping; ?></td>
          <td>
              <div class="col-sm-3">
                  <?php echo CHtml::radioButtonList('shipping',$shipping,array(1 => $text_yes,0 => $text_no)); ?>
              </div>
          </td>
        </tr>

        <tr>
          <td><?php echo $entry_product; ?></td>
          <td>
            <table>
              <tr>
                <td style="padding: 0;" colspan="3">
                  <div class="col-sm-4">
                      <?php echo CHtml::dropDownList('category','',$categories,array('class' => 'form-control category','prompt' => 'Select Category')); ?>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="no-padding" width="47%">
                  <div class="col-sm-12">
                      <select multiple="multiple" id="product" size="10" class="form-control"></select>
                  </div>
                </td>
                <td width="6%" style="vertical-align: middle;" class="text-center">
                  <button type="button" onclick="addProduct();" class="btn btn-default"><i class="icon-circle-arrow-right"></i></button>
                  <button type="button" onclick="removeProduct();" class="btn btn-default"><i class="icon-circle-arrow-left"></i></button>
                </td>
                <td class="no-padding" width="47%">
                  <div class="col-sm-12">
                    <select id="discount" multiple="multiple" size="10" class="form-control"></select>
                  </div>
                </td>
              </tr>
            </table>

            <div id="discount_product">
              <?php foreach ($discount_product as $product_id) { ?>
                  <input type="hidden" name="discount_product[]" value="<?php echo $product_id; ?>" />
              <?php } ?>
            </div>
          </td>
        </tr>

        <tr>
          <td><?php echo $entry_customer_groups; ?></td>
          <td>
              <div class="col-sm-3">
                  <?php echo CHtml::dropDownList('customer_g_id',$customer_group_id,$customer_groups,array('class' => 'form-control','prompt' => 'All Customers')); ?>
              </div>
          </td>
        </tr>

        <tr>
          <td><span class="required">*</span> <?php echo $entry_sort_order; ?></td>
          <td>
              <div class="col-sm-2">
                <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="12" id="sort_order" class="form-control" />
              </div>
              <?php if (isset($error_sort_order)) { ?>
                <span class="error"><?php echo $error_sort_order; ?></span>
              <?php } ?></td>
        </tr>

        <tr>
          <td><?php echo $entry_date_start; ?></td>
          <td>
            <div class="col-sm-3">
                <input type="text" name="date_start" value="<?php echo $date_start; ?>" size="12" data-provide="datepicker-inline" class="form-control" />
            </div>
          </td>
        </tr>

        <tr>
          <td><?php echo $entry_date_end; ?></td>
          <td>
              <div class="col-sm-3">
                  <input type="text" name="date_end" value="<?php echo $date_end; ?>" size="12" data-provide="datepicker-inline" class="form-control" />
              </div>
          </td>
        </tr>

        <tr>
          <td><?php echo $entry_status; ?></td>
          <td>
            <div class="col-sm-3">
                <?php echo CHtml::dropDownList('status',$status,array(1 => $text_enabled,0 => $text_disabled),array('class' => 'form-control')); ?>
            </div>
          </td>
        </tr>
      </table>
  </div>
</div>
</form>


<script type="text/javascript">
function addProduct() {
	$('#product :selected').each(function() {
		$(this).remove();
		$('#discount option[value=\'' + $(this).attr('value') + '\']').remove();
		$('#discount').append('<option value="' + $(this).attr('value') + '">' + $(this).text() + '</option>');
		$('#discount_product input[value=\'' + $(this).attr('value') + '\']').remove();
		$('#discount_product').append('<input type="hidden" name="discount_product[]" value="' + $(this).attr('value') + '" />');
	});
}

function removeProduct() {
	$('#discount :selected').each(function() {
		$(this).remove();
		$('#discount_product input[value=\'' + $(this).attr('value') + '\']').remove();
	});

}

function getProducts() {
	$('#product option').remove();
	$.ajax({
		url: '<?php echo $sCatUrl; ?>',
		dataType: 'json',
    data: {'category_id':$('#category').attr('value')},
		success: function(data) {
  			for (i = 0; i < data.length; i++) {
  	 			$('#product').append('<option value="' + data[i]['product_id'] + '">' + data[i]['name'] + '</option>');
  			}
		}
	});
}

function getProduct() {
  $('#discount option').remove();
  $.ajax({
    url: '<?php echo $sProdUrl; ?>',
    type: 'POST',
    dataType: 'json',
    data: $('#discount_product input'),
    success: function(data) {
      for (i = 0; i < data.length; i++) {
        $('#discount').append('<option value="' + data[i]['product_id'] + '">' + data[i]['name'] + '</option>');
      } 
    }
  });
}

$(document).on('change','.category',function(e) {
  getProducts();
});

getProduct();
</script>

