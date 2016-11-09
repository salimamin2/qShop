<!DOCTYPE html>
<?php
$content = $this->load($content);
 // $menu = $this->load('module/menu', array('position' => MENU_POSITION_MAIN));
$cart = $this->load('module/cart',array('success_main' => $success_main,'error' => $error));
$layout_class = (isset(QS::app()->document->layout_col) ? QS::app()->document->layout_col : "col1-layout");
$cart_quantity = $this->cart->getQty();
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
		<meta itemprop="name" content="<?php echo $this->config->get('config_title') . ' | ' . (QS::app()->document->title); ?>" />
		<meta itemprop="description" content="<?php echo QS::app()->document->description; ?>" />

		<!-- Twitter Card data -->
		<meta name="twitter:card" content="product" />
		<meta name="twitter:site" content="<?php echo $aBreadcrumb['href']; ?>" />
		<meta name="twitter:title" content="<?php echo $this->config->get('config_title') . ' | ' . (QS::app()->document->title); ?>" />
		<meta name="twitter:description" content="<?php echo QS::app()->document->description; ?>" />
		<meta name="twitter:creator" content="<?php echo $this->config->get('config_owner'); ?>" />

		<!-- Open Graph data -->
		<meta property="og:title" content="<?php echo $this->config->get('config_title') . ' | ' . (QS::app()->document->title); ?>" />
		<meta property="og:type" content="article" />
		<meta property="og:url" content="<?php echo $aBreadcrumb['href']; ?>" />
		<meta property="og:description" content="<?php echo QS::app()->document->description; ?>" />
		<meta property="og:site_name" content="<?php echo $this->config->get('config_name'); ?>" />
		<?php if(isset(QS::app()->document->meta['image'])): ?>
			<meta itemprop="image" content="<?php echo QS::app()->document->meta['image']; ?>">
			<meta name="twitter:image" content="<?php echo QS::app()->document->meta['image']; ?>">
			<meta property="og:image" content="<?php echo QS::app()->document->meta['image']; ?>" />
		<?php endif; ?>

		<?php if(isset(QS::app()->document->meta['price'])): ?>
			<?php $symbol = QS::app()->currency->getSymbol(); ?>
			<meta name="twitter:data1" content="<?php echo $symbol['symbol_left'] . QS::app()->document->meta['price'] . $symbol['symbol_right']; ?>" />
			<meta name="twitter:label1" content="Price" />
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
		<link rel="stylesheet" type="text/css" href="catalog/view/theme/mobile/stylesheet/owl-carousel/owl.carousel.css"/>
		<link rel="stylesheet" type="text/css" href="catalog/view/theme/mobile/stylesheet/owl-carousel/owl.theme.css"/>
		<link rel="stylesheet" type="text/css" href="catalog/view/theme/mobile/stylesheet/owl-carousel/owl.transitions.css"/>
		
        <link rel="stylesheet" type="text/css" href="catalog/view/theme/mobile/stylesheet/jquery-ui.css"/>
		<link rel="stylesheet" type="text/css" href="catalog/view/theme/mobile/stylesheet/font-awesome.css">

		<!-- Ichecks -->
		<link rel="stylesheet" type="text/css" href="catalog/view/theme/mobile/stylesheet/icheks.css"/>
		<link rel="stylesheet" type="text/css" href="catalog/view/theme/mobile/stylesheet/all-square-color.css"/>
		<link rel="stylesheet" type="text/css" href="catalog/view/theme/mobile/stylesheet/square.css"/>
		<!-- Ichecks -->

		<link rel="stylesheet" type="text/css" href="catalog/view/theme/mobile/stylesheet/print.css" media="print" />

		<!-- Zurb Foundation CSS -->
		<link rel="stylesheet" type="text/css" href="catalog/view/theme/mobile/stylesheet/zurb/foundation.css"/>
		<link rel="stylesheet" type="text/css" href="catalog/view/theme/mobile/stylesheet/zurb/normalize.css"/>
		<!-- Zurb Foundation CSS End -->

		<!-- zoom plugin -->
		<link rel="stylesheet" type="text/css" href="catalog/view/theme/mobile/stylesheet/zoom/photoswipe.css"/>
		<link rel="stylesheet" type="text/css" href="catalog/view/theme/mobile/stylesheet/zoom/default-skin.css"/>
		<!-- //zoom plugin -->

		<script src="catalog/view/theme/mobile/javascript/zurb/vendor/jquery.js"></script>
		<?php /*<script>//jQuery.noConflict();</script>
		<script src="http://code.jquery.com/jquery-migrate-1.2.1.js"></script> -->

		<script src="catalog/view/theme/mobile/javascript/zurb/vendor/modernizr.js"></script>
		<script src="catalog/view/theme/mobile/javascript/zurb/foundation.min.js"></script>
		<script src="catalog/view/theme/mobile/javascript/zurb/foundation/foundation.magellan.js"></script>

		<!-- owl-carousel -->
		<script src="catalog/view/theme/mobile/javascript/owl-carousel/owl.carousel.js"></script>
		<!-- //owl-carousel -->*/ ?>

		<!-- zoom plugin -->
		<script src="catalog/view/theme/mobile/javascript/zoom/photoswipe.js"></script>
		<script src="catalog/view/theme/mobile/javascript/zoom/photoswipe-ui-default.min.js"></script>
		<!-- //zoom plugin -->

		<?php foreach ($this->document->getStyles() as $style) : ?>
			<link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
		<?php endforeach; ?>

		<?php /*<script src="catalog/view/theme/mobile/javascript/zurb/vendor/jquery.js"></script>
		<script src="http://code.jquery.com/jquery-migrate-1.2.1.js"></script> -->

		<!-- <link rel="stylesheet" type="text/css" href="catalog/view/theme/<?php // echo $this->config->get('config_template'); ?>/stylesheet/jquery.bxslider.css" media="screen" />*/ ?>
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
		
		<?php /*<script type="text/javascript">var Translator = new Translate([]);</script>*/ ?>
		<!--[if IE]>
			<link rel="stylesheet" type="text/css" href="catalog/view/theme/modern/stylesheet/styles-ie.css" media="all" />
		<![endif]-->
		<!--[if lte IE 7]>
			<link rel="stylesheet" type="text/css" href="catalog/view/theme/modern/stylesheet/styles-ie7.css" media="all" />
		<![endif]-->
		<!--[if lte IE 8]>
			<link rel="stylesheet" type="text/css" href="catalog/view/theme/modern/stylesheet/styles-ie8.css" media="all" />
		<![endif]-->
		
		<!--Mobile Menu Css-->
        <link type="text/css" rel="stylesheet" href="catalog/view/theme/mobile/stylesheet/menu/jquery.mmenu.all.css" />
        <script type="text/javascript" src="catalog/view/javascript/jquery/menu/jquery.mmenu.min.all.js"></script>
		<script type="text/javascript">
			jQuery(function() {
				jQuery('nav#menu').mmenu();
				jQuery('nav#blog_option').mmenu();
			});
			jQuery(document).ready(function(){
				var bg_grey = jQuery('.bg-grey').height();
				jQuery('aside.side-menu-main').css('margin-top', bg_grey);
			});
		</script>

		<?php /*<link type="text/css" rel="stylesheet" href="catalog/viee/theme/mobile/stylesheet/materialize/materialize.min.css" />*/ ?>
		<link rel="shortcut icon" type="image/x-icon" href="image/data/favicon.ico">
		<?php echo $google_analytics; ?>		
	</head>

	<body class="<?php echo $bodyClass ?>">
		<?php echo QS::app()->document->renderBodyBegin(); ?>
		<div class="app">

			<nav id="menu" class="mobile">
				<div class="panel">
					<ul>
		  				<?php if ( $this->customer->isLogged() ) { ?>
  							<li><a href="<?php echo $account ?>">My Account</a></li>
  						<?php } ?>
		  			</ul>
					<?php echo $mobile_menu; ?>
				</div>
			</nav>

			<div class="bg-grey fixed-top navigation-menu acount-nav">
  				<div class="container-fluid">
	  				<ul class="login_menu">
	  					<li class="menu<?php echo ( $this->customer->isLogged() ) ? ' acount-link' : '' ?>"><a href="#menu" class="menu icon"></a></li>
	  					<li class="subscribe-i<?php echo ( $this->customer->isLogged() ) ? ' acount-link' : '' ?>" id="open_subs">
	  						<a class="subscribe-i icon">&nbsp;</a>
	  					</li>
	  					<!-- fpc %customer_links% -->
	  					<?php if ($this->customer->isLogged()): ?>
		  					<li class="<?php echo ( $this->customer->isLogged() ) ? 'acount-link' : '' ?>">
		  						<a href="<?php echo $logout_url ?>" class="logout icon">&nbsp;</a>
	  						</li>
							<li class="<?php echo ( $this->customer->isLogged() ) ? 'acount-link' : '' ?>">
	  							<a href="<?php echo $account ?>" class="account icon">&nbsp;</a>
							</li>
						<?php else: //<?php echo $login_url ?>
							<li id="open_login"><a class="account icon">&nbsp;</a></li>
						<?php endif; ?>
						<!-- fpc end -->
						<li class="<?php echo ( $this->customer->isLogged() ) ? 'acount-link' : '' ?>"><a class="search-icon icon">&nbsp;</a></li>
						<li class="<?php echo ( $this->customer->isLogged() ) ? 'acount-link' : '' ?>">
	  						<a class="cart-link cart-i icon" id="activator">
	  						<span class="badge-cart">
	  							<!-- fpc %cart_quantity% -->
	  								<?php echo $cart_quantity; ?>
  								<!-- fpc end -->
	  						</span>
	  						</a>
						</li>
					</ul>
	  			</div>
	  			<div class="search-panel" style="display: none;">
					<div class="search-inputs">
						<input class="" type="text" id="tags1" placeholder="Search...">
					</div>
					<div class="ui-widget" style="margin-top: 0;">
						<div id="log" style="display: none" class="ui-widget-content"></div>
					</div>
				</div>
	  		<div class="clearfix"></div>
			</div>

			<aside class="side-menu-main hover bg-black columns no-padding">
				<div class="large-12 columns">
					<div class="row">

						<div class="logo">
							<div class="center-div">
								<a href="<?php echo HTTP_SERVER ?>" title="<?php echo $store ?>">
									<img class="center-div" src="<?php echo $mobile_logo ?>" alt="<?php echo $store ?>" />
								</a>
							</div>
						</div><!-- Logo -->

					</div>
				</div>
			</aside>

			<div class="main-content" style="">
				<div class="box">
					<!-- Main Content Start -->
					<div class="box-row">
						<div class="box-cell" id="box-cell">
							<div class="box-inner">
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
								<?php endif;*/ //breadcrumb ?>

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
												<div class="footer-content footer-menu">

													<div id="accordion" class="row">
														<div class="col-sm-6 col-md-3">
															<h2><a href="#about"><label class="mark"><span>+</span></label>About cLoveBuy.com</a></h2>
															<div class="answer" id="about">
																<?php echo $menu_footer_1; ?>
															</div>
														</div>
														<div class="col-sm-6 col-md-3">
															<h2><a href="#buying"><label class="mark"><span>+</span></label>Buying at cLoveBuy.com</a></h2>
															<div class="answer" id="buying">
																<?php echo $menu_footer_2; ?>
															</div>
														</div>
														<div class="hidden-md clearfix"></div>
														<div class="col-sm-6 col-md-3">
															<h2><a href="#know"><label class="mark"><span>+</span></label>What you Must Know</a></h2>
															<div class="answer" id="know">
																<?php echo $menu_footer_3; ?>
															</div>
														</div>
														<div class="col-sm-6 col-md-3">
															<h2><a href="#know"><label class="mark"><span>+</span></label>Customer Service</a></h2>
															<div class="answer" id="customer">
																<?php echo $left_menu; ?>
															</div>
														</div>
													</div>

													<div class="c-columns-footer social columns">
														<div class="footer-menu">
															<h3>We would love you to follow us around on</h3>
															<p class="social-icon">
																<span><a href="<?php echo $facebook_page; ?>" class="facebook"></a></span>
																<span><a href="<?php echo $twitter_page; ?>" class="twiter"></a></span>
																<span><a href="<?php echo $instagram_page; ?>" class="instagram"></a></span>
																<span><a href="https://www.pinterest.com/Clovebuycom/" class="pintrest"></a></span>
																<?php /* <span><a href="#" class="youtube"></a></span>*/ ?>
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


								<div class="site-bottom-info">
									<div class="col-xs-12 col-sm-12 col-md-8 large-8 columns">
										<p>2015 cLoveBuy.com. All rights reserved. cLoveBuy.com is at Dubai Silicon Oasis</p>
									</div>
									<div class="col-xs-12 col-sm-12 col-md-4 large-4 columns text-right">
										<p><a href="http://www.conceptualize.ae/" target="_blank">Web Design Dubai</a> by Conceptualize</p>
									</div>
								</div>
							</div>
							<!-- //Footer -->

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
					
					<div class="menu-visiblity" data-timeoutId="">
						<?php echo $men_menu; ?>
					</div>

				</div>
			</div><!-- //Main Content End -->	
		</div>

		<!-- The overlay and the box -->
		<div class="overlay" id="overlay-login" style="display:none;"></div>
		<div class="overlay" id="overlay" style="display:none;"></div>
		<div class="cartopen main-cart" id="cartopen">
			<!-- fpc %cart% -->
				<?php echo $cart; ?>
			<!-- fpc end -->
		</div>

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
						<span><img alt="Mail Icon" src="catalog/view/theme/mobile/image/img/mail-icon.png"></span>
						<span>Subscribe</span>
					</button>
					<p>We won’t spam - Pinky promise!</p>
				</div>
			</form>
			</div>
		</div>
		<!-- //Dropdown modal For Subscribe -->

		<!-- Dropdown modal For Login -->
		<div class="quickCourse">
			<?php if ( $this->customer->isLogged() ) {
				echo 'Logged In';
			} else { ?>
				<?php echo $this->load('module/login'); ?>
			<?php } ?>
		</div>
		<!-- //Dropdown modal For Login -->

    	<?php echo QS::app()->document->renderBodyEnd(); ?>
			<?php if($this->config->get('social_promotion_status')):
				echo $this->load('module/social_promotion');
		endif; ?>
	</div>

	<script src="catalog/view/theme/mobile/javascript/zurb/vendor/modernizr.js"></script>
	<script src="catalog/view/theme/mobile/javascript/zurb/foundation.min.js"></script>
	<script src="catalog/view/theme/mobile/javascript/zurb/foundation/foundation.magellan.js"></script>

	<!-- Ichecks -->
	<script src="catalog/view/theme/default/javascript/icheck.js"></script>
	<script src="catalog/view/theme/mobile/javascript/icheck.js"></script>
	<!-- Ichecks -->
	<script src="catalog/view/theme/default/javascript/jquery-ui.js"></script>

	<script src="catalog/view/javascript/jquery/scripts.js"></script>

    <script type="text/javascript">
		var logged ="<?php echo $customer_logged ? 'true' : 'false'; ?>";
		var cart_updated = '<?php echo $cart_updated; ?>';
		var newsletterSubscriberFormDetail = new VarienForm('newsletter-validate-detail', null, false);
	    var varienSearchForm = new Varien.searchForm('newsletter-validate-detail', 'newsletter', '');
    </script>

    <?php 
    $cart_updated = 0;
	if(isset(QS::app()->session->data['cart_updated'])) {
		$cart_updated = 1;
		unset(QS::app()->session->data['cart_updated']);
	}
	?>
	</body>
</html>