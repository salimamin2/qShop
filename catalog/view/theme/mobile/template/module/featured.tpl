<?php if ($products): ?>
<div class="bestsellers">
	<div class="container">
    <h3 class="section-title"><?php echo $heading_title; ?></h3>
    <div class="itemslider-wrapper slider-arrows1 slider-arrows1-pos-top-right slider-pagination1">
        <div id="itemslider-featured-cf68399613681a334e396c5283737b72" class="itemslider itemslider-responsive products-grid size-s centered">
	    <?php foreach ($products as $i => $product) : ?>
		<div class="item">
		    <div class="product-image-wrapper">
			<a href="<?php echo $product['href'] ?>" title="<?php echo $product['meta_link'] ?>" class="product-image">
			    <img class="lazyOwl" src="<?php echo $product['image'] ?>" title="<?php echo $product['alt_title'] ?>" alt="<?php echo $product['alt_title'] ?>" />
			    <?php /* if ($product['extra_img']): ?>
	    		    <img src="<?php echo $product['extra_img']; ?>" title="<?php echo $product['alt_title'] ?>" alt="<?php echo $product['alt_title'] ?>" class="alt-img" />
			    <?php endif; */?>                   
			</a>
			<ul class="add-to-links clearer addto-links-icons addto-onimage visible-onhover">
			    <li><a class="link-wishlist" 
				   href="<?php echo $product['wishlist'] ?>" 
				   title="Add to Wishlist">
				    <span class="icon icon-hover i-wishlist-bw"></span>
				</a></li>
			</ul>                
		    </div> <!-- end: product-image-wrapper -->
		    <h3 class="product-name"><a href="<?php echo $product['href'] ?>" title="<?php echo $product['meta_link'] ?>"><?php echo $product['name'] ?></a></h3>
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
	    		<span class="regular-price">	    			    
	    		    <span class="price"><?php echo $product['price'] ?></span> 
	    		</span>
			<?php } else { ?>
	    		<p class="old-price">
	    		    <span class="price-label">Regular Price:</span>
	    		    <span class="price" id="old-price-<?php echo $product['product_id']; ?>-new"><?php echo $product['price']; ?></span>
	    		</p>
	    		<p class="special-price">
	    		    <!--span class="price-label">Now only:</span>-->
	    		    <span class="price" id="product-price-<?php echo $product['product_id']; ?>-new"><?php echo $product['special']; ?></span>
	    		</p>
			<?php } ?>
		    </div>
		</div>
	    <?php endforeach; ?>
        </div> <!-- end: itemslider -->
    </div> <!-- end: itemslider-wrapper -->
    </div> <!-- end container -->
</div> <!-- end bestsellers -->
    <script type="text/javascript">
        //<![CDATA[
        jQuery(function($) {

    	var owl = $('#itemslider-featured-cf68399613681a334e396c5283737b72');
    	owl.owlCarousel({
    	    lazyLoad: true,
    	    itemsCustom: [[0, 1], [320, 2], [480, 2], [768, 3], [960, 4], [1280, 4]],
    	    responsiveRefreshRate: 50,
    	    slideSpeed: 200,
    	    paginationSpeed: 500,
    	    scrollPerPage: true,
    	    stopOnHover: true,
    	    rewindNav: true,
    	    rewindSpeed: 600,
    	    pagination: true,
    	    navigation: true

    	}); //end: owl

        });
        //]]>
    </script>
<?php endif; ?>