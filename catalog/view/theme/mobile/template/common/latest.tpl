<div id="Content">
    <?php if ($products) { ?>
        <div class="module module-center">
            <h1><?php echo $heading_title; ?></h2>
            <div class="grd">
    	    <ul class="products-grid category-products-grid itemgrid itemgrid-adaptive itemgrid-3col single-line-name centered hover-effect equal-height">
		    <?php foreach ($products as $i => $product): ?>
			<li class="item <?php
			echo ($i == 0) ? 'first ' : '';
			echo ($i % 3 == 0) ? 'row-first ' : '';
			echo ($i % 3 == 2) ? 'row-last ' : '';
			echo (count($products) - 1 == $i) ? 'last' : '';
			?>">
				<?php if ($product['image']): ?>
	    		    <div class="product-image-wrapper" style="max-width: 295px;">
	    			<a href="<?php echo str_replace('&', '&amp;', $product['href']); ?>" title="<?php echo $product['meta_link']; ?>" class="product-image">
					<?php if ($product['special']) : ?><div class="icon-sale"></div><?php endif; ?>
	    			    <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['alt_title']; ?>"  title="<?php echo $product['alt_title']; ?>" />
					<?php /* if ($product['extra_img']): ?>
					    <img src="<?php echo $product['extra_img']; ?>"  alt="<?php echo $product['alt_title']; ?>"  title="<?php echo $product['alt_title']; ?>" class="alt-img" />
					<?php endif;*/ ?>
					<?php if ($product['special']): ?>
					    <span class="sticker-wrapper top-left"><span class="sticker sale">Sale</span></span>
					<?php endif; ?>
					</a>
	    			<ul class="add-to-links clearer addto-links-icons addto-onimage visible-onhover">
	    			    <li><a class="link-wishlist" 
	    				   href="<?php echo $product['wishlist'] ?>" 
	    				   title="Add to Wishlist">
	    				    <span class="icon icon-hover i-wishlist-bw"></span>
	    				</a></li>
	    			</ul> 
	    		    </div>
			    <?php endif; ?>
			    <h2 class="product-name"><a href="<?php echo str_replace('&', '&amp;', $product['href']); ?>" title="<?php echo $product['meta_link']; ?>" ><?php echo $product['name']; ?></a></h2>
			    <div class="display-onhover"></div>
			    <div class="stock-status">
					<span>Stock Availability</span><br />
					<span class="availability"><?php echo $product['stock']; ?></span>
				</div>
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
					    <span class="price"><?php echo $product['special']; ?></span>
					</p>
				    <?php } ?>
	    		    </div>
			    <?php } ?>
			</li>
		    <?php endforeach; ?>
    	    </ul>
            </div>
            <div class="bottom"><span></span></div>
        </div>
    <?php } ?>
</div>