<?php if ($products) { ?>
    <div class="grid12-6 no-right-gutter">
        <h3 class="section-title padding-right"><?php echo $heading_title; ?></h3>
        <div class="itemslider-wrapper slider-arrows1 slider-arrows1-pos-top-right slider-pagination1">
    	<div id="itemslider-featured-03d3917c9c97920bbb518aba47b34703" class="itemslider itemslider-responsive products-grid size-s centered">
		<?php foreach ($products as $i => $product) : ?>
		    <div class="item">
			<div class="product-image-wrapper" style="max-width:196px;">

			    <a href="<?php echo $product['href'] ?>" title="<?php echo $product['meta_link'] ?>" alt="<?php echo $product['meta_link'] ?>" class="product-image">
				<img class="lazyOwl" data-src="<?php echo $product['image'] ?>" title="<?php echo $product['alt_title'] ?>" alt="<?php echo $product['alt_title'] ?>" />
				<?php if ($product['extra_img']): ?>
	    			<img src="<?php echo $product['extra_img']; ?>" title="<?php echo $product['alt_title'] ?>" alt="<?php echo $product['alt_title'] ?>" class="alt-img" />
				<?php endif; ?>                   
			    </a>

			    <ul class="add-to-links clearer addto-links-icons addto-onimage visible-onhover">
				<li><a class="link-wishlist" 
				       href="<?php echo $product['wishlist'] ?>" 
				       title="Add to Wishlist">
					<span class="icon icon-hover i-wishlist-bw"></span>
				    </a></li>
			    </ul>                
			</div> <!-- end: product-image-wrapper -->

			<h3 class="product-name"><a href="<?php echo $product['href'] ?>" title="<?php echo $product['meta_link'] ?>" alt="<?php echo $product['meta_link'] ?>"><?php echo $product['name'] ?></a></h3>
			<?php if($product['rating']): ?>
                <div class="ratings">
                    <div class="rating-box">
                    <div class="rating" style="width:<?php echo (20 * (int) $product['rating']) ?>%"></div>
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
	    			<span class="price-label">Now only:</span>
	    			<span class="price" id="product-price-<?php echo $product['product_id']; ?>-new"><?php echo $product['special']; ?></span>
	    		    </p>
			    <?php } ?>
			</div>
			<div class="actions">
			    <?php $act = ($product['options']) ? $product['href'] : $action; ?>
			    <form action="<?php echo str_replace('&', '&amp;', $act); ?>" name="forthcoming_<?php echo $i ?>" method="post" enctype="multipart/form-data" id="forthcoming_<?php echo $i ?>">
				<input type="hidden" name="quantity" value="1" />
				<input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>" />
				<input type="hidden" name="redirect" value="<?php echo str_replace('&', '&amp;', $product['href']); ?>" />
				<button type="submit" title="Add to Cart" class="button btn-cart"><span><span>Add to Cart</span></span></button>
			    </form>

			</div>

		    </div>
		<?php endforeach; ?>
    	</div> <!-- end: itemslider -->
        </div> <!-- end: itemslider-wrapper -->
        <script type="text/javascript">
    	//<![CDATA[
    	jQuery(function($) {

    	    var owl = $('#itemslider-featured-03d3917c9c97920bbb518aba47b34703');
    	    owl.owlCarousel({
    		lazyLoad: true,
    		itemsCustom: [[0, 1], [320, 2], [480, 3], [768, 2], [960, 3], [1280, 3]],
    		responsiveRefreshRate: 50,
    		slideSpeed: 200,
    		paginationSpeed: 500,
    		scrollPerPage: true,
    		autoPlay: 4000,
    		stopOnHover: true,
    		rewindNav: true,
    		rewindSpeed: 600,
    		pagination: false,
    		navigation: true

    	    }); //end: owl

    	});
    	//]]>
        </script>
    </div>
    <div class="clearer"></div>
<?php } ?>