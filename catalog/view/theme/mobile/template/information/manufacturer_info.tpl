<div id="Content" class="content-1100 manufacturer-info">
    <?php if($header_text != ''): ?>
        <h4>
            <?php echo $header_text; ?>
            <br/>
            <span class="line"></span>
        </h4>
    <?php endif; ?>
   <div class="products-grid-best row" id="manufacturer">
    <?php if($manufacturer_info){ ?>
        <?php foreach ($manufacturer_info as $i => $product): ?>
            <div class="item-best col-xs-6 col-sm-4 col-md-3">
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

                    <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php }else{ ?>
        <p class="text-align-center">Sorry we have no product yet</p>
     <?php } ?>
    </div>
</div>