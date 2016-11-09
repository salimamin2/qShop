<!DOCTYPE html>
<?php
$content = $this->load($content);
$cart_quantity = $this->cart->getQty();
//$menu = $this->load('module/menu', array('position' => MENU_POSITION_MAIN));
$cart = $this->load('module/cart',array('success_main' => $success_main,'error' => $error));
$layout_class = (isset(QS::app()->document->layout_col) ? QS::app()->document->layout_col : "col1-layout");
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $lang; ?>" lang="<?php echo $lang; ?>" itemscope itemtype="http://schema.org/Product">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo (QS::app()->document->title) ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
		<meta name="description" content="<?php echo QS::app()->document->description; ?>" />
		<meta name="keywords" content="<?php echo QS::app()->document->keywords; ?>" />
		<base href="<?php echo $base; ?>" />
		<meta name="robots" content="INDEX,FOLLOW" />
		<link rel="canonical" href="<?php echo $pageUrl; ?>" />

		<?php $aBreadcrumb = end(QS::app()->document->breadcrumbs); ?>
		<!-- Schema.org markup for Google+ -->
		<meta itemprop="name" content="<?php echo $this->config->get('config_title') . ' | ' . (QS::app()->document->title); ?>">
		<meta itemprop="description" content="<?php echo QS::app()->document->description; ?>">

		<!-- Twitter Card data -->
		<meta name="twitter:card" content="product">
		<?php if($aBreadcrumb['href'] !='') { ?>
		<meta name="twitter:site" content="<?php echo $aBreadcrumb['href']; ?>">
		<?php } ?>
		<meta name="twitter:title" content="<?php echo $this->config->get('config_title') . ' | ' . (QS::app()->document->title); ?>">
		<meta name="twitter:description" content="<?php echo QS::app()->document->description; ?>">
		<meta name="twitter:creator" content="<?php echo $this->config->get('config_owner'); ?>">

		<!-- Open Graph data -->
		<meta property="og:title" content="<?php echo $this->config->get('config_title') . ' | ' . (QS::app()->document->title); ?>" />
		<meta property="og:type" content="article" />
		<?php if($aBreadcrumb['href'] !='') { ?>
		<meta property="og:url" content="<?php echo $aBreadcrumb['href']; ?>" />
		<?php } ?>
		<meta property="og:description" content="<?php echo QS::app()->document->description; ?>" />
		<meta property="og:site_name" content="<?php echo $this->config->get('config_name'); ?>" />
		<?php if(isset(QS::app()->document->meta['image'])): ?>
			<meta itemprop="image" content="<?php echo QS::app()->document->meta['image']; ?>">
			<meta name="twitter:image" content="<?php echo QS::app()->document->meta['image']; ?>">
			<meta property="og:image" content="<?php echo QS::app()->document->meta['image']; ?>" />
		<?php endif; ?>

		<?php if(isset(QS::app()->document->meta['price'])): ?>
			<?php $symbol = QS::app()->currency->getSymbol(); ?>
			<meta name="twitter:data1" content="<?php echo $symbol['symbol_left'] . QS::app()->document->meta['price'] . $symbol['symbol_right']; ?>">
			<meta name="twitter:label1" content="Price">
			<meta property="og:price:amount" content="<?php echo QS::app()->document->meta['price']; ?>" />
			<meta property="og:price:currency" content="<?php echo $this->config->get('config_currency'); ?>" />
		<?php endif; ?>

		<?php if ($icon) : ?>
			<link href="<?php echo HTTP_IMAGE.$icon; ?>" rel="icon" />
		<?php endif; ?>

		<!--[if lt IE 7]>
			<script type="text/javascript">
				//<![CDATA[
					var BLANK_URL = 'image/blank.html';
					var BLANK_IMG = 'image/spacer.gif';
				//]]>
			</script>
		<![endif]-->

		<!-- owl-carousel -->
		<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/owl-carousel/owl.carousel.css"/>
		<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/owl-carousel/owl.theme.css"/>
		<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/owl-carousel/owl.transitions.css"/>
		
        <link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/jquery-ui.css"/>
		<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/font-awesome.css">

		<!-- Ichecks -->
		<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/icheks.css"/>
		<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/all-square-color.css"/>
		<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/square.css"/>
		<!-- Ichecks -->

		<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/print.css" media="print" />

		<!-- Zurb Foundation CSS -->
		<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/zurb/foundation.css"/>
		<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/zurb/normalize.css"/>
		<!-- Zurb Foundation CSS End -->

		<!-- zoom plugin -->
		<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/zoom/photoswipe.css"/>
		<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/zoom/default-skin.css"/>
		<!-- //zoom plugin -->

		<!--Jquery UI css-->

		<script src="catalog/view/theme/default/javascript/zurb/vendor/jquery.js"></script>
		<script>jQuery.noConflict();</script>
		<script src="http://code.jquery.com/jquery-migrate-1.2.1.js"></script>

		<!-- zoom plugin -->
		<script src="catalog/view/theme/default/javascript/zoom/photoswipe.js"></script>
		<script src="catalog/view/theme/default/javascript/zoom/photoswipe-ui-default.min.js"></script>
		<!-- //zoom plugin -->

		<?php foreach ($this->document->getStyles() as $style) : ?>
			<link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
		<?php endforeach; ?>


		<!-- Slider -->
		<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/slider/nivo-slider.css"/>
		<link rel="stylesheet" href="catalog/view/theme/default/stylesheet/slider/default/default.css" type="text/css" media="screen" />
		<?php echo $this->document->renderHead(); ?>
		<script type="text/javascript">
			//<![CDATA[
				var infortisTheme = {};
				infortisTheme.responsive = true;
				infortisTheme.maxBreak = 1280;
			//]]>
		</script>

		<script type="text/javascript">
			//<![CDATA[
				optionalZipCountries = ["HK", "IE", "MO", "PA"];
			//]]>
		</script>
		
		<!--[if IE]>
			<link rel="stylesheet" type="text/css" href="catalog/view/theme/modern/stylesheet/styles-ie.css" media="all" />
		<![endif]-->
		<!--[if lte IE 7]>
			<link rel="stylesheet" type="text/css" href="catalog/view/theme/modern/stylesheet/styles-ie7.css" media="all" />
		<![endif]-->
		<!--[if lte IE 8]>
			<link rel="stylesheet" type="text/css" href="catalog/view/theme/modern/stylesheet/styles-ie8.css" media="all" />
		<![endif]-->
		
		<link rel="shortcut icon" type="image/x-icon" href="image/data/favicon.ico">


		<?php echo $google_analytics; ?>		
	</head>
	<body class="<?php echo $bodyClass ?>">
		<!-- Go to www.addthis.com/dashboard to customize your tools -->
		<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5209b38b4153210b" async="async"></script>
		<?php echo QS::app()->document->renderBodyBegin(); ?>
		<div class="app">
			<aside class="side-menu-main hover bg-black columns no-padding">
				<div class="large-12 columns">
					<div class="row">

						<div class="logo">
							<div class="center-div">
								<a href="<?php echo HTTP_SERVER ?>" title="<?php echo $store ?>">
									<img class="center-div" src="<?php echo $logo ?>" alt="<?php echo $store ?>" />
								</a>
							</div>
						</div><!-- Logo -->

						<div class="menu">

							<div class="menu-shop">
								<h4>Shop Now</h4>
								<div class="line"></div>
								<?php echo $menu_header; ?>
							</div>
							<div class="menu-browse-by">
								<h4>Browse By Designer</h4>
								<div class="line"></div>
								<?php echo $menu_topmenu; ?>
							</div>

							<div class="menu-learn-a-bit">
								<h4>Learn A Bit</h4>
								<div class="line"></div>
								<?php echo $menu_footer; ?>
							</div>

						</div>

					</div>
				</div>
			</aside>

			<div class="main-content" style="">
				<div class="box">
					<div class="" style="color:#fff;">
						<div class="bg-grey fixed-top navigation-menu">
			  				<div class="col-sm-4 columns">
				  				<ul>
					  				<li class="search-li">
					  					<div class="search-icon">
					  					</div>
					  					<div class="search-inputs">
                                            <input class="" type="text" id="tags1" placeholder="Search..."></div>
                                        <div class="ui-widget" style="margin-top:2em; font-family:Arial">
                                            <div id="log" style="display: none" class="ui-widget-content"></div>
                                        </div>
					  				</li>
				  				</ul>
			  				</div>
			  				<div class="col-sm-8 columns no-padding">
			  					<div class="right acount-nav">
					  				<ul class="login_menu">
					  					<li><a href="<?php echo $customer_service_link; ?>">Customer Service</a></li>
					  					<li id="open_subs">
					  						<a>Subscribe</a>
					  						<!-- Dropdown modal For Subscribe -->
											<div class="subscribe hide">
											 	<div class="arrow-navigate_subs"></div>
												<div id="subscribe-form" class="clearer">
										    		<form action="<?php echo $newsletter_action ?>" method="post" id="newsletter-validate-detail">

										        		<div class="col-md-12">
										            		<p>
										            			Stay updated with our latest trends and news from our in-house bloggers and editorials.

