<div id="summary" class="sidebar grid12-3 grid-col2-sidebar in-sidebar">
    <div id="checkout-progress-wrapper">
		<div class="left-cart">
	    	<div class="left-cart-title">
				<span><?php echo __('text_order_summary'); ?></span>
    		</div>

    		<div class="block-content">
				<input type="hidden" name="symbol_left" id="currency_symbol_left" value="<?php echo $currency_sign_left ?>" />
				<input type="hidden" name="symbol_right" id="currency_symbol_right" value="<?php echo $currency_sign_right ?>" />
				<div class="row">
					<?php $i = 0; foreach ($products as $product): ?>
						<div class="<?php if ($i == 0): ?>first<?php endif; ?> left-cart-main-pro">
		    				<div class="col-sm-3 product-img">
								<a href="<?php echo $product['href']; ?>" title="<?php echo $product['meta_link'] ?>" alt="<?php echo $product['meta_link'] ?>">
									<img src="<?php echo $product['thumb'] ?>" title="<?php echo $product['alt_title'] ?>" alt="<?php echo $product['alt_title'] ?>" />
								</a>
            				</div>

		    				<div class="col-sm-9 product-details">
            					<a href="<?php echo $product['href']; ?>" title="<?php echo $product['meta_link'] ?>" alt="<?php echo $product['meta_link'] ?>" class="product-name">
            						<?php echo $product['name']; ?>

            						<span class="cross"><?php echo __('&#x2715;')?></span>

            						<span><?php echo $product['quantity']; ?></span>
        						</a>
			                    <?php  /*
							    if ($product['option']): foreach ($product['option'] as $option): ?>
			                        <div class="mini-font"><?php echo $option['name']; ?>:<span class="bold"><?php echo $option['value']; ?></span><br class="clr" /></div>
			                        <?php $i++; ?>
								<?php endforeach; */ ?>

								<?php if ($product['option']): ?>
					    			<div class="truncated">
					    			    <div class="truncated_full_value">
						    				<dl class="item-options">
											<?php foreach ($product['option'] as $option) : ?>
											    <dd><?php echo $option['value']; ?>/</dd>
											<?php endforeach; ?>
						    				</dl>
					    			    </div>
					    			   <!--  <a href="#" onclick="return false;" class="details">Details</a> -->
					    			   <div class="clearfix"></div>
					    			</div>
								<?php endif; ?>
								<div class="clearfix"></div>
		    				</div>
		    				<div class="clearfix"></div>

		    				<?php /*
		    				<div>
		    					<?php echo $product['price'] ?>
								<input type="hidden" name="product_total" class="product_total" value="<?php echo $product['total'] ?>" />
		    				</div>
		    				*/ ?>
						</div>
					<?php endforeach; ?>
                </div>
                <?php if(!$coupon): ?>
    				<div class="discount-code coupon-message" style="cursor:pointer;">
    					<?php echo __('text_discount'); ?>
    					<div class="clearfix"></div>
    				</div>
    				<div class="coupon-form" style="display: none">
        				<div class="col-md-12" >
            				<div class="coupon_option">
                                <div class="field left">
                                    <div class="input-box">
                                        <input type="text" name="coupon" class="input-text" placeholder="<?php echo __('Enter Coupon:'); ?>" />
                                        <!-- <div class='alert alert-success' id='coupon_message' style="display: none;"></div> -->
                                    </div>
                                </div>
                                <div class="field left">
                                    <div class="input-box">
                                        <button type="button" class="btn btn-coupon">
                                        	<span>
                                        		<span>
                                        			<?php echo __("Apply Coupon"); ?>
                                    			</span>
                                			</span>
                            			</button>
                                    </div>
                                </div>
            				</div>
        				</div>
    				</div>
                <?php endif; ?>
    			<div class="totals">
					<?php foreach ($totals as $total) { ?>
						<div class="col-md-12 no-padding">
		    				<div class="left <?php echo $total['key'] ?> sum_price"><?php echo $total['title']; ?></div>
		    				<div class="right"><?php echo $total['text']; ?></div>
		    				<div class="clearfix"></div>
						</div>

					<?php } ?>
				</div>
    		</div>
    		<div class="clearfix"></div>
		</div>
	</div>
</div>