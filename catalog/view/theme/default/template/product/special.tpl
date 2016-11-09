<div class="col-main grid12-9 grid-col2-main in-col2">

    <div class="page-title category-title">
	<h1><?php echo $heading_title; ?></h1>
    </div>

    <?php if ($products): //Show Products  ?>

        <div class="category-products">
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
		<?php foreach ($products as $i => $product): ?>
		    <li class="item <?php

		    echo ($i == 0) ? 'first ' : '';
		    echo ($i % 4 == 0) ? 'row-first ' : '';
		    echo ($i % 4 == 3) ? 'row-last ' : '';
		    echo (count($products) - 1 == $i) ? 'last' : '';

		    ?>">
			    <?php if ($product['thumb']): ?>
	    		<div class="product-image-wrapper" style="max-width: 295px;">
	    		    <a href="<?php echo str_replace('&', '&amp;', $product['href']); ?>" title="<?php echo $product['meta_link']; ?>" alt="<?php echo $product['meta_link']; ?>" class="product-image">
				    <?php if ($product['special']) : ?><div class="icon-sale"></div><?php endif; ?>
	    			<img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['alt_title']; ?>" alt="<?php echo $product['alt_title']; ?>" />
				    <?php if ($product['extra_img']): ?>
					<img src="<?php echo $product['extra_img']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['alt_title']; ?>" title="<?php echo $product['alt_title']; ?>" class="alt-img" />
				    <?php endif; ?>
	    		    </a>
	    		</div>
			<?php endif; ?>

			<h2 class="product-name"><a href="<?php echo str_replace('&', '&amp;', $product['href']); ?>" title="<?php echo $product['meta_link']; ?>" alt="<?php echo $product['meta_link']; ?>"><?php echo $product['name']; ?></a></h2>
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
					<span class="price-label">Now Only: </span>
					<span class="price"><?php echo $product['special']; ?></span>
				    </p>
				<?php } ?>
	    		</div>
			<?php } ?>
			<?php $act = ($product['options']) ? $product['href'] : $action; ?>
			<form action="<?php echo str_replace('&', '&amp;', $act); ?>" name="product_<?php echo $i ?>" method="post" enctype="multipart/form-data" id="product_<?php echo $i ?>">
			    <input type="hidden" name="quantity" value="1" />
			    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>" />
			    <input type="hidden" name="redirect" value="<?php echo str_replace('&', '&amp;', $product['href']); ?>" />
			    <div class="actions clearer">
				<button type="submit" title="<?php echo $button_add_to_cart; ?>" class="button btn-cart"><span><span><?php echo $button_add_to_cart; ?></span></span></button>
			    </div>
			</form>

		    </li>
		    <?php echo ($i % 4 == 3) ? '<li class="clr"></li>' : ''; ?>
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
        </div>
    <?php else: //Else Show Not found  ?>
        <div class="block_category_above_empty_collection std">
            <div class="note-msg empty-catalog">
                <h3>There are no products matching the selection</h3>
            </div>
        </div>
    <?php endif; //End Show Products   ?>
</div>
<script type="text/javascript">
    jQuery(function() {
//	jQuery('.filter-col').jScrollPane();
    });
    jQuery(document).ready(function() {
	jQuery('.filter h2').click(function() {
	    if (jQuery(this).parent('div.filter-body').hasClass('active')) {
		jQuery(this).siblings('div.filter-col').slideUp(500).parent('div.filter-body').removeClass('active');
	    } else {
		jQuery(this).siblings('div.filter-col').slideDown(500).parent('div.filter-body').addClass('active');
	    }
	});
    });
    var url = '<?php echo $filter_action ?>';
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
	<?php echo $this->load('common/column_left'); ?>
    </div>
</div>