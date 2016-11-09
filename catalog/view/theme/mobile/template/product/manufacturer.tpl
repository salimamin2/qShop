<div id="Content" class="container">
	 <div class="col-main grid12-9 grid-col2-main in-col2 col-md-12">
        <?php /*<div class="col-md-6">
             [manufacturer_info manufacturer_id="<?php echo $manufacturer_id; ?>" limit="4"]
         </div>*/ ?>

         <div class="designer-profile text-center">
	    	<?php if ($manufacturer['image'] != ''): ?>
				<div class="img">
					<img src="<?php echo HTTP_IMAGE . $manufacturer['image']; ?>" alt="<?php echo $manufacturer['name']; ?>" title="<?php echo ($manufacturer['meta_title'] != '' ? $manufacturer['meta_tile'] : $manufacturer['name']); ?>" />
				</div>
			<?php endif; ?>
			<h1><?php echo $manufacturer['name']; ?></h1>
			<?php /*<div class="email"><?php echo $manufacturer['email']; ?></div>*/ ?>
			<p><?php echo $manufacturer['description']; ?></p>
			<div class="social-icon">
				<?php if($manufacturer['facebook']): ?>
					<span><a href="<?php echo ($manufacturer['facebook'] ? $manufacturer['facebook'] : 'http://facebook.com/'); ?>" target="_blank" class="facebook-square"></a></span>
				<?php endif; ?>
				<?php if($manufacturer['twitter']): ?>
					<span><a href="<?php echo ($manufacturer['twitter'] ? $manufacturer['twitter'] : 'http://www.twitter.com/'); ?>" target="_blank" class="twitter-square"></a></span>
				<?php endif; ?>
			</div>
	    </div>

         <div class="category-products">
			<?php if ($products): ?>

				<div class="text-center">
			        <p class="section-title-account">
			            <span class="background-account">
			                <span class="border-account">Latest</span>
			            </span>
			        </p>
			    </div>

		    	<?php /*<div class="large- columns no-padding">
		        	<div class="sorter">
		            	<div class="filter-sort-main columns no-pading">
		                	<span class="custom-dropdown__select custom-dropdown__select--white">
		                		<label class="filter-lebel">Sort:</label>
		                		<select class="select-style" onchange="location = this.value">
		                        	<?php foreach($sorts as $aSort): ?>
		                        		<option value="<?php echo $aSort['href']; ?>" 
		                        		<?php echo ($aSort['value'] == ($sort . '-' . $order)  ? 'selected' : ''); ?>><?php echo $aSort['text']; ?></option>
		                        	<?php endforeach; ?>
		                    	</select>
		                	</span>
		            	</div>
		            	<div class="clearfix"></div>
		        	</div>
		    	</div>
		    	<div class="clearfix"></div>*/ ?>
		        <ul class="products-grid" id="scroll-container">
				    <?php
				    $i = 0;
				    foreach ($products as $product): ?>
				    	<li>
							<div class="grow">
								<?php if ($product['thumb']): ?>
		    		    			<div class="product-image-wrapper">
		    							<a href="<?php echo str_replace('&', '&amp;', $product['href']); ?>" title="<?php echo $product['name']; ?>" class="product-image">
											<?php if ($product['special']) : ?>
												<div class="icon-sale"></div>
											<?php endif; ?>

		    			    					<img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>"  title="<?php echo $product['name']; ?>" />
							
											<?php if ($product['special']): ?>
						    					<span class="sticker-wrapper top-left">
						    						<span class="sticker sale">Sale</span>
					    						</span>
											<?php endif; ?>
										</a>
									</div>
			    				<?php endif; ?>
		    					<div class="product-content">
				    				<span class="product-name">
					    				<a href="<?php echo str_replace('&', '&amp;', $product['href']); ?>" title="<?php echo $product['name']; ?>" ><?php echo $product['name']; ?></a>
				    				</span>
				    				<div class="clearfix"></div>

					    			<?php if ($display_price) { ?>
			    		    			<div class="price-box">
						    				<?php if (!$product['special']) { ?>
												<span class="regular-price">
							    					<span class="price"><?php echo $product['price']; ?></span>
												</span>
						    				<?php } else { ?>
												<p class="old-price">
							    					<span class="price-label">Regular Price:</span>
							    					<span class="price"><?php echo $product['price']; ?></span>
												</p>
												<p class="special-price">
							    					<span class="price">
							    						<?php echo $product['special']; ?>
						    						</span>
												</p>
						    				<?php } ?>
			    		    			</div>
					    			<?php } ?>
					    			<div class="actions-btn">
					    				<?php $act = ($product['options']) ? $product['href'] : $action; ?>
				    					<form action="<?php echo str_replace('&', '&amp;', $act); ?>" name="product_<?php echo $i ?>" method="post" enctype="multipart/form-data" id="manufacturer_product_<?php echo $i ?>">
											<input type="hidden" name="quantity" value="1" />
											<input type="hidden" name="product_id" value="<?php echo $product['id']; ?>" />
											<input type="hidden" name="redirect" value="<?php echo str_replace('&', '&amp;', $product['href']); ?>" />

											<div class="actions clearer">

						    					<button type="submit" title="<?php echo $button_add_to_cart; ?>" class="button btn-cart">
						    						<span>
						    							<span>
						    								<img src="catalog/view/theme/default/image/img/bag-icon.png">
					    								</span>
				    								</span>
												</button>

												<button type="button" title="" class="button btn-heart product-like <?php echo ($product['wishlist_id'] != '' ? 'red' : ''); ?>" value="<?php echo $product['id']; ?>">
													<span>
														<span>
															<img src="catalog/view/theme/default/image/img/heart-icon.png">
														</span>
													</span>
												</button>

											</div>
					    				</form>

				    				</div>
					    		<div class="clearfix"></div>
				    			</div>
			    			<div class="clearfix"></div>
		    				</div>
						</li>
					<?php $i++; endforeach; ?>
				</ul>
		    	<?php if($pagination): ?>
				<div class="toolbar-bottom">
					<div class="toolbar">
			    		<div class="pager">
			        		<div id="pages" class="pagination pages gen-direction-arrows1">
			            		<?php /*<strong>Page:</strong>*/ ?>
			            		<?php echo $pagination; ?>
			        		</div>
			    		</div>
					</div>
				</div>
				<?php endif; ?>
		    <?php else: ?>
				<div class="block_category_above_empty_collection std">
					<div class="note-msg empty-catalog">
						<h3>There are no products matching the selection</h3>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>