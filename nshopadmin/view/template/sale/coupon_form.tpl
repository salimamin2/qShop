<div class="box">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
    <div class="head well">
      <h3>
        <i class="icon-th-list"></i> <?php echo $heading_title; ?>
		  	<div class="pull-right">
          <button type="submit" class="btn-flat btn-success btn-sm"><span><?php echo $button_save; ?></span></button>
          <a href="<?php echo $cancel; ?>" class="btn-flat btn-default btn-sm"><span><?php echo $button_cancel; ?></span></a>
				</div>
      </h3>	
    </div>
    <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><?php echo $error_warning; ?></div>
    <?php } ?>
  <div class="content">

      <ul class="nav nav-tabs">

        <?php foreach ($languages as $language) { ?>

            <li class="active"><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><span><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></span></a></li>

        <?php } ?>

      </ul>

      <div class="tab-content">

      <?php foreach ($languages as $language) { ?>

      <div id="language<?php echo $language['language_id']; ?>" class="tab-pane active">

        <table class="form">

          <tr>

            <td> <?php echo $entry_name; ?><span class="required">*</span></td>

            <td><input class="form-control" name="coupon_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($coupon_description[$language['language_id']]) ? $coupon_description[$language['language_id']]['name'] : ''; ?>" />

              <?php if (isset($error_name[$language['language_id']])) { ?>

              <span class="error"><?php echo $error_name[$language['language_id']]; ?></span>

              <?php } ?></td>

          </tr>

          <tr>

            <td> <?php echo $entry_description; ?><span class="required">*</span></td>

            <td><textarea  class="form-control" name="coupon_description[<?php echo $language['language_id']; ?>][description]" cols="40" rows="5"><?php echo isset($coupon_description[$language['language_id']]) ? $coupon_description[$language['language_id']]['description'] : ''; ?></textarea>

              <?php if (isset($error_description[$language['language_id']])) { ?>

              <span class="error"><?php echo $error_description[$language['language_id']]; ?></span>

              <?php } ?></td>

          </tr>

        </table>

      </div>

      <?php } ?>

      </div>



      <table class="form">

        <tr>

          <td>
		   <?php echo $entry_code; ?><span class="required">*</span></td>

          <td><input class="form-control" type="text" name="code" value="<?php echo $code; ?>" />

            <?php if ($error_code) { ?>

            <span class="error"><?php echo $error_code; ?></span>

            <?php } ?></td>

        </tr>

        <tr>

          <td><?php echo $entry_type; ?></td>

          <td>
		  <div class="ui-select">
		  <select name="type">

              <?php if ($type == 'P') { ?>

              <option value="P" selected="selected"><?php echo $text_percent; ?></option>

              <?php } else { ?>

              <option value="P"><?php echo $text_percent; ?></option>

              <?php } ?>

              <?php if ($type == 'F') { ?>

              <option value="F" selected="selected"><?php echo $text_amount; ?></option>

              <?php } else { ?>

              <option value="F"><?php echo $text_amount; ?></option>

              <?php } ?>

            </select>
			</div></td>

        </tr>

        <tr>

          <td><?php echo $entry_discount; ?></td>

          <td><input class="form-control" type="text" name="discount" value="<?php echo $discount; ?>" /></td>

        </tr>

        <tr>

          <td><?php echo $entry_total; ?></td>

          <td><input class="form-control" type="text" name="total" value="<?php echo $total; ?>" /></td>

        </tr>

        <tr>

          <td><?php echo $entry_logged; ?></td>

          <td><?php if ($logged) { ?>

            <input type="radio" name="logged" value="1" checked="checked" />

            <?php echo $text_yes; ?>

            <input type="radio" name="logged" value="0" />

            <?php echo $text_no; ?>

            <?php } else { ?>

            <input  type="radio" name="logged" value="1" />

            <?php echo $text_yes; ?>

            <input type="radio" name="logged" value="0" checked="checked" />

            <?php echo $text_no; ?>

            <?php } ?></td>

        </tr>

        <tr>

          <td><?php echo $entry_shipping; ?></td>

          <td><?php if ($shipping) { ?>

            <input type="radio" name="shipping" value="1" checked="checked" />

            <?php echo $text_yes; ?>

            <input  type="radio" name="shipping" value="0" />

            <?php echo $text_no; ?>

            <?php } else { ?>

            <input type="radio" name="shipping" value="1" />

            <?php echo $text_yes; ?>

            <input type="radio" name="shipping" value="0" checked="checked" />

            <?php echo $text_no; ?>

            <?php } ?></td>

        </tr>

        <tr>

          <td><?php echo $entry_product; ?></td>

          <td><table>

                  <thead>

                    <col width="1"  />

                    <col width="1"  />

                    <col />



                  </thead>

              <tr>

                <td style="padding: 0;" colspan="3"><div class="ui-select"><select id="category" style="margin-bottom: 5px;" onchange="getProducts();">

                    <?php foreach ($categories as $category) { ?>

                    <option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>

                    <?php } ?>

                  </select></div></td>

              </tr>

              <tr>

                <td style="padding: 0;"><select multiple="multiple" id="product" size="10" style="width: 200px;">

                  </select></td>

                <td style="vertical-align: middle;"><input class="btn-glow" type="button" value="--&gt;" onclick="addProduct();" />

                  <br />

                  <input class="btn-glow"  type="button" value="&lt;--" onclick="removeProduct();" /></td>

                <td style="padding: 0;"><select multiple="multiple" id="coupon" size="10" style="width: 200px;">

                  </select></td>

              </tr>

            </table>

            <div id="coupon_product">

              <?php foreach ($coupon_product as $product_id) { ?>

              <input type="hidden" name="coupon_product[]" value="<?php echo $product_id; ?>" />

              <?php } ?>

            </div></td>

        </tr>

        <tr>

          <td> <?php echo $entry_date_start; ?><span class="required">*</span></td>

          <td>

              <input type="text" class="form-control" name="date_start" value="<?php echo $date_start; ?>" size="12" id="date_start" data-provide="datepicker-inline" />

              <?php if($error_date_start): ?>

              <span class="error"><?php echo $error_date_start; ?></span>

              <?php endif; ?>

          </td>

        </tr>

        <tr>

          <td> <?php echo $entry_date_end; ?><span class="required">*</span></td>

          <td>

              <input type="text" class="form-control" name="date_end" value="<?php echo $date_end; ?>" size="12" id="date_end" data-provide="datepicker-inline" />

              <?php if($error_date_end): ?>

                <span class="error"><?php echo $error_date_end; ?></span>

              <?php endif; ?>

          </td>

        </tr>

        <tr>

          <td><?php echo $entry_uses_total; ?></td>

          <td><input type="text" class="form-control" name="uses_total" value="<?php echo $uses_total; ?>" /></td>

        </tr>

        <tr>

          <td><?php echo $entry_uses_customer; ?></td>

          <td><input type="text" class="form-control" name="uses_customer" value="<?php echo $uses_customer; ?>" /></td>

        </tr>

        <tr>

          <td><?php echo $entry_status; ?></td>

          <td>
		  <div class="ui-select">
		  <select name="status">

              <?php if ($status) { ?>

              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>

              <option value="0"><?php echo $text_disabled; ?></option>

              <?php } else { ?>

              <option value="1"><?php echo $text_enabled; ?></option>

              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>

              <?php } ?>

            </select>
			</div></td>

        </tr>

      </table>

