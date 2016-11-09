<div class="breadcrumbs">
    <?php foreach ($breadcrumbs as $i => $breadcrumb): ?>
	<?php echo $breadcrumb['separator'] ? '<span>' . $breadcrumb['separator'] . '</span>' : ''; ?>
        <a class="<?php echo count($breadcrumbs) - 1 == $i ? 'last' : ''; ?>" href="<?php echo str_replace('&', '&amp;', $breadcrumb['href']); ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php endforeach; ?>
</div>

<script type="text/javascript">
    $(document).ready(function() {
	$("#price_range").slider({
	    range: true,
	    step: <?php echo round(($filter['price_range']['max_price'] - $filter['price_range']['min_price']) / 10); ?>,
	    min: <?php echo $filter['price_range']['min_price']; ?>,
	    max: <?php echo $filter['price_range']['max_price']; ?>,
	    values: [<?php echo isset($filter['price_min']) ? $filter['price_min'] : $filter['price_range']['min_price']; ?>, <?php echo isset($filter['price_max']) ? $filter['price_max'] : $filter['price_range']['max_price']; ?>],
	    slide: function(event, ui) {
		$("#price_range_amount").text("From <?php echo $currency_symbol['symbol_left']; ?>" + ui.values[ 0 ] + " to <?php echo $currency_symbol['symbol_left']; ?>" + ui.values[ 1 ]);
		$("#price_min").val(ui.values[ 0 ]);
		$("#price_max").val(ui.values[ 1 ]);
	    }
	});
	$("#price_range_amount").text("From <?php echo $currency_symbol['symbol_left']; ?>" + $("#price_range").slider("values", 0) + " to <?php echo $currency_symbol['symbol_left']; ?>" + $("#price_range").slider("values", 1));

	/*$('.filter-button.show').click(function(){
	 $(this).animate({right: '165'},200);
	 $('.block-filter').animate({right: '0'},200);
	 $(this,'a').removeClass('show').addClass('close').text('<?php echo $text_hide_filter; ?>');
	 return false;
	 });
	 $('.filter-button.close').click(function() {
	 $(this).animate({right: '0'},200);
	 $('.block-filter').animate({right: '-165'},200);
	 $(this,'a').removeClass('close').addClass('show').text('<?php echo $text_show_filter; ?>');
	 return false;
	 });*/

    });
    function resetFilterList() {
	var $slider = $("#price_range");
	$slider.slider("values", 0, <?php echo $filter['price_range']['min_price']; ?>);
	$slider.slider("values", 1, <?php echo $filter['price_range']['max_price']; ?>);
	$("#price_min").val(<?php echo $filter['price_range']['min_price']; ?>);
	$("#price_max").val(<?php echo $filter['price_range']['max_price']; ?>);

	$('#filter_color').val('');
	$('.color-sample').removeClass('active');

	$('form#form-filter').submit();
    }
</script>



<div id="Content" class="content-1100">
	<h1><?php echo $heading_title; ?></h1>

    <?php if ($categories): //Show Sub Categories ?>
        <ul class="list-category">
			<?php foreach ($categories as $i => $category): ?>
		    	<li>
	    			<?php if ($category['thumb']): ?>

    					<a href="<?php echo str_replace('&', '&amp;', $category['href']); ?>">
    						<img src="<?php echo $category['thumb']; ?>" alt="<?php echo $category['name']; ?>" />
						</a>

						<br />

	    				<a href="<?php echo str_replace('&', '&amp;', $category['href']); ?>" class="category-name"><?php echo $category['name']; ?></a>

	    			<?php endif; ?>
			    </li>
	    		<?php echo ($i % 6 == 5) ? '<hr class="sep clr" />' : ''; ?>
			<?php endforeach; ?>
        </ul>

	<?php else: //Else Show Not found ?>
			<?php echo $text_error_product; ?>
	<?php endif; //End Show Sub Categories ?>

    <h1>Products</h1>

    <?php if ($products): //Show Products ?>
    	<ul class="list-product product_listing">
	    	<?php foreach ($products as $i => $product): ?>
				<li <?php echo ($i == 0) ? 'class="first"' : '';
					echo (count($products) - 1 == $i) ? 'class="last"' : ''; ?>>
			    		<?php if ($product['thumb']): ?>
	    	    			<div class="product-img">
	    						<a href="<?php echo str_replace('&', '&amp;', $product['href']); ?>">
    							<?php if ($product['special']) : ?><div class="icon-sale"></div><?php endif; ?>
	    		    				<img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" />
	    						</a>
	    	    			</div>
						<?php endif; ?>

		    			<div class="product-dtl">
							<a href="<?php echo str_replace('&', '&amp;', $product['href']); ?>" class="product-name"><?php echo $product['name']; ?></a>
							<div class="product-desc"><?php echo$product['description']; ?></div>
			    				<?php echo (isset($product['stock']) && $product['stock'] != '') ? '<div class="product-stock">' . $product['stock'] . '</div>' : ''; ?>
		   		 				<?php if ($display_price) { ?>
	    							<div class="product-price">
										<?php if (!$product['special']) { ?>
											<?php echo $product['price']; ?>
										<?php } else { ?>
				    						<span class="old-price"><?php echo $product['price']; ?></span>
				    						<span class="special-price"><?php echo $product['special']; ?></span>
			    						<?php } ?>
	    							</div>
								<?php } ?>

								<?php $act = ($product['options']) ? $product['href'] : $action; ?>
		    <!--<form action="<?php echo str_replace('&', '&amp;', $act); ?>" name="product_<?php echo $i ?>" method="post" enctype="multipart/form-data" id="product_<?php echo $i ?>">
			<input type="hidden" name="quantity" value="1" />
			<input type="hidden" name="product_id" value="<?php echo $product['id']; ?>" />
			<input type="hidden" name="redirect" value="<?php echo str_replace('&', '&amp;', $product['href']); ?>" />
			<a onclick="$('#product_<?php echo $i ?>').submit();" href="javascript:void(0)" class="button button-cart"><span><span class="icon-cart"><?php echo $button_add_to_cart; ?></span></span></a>
		    </form>-->
		    </div>
		</li>
	<?php echo ($i % 4 == 3) ? '<hr class="sep clr" /><br /><br />' : ''; ?>
	<?php endforeach; ?>
        </ul>
        <div class="clr"></div>
    <?php else: //Else Show Not found ?>
    <?php echo $text_error_product; ?>
<?php endif; //End Show Products   ?>
</div>