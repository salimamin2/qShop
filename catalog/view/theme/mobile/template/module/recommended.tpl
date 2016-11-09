<?php //if ($products): ?>
    <h3 class="recommend-title">
        <div class="pull-left"><?php echo $heading_title ?></div>
        <!--<div class="pull-right"><a href="#">Browse more&nbsp;<i class="fa fa-caret-right"></i></a></div>-->
    </h3>
    
    <div id="itemslider-featured-0bfac31705b4235d714ba7026533f048" class="recommended-products">
        <?php foreach ($products as $i => $product) : ?>
            <div class="item">
                <div class="product-image-wrapper">
                    <a href="<?php echo $product['href'] ?>" title="<?php echo $product['meta_link'] ?>" alt="<?php echo $product['meta_link'] ?>" class="product-image">
                        <img class="lazyOwl" src="<?php echo $product['image'] ?>" title="<?php echo $product['alt_title'] ?>" alt="<?php echo $product['alt_title'] ?>" />
                    </a>                
                </div> <!-- end: product-image-wrapper -->

                <h3 class="product-name"><a href="<?php echo $product['href'] ?>" title="<?php echo $product['meta_link'] ?>" alt="<?php echo $product['meta_link'] ?>"><?php echo $product['name'] ?></a></h3>
                <h5 class="author"> <?php echo $product['author'] ?></h5>
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
                        <p class="special-price price">
                            <span class="regular-price" id="product-price-35">                      
                                <span><?php echo $product['price'] ?></span> 
                            </span>
                        </p>
                    <?php } else { ?>
                        <p class="old-price">
                            <span id="old-price-<?php echo $product['product_id']; ?>-new"><?php echo $product['price']; ?></span>
                        </p>

                        <p class="special-price price">
                            <span id="product-price-<?php echo $product['product_id']; ?>-new"><?php echo $product['special']; ?></span>
                        </p>
                    <?php } ?>
                </div>
                <div class="actions">
                    <?php $act = ($product['options']) ? $product['href'] : $action; ?>
                    <div class="cart-button">
                        <form action="<?php echo str_replace('&', '&amp;', $act); ?>" name="latest_product_<?php echo $i ?>" method="post" enctype="multipart/form-data" id="latest_product_<?php echo $i ?>">
                            <input type="hidden" name="quantity" value="1" />
                            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>" />
                            <input type="hidden" name="redirect" value="<?php echo str_replace('&', '&amp;', $product['href']); ?>" />
                            <button type="submit" title="Add to Cart" class="button btn-cart">
                                <span class="cart-icon">&nbsp;<span>
                            </button>
                        </form>
                    </div>
                    <div class="wishlist-button">
                        <a class="add-to-links link-wishlist" href="<?php echo $product['wishlist'] ?>" title="Add to Wishlist">
                            <!-- <i class="fa fa-heart fa-2x"></i> -->
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div> <!-- end: itemslider -->

    <!-- end: itemslider-wrapper -->
    <script type="text/javascript">
        //<![CDATA[
        jQuery(document).ready(function() {
            var owl = jQuery('#itemslider-featured-0bfac31705b4235d714ba7026533f048');
            owl.owlCarousel({
                lazyLoad: true,
                itemsCustom: [[0, 1], [320, 2], [480, 3], [768, 3], [960, 4], [1280, 5]],
                responsiveRefreshRate: 50,
                slideSpeed: 100,
                paginationSpeed: 500,
                scrollPerPage: true,
                stopOnHover: true,
                rewindNav: true,
                rewindSpeed: 300,
                pagination: true,
                navigation: true
            }); //end: owl
        });
    </script>

<?php //endif; ?>