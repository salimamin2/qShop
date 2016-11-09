<div id="Content" class="col-main grid12-9 grid-col2-main in-col2">
	<?php if ($products): ?>
    	<ul class="products-grid" id="scroll-container">
    		<?php foreach ($products as $i => $product): ?>

				<li id="" class="item <?php
					echo ($i == 0) ? 'first ' : '';
					echo ($i % 4 == 0) ? 'row-first ' : '';
					echo ($i % 4 == 3) ? 'row-last ' : '';
					echo (count($products) - 1 == $i) ? 'last' : '';
				?>">

					<div class="badge">
                        <?php if ($product['special']) : ?>
                            <div class="offer">
                                <div class="offer-in">
                                    <div class="title">Sale</div>
                                    <div class="text"><?php echo ($product['percent'] > 0 ? $product['percent'] . '%' : ''); ?></div>    
                                </div>
                            </div>
                        <?php endif; ?>
                        <!-- <div class="last-offer">&nbsp;</div> -->
                    </div>
					<div class="grow">
						<?php if ($product['thumb']): ?>
    		    			<div class="product-image-wrapper">
    							<a href="<?php echo str_replace('&', '&amp;', $product['href']); ?>" title="<?php echo $product['meta_link']; ?>" class="product-image">
									<?php /*if ($product['special']) : ?>
										<div class="icon-sale"></div>
									<?php endif;*/ ?>

    			    					<img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['alt_title']; ?>"  title="<?php echo $product['alt_title']; ?>" />
					
									<?php /*if ($product['special']): ?>
				    					<span class="sticker-wrapper top-left">
				    						<span class="sticker sale">Sale</span>
			    						</span>
									<?php endif;*/ ?>
								</a>
							</div>
	    				<?php endif; ?>
	    				<div class="product-content wishlist">
		    				<span class="product-name">
			    				<a href="<?php echo str_replace('&', '&amp;', $product['href']); ?>" title="<?php echo $product['meta_link']; ?>" ><?php echo $product['name']; ?></a>
	    					</span>
		    				<div class="like-btn">
								<img src="catalog/view/theme/default/image/img/heart-icon-red.png">
							</div>		    			
		    				<div class="clearfix"></div>
	    				</div>
	    				<div class="clearfix"></div>
    				</div>
				</li>
    		<?php endforeach; ?>
		</ul>

    	<?php if($pagination): ?>
    		<div class="toolbar-bottom">
        		<div class="toolbar">
            		<div class="pager">
                		<div id="pages" class="pagination pages gen-direction-arrows1">
                    		<?php echo $pagination; ?>
                		</div>
            		</div>
        		</div>
    		</div>
    	<?php endif; ?>
    <?php else: ?>
		<div class="if-orders">
			<img src="catalog/view/theme/default/image/img/emotion-cart.png">
            <p><?php echo __('There are no products you’ve liked.'); ?></p>
    	</div>
	<?php endif; ?>
</div>