Subscribe to our newsletter today.
										            		</p>
										            		<input type="text" name="email" id="newsletter" title="Sign up for our newsletter" class="input-text required-entry validate-email left" placeholder="<?php echo __('Insert Your Email') ?>" />
										            		<div class="clearfix"></div>
										            		<button type="submit" title="Subscribe" class="btn btn-subs">
										        				<span><img alt="Mail Icon" src="catalog/view/theme/default/image/img/mail-icon.png"></span>
										            			<span>Subscribe</span>
										        			</button>
										        			<p class="spam">We won’t spam - Pinky promise!</p>
										        		</div>

											    	</form>
										    	</div>
											</div>
									    	<!-- //Dropdown modal For Subscribe -->
					  					</li>

					  					<?php if ( $this->customer->isLogged() ) { ?>
						  					<li class="acount-link">
						  						<a title="Logout" href="account/logout" class="logout-image" style="padding: 14px 32px;">
						  							<img src="catalog/view/theme/default/image/img/logout-icon.png">
					  							</a>
					  						</li>
			  						 	<?php } ?>

			  						 	<?php if ( $this->customer->isLogged() ) { ?>

											<li class="acount-link">
					  							<a title="My Account" href="account/account">
					  								<img src="catalog/view/theme/default/image/img/acount-icon.png" alt="Account">
				  								</a>
				  								<ul class="sub">
				  									<li><a href="account/account">My Account</a></li>
				  								</ul>
			  								</li>

										<?php } else { ?>

											<li class="acount-link" id="open_login">
					  							<a title="Login">
					  								<img src="catalog/view/theme/default/image/img/acount-icon.png" alt="Account">
				  								</a>
												<!-- Dropdown modal For Login -->
												<div class="quickCourse">
													<?php if ( $this->customer->isLogged() ) {
														echo 'Logged In';
													} else { ?>
														<?php echo $this->load('module/login'); ?>
													<?php } ?>
												</div>
												<!-- //Dropdown modal For Login -->
			  								</li>


										<?php } ?>

			  							<li class="acount-link">
					  						<a class="cart-link" title="Shopping Cart" id="activator" style="margin-right: 10px;">
					  							<img src="catalog/view/theme/default/image/img/cart-icon.png" alt="Cart">
					  							<span class="badge-cart"><?php echo $cart_quantity; ?></span>
				  							</a>
			  							</li>
					  				</ul>
			  					</div>
			  				</div>
		  					<div class="clearfix"></div>
						</div>
						<div class="clearfix"></div>
					</div>

					<!-- Main Content Start -->
					<div class="box-row">
						<div class="box-cell" id="box-cell">
							<div class="box-inner container">
								<!-- load content -->

								<?php /*if (QS::app()->document->breadcrumbs) : ?>
									<div class="grid-full breadcrumbs">
										<ul>
											<?php foreach (QS::app()->document->breadcrumbs as $breadcrumb) : ?>
												<li class="<?php echo isset($breadcrumb['class']) ? $breadcrumb['class'] : '' ?>">
													<a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
													<?php echo $breadcrumb['separator'] ? '<span>' . $breadcrumb['separator'] . '</span>' : ''; ?>
												</li>
											<?php endforeach; ?>
										</ul>
									</div>
									<div class="clear"></div>
								<?php endif; *///breadcrumb ?>
								
								<?php if(QS::app()->document->loadKnow): ?>
									[module name="know"]
								<?php endif; ?>
								<?php echo $content; ?>
								<!-- //load content -->


								<!-- Footer -->
								<div class="clearfix"></div>
								
								<div class="container">
									<div class="col-md-12">
										<div class="footer-main">
											<div class="">
												<div class="footer-content">

													<div class="c-columns-footer columns f-m-l">
														<div class="footer-menu">
															<h3>About cLoveBuy.com</h3>
															<?php echo $menu_footer_1; ?>
														</div>
													</div>

													<div class="c-columns-footer columns">
														<div class="footer-menu">
															<h3>Buying at cLoveBuy.com</h3>
															<?php echo $menu_footer_2; ?>
														</div>
													</div>

													<div class="c-columns-footer columns">
														<div class="footer-menu">
															<h3>What you must Know</h3>
															<?php echo $menu_footer_3; ?>
														</div>
													</div>

													<div class="c-columns-footer columns">
														<div class="footer-menu">
															<h3>Customer Service</h3>
															<?php echo $left_menu; ?>
														</div>
													</div>

													<div class="c-columns-footer social columns">
														<div class="footer-menu">
															<h3>We would love you to follow us around on</h3>
															<p class="social-icon">
																<span><a href="<?php echo $facebook_page; ?>" target="_blank" class="facebook"></a></span>
																<span><a href="<?php echo $twitter_page; ?>" target="_blank" class="twiter"></a></span>
																<span><a href="<?php echo $instagram_page; ?>" target="_blank" class="instagram"></a></span>
																<span><a href="https://www.pinterest.com/Clovebuycom/" target="_blank" class="pintrest"></a></span>
																<?php // <span><a href="#" class="youtube"></a></span> ?>
															</p>
															<h3>We Accept</h3>
															<p class="payment-icon">
																<span class="visa"></span>
																<span class="mastercard"></span>
															</p>
														</div>
													</div>

												<div class="clearfix"></div>
												</div>
											</div>
										<div class="clearfix"></div>
										</div>
									</div>
								<div class="clearfix"></div>
								</div>


								<div class="site-bottom-info container">
									<div class="large-8 columns">
										<p>2015 cLoveBuy.com. All rights reserved. cLoveBuy.com is at Dubai Silicon Oasis</p>
									</div>
									<div class="large-4 columns text-right">
										<p><a href="http://www.conceptualize.ae/" target="_blank">Web Design Dubai</a> by Conceptualize</p>
									</div>
								</div>
							</div>
							<!-- //Footer -->

					        <!-- The overlay and the box -->
					        <div class="overlay" id="overlay-login" style="display:none;"></div>
					        <div class="overlay" id="overlay" style="display:none;"></div>
					        <div class="cartopen main-cart" id="cartopen">
				        		<!-- fpc %module_cart% -->
					            <?php echo $cart; ?>
					            <!-- fpc end -->
					        </div>

					 	</div>
				 	</div>
		 			<div class="small-8">
				        <?php if ($post_filters): ?>
				        	<div class="currently">
				                <?php foreach ($filters as $i => $filter):
				                	if ($post_filters && isset($post_filters[$filter['field']])):
				            			$sValue = $filter['values'][$post_filters[$filter['field']][0]]['name'];

				                    	if ($sValue == '') {
				                    		$sValue = $post_filters[$filter['field']][0];
				                    	}?>

				                        <span class="label"><?php echo $filter['name'] ?>:</span>
				                        <span class="value"><?php echo $sValue; ?></span>
				                        <a class="btn-remove filter-remove" href="javascript:void(0)" rel="filter[<?php echo $filter['field'] ?>][]=<?php echo $post_filters[$filter['field']][0] ?>" title="Remove This Item">
				                        	<span style="font-weight: bold">X</span>
				                    	</a>

				                	<?php endif;
				            	endforeach; ?>
				        	</div>
				    	<?php endif; ?>
				    </div>
					
					<!-- Men Menu -->
					<div class="menu-visiblity" data-timeoutId="">
						<?php echo $men_menu; ?>
					</div>
					<!-- Women Menu -->
					<div class="women-visiblity" data-timeoutId="">
						<?php echo $women_menu; ?>
					</div>

				</div>
			</div><!-- //Main Content End -->	
		</div>

    	<?php echo QS::app()->document->renderBodyEnd(); ?>
			<?php if($this->config->get('social_promotion_status')):
				echo $this->load('module/social_promotion');
		endif; ?>

		<!-- <script type="text/javascript" src="catalog/view/javascript/materialize.min.js"></script> -->

		<script src="catalog/view/theme/default/javascript/zurb/vendor/modernizr.js"></script>
		<script src="catalog/view/theme/default/javascript/zurb/foundation.min.js"></script>
		<script src="catalog/view/theme/default/javascript/zurb/foundation/foundation.magellan.js"></script>
		<script src="catalog/view/javascript/jquery/scripts.js"></script>

		<!-- Ichecks -->
		<script src="catalog/view/theme/default/javascript/icheck.js"></script>
		<script type="text/javascript" src="catalog/view/javascript/jquery/icheck.js"></script>
		<!-- Ichecks -->

		<script src="catalog/view/theme/default/javascript/jquery-ui.js"></script>
	    <?php
		    $cart_updated = 0;
			if(isset(QS::app()->session->data['cart_updated'])) {
				$cart_updated = 1;
				unset(QS::app()->session->data['cart_updated']);
			}
		?>
	    <script type="text/javascript">
			var logged ="<?php echo $customer_logged ? 'true' : 'false'; ?>";
			var cart_updated = '<?php echo $cart_updated; ?>';
			var newsletterSubscriberFormDetail = new VarienForm('newsletter-validate-detail', null, false);
			var varienSearchForm = new Varien.searchForm('newsletter-validate-detail', 'newsletter', '');
			jQuery(document).ready(function(){
				jQuery('body').tooltip({
					items: '.select-style,.btn-cart,.btn-heart,.acount-link a[title="Login"],.cart-link,.logout-image',
					position: {
						my: "center bottom-20",
			        	at: "center top",
						using: function( position, feedback ) {
				          jQuery( this ).css( position );
				          if(jQuery(".tooltip-arrow",this).length < 1){
				         	 jQuery( "<div>" )
					            .addClass( "tooltip-arrow" )
					            .addClass( feedback.vertical )
					            .addClass( feedback.horizontal )
					            .appendTo( this );
				        	}
				    	}
				 	}
				});
			});
		</script>
	</body>
</html>