</div>

    </form>

</div>

<script type="text/javascript"><!--

function addProduct() {

	$('#product :selected').each(function() {

		$(this).remove();

		

		$('#coupon option[value=\'' + $(this).attr('value') + '\']').remove();

		

		$('#coupon').append('<option value="' + $(this).attr('value') + '">' + $(this).text() + '</option>');

		

		$('#coupon_product input[value=\'' + $(this).attr('value') + '\']').remove();

		

		$('#coupon_product').append('<input type="hidden" name="coupon_product[]" value="' + $(this).attr('value') + '" />');

	});

}



function removeProduct() {

	$('#coupon :selected').each(function() {

		$('#coupon_product input[value=\'' + $(this).attr('value') + '\']').remove();

        $('#product').append('<option value="' + $(this).attr('value') + '">' + $(this).text() + '</option>');

        $(this).remove();

	});

}



function getProducts() {

	$('#product option').remove();

	

	$.ajax({

		url: 'sale/coupon/category&category_id=' + $('#category').attr('value'),

		dataType: 'json',

		success: function(data) {

			for (i = 0; i < data.length; i++) {

	 			$('#product').append('<option value="' + data[i]['product_id'] + '">' + data[i]['name'] + '</option>');

			}

		}

	});

}



function getProduct() {

	$('#coupon option').remove();

	

	$.ajax({

		url: 'sale/coupon/product',

		type: 'POST',

		dataType: 'json',

		data: $('#coupon_product input'),

		success: function(data) {

			$('#coupon_product input').remove();

			

			for (i = 0; i < data.length; i++) {

	 			$('#coupon').append('<option value="' + data[i]['product_id'] + '">' + data[i]['name'] + '</option>');

				

				$('#coupon_product').append('<input type="hidden" name="coupon_product[]" value="' + data[i]['product_id'] + '" />');

			} 

		}

	});

}



getProducts();

getProduct();

//--></script>