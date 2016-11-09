<div class="col-main grid12-9 grid-col2-main in-col2">
    <div class="page-title">
        <h1><?php //echo $heading_title;            ?><?php echo $text_search; ?></h1>
        <div class="category-products">
	    <?php if ($products): ?>
            <div class="toolbar">
                <div class="sorter">
                    <p class="amount"><strong><?php echo $total_amount; ?></strong></p>
                    <div class="sort-by">
                        <label>Sort By</label>
                        <select id="sort_by">
                            <?php foreach($sort_by as $col => $sort_col): ?>
                                <option value="<?php echo $col; ?>" <?php echo ($col == $sort ? 'selected' : ''); ?>><?php echo $sort_col; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <a class="<?php echo $sort_type; ?> v-middle" id="sort_order" rel="<?php echo $order; ?>">&nbsp;</a>
                    </div>
                    <div class="limiter">
                        <label>Show</label>
                        <select id="limit">
                            <?php foreach($limit_by as $num): ?>
                            <option value="<?php echo $num; ?>" <?php echo ($limit == $num ? 'selected' : ''); ?>><?php echo $num; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="per-page"> per page</span>
                    </div>
                </div>
            </div>
    	    <ul class="products-grid category-products-grid itemgrid itemgrid-adaptive itemgrid-3col single-line-name centered hover-effect equal-height">
		    <?php foreach ($products as $product): ?>
			<li class="item">
			    <div class="product-image-wrapper" style="max-width:295px;">
				<a href="<?php echo str_replace('&', '&amp;', $product['href']); ?>" title="<?php echo $product['meta_link']; ?>" alt="<?php echo $product['meta_link']; ?>" class="product-image">
				    <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['alt_title']; ?>" title="<?php echo $product['alt_title']; ?>" />
				    <?php /*if ($product['extra_img']): ?>
	    			    <img src="<?php echo $product['extra_img']; ?>" alt="<?php echo $product['alt_title']; ?>" title="<?php echo $product['alt_title']; ?>" class="alt-img" />
				    <?php endif; */?>
				    <?php if ($product['special']): ?>
	    			    <span class="sticker-wrapper top-left"><span class="sticker sale">Sale</span></span>
				    <?php endif; ?>
				</a>
				<ul class="add-to-links clearer addto-links-icons addto-onimage display-onhover">
				    <li>
					<a class="link-wishlist" 
					   href="<?php echo $product['wishlist'] ?>" 
					   title="Add to Wishlist">
					    <span class="icon icon-hover i-wishlist-bw"></span>
					</a>
				    </li>
				</ul>
			    </div>
			    <h2 class="product-name"><a href="<?php echo str_replace('&', '&amp;', $product['href']); ?>" alt="<?php echo $product['meta_link']; ?>" title="<?php echo $product['meta_link']; ?>"><?php echo $product['name']; ?></a></h2>
			    <div class="display-onhover"></div>
			    <?php echo (isset($product['stock']) && $product['stock'] != '') ? '<div class="product-stock">' . $product['stock'] . '</div>' : ''; ?>
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
					    <!--<span class="price-label">Now Only: </span>-->
					    <span class="price"><?php echo $product['special']; ?></span>
					</p>
				    <?php } ?>
	    		    </div>
			    <?php } ?>

			    <div class="actions clearer">
				<?php $act = ($product['options']) ? $product['href'] : $action; ?>
				<!-- <form action="<?php echo str_replace('&', '&amp;', $act); ?>" name="product_<?php echo $i ?>" method="post" enctype="multipart/form-data" id="product_<?php echo $i ?>">
				    <input type="hidden" name="quantity" value="1" />
				    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>" />
				    <input type="hidden" name="redirect" value="<?php echo str_replace('&', '&amp;', $product['href']); ?>" />
				    <button type="submit" title="<?php echo $button_add_to_cart; ?>" class="button btn-cart"><span><span><?php echo __('Add to Cart'); ?></span></span></button>
				</form>
			    </div> end: actions -->
			</li>
		    <?php endforeach; ?>
    	    </ul>

            <?php if($pagination): ?>
            <div class="toolbar-bottom">
                <div class="toolbar">
                    <div class="pager">
                        <div class="pages gen-direction-arrows1">
                            <strong>Page:</strong>
                            <?php echo $pagination; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
	    <?php else: ?>
    	    <p class="note-msg">
    		Your search returns no results.    </p>
	    <?php endif; ?>
	</div>
    </div>
</div>
<script>
    jQuery(document).on('change','#sort_by',function(e) {
        var val = jQuery(this).val();
        var url = '<?php echo $sort_url; ?>&sort=' + val;
        location.href = url;
    });

    jQuery(document).on('change','#limit',function(e) {
        var val = jQuery(this).val();
        var url = '<?php echo $limit_url; ?>&limit=' + val;
        location.href = url;
    });

    jQuery(document).on('click','#sort_order',function(e) {
        e.preventDefault();
        var rel = jQuery(this).attr('rel');
        var order = "ASC";
        if(rel.toLowerCase() == "asc") {
            order = "DESC";
        }
        var url = '<?php echo $order_url; ?>&order=' + order;
        location.href = url;
    });
</script>
<div class="col-left sidebar grid12-3 grid-col2-sidebar in-sidebar">
    <div class="block block-layered-nav">
	<?php echo $this->loadFetch('module/filter'); ?>
	<?php // echo $this->load('common/column_left'); ?>
    </div>
</div>

