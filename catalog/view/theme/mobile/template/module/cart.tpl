<div class="cart" style="">
	<?php if ($products) : ?>
    	<div class="">
    	   <!--  <h4 class="block-subtitle"><?php // echo __('text_recent_item') ?></h4> -->
    	   	<div class="overlay-cart">
                <?php if($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                <?php if($error): ?>
                    <div class="alert alert-warning"><?php echo $error; ?></div>
                <?php endif; ?>
	    	    <ol id="cart-sidebar" class="mini-products-list clearer">
			    	<?php foreach ($products as $aProduct): ?>
						<li class="item last odd">
							<div class="col-md-12 no-padding">

								<div class="image-cart">
						    		<a href="<?php echo $aProduct['href']; ?>" title="<?php echo $aProduct['meta_link'] ?>" alt="<?php echo $aProduct['meta_link'] ?>" class="product-image">
						    			<img src="<?php echo $aProduct['thumb'] ?>" width="100%" height="" alt="<?php echo $aProduct['	']; ?>" title="<?php echo $aProduct['alt_title']; ?>">
					    			</a>
					    		</div>

				    			<div class="product-details">
									<!-- <a href="<?php echo $aProduct['remove'] ?>" title="Remove This Item" class="btn-remove cart_remove">Remove This Item</a> -->
									<!--<a href="javascript:void(0)" title="Edit item" class="btn-edit">Edit item</a>-->
									<p class="product-name-cart">
										<a href="<?php echo $aProduct['href']; ?>" title="<?php echo $aProduct['meta_link']; ?>" alt="<?php echo $aProduct['meta_link']; ?>">
											<?php echo $aProduct['name'] ?>
                                            <?php if (!$aProduct['stock']): ?>
                                                <span class="required color-red" id="required_<?php echo $aProduct['id']; ?>">***</span>
                                            <?php endif; ?>
										</a>
									</p>

									<?php if ($aProduct['option']): ?>
				    					<div class="truncated">
				    			    		<div class="truncated_full_value">
				    							<dl class="item-options">
													<?php foreach ($aProduct['option'] as $option) : ?>
									    				<!-- <dt>Select <?php echo $option['name']; ?></dt> -->
									    				<dd><?php echo $option['value']; ?>/</dd>
													<?php endforeach; ?>
			    								</dl>
				    			    		</div>
				    			    		<!-- <a href="#" onclick="return false;" class="details">Details</a> -->
				    			    		<div class="clearfix"></div>
				    					</div>
									<?php endif; ?>

									<div class="qty"><?php echo $aProduct['quantity'] ?></div>
									<span class="price"><?php echo $aProduct['price'] ?></span>
									<div class="clearfix"></div>
						    	</div>
				    		</div>
				    		<div class="clearfix"></div>
				    		<div class="hover-cart">
				    			<div class="remove-cart">
	    							<a href="#" data-key="<?php echo $aProduct['key']; ?>" title="Remove This Item" class="btn-remove cart_remove">
	    								<img src="catalog/view/theme/default/image/remove-icon.png">
    								</a>
	    						</div>
	    						<form id='myform' method='POST' action='#'>
								    <button type='button' data-id="<?php echo $aProduct['id']; ?>" class='qtyminus hover-qty'>-</button>

								    <input id="quantity_<?php echo $aProduct['id']; ?>" type='text' name='quantity[<?php echo $aProduct['key']; ?>]' value='<?php echo $aProduct['quantity']; ?>' class='qty_hover hover-qty-result quantity' data-id="<?php echo $aProduct['id']; ?>" data-old="<?php echo $aProduct['quantity']; ?>" />
                                    <input type="hidden" id="stock_quantity_<?php echo $aProduct['id']; ?>" value="<?php echo $aProduct['stock_quantity']; ?>" />

								    <button type='button' class='qtyplus hover-qty' data-id="<?php echo $aProduct['id']; ?>">+</button>
								</form>
	    					</div>
						</li>
						
		    		<?php endforeach; ?>
	    		</ol>
	    		<div class="clearfix"></div>
	    		
	    		<div class="main-sum">
			<div class="left sum_price"><p>Total</p></div>
		    <div class="right sum_price cart-total"><?php echo $subtotal; ?></div>
<!-- 		    <td class="a-right"><?php echo $total_qty; ?></td> -->
					</div>
    		</div>
    	    

    	   <!--  <div class="actions clearer">
    		<button type="button" title="<?php echo $text_view; ?>" class="button btn-checkout btn-inline" onclick="location.href = '<?php echo $view; ?>'"><span><span><?php echo $text_view; ?></span></span></button>

    		<button type="button" title="<?php echo $text_checkout; ?>" class="button btn-checkout btn-inline " onclick="location.href = '<?php echo $checkout; ?>'"><span><span><?php echo $text_checkout; ?></span></span></button>
    	    </div> -->
    	</div> <!-- end: block-content-inner -->
    	<div class="continue-shop">
			<button type="button" title="<?php echo $text_checkout; ?>" class="button btn-checkout btn-inline " onclick="location.href = '<?php echo $checkout; ?>'"><span><span><?php echo $text_checkout; ?></span></span></button>
		</div>
	<?php else : ?>
        <div class="block-content-inner">
            <div class="cart-img-empty">
                <img src="catalog/view/theme/default/image/img/emotion-cart.png">
            </div>
            <div class="empty"><?php echo $text_empty; ?></div>
        </div>
        <div class="continue-shop">
            <button type="button" class="button btn-inline btn-continue">Countinue Shopping</button> 
        </div>
	<?php endif; ?>

    <div class="empty-html hide">
        <div class="block-content-inner">
            <div class="cart-img-empty">
                <img src="catalog/view/theme/default/image/img/emotion-cart.png">
            </div>
            <div class="empty"><?php echo $text_empty; ?></div>
        </div>
        <div class="continue-shop">
            <button type="button" class="button btn-inline btn-continue">Countinue Shopping</button> 
        </div>
    </div>


		
	

    </div> <!-- end: dropdown-menu -->

</div>

<script type="text/javascript">
    var error_message = '<?php echo $text_error_stock; ?>';
    var cart_link = '<?php echo $cart_link; ?>';
    var confirm_remove = '<?php echo $text_confirm_remove; ?>';
    var error_stock = '<?php echo $error_stock; ?>';
    jQuery('.qtyplus').click(function(e){
        var id = jQuery(this).attr('data-id');
        var input = jQuery('input#quantity_'+id);
        var currentVal = parseInt(input.val());
        var stock = jQuery('#stock_quantity_' + id);
        if (!isNaN(currentVal) && checkStockQuantity(input,false)) {
             input.val(currentVal + 1);
             input.attr('data-old',currentVal);
             updateCart(input);
        }
    });

    jQuery(".qtyminus").click(function(e) {
        var id = jQuery(this).attr('data-id');
        var input = jQuery('input#quantity_'+id);
        var currentVal = parseInt(input.val());
        if (!isNaN(currentVal) && currentVal > 1) {
            input.val(currentVal - 1);
            input.attr('data-old',currentVal);
            updateCart(input);
        }
    });

    jQuery(document).on('blur','.quantity',function(e) {
        var obj = jQuery(this);
        var bValid = checkStockQuantity(obj,true);
        if(!bValid) {
            alert(error_message);
            obj.val(obj.attr('data-old'));
            return;
        }
        if(obj.val() != obj.attr('data-old')) {
            obj.attr('data-old',obj.val());
            updateCart(obj);
        }
    });

    function checkStockQuantity(obj,bChange) {
        var id = obj.attr('data-id');
        var bValid = true;
        var val = parseInt(obj.val()) + (bChange ? 0 : 1);
        if(val > jQuery('#stock_quantity_' + id).val())
            bValid = false;
        return bValid;
    }

    function updateCart(obj) {
        var form = obj.parent('form');
        var data = form.serializeArray();
        data.push({name: 'isAjax',value: 1});
        jQuery.ajax({
            url: cart_link,
            type: 'POST',
            data: data,
            dataType: 'json',
            beforeSend: function() {
                jQuery('.hover-qty',form).attr('disabled',true);
            },
            success: function(res) {
                cartUpdated(res);
                if(obj.val() < jQuery('#stock_quantity_' + obj.attr('data-id'))) {
                    jQuery('span#required_' + obj.attr('data-id')).remove();
                }
                jQuery('div.qty',obj.parents('li.item')).html(obj.val());

            },
            complete: function() {
                jQuery('.hover-qty',form).attr('disabled',false);
            }
        }); 
    }

    jQuery(document).on('click','.btn-remove',function(e) {
        e.preventDefault();
        var obj = jQuery(this);
        if(window.confirm(confirm_remove)) {
            var key = obj.attr('data-key');
            var data = {remove:{},isAjax:1};
            data.remove[key] = 1;
            jQuery.ajax({
                url: cart_link,
                type: 'POST',
                data: data,
                dataType: 'json',
                beforeSend: function() {
                    obj.hide();
                },
                success: function(res) {
                    cartUpdated(res);
                    if(res.hasOwnProperty('total')) {
                        obj.parents('li.item').remove();
                        if(jQuery('ol#cart-sidebar li').length == 0) {
                            jQuery('.cart').html(jQuery('.empty-html').html());
                        }
                    }
                },
                complete: function() {
                    obj.show();
                }
            });
        }
        return false;
    });

    function cartUpdated(res) {
        if(res.hasOwnProperty('error')) {
            var alert = '<div class="alert alert-danger">' + res.error + '<div>';
        }
        else {
            var alert = '<div class="alert alert-success">' + res.success + '</div>'; 
            jQuery('.cart-total').html(res.total);
            jQuery('a.cart-link span.badge-cart').html(res.count);
        }
        jQuery('#cart-sidebar').before(alert);
        setTimeout(function() {
            jQuery('.alert').remove();
        },2000);
    }

    jQuery(document).on('click','.btn-checkout',function(e) {
        var bStock = true;
        jQuery('ol#cart-sidebar li').each(function() {
            if(jQuery('span.required',jQuery(this)).length) {
                bStock = false;
                return false;
            }
        });
        if(bStock) {
            location.href = '<?php echo $checkout; ?>';
        }
        else {
            jQuery('.alert').remove();
            jQuery('#cart-sidebar').before('<div class="alert alert-warning">' + error_stock + '</div>');
        }
    });


    jQuery(document).on('click','.btn-continue',function(e) {
        e.preventDefault();
        jQuery('.cart-link').trigger('click');
        return false;
    });
</script>