<?php if ($products): ?>
	<div class="bestsellers" style="padding:0px 17px;">
	<div class="clearfix"></div>
    	<h3 class="section-title-best">
    		<span class="background-best">
    			<Span class="border-best"> Just In </Span>
			</span>
		</h3>

    	<div class="itemslider-wrapper slider-arrows1 slider-arrows1-pos-top-right slider-pagination1">

    		<div class="itemslider itemslider-responsive products-grid size-s centered">
				<div class="products-grid-best" id="best_seller">
					<?php foreach ($products as $i => $product) : ?>
	    				<div class="item-best">
		    				<div class="grow">
								<div class="product-image-wrapper-best">
			    					 <a href="<?php echo $product['href'] ?>" title="<?php echo $product['meta_link'] ?>" alt="<?php echo $product['meta_link'] ?>" class="product-image">
										<img class="lazyOwl" src="<?php echo $product['image'] ?>" title="<?php echo $product['alt_title'] ?>" alt="<?php echo $product['alt_title'] ?>" />
										<?php //if ($product['extra_img']): ?>
	    									<!-- <img src="<?php echo $product['extra_img']; ?>" title="<?php echo $product['alt_title'] ?>" alt="<?php echo $product['alt_title'] ?>"class="alt-img" /> -->
										<?php //endif; ?>
			    					</a>
			    					<!-- <ul class="add-to-links clearer addto-links-icons addto-onimage visible-onhover">
										<li>
											<a class="link-wishlist" href="<?php echo $product['wishlist'] ?>" title="Add to Wishlist">
												<span class="icon icon-hover i-wishlist-bw"></span>
			    							</a>
		    							</li>
			    					</ul> -->
								</div> <!-- end: product-image-wrapper -->

								<div class="product-content">
									<h3 class="product-name">
										<a href="<?php echo $product['href'] ?>" title="<?php echo $product['meta_link'] ?>" alt="<?php echo $product['meta_link'] ?>"><?php echo $product['name'] ?>
										</a>
									</h3>										

									<?php if($product['rating']): ?>
            							<div class="ratings">
			    							<div class="rating-box">
												<div class="rating" style="width:<?php echo (20 * (int) $product['rating']) ?>%">
												</div>
			    							</div>
	    									<span class="amount"><?php echo (int) $product['rating'] ?> Rating(s)</span>
										</div>
            						<?php endif; ?>

									<div class="price-box">
			    						<?php if (!$product['special']) { ?>
	    		    						<span class="regular-price" id="product-price-35">    
	    										<span class="price"><?php echo $product['price'] ?></span> 
	    		    						</span>
				    					<?php } else { ?>
		    		    					<p class="old-price">
		    									<span class="price-label">Regular Price:</span>
		    									<span class="price" id="old-price-<?php echo $product['product_id']; ?>-new"><?php echo $product['price']; ?></span>
	    		    						</p>

		    		    					<p class="special-price">
		    									<!--<span class="price-label">Now only:</span>-->
		    									<span class="price" id="product-price-<?php echo $product['product_id']; ?>-new"><?php echo $product['special']; ?></span>
		    		    					</p>
			    						<?php } ?>
									</div>

									<div class="actions-btn">
			    						<?php $act = ($product['options']) ? $product['href'] : $action; ?>
		    							<form action="<?php echo str_replace('&', '&amp;', $act); ?>" name="best_product_<?php echo $i ?>" method="post" enctype="multipart/form-data" id="latest_product_<?php echo $i ?>">
											<input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>" />
											<input type="hidden" name="redirect" value="<?php echo str_replace('&', '&amp;', $product['href']); ?>" />
											<input type="hidden" name="quantity" value="" size="2" placeholder="Qty" class="mini-inputbox"/>

				   	 						<button type="submit" title="Add to Cart" class="button btn-cart">
				   	 							<span>
				   	 								<span>
				   	 									<img src="catalog/view/theme/default/image/img/bag-icon.png">
				   	 								</span>
			   	 								</span>
		   	 								</button>

		   	 								<button type="submit" title="" class="button btn-heart">
		    									<span>
		    										<span>
		    											<img src="catalog/view/theme/default/image/img/heart-icon.png">
	    											</span>
    											</span>
											</button>
								

			    							<!-- <a href="<?php echo $product['href'] ?>" title="Read More" class="button">
		    										<span>Read More</span>
		    									</a> -->

			    						</form>
		    						</div>
		    						<div class="clearfix"></div>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
    		</div> <!-- end: itemslider -->
        </div> <!-- end: itemslider-wrapper -->

        <script type="text/javascript">

    	//<![CDATA[

    	// jQuery(document).ready(function($) {

    	//     var owl = $('#itemslider-featured-0bfac31705b4235d714ba7026533f047');
    	//     owl.owlCarousel({

	    // 		lazyLoad: true,
	    // 		itemsCustom: [[0, 1], [320, 2], [480, 3], [768, 2], [960, 3], [1280, 3]],
	    // 		responsiveRefreshRate: 50,
	    // 		slideSpeed: 200,
	    // 		paginationSpeed: 500,
	    // 		scrollPerPage: true,
	    // 		autoPlay: 4000,
	    // 		stopOnHover: true,
	    // 		rewindNav: true,
	    // 		rewindSpeed: 600,
	    // 		pagination: false,
	    // 		navigation: true

    	//     }); //end: owl
    	// });

    	
    	//]]>
        </script>
    </div>
<?php endif; ?>