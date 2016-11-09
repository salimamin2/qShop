<?php echo $this->load('module/account'); ?>
<div id="Content" class="col-main grid12-9 grid-col2-main in-col2">
    <div class="my-account">
        <div class="my-wishlist">
            <div class="page-title title-buttons">
                <h1><?php echo $heading_title; ?></h1>
            </div>
	    <ul class="messages">
		<?php if ($success) { ?>
    		<li class="success-msg">
    		    <ul>
    			<li><span><?php echo $success; ?></span></li>
    		    </ul>
    		</li>
		<?php } ?>
		<?php if ($error_warning) { ?>
    		<li class="error-msg">
    		    <ul>
    			<li><span><?php echo $error_warning; ?></span></li>
    		    </ul>
    		</li>
		<?php } ?>
	    </ul>
            <form name="wishlist" action="<?php echo str_replace('&', '&amp;', $action) ?>" method="post" id="wishlist">
                <fieldset>
		    <?php if (!empty($products)): ?>
    		    <table class="data-table" id="wishlist-table">
    			<thead>
    			    <tr class="first last">
    				<th></th>
    				<th><?php echo __('Product Details and Comment'); ?></th>
    				<th><?php echo __('Add to Cart'); ?></th>
    			    </tr>
    			</thead>
    			<tbody>
				<?php $length = count($products); ?>
				<?php

				foreach ($products as $i => $product):
				    $j = $i + 1;
				    $class = ($j % 2 == 0 ? 'even' : 'odd');
				    $class .= ($j == 1 ? ' first' : '');
				    $class .= ($j == $length ? ' last' : '');

				    ?>
				    <tr class="<?php echo $class; ?>">
					<td>
					    <a href="<?php echo str_replace('&', '&amp;', $product['href']); ?>" class="product-image"><img src="<?php echo $product['thumb']; ?>" title="<?php echo $product['product_name']; ?>" alt="<?php echo $product['product_name']; ?>" /></a>
					</td>
					<td>
					    <h3 class="product-name"><a href="<?php echo str_replace('&', '&amp;', $product['href']); ?>" class="product-name"><?php echo $product['product_name']; ?></a></h3>
					    <div class="description std">
						    <div class="inner"><?php echo $product['description']; ?></div>
                        </div>
					    <textarea row="3" col="5" name="comment[<?php echo $product['wishlist_id'] ?>]"><?php echo $product['comments'] ?></textarea>
					</td>
					<td>
					    <div class="cart-cell">
						<div class="price-box">
						    <?php if (!$product['special']) { ?>
	    					    <span class="regular-price"><span class="price"><?php echo $product['price']; ?></span></span>
						    <?php } else { ?>
	    					    <p class="old-price">
	    						<span class="price-label"><?php echo __('Regular Price'); ?></span>
	    						<span class="price"><?php echo $product['price']; ?></span>
	    					    </p>
	    					    <p class="special-price">
	    						<span class="price-label"><?php echo __('Now Only:'); ?></span>
	    						<span class="price"><?php echo $product['special']; ?></span>
	    					    </p>
						    <?php } ?>
						</div>
						<div class="add-to-cart-alt">
						    <button type="button" onclick="location.href = '<?php echo str_replace('&', '&amp;', $product['cart']); ?>'" 
						    class="button"><span><span><?php echo __('button_add_to_cart'); ?></span></span></button>
						</div>
					    </div>
					</td>
					<td class="last">
					    <a alt="<?php echo $button_delete; ?>" onclick="if (confirm('Are you sure you want to remove this product from your wishlist?')) {
								location = '<?php echo str_replace('&', '&amp;', $product['delete']); ?>'
							    }" class="btn-remove btn-remove2"><?php echo __('button_delete'); ?></a>
					</td>
				    </tr>
				<?php endforeach; ?>
    			</tbody>
    		    </table>
    		    <div class="buttons-set buttons-set2">
    			<button type="submit" class="button btn-update"><span><span><?php echo $button_update; ?></span></span></button>
    		    </div>
		    <?php else: ?>
    		    <p class="wishlist-empty"><?php echo __('You have no items in your wishlist'); ?></p>
		    <?php endif; ?>
                </fieldset>
            </form>
        </div>
        <div class="buttons-set">
            <p class="back-link">
                <a onclick="location = '<?php echo str_replace('&', '&amp;', $back); ?>'"><small>&laquo; </small><?php echo $button_back; ?></a>
            </p>
            <button onclick="location.href = '<?php echo str_replace('&', '&amp;', $continue); ?>'" class="button"><span><span><?php echo __('Continue Shopping'); ?></span></span></button>
        </div>
    </div>
</div>
<script type="text/javascript">
//    $(document).ready(function() {
//	$('#icon a').hover(function() {
//	    $(this).append('<div id="box"><div class="head">' + $(this).attr('alt') + '</div></div>');
//	});
//	$('#icon a').mouseleave(function() {
//	    $('#icon a').find('#box').remove();
//	});
//    });
</script>