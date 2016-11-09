<div id="Content" class="content-main">
    <div class="col-main grid-full incol1">
        <?php if(isset($products) && !empty($products)): ?>
        <div class="cart">
            <div class="page-title title-buttons">
                <h1><?php echo $heading_title; ?></h1>
            </div>

	    <?php if ($error_warning) { ?>
            <ul class="messages">
                <li class="notice-msg">
                    <ul>
                        <li><span><?php echo $error_warning; ?></span></li>
                    </ul>
                </li>
            </ul>
	    <?php } ?>
	    <form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" enctype="multipart/form-data" id="cart">
		<fieldset>
		    <div class="cart-table-wrapper">
			<table id="shopping-cart-table" class="data-table cart-table">
			    <col class="col-img" width="1" />
			    <col width="1" />
			    <col class="col-edit" width="1" />
			    <col class="col-unit-price" width="1" />
			    <col width="1" />
			    <col class="col-total" width="1" />
			    <col class="col-delete" width="1" />
			    <thead>
				<tr>
				    <th rowspan="1"><?php echo __('Product Image'); ?></th>
				    <th rowspan="1"><?php echo __('Product Details'); ?></th>
				    <th class="col-unit-price a-center" colspan="1"><span class="nobr"><?php echo $column_price; ?></span></th>
				    <th class="a-center" rowspan="1"><?php echo __('Quantity'); ?></th>
				    <th class="a-center" colspan="1"><?php echo $column_total; ?></th>
				    <th class="col-delete a-center" rowspan="1">&nbsp;</th>
				</tr>
			    </thead>
			    <tbody>
				<?php

				$iCount = count($products);
				foreach ($products as $i => $product) :
				    $class = "";
				    if ($i == 0) {
					$class .= "first ";
				    }
				    if ($i % 2 == 0) {
					$class .= 'odd ';
				    } else {
					$class .='even ';
				    }
				    if (($i + 1) == $iCount) {
					$class .= "last";
				    }

				    ?>
    				<tr class="<?php echo $class; ?>">
    				    <td class="col-img">
    					<a href="<?php echo str_replace('&', '&amp;', $product['href']); ?>" title="<?php echo $product['meta_link']; ?>" alt="<?php echo $product['meta_link']; ?>"><img class="product-image img-left" src="<?php echo $product['thumb']; ?>" title="<?php echo $product['alt_title']; ?>" alt="<?php echo $product['alt_title']; ?>" /></a>
    				    </td>
    				    <td style="position: relative; padding-bottom: 30px;">
    					<h2 class="product-name"><a href="<?php echo str_replace('&', '&amp;', $product['href']); ?>" title="<?php echo $product['meta_link']; ?>" alt="<?php echo $product['meta_link']; ?>"><?php echo $product['name']; ?></a><br />
    					 <span class="mini-font">Item Code: <?php echo $product['model']; ?></span>
						<?php if (!$product['stock']) { ?>
						    <span class="required red">***</span>
						<?php } ?>
    					</h2>
    					<dl class="item-options mini-font">
						<?php if ($product['option']): ?>
		    			<div class="truncated">
		    			    <div class="truncated_full_value">
			    				<dl class="item-options">
								<?php foreach ($product['option'] as $option) : ?>
								    <dt><?php echo $option['name']; ?></dt>
								    <dd><?php echo $option['value']; ?></dd>
								<?php endforeach; ?>
			    				</dl>
		    			    </div>
			    			    <a href="#" onclick="return false;" class="details">Details</a>
			    			</div>
						<?php endif; ?>
    					</dl>
    					<div class="remove-item" style="position: absolute; bottom: 0;">
    						<a href="<?php echo $product['delete']; ?>" class="mini-font">Remove Item<span class="left btn-remove btn-remove2" style="margin-top: 4px;"><?php echo __('button_delete'); ?></span></a>
    					</div>
    					</td>
    				    <!--td class="col-edit a-center">
    					<a class="underline" onclick="$('#cart').submit();" title="<?php echo $button_update; ?>"><?php echo $button_update; ?></a>
    				    </td-->
    				    <td class="col-unit-price a-center">
    					<span class="cart-price">
    					    <span class="price"><?php echo $product['price']; ?></span>
    					</span>
    				    </td>
    				    <td class="a-center">
    					<input type="text" name="quantity[<?php echo $product['key']; ?>]" value="<?php echo $product['quantity']; ?>" class="required number input-text qty" size="1" />
    				    </td>
    				    <td class="col-total a-center">
    					<span class="cart-price">
    					    <span class="price"><?php echo $product['total']; ?></span>
    					</span>
    				    </td>
    				    <td class="col-delete a-center last">
    					<a href="<?php echo $product['delete']; ?>" class="btn-remove btn-remove2"><?php echo __('button_delete'); ?></a>
    				    </td>
    				</tr>
				<?php endforeach; ?>
			    </tbody>
			    <tfoot>
				<tr>
				    <td colspan="50" class="a-right last">
					<button type="button " onclick="location = '<?php echo str_replace('&', '&amp;', $continue); ?>'" class="button btn-continue btn-inline transprent"><span><span><?php echo $button_shopping; ?></span></span></button>
					<button type="submit" class="button btn-update btn-inline transprent"><span><span><?php echo __('Update Shopping Cart'); ?></span></span></button>
				    </td>
				</tr>
			    </tfoot>
			</table>
		    </div>
		    <!--    -->
		</fieldset>
	    </form>
	    <div class="cart-collaterals nested-container">
		<div class="cart-right-column grid12-4">
		    <div class="totals grid-full alpha omega">
			<div class="totals-inner">
			    <table id="shopping-cart-totals-table">
				<tbody>
				    <tr>
					<td class="a-right" colspan="1"><?php echo $text_sub_total; ?></td>
					<td class="a-right"><span class="price"><?php echo $sub_total; ?></span></td>
				    </tr>
				</tbody>
				<tfoot>
                                <td class="a-right" colspan="1"><strong><?php echo __('Grand Total'); ?></strong></td>
                                <td class="a-right"><strong><span class="price"><?php echo $sub_total; ?></span></strong></td>
				</tfoot>
			    </table>
			    <ul class="checkout-types">
				<li><button type="button" onclick="location = '<?php echo str_replace('&', '&amp;', $checkout); ?>'" class="button btn-proceed-checkout btn-checkout black"><span><span><?php echo $button_checkout; ?></span></span></button></li>
			    </ul>
			</div>
		    </div>
		</div>
	    </div>
        </div>
        <?php else: ?>
            <div class="page-title">
                <h1><?php echo $heading_title; ?></h1>
            </div>
            <div class="cart-empty">
                <p><?php echo $text_message; ?></p>
                <button type="button" onclick="location='<?php echo $continue; ?>'" class="button"><span><span><?php echo $button_continue; ?></span></span></button>
            </div>
        <?php endif; ?>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function() {
	//console.log(jQuery('form#cart').length)//.validate();
    });
    decorateTable('shopping-cart-table');
</script>