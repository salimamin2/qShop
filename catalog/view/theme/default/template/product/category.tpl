<div id="content" class="container">

	<div class="col-main grid12-9 grid-col2-main in-col2 category-panel">

		<?php /* if ($category_image): ?>
			<img src="<?php echo $category_image; ?>" alt="<?php echo $alt_title; ?>" title="<?php echo $alt_title; ?>" />
		<?php endif;  */?>

		<div class="large- columns no-padding">
			<div class="sorter">
				<div class="large-8 columns" class="" style="">
					<div class="filter-types left">
		                <?php if ($post_filters): ?>
		                	<div class="currently">
		                		<label class="select-style left">Filter by</label>
		                        <?php foreach ($post_filters as $field => $filters):
		                        		foreach($filters as $i => $filter):
		                        	// if ($post_filters && isset($post_filters[$filter['field']])):
		                        		// $sValue = $filter['values'][$post_filters[$filter['field']][0]]['name'];

		                            	// if ($sValue == '') {
		                            		// $sValue = $post_filters[$filter['field']][0];
		                            	// }?>
		                                <div class="type">
		                                	<span class="label"><?php echo ucfirst(str_replace('option_','',$field)); ?>:</span>
											<span class="value"><?php echo $filter['name']; ?></span>
		                                	<a class="filter-remove" href="javascript:void(0)" rel="filter[<?php echo $field; ?>][]=<?php echo $filter['value']; ?>" title="Remove This Item">
		                                		<span>X</span>
		                            		</a>
		                            	</div>
		                        	<?php //endif;
		                        	endforeach;
		                    	endforeach; ?>
		                	</div>
		            	<?php endif; ?>
		            </div>
		            <div class="left">
		            	<button id="select_filters" class="select-style left" title="<?php echo $text_tooltip; ?>"><?php echo (count($post_filters) > 0 ? 'Add More' : 'Filter by'); ?></button>
		            </div>
				</div>

				<div class="large-4 filter-sort-main columns right no-pading">
					<span class="custom-dropdown__select custom-dropdown__select--white">
						<label class="filter-lebel">Sort:</label>
						<select class="select-style" onchange="location = this.value">
							<?php foreach($sorts as $aSort): ?>
								<option value="<?php echo $aSort['href']; ?>" <?php echo ($aSort['value'] == ($sort . '-' . $order)  ? 'selected' : ''); ?>><?php echo $aSort['text']; ?></option>
							<?php endforeach; ?>
						</select>
					</span>
				</div>
			<div class="clearfix"></div>
			</div>
		</div>		
		<div class="filters-div" style="">
			<?php echo $this->loadFetch('module/filter'); ?>
		</div>
		[module name="know"]

        <div class="category-products col-md-12">
			<?php if ($products): //Show Products ?>
        	<div id="loader"></div>
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
	    				<div class="product-content">

	    				<span class="product-name">
		    				<a href="<?php echo str_replace('&', '&amp;', $product['href']); ?>" title="<?php echo $product['meta_link']; ?>" ><?php echo $product['name']; ?></a>
	    				</span>
	    				<div class="clearfix"></div>

		    			<?php if ($display_price) { ?>
    		    			<div class="price-box">
			    				<?php if (!$product['special']) { ?>
									<span class="regular-price">
				    					<span class="price"><?php echo $product['price']; ?></span>
									</span>
			    				<?php } else { ?>
									<span class="old-price"><?php echo $product['price']; ?></span>
									<span class="price"><?php echo $product['special']; ?></span>
			    				<?php } ?>
    		    			</div>
		    			<?php } ?>
		    			<div class="actions-btn">
		    				<?php $act = ($product['options']) ? $product['href'] : $action; ?>
	    					<form action="<?php echo str_replace('&', '&amp;', $act); ?>" name="product_<?php echo $i ?>" method="post" enctype="multipart/form-data" id="product_<?php echo $i ?>">
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

									<button type="button" title="" class="button btn-heart product-like" value="<?php echo $product['id']; ?>">
										<span>
											<span>
												<img src="catalog/view/theme/default/image/img/heart-icon<?php echo ($product['wishlist_id'] != '' ? '-red' : ''); ?>.png">
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

	    		<?php endforeach; ?>
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

			<?php else: //Else Show Not found  ?>
				<div class="block_category_above_empty_collection std col-md-12 text-center">
	    			<div class="note-msg empty-catalog">
						<h3>There are no products matching the selection</h3>
	    			</div>
				</div>
			<?php endif; //End Show Products   ?>
		</div>
	<div class="clearfix"></div>
	</div>
