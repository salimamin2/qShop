
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
    <div class="left"></div>
    <div class="right"></div>
    <div class="heading">
        <h1 style="background-image: url('view/image/product.png');"><?php echo __('Product Price'); ?></h1>
        <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
    </div>
    <div class="content">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
            <div id="tab_general">
        <table id="price" class="list">
          <thead>
            <tr>
              <td class="left"><?php echo __('Quantity'); ?></td>
              <td class="left"><?php echo __('Percent'); ?></td>
              <td class="left"><?php echo __('Priority'); ?></td>
              <td></td>
            </tr>
          </thead>
          <?php $price_row = 0; ?>
          <?php foreach ($product_prices as $product_price) { ?>
          <tbody id="price_row<?php echo $price_row; ?>">
            <tr>
              <td class="left"><input type="text" name="product_price[<?php echo $price_row; ?>][quantity]" value="<?php echo $product_price['quantity']; ?>" /></td>
              <td class="left"><input type="text" name="product_price[<?php echo $price_row; ?>][percent]" value="<?php echo $product_price['percent']; ?>" /></td>
              <td class="left"><input type="text" name="product_price[<?php echo $price_row; ?>][priority]" value="<?php echo $product_price['priority']; ?>" /></td>
              <td class="left"><a onclick="$('#price_row<?php echo $price_row; ?>').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>
            </tr>
          </tbody>
          <?php $price_row++; ?>
          <?php } ?>
          <tfoot>
            <tr>
              <td colspan="3"></td>
              <td class="left"><a onclick="addprice();" class="button"><span><?php echo __('Add Price'); ?></span></a></td>
            </tr>
          </tfoot>
        </table>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript"><!--
var price_row = <?php echo $price_row; ?>;

function addprice() {
    html  = '<tbody id="price_row' + price_row + '">';
    html += '<tr>'; 
    html += '<td class="left"><input type="text" name="product_price[' + price_row + '][quantity]" value="" /></td>';
    html += '<td class="left"><input type="text" name="product_price[' + price_row + '][percent]" value="" /></td>';
    html += '<td class="left"><input type="text" name="product_price[' + price_row + '][priority]" value="" /></td>';
    html += '<td class="left"><a onclick="$(\'#price_row' + price_row + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
    html += '</tr>';	
    html += '</tbody>';
	
	$('#price tfoot').before(html);
		
	price_row++;
}
//--></script>
