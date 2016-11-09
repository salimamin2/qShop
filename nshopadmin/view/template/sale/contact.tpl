<div class="box">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
<div class="box table-wrapper products-table section">
  <div class="head well">
    <h3><i class="icon-envelope"></i> <?php echo $heading_title; ?>
			<div class="pull-right">
            <button type="submit" class="btn-flat btn-success btn-sm"><span><?php echo $button_send; ?></span></button>
            <a href="<?php echo $cancel; ?>" class="btn-flat btn-default btn-sm"><span><?php echo $button_cancel; ?></span></a>
			</div>
    </h3>	
  </div>
    <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><?php echo $error_warning; ?></div>
    <?php } ?>
    <?php if ($success) { ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php } ?>
  <div class="content">



      <table class="form">

        <tr>

          <td><?php echo $entry_to; ?></td>

          <td><div class="ui-select"><select name="group">

              <option value=""></option>

              <?php if ($group == 'newsletter') { ?>

              <option value="newsletter" selected="selected"><?php echo $text_newsletter; ?></option>

              <?php } else { ?>

              <option value="newsletter"><?php echo $text_newsletter; ?></option>

              <?php } ?>

              <?php if ($group == 'customer') { ?>

              <option value="customer" selected="selected"><?php echo $text_customer; ?></option>

              <?php } else { ?>

              <option value="customer"><?php echo $text_customer; ?></option>

              <?php } ?>

            </select></div></td>

        </tr>

        <tr>

          <td></td>

          <td><table width="100%">

              <tr>

                <td style="padding: 0;" colspan="3"><input class="form-control"type="text" id="search" value="" style="margin-bottom: 5px;" />

                  <a onclick="getCustomers();" style="margin-bottom: 5px;" class="button"><span><?php echo $text_search; ?></span></a></td>

              </tr>

              <tr>

                <td width="49%" style="padding: 0;"><select multiple="multiple" id="customer_from" size="10" style="width: 100%; margin-bottom: 3px;">

                  </select></td>

                <td width="2%" style="text-align: center; vertical-align: middle;">

                    <button type="button" class="btn-glow" value="--&gt;" onclick="addCustomer();"><i class="icon-arrow-right"></i></button>

                  <br />

                  <button type="button"  class="btn-glow" onclick="removeCustomer();" class="btn btn-default btn-sm" ><i class="icon-arrow-left"></i></button>

                </td>

                <td width="49%" style="padding: 0;"><select multiple="multiple" id="to" size="10" style="width: 100%; margin-bottom: 3px;">

                    <?php foreach ($customers as $customer) { ?>

                    <option value="<?php echo $customer['customer_id']; ?>"><?php echo $customer['name']; ?></option>

                    <?php } ?>

                  </select></td>

              </tr>

            </table></td>

        </tr>

		<tr>

		  <td><?php echo $entry_product; ?></td>

		  <td>

              <table width="100%">

			  <tr>

			    <td style="padding: 0;" colspan="3"><div class="ui-select"><select id="category" style="margin-bottom: 5px;" onchange="getProducts();">

			      <?php foreach ($categories as $category) { ?>

			      <option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>

			      <?php } ?>

			      </select></div></td>

			  </tr>

			  <tr>

			    <td width="49%">

                    <select multiple="multiple" id="product" size="10" style="width: 100%;"></select>

                </td>

			    <td style="vertical-align: middle;" width="2%">

                  <button type="button" class="btn-glow" onclick="addItem();" ><i class="icon-arrow-right"></i></button>

			      <br />

			      <button type="button" class="btn-glow" onclick="removeItem();"><i class="icon-arrow-left"></i></button>

                </td>

			    <td width="49%">

                    <select multiple="multiple" id="item" size="10" style="width: 100%"></select>

                </td>

			  </tr>

		    </table>

		    <div id="product_item">

		    </div></td>

		</tr>

        <tr>

          <td><span class="required">*</span> <?php echo $entry_subject; ?></td>

          <td><input class="form-control" name="subject" value="<?php echo $subject; ?>" />

            <?php if ($error_subject) { ?>

            <span class="error"><?php echo $error_subject; ?></span>

            <?php } ?></td>

        </tr>

        <tr>

          <td><span class="required">*</span> <?php echo $entry_message; ?></td>

          <td><textarea name="message" id="message" data-rel="wyswyg"><?php echo $message; ?></textarea>

            <?php if ($error_message) { ?>

            <span class="error"><?php echo $error_message; ?></span>

            <?php } ?></td>

        </tr>

      </table>

      <div id="customer_to">

        <?php foreach ($customers as $customer) { ?>

        <input type="hidden" name="to[]" value="<?php echo $customer['customer_id']; ?>" />

        <?php } ?>

      </div>

  </div>

    </form>

  </div>

</div>


<script type="text/javascript"><!--

function addCustomer() {

	$('#customer_from :selected').each(function() {

		$(this).remove();

		

		$('#to option[value=\'' + $(this).attr('value') + '\']').remove();

		

		$('#to').append('<option value="' + $(this).attr('value') + '">' + $(this).text() + '</option>');

		

		$('#customer_to').append('<input type="hidden" name="to[]" value="' + $(this).attr('value') + '" />');

	});

}



function removeCustomer() {

	$('#to :selected').each(function() {

		$(this).remove();

		

		$('#customer_to input[value=\'' + $(this).attr('value') + '\']').remove();

        $('#customer_from').append('<option value="' + $(this).attr('value') + '">' + $(this).text() + '</option>');

	});

}



function getCustomers() {

	$('#customer option').remove();

	

	$.ajax({

		url: 'sale/contact/customers&token=<?php echo $token; ?>&keyword=' + encodeURIComponent($('#search').attr('value')),

		dataType: 'json',

		success: function(data) {

            $.each(data,function(i,val) {

                $('#customer_from').append('<option value="' + val.customer_id + '">' + val.name + '</option>');

            });

		}

	});

}

//--></script>

<script type="text/javascript"><!--

function addItem() {

	$('#product :selected').each(function() {

		$(this).remove();

		

		$('#item option[value=\'' + $(this).attr('value') + '\']').remove();

		

		$('#item').append('<option value="' + $(this).attr('value') + '">' + $(this).text() + '</option>');

		

		$('#product_item input[value=\'' + $(this).attr('value') + '\']').remove();

		

		$('#product_item').append('<input type="hidden" name="product[]" value="' + $(this).attr('value') + '" />');

	});

}



function removeItem() {

	$('#item :selected').each(function() {

		$(this).remove();

		

		$('#product_item input[value=\'' + $(this).attr('value') + '\']').remove();

	});

}



function getProducts() {

	$('#product option').remove();

	

	$.ajax({

		url: 'sale/contact/category&token=<?php echo $token; ?>&category_id=' + $('#category').attr('value'),

		dataType: 'json',

		success: function(data) {

			for (i = 0; i < data.length; i++) {

	 			$('#product').append('<option value="' + data[i]['product_id'] + '">' + data[i]['name'] + ' (' + data[i]['model'] + ') </option>');

			}

		}

	});

}



getProducts();

//--></script>

