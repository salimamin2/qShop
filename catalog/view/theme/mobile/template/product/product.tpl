<?php if($notice_main): ?>
    <div class="lightbox">
        <ul class="messages">
            <li class="notice-msg">
                <ul>
                    <li><span><?php echo $notice_main; ?></span></li>
                </ul>
            </li>
        </ul>
    </div>
<?php endif; ?>

<div id="Content" class="product-detail">
    <div class="container">
        <div class="product-view">
            <form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" enctype="multipart/form-data" id="product-form">

                <!-- Image - Large -->
                <div class="col-sm-12 col-md-8 product-div no-padding">
                    <div class="badge">
                        <?php if ($special): ?>
                            <div class="offer">
                                <div class="offer-in">
                                    <div class="title">Sale</div>
                                    <div class="text"><?php echo ($save_percent > 0 ? ($save_percent . '%') : ''); ?></div>    
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if($quantity <= '2' && $quantity > '0') { ?>
                        <div class="last-offer">&nbsp;</div>
                        <?php } ?>
                    </div>

					<div class="col-sm-1 col-md-1 no-padding">
					    <div id="" class="slides-images">
	                       <?php foreach($images as $i => $image): ?>
                                <a href="#<?php echo $i; ?>" class="thumbs thumb-<?php echo $i; ?> <?php echo ($i == 0 ? 'active' : ''); ?>" title="<?php echo $alt_title; ?>" data-rel="<?php echo $i; ?>">
                                    <img src="<?php echo $image['thumb']; ?>" alt="<?php echo $alt_title; ?>" title="<?php echo $alt_title; ?>"/>
                                </a>
							<?php endforeach; ?>
					    </div>
					</div> <!-- end: more-images -->

					<div class="col-sm-11 col-md-11 no-padding" style="overflow:hidden;">
						<div class="product-img" id="itemslider-zoom" style="">
                            <?php foreach($images as $n => $thumbimages): ?>
						    	<div class="item" data-hash="<?php echo $n; ?>">
						    		<div class="my-gallery" itemscope itemtype="http://schema.org/ImageGallery">
										<figure itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
											<a href="<?php echo $thumbimages['popup']; ?>" itemprop="contentUrl" data-size="<?php echo $thumbimages['size']; ?>">
		    									<img src="<?php echo $thumbimages['large']; ?>" alt="<?php echo $alt_title; ?>" title="<?php echo $alt_title; ?>"/>
	    									</a>
    									</figure>
									</div>
		    				   </div>
							<?php endforeach; ?>
                            <div class="clearfix"></div>
						</div>
                        <div class="product-overlay"></div>
						<div class="clearfix"></div>
					</div>                    
					<div class="clearfix"></div>
				</div>
				
				<div class="col-sm-12 col-md-4 no-padding">
					<div class="bg-light-grey main-product-content">
				    	<!-- Name and Model -->
				    	<div class="left">
					    	<div class="main-product-name"><h1><?php echo $heading_title; ?></h1></div>
				    		<!-- <div class="product-model">
								<div class="std"> Model No <?php echo $model; ?></div>
					    	</div> -->
		    			</div>
		    			<div class="clearfix"></div>

                        <div class="price-box">
                            <?php if ($special): ?>
                                <?php /*p class="old-price">
                                    <span class="price-label"><?php //echo __('Regular Price'); ?></span>
                                    <span class="price"><?php //echo $price; ?></span>
                                </p>*/ ?>
                                <span id="priceupdate price-product" data-price="<?php echo $price_org; ?>" class="price"><?php echo $price_org; ?></span>
                                <p class="special-price">
                                    <?php /*<span class="price-label"><?php //echo __('Now Only'); ?></span>*/ ?>
                                    <span id="priceupdate-special" data-price="<?php echo $special_price_org; ?>" class="price-special">
                                        <?php echo $special_price_org; ?>
                                    </span>
                                </p>
                            <?php else: ?>
			    			    <span class="regular-price">
		        					<div id="thisIsOriginal price-product" style="display:none;"><?php echo $price; ?></div>
				    				<span class="price price-product"><?php echo $price; ?></span>
				    				<span class="hide" id="priceupdate" data-price="<?php echo $price_org; ?>"><?php echo $price_org; ?></span>
								</span>
                            <?php endif; ?>
                        </div>

                        <div class="clearfix"></div>

                        <?php if ($meta_description): ?>
                            <div class="product-desc">
                                <div class="std">
                                    <h4>Overview -</h4>
                                    <?php echo $meta_description; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <hr />

                        <!-- Colors and Add to Cart -->
                        <?php if($quantity > 0): ?>
                        <div class="product-options <?php echo $category_code; ?>" id="product-options-wrapper">
                            <?php echo $this->loadFetch('product/product_options'); ?>
                        </div>
                       

                        <div class="row">
                            <div class="col-xs-8 col-sm-8">
                                <div class="quantity-option">
                                    <div class="col-xs-4 col-sm-4 qty-wrapper">
                                        <select name="quantity" class="input-text qty">
                                        <?php $max = (int) $quantity > 20?20: (int) $quantity; ?>
                                        <?php for ($i=1;$i<=$max;$i++) : ?>
                                            <option value="<?php echo $i ?>"><?php echo $i ?></option>
                                        <?php endfor; ?>
                                        </select>
                                        <?php /*<input type="text" name="quantity" value="1" class="input-text qty"/> */?>
                                    </div>
                                    <div class="col-xs-8 col-sm-8">
                                        <div class="add-to-cart">
                                            <button type="submit" title="Add To Cart" class="btn btn-cart-main"><span><?php echo $button_add_to_cart; ?></span></button>
                                        </div>
                                    </div>
                                <div class="clearfix"></div>
                                </div>
                            </div>
                            <div class="col-xs-4 col-sm-4">
                                <div class="add-to-wishlist"<?php echo ($wishlist_id != '' ? 'style="border-color: #ff0707;"' : ''); ?>>
                                    <button type="button" title="" class="button btn-heart-main btn-heart product-like" value="<?php echo $product_id; ?>">
                                        <span><span><img src="catalog/view/theme/default/image/img/heart-icon<?php echo ($wishlist_id != '' ? '-red' : ''); ?>.png"></span></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php else: ?>
                            <p><?php echo $stock; ?></p>
                            <input type="text" class="input-text email-out-of-stock" placeholder="<?php echo $text_email; ?>" />
                            <button type="button" class="btn btn-out-of-stock1"><?php echo $text_send; ?></button>

                        <?php endif; ?>

                        <!-- <a class="add-wishlist button right" href="<?php // echo $wishlist ?>" title="Add to Wishlist">
                            <span class="v-center">Add to Wishlist</span>
                        </a> -->

                        <div class="clr"></div>

                        <div class="action-box clearer">
                            <div class="add-to-links addto-gaps-right grid12-8  left">
                               <?php /*<span class="left add-wishlist" style="width: 25%;"><a href="<?php echo $wishlist ?>" title="Add to Wishlist" class="mini-font link add-wishlist">Add to Wishlist</a></span>
                               <span class="grid12-3 left send-query"><a href="mailto:<?php echo $email; ?>" title="Send Query" class="mini-font link send-query">Send Query</a></span>*/ ?>
                            </div>
                            <?php /*<div class="feature-wrapper top-border right">
                                <div class="addthis_sharing_toolbox"></div>-->
                                <div class="addthis_horizontal_follow_toolbox">&nbsp;</div>
                                <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-54c6317a36138e65" async="async"></script>
                            </div>*/ ?>
                        </div>

                        <div class="clearfix"></div>

                <div class="socials">
                    <p class="sub-heading-acount"><?php echo __('Share this on social'); ?></p>
                    <!-- <div class="addthis_sharing_toolbox"></div>-->
                    <div class="socials">

                        <a class="btn btn-social-icon btn-facebook" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" href="http://www.facebook.com/share.php?s=100&amp;p[url]=<?php echo $product_link; ?>&amp;p[title]=<?php echo $heading_title; ?>&amp;p[images][0]=<?php echo $thumb; ?>">
                            <img class="img-social-fb" src="catalog/view/theme/default/image/icons/social_fb.png">
                        </a>

                        <a class="btn btn-social-icon btn-twitter" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" href="http://twitter.com/share?url=<?php echo $product_link; ?>&amp;text=<?php echo $heading_title; ?>">
                            <img class="img-social-twitter" src="catalog/view/theme/default/image/icons/social_twitter.png">
                        </a>

                        <a class="btn btn-social-icon btn-twitter" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" href="https://www.pinterest.com/share?url=<?php echo $product_link; ?>&amp;text=<?php echo $heading_title; ?>">
                            <img class="img-social-pin" src="catalog/view/theme/default/image/icons/social_pinit.png">
                        </a>

                        <?php /*<a class="btn btn-social-icon btn-email" href="mailto:<?php echo $email; ?>">
                        <img src="catalog/view/theme/default/image/icons/email.png">
                        </a>*/ ?>
                    </div>
                </div>

                        <?php /*<div class="designer">
                            <div class="col-md-12 no-padding">
                                <div class="col-md-2 no-padding">
                                    <div class="designer-image">
                                        <img src="catalog/view/theme/default/image/img/designer-thumbnail.png" class="image-round-designer">
                                    </div>
                                </div>

                                <div class="col-md-10 no-padding">
                                    <div class="designer-text">
                                        <h6>Mathew Willson</h6>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed eiusmod tempor incididunt ut labore et dolore [...]</p>
                                    </div>
                                </div>
                            </div>
                        </div> */?>

                        <div class="long-description">
                            <?php echo $description; ?>
                            <?php /*if($category_code == 'ready-to-wear') { ?>
                                <a class="lightbox sizing-guide" href="image/data/size_chart-rtw.jpg" title="Sizing Guide">
                            <?php } else { ?>
                                <a class="lightbox sizing-guide" href="image/data/size_chart.jpg" title="Sizing Guide">
                                    <span>Not sure what see? See our sizing chart</span>
                                </a>
                            <?php }*/ ?>
                        </div>
                            <?php if($size_chart=='1') { ?>
                            <a href="http://clovebuy.com/charts/SIZECHART.pdf" target="new">Size chart</a>
                            <?php } ?>
                        <div class="designer">
                            <p class="sub-heading-acount"><?php echo __('The Designer\'s Den'); ?></p>
                            <div class="line" style="margin-bottom: 25px;"></div>
                            [product_manufacturer product_id="<?php echo $product_id; ?>" link_text="(...)"][/product_manufacturer]
                        </div>
                        
                        <div class="clearfix"></div>

                        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
                        <input type="hidden" name="redirect" value="<?php echo str_replace('&', '&amp;', $redirect); ?>" />
                    </div>
                </div>
            </form>

        <div class="clearfix"></div>
        </div>

        <div class="col-md-12">

            <div class="seprator spaceing"></div>

            <div id="accordion" class="row product-relate">
                <div class="col-md-12 col-lg-4">
                    <h2><a href="#tab-1">Similar Products</a></h2>
                    <div id="tab-1" class="tab-content-product answer">
                        <div class="content">
                            <?php if ($cross_products) : ?>
                                <div class="box-additional box-up-sell grid12-9">
                                    <div class="products-grid-best" id="similar-seller">
                                        <?php foreach ($cross_products as $product) : ?>
                                            <div class="item-best col-xs-6 col-sm-4 col-md-3">
                                                <div class="grow">
                                                    <div class="product-image-wrapper-best">
                                                        <a href="<?php echo $product['href'] ?>" title="<?php echo $product['meta_link'] ?>" alt="<?php echo $product['meta_link'] ?>" class="product-image">
                                                            <img class="lazyOwl" src="<?php echo $product['thumb'] ?>" title="<?php echo $product['alt_title'] ?>" alt="<?php echo $product['alt_title'] ?>" />
                                                        </a>
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
                                                                    <span class="price" id="product-price-<?php echo $product['product_id']; ?>-new"><?php echo $product['special']; ?></span>
                                                                </p>
                                                            <?php } ?>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php else : ?>
                                <p class="text-align-center">Sorry we have no product yet</p>
                            <?php endif; ?>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="col-md-12 col-lg-4">
                    <h2><a href="#tab-2" class="ajax" id="tab2">Shop the Look</a></h2>
                    <div id="tab-2" class="tab-content-product answer">
                        <div class="content">
                            <?php if ($products) : ?>
                                <div class="products-grid-best" id="shape-the-look">
                                    <?php foreach ($products as $product) : ?>
                                        <div class="item-best col-xs-6 col-sm-4 col-md-3">
                                            <div class="grow">
                                                <div class="product-image-wrapper-best">
                                                     <a href="<?php echo $product['href'] ?>" title="<?php echo $product['meta_link'] ?>" alt="<?php echo $product['meta_link'] ?>" class="product-image">
                                                        <img class="lazyOwl" src="<?php echo $product['thumb'] ?>" title="<?php echo $product['alt_title'] ?>" alt="<?php echo $product['alt_title'] ?>" />
                                                    </a>
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
                                </div>
                            <?php else : ?>
                                <p class="text-align-center">Sorry we have no product yet</p>
                            <?php endif; ?>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="col-md-12 col-lg-4">
                    <h2><a href="#tab-3" class="ajax" id="tab3">Works From the Designer</a></h2>
                    <div id="tab-3" class="tab-content-product answer">
                        <div class="content">
                            [manufacturer_info category_id="<?php echo $category_id; ?>" manufacturer_id="<?php echo $manufacturer_id; ?>" product_id="<?php echo $product_id; ?>" limit="4" /]
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Root element of PhotoSwipe. Must have class pswp. -->
        <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">

            <!-- Background of PhotoSwipe. 
                 It's a separate element as animating opacity is faster than rgba(). -->
            <div class="pswp__bg"></div>

            <!-- Slides wrapper with overflow:hidden. -->
            <div class="pswp__scroll-wrap">

                <!-- Container that holds slides. 
                    PhotoSwipe keeps only 3 of them in the DOM to save memory.
                    Don't modify these 3 pswp__item elements, data is added later on. -->
                <div class="pswp__container">
                    <div class="pswp__item"></div>
                    <div class="pswp__item"></div>
                    <div class="pswp__item"></div>
                </div>

                <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
                <div class="pswp__ui pswp__ui--hidden">

                    <div class="pswp__top-bar">

                        <!--  Controls are self-explanatory. Order can be changed. -->

                        <div class="pswp__counter"></div>

                        <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>

                        <button class="pswp__button pswp__button--share" title="Share"></button>

                        <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>

                        <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>

                        <!-- Preloader demo http://codepen.io/dimsemenov/pen/yyBWoR -->
                        <!-- element will get class pswp__preloader--active when preloader is running -->
                        <div class="pswp__preloader">
                            <div class="pswp__preloader__icn">
                              <div class="pswp__preloader__cut">
                                <div class="pswp__preloader__donut"></div>
                              </div>
                            </div>
                        </div>
                    </div>

                    <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                        <div class="pswp__share-tooltip"></div> 
                    </div>

                    <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
                    </button>

                    <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
                    </button>

                    <div class="pswp__caption">
                        <div class="pswp__caption__center"></div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('.btn-out-of-stock1').click(function(){
            jQuery('.validation-advice').remove();
            jQuery('.stock_success').remove();

            var email = jQuery('.email-out-of-stock').val();
            var product_id = '<?php echo $product_id; ?>'
            if (email == '') {
                jQuery('.email-out-of-stock').after('<div class="validation-advice">Enter your Email Address</div>');
            } else {
                var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
                if (!emailReg.test(email)) {
                    jQuery('.email-out-of-stock').after('<div class="validation-advice">Enter a Valid Email Address</div>');
                } else {
                    jQuery.ajax({
                        type: 'GET',
                        url: 'product/product/emailStockProduct',
                        dataType: 'json',
                        data: 'email='+email+'&product_id='+product_id,
                        beforeSend: function(){
                            jQuery('.btn-out-of-stock1').fadeOut();
                            jQuery('.btn-out-of-stock1').before('<div class="loader"></div>');
                        },
                        complete: function(){
                        },
                        success: function (data) {
                            if (data.success != undefined) {
                                jQuery('.loader').remove();
                                jQuery('.btn-out-of-stock1').fadeIn();
                                jQuery('.email-out-of-stock').before('<div class="stock_success">Email is added successfully</div>');
                                setTimeout(function() {
                                    jQuery('.stock_success').remove();
                                }, 3000);
                            }
                        }
                    });
                }
            }
        });
    });
</script>