</div>

<?php /*<!-- Infinite Scrolling -->
<script src="catalog/view/theme/default/javascript/script-scrol.js"></script>
<!-- Infinite Scrolling -->*/ ?>

<script type="text/javascript">
	var url = '<?php echo $filter_action ?>';
	var open_filter = "<?php echo $open_filter; ?>";
    jQuery(document).ready(function() {
    	jQuery('.filters-div').fadeOut();
	    if(open_filter == 'true') {
	        jQuery('.filters-div').fadeIn();
	    }
		jQuery('.filter h2').click(function() {
		    if (jQuery(this).parent('div.filter-body').hasClass('active')) {
				jQuery(this).siblings('div.filter-col').slideUp(500).parent('div.filter-body').removeClass('active');
		    } else {
				jQuery(this).siblings('div.filter-col').slideDown(500).parent('div.filter-body').addClass('active');
		    }
		});
    });
    
    function filterProduct(obj) {
		var bSubmitI = false;
		var bSubmitS = false;
		jQuery('#filter_form select').each(function() {
		    if (jQuery(this).find(':selected').val() != '') {
			url += '&' + jQuery(this).attr('name') + '=' + jQuery(this).find(':selected').val();
			bSubmitS = true;
		    }
		});
		jQuery('#filter_form input[type=checkbox]:checked, #filter_form input[type=radio]:checked, #filter_form input[type=hidden]').each(function() {
		    if (jQuery(this).val() != '') {
			url += '&' + jQuery(this).attr('name') + '=' + jQuery(this).val();
			bSubmitI = true;
		    }
		});
		if (bSubmitI || bSubmitS) {
		    location = url;
		}
    }

    function clearFilter() {
		jQuery('#filter_form input[type=checkbox]').removeAttr('checked');
		jQuery('#filter_form input[type=radio]').removeAttr('checked');
		jQuery('#filter_form select').removeAttr('selected');
		jQuery('#filter_form input[type=text]').val('');
		jQuery('#filter_form input[type=hidden]').val('');
		jQuery('#filter_form input[type=hidden]#from_val').val(0);
		jQuery('#filter_form input[type=hidden]#to_val').val(0);
		location = url;
    }

    // jQuery(document).on('change','#sort_by',function(e) {
    //     var val = jQuery(this).val();
    //     var url = '<?php echo $sort_url . (stristr($sort_url,"?") ? "&" : "?"); ?>sort=' + val;
    //     location.href = url;
    // });

    // jQuery(document).on('change','#limit',function(e) {
    //     var val = jQuery(this).val();
    //     var url = '<?php echo $limit_url . (stristr($limit_url,"?") ? "&" : "?"); ?>limit=' + val;
    //     location.href = url;
    // });
    jQuery(document).on('click','#select_filters',function(e) {
        jQuery('.filters-div').fadeIn('fast');

        document.getElementById("box-cell").className = "box-cell overflow-hidden";


    });

    jQuery(document).on('click','.close-filter',function(e) {

        document.getElementById("box-cell").className = "box-cell";


    });
    // jQuery(document).on('click','#sort_order',function(e) {
    //     e.preventDefault();
    //     var rel = jQuery(this).attr('rel');
    //     var order = "ASC";
    //     if(rel.toLowerCase() == "asc") {
    //         order = "DESC";
    //     }
    //     var url = '<?php echo $order_url . (stristr($order_url,"?") ? "&" : "?"); ?>order=' + order;
    //     location.href = url;
    // });

// jQuery('.skin-minimal input').icheck({
//             checkboxClass: 'icheckbox_square-grey',
//             radioClass: 'iradio_square-grey',
//             increaseArea: '80%'
//         });

</script>
<div class="col-md-12">
        <div class="recommended-blog container">
            <div class="row">
                <div class="head col-sm-3 col-md-3">
                    <h2>from our Editorial</h2>
                    <p><a href="<?php echo $blog_page; ?>">View All</a></p>
                </div>
                <div class="col-sm-9 col-md-9">
                    <div class="row">
                        [module name="blog_latest" class="col-sm-4" limit="3" /]
                    </div>
                </div>
            </div>
        </div>
    </div>