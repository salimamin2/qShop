<!DOCTYPE html>
<?php $content = $this->load($content); ?>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" xml:lang="<?php echo $lang; ?>">
    <head>
        <title><?php echo (QS::app()->document->title) ?></title>
        <base href="<?php echo $base; ?>" />
	<?php foreach ($links as $link) : ?>
        <link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
	<?php endforeach; ?>
        <link rel="stylesheet" type="text/css" href="view/stylesheet/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="view/stylesheet/style.css" />
        <link rel="stylesheet" type="text/css" href="view/stylesheet/DT_bootstrap.css" />
        <link rel="stylesheet" type="text/css" href="view/stylesheet/bootstrap-wysihtml5.css" />
        <link rel="stylesheet" type="text/css" href="view/stylesheet/datepicker.css" />
        <!--<link rel="stylesheet" type="text/css" href="view/stylesheet/bootstrap-responsive.css" />-->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600" rel="stylesheet" />
        <link rel="stylesheet" type="text/css" href="view/stylesheet/font-awesome.css" />
        <!--<link rel="stylesheet" type="text/css" href="view/stylesheet/style-responsive.css" />-->
        <!-- <link rel="stylesheet" type="text/css" href="view/stylesheet/stylesheet.css" /> -->
        <!--<link rel="stylesheet" type="text/css" href="view/stylesheet/style-custom-page.css" />-->

	<?php foreach (QS::app()->document->getStyles() as $style) : ?>
        <link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
	<?php endforeach; ?>
	<?php echo QS::app()->document->renderHead(); ?>

        <!-- bootstrap -->
        <link href="view/stylesheet/bootstrap-overrides.css" type="text/css" rel="stylesheet" />

        <!-- global styles -->
        <link rel="stylesheet" type="text/css" href="view/stylesheet/layout.css" />
        <link rel="stylesheet" type="text/css" href="view/stylesheet/elements.css" />
        <link rel="stylesheet" type="text/css" href="view/stylesheet/icons.css" />
        <link rel="stylesheet" href="view/stylesheet/compiled/tables.css" type="text/css" media="screen" />

        <!-- libraries -->
        <link rel="stylesheet" type="text/css" href="view/stylesheet/font-awesome.css" />

        <!-- this page specific styles -->
        <link rel="stylesheet" href="view/stylesheet/signin.css" type="text/css" media="screen" />

        <!-- open sans font -->
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css' />
        <link rel="shortcut icon" type="image/x-icon" href="image/data/favicon.ico">

        <!--[if lt IE 9]>
          <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

    </head>
    <body>
        <!-- navbar my cocde-->
        <header class="navbar navbar-inverse" role="banner">
            <div class="navbar-header">
                <button class="navbar-toggle" type="button" data-toggle="collapse" id="menu-toggler">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <a class="navbar-brand" href="<?php echo $home; ?>"><img src="view/image/logo.png" /></a>
            <ul class="nav navbar-nav pull-right hidden-xs">
                <li id="store" class="dropdown"><a <?php if (!empty($stores)): ?>class="dropdown-toggle" data-toggle="dropdown"<?php endif; ?> href="<?php echo $this->config->get('config_url'); ?>" target="_blank"><i class="icon-globe"></i><span><?php echo __('menu_front'); ?></span></a>
				<?php if (!empty($stores)): ?>
                    <ul class="dropdown-menu">
                        <li><a  href="<?php echo $store; ?>" target="_blank" class="top"><?php echo $this->config->get('config_name'); ?></span></a></li>
					<?php foreach ($stores as $stores) { ?>
                        <li><a ><?php echo $stores['name']; ?></a></li>
					<?php } ?>
                    </ul>
				<?php endif; ?>
                </li>
                <li><a class="top" href="<?php echo HTTP_SERVER; ?>import_product.php"><i class="icon-list"></i><span><?php echo __('menu_import_product'); ?></span> </a></li>
                <li><a class="top" href="<?php echo $clear_fpc_cache; ?>" id="clear_fpc_cache"><i class="icon-trash"></i><span><?php echo __('menu_fpc_cache'); ?></span> </a></li>
                <li><a class="top" href="<?php echo $clear_cache; ?>" id="clear_cache"><i class="icon-trash"></i><span><?php echo __('menu_cache'); ?></span> </a></li>
                <li id="logout"><a class="top" href="<?php echo $logout; ?>"><i class="icon-signout"></i><span><?php echo __('text_logout'); ?></span></a></li>
                <!-- navbar my cocde-->
	<?php echo QS::app()->document->renderBodyBegin(); ?>
                <!--<div class="navbar navbar-default navbar-static-top" role="navigation">
                    <div class="container"> 
                        <div class="navbar-header">--> 


                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                </div>
        </header>
        <!-- sidebar my cocde-->
        <div id="sidebar-nav">
	<?php if (QS::app()->user->isLogged()) : ?>
            <ul id="dashboard-menu">
			<?php /*<div class="pointer">
                    <div class="arrow"></div>
                    <div class="arrow_border"></div>
                </div>*/ ?>
                <li id="dashboard"><a href="<?php echo $home; ?>"><i class="icon-dashboard"></i><span><?php echo __('menu_dashboard'); ?></span></a></li>
                <li id="catalog" class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown"><i class="icon-folder-open"></i><i class="icon-chevron-down"></i><span><?php echo __('menu_catalog'); ?></span></a>
                    <ul class="submenu">
                        <li><a href="<?php echo $category; ?>"><?php echo __('menu_category'); ?></a></li>
                        <li><a href="<?php echo $product; ?>"><?php echo __('menu_product'); ?></a></li>
                        <li><a href="<?php echo $option; ?>"><?php echo __('menu_option'); ?></a></li>
                        <li><a href="<?php echo $manufacturer; ?>"><?php echo __('menu_manufacturer'); ?></a></li>
                        <?php /*<li><a href="<?php echo $review; ?>"><?php echo __('menu_review'); ?></a></li> */ ?>
                    </ul>
                </li>
                <li id="catalog" class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown"><i class="icon-folder-open"></i><i class="icon-chevron-down"></i><span>Blog</span></a>
                    <ul class="submenu">
                        <li><a href="<?php echo $blog_category; ?>"><?php echo __('blog_category'); ?></a></li>
                        <li><a href="<?php echo $blog_post; ?>"><?php echo __('blog_post'); ?></a></li>
                        <li><a href="<?php echo $blog_author; ?>"><?php echo __('blog_authors'); ?></a></li>
                    </ul>
                </li>
                <li id="cms" class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown"><i class="icon-tags"></i><span><?php echo __('menu_cms'); ?><i class="icon-chevron-down"></i></span></a>
                    <ul class="submenu">
                        <li><a href="<?php echo $menu; ?>"><?php echo __('menu_menu'); ?></a></li>
                        <li><a href="<?php echo $information; ?>"><?php echo __('menu_information'); ?></a></li>
                        <?php /* <li><a href="<?php echo $testimonial; ?>"><?php echo __('menu_testimonial'); ?></a></li> */ ?>
                        <li><a href="<?php echo $filemanager; ?>"><?php echo __('menu_filemanager'); ?></a></li>
                    </ul>
                </li>
                <li id="extension" class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown"><i class="icon-wrench"></i><span><?php echo __('menu_extension'); ?><i class="icon-chevron-down"></i></span></a>
                    <ul class="submenu">
                        <li><a href="<?php echo $module; ?>"><?php echo __('menu_module'); ?></a></li>
                        <li><a href="<?php echo $shipping; ?>"><?php echo __('menu_shipping'); ?></a></li>
                        <li><a href="<?php echo $payment; ?>"><?php echo __('menu_payment'); ?></a></li>
                        <li><a href="<?php echo $total; ?>"><?php echo __('menu_total'); ?></a></li>
                        <li><a href="<?php echo $feed; ?>"><?php echo __('menu_feed'); ?></a></li>
                    </ul>
                </li>
                <li id="sale" class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown"><i class="icon-shopping-cart"></i><span><?php echo __('menu_sale'); ?><i class="icon-chevron-down"></i></span></a>
                    <ul class="submenu">
                        <li><a href="<?php echo $order; ?>"><?php echo __('menu_order'); ?></a></li>
                        <li><a href="<?php echo $shipment; ?>"><?php echo __('menu_shipment'); ?></a></li>
                        <li><a href="<?php echo $coupon; ?>"><?php echo __('menu_coupon'); ?></a></li>
                        <li class="dropdown-submenu"><a  data-toggle="dropdown"><?php echo __('Gift Voucher'); ?></a>
                            <ul class="dropdown-menu">
                                <li><a href="<?php echo $voucher; ?>"><?php echo __('Gift Voucher'); ?></a></li>
                                <li><a href="<?php echo $voucher_theme; ?>"><?php echo __('Voucher Themes'); ?></a></li>
                            </ul>
                        </li>
                        <li><a href="<?php echo $order_status; ?>"><?php echo __('menu_order_status'); ?></a></li>
                        <li><a href="<?php echo $discount; ?>"><?php echo __('menu_discount'); ?></a></li>
                        <li><a href="<?php echo $return; ?>"><?php echo __('menu_return'); ?></a></li>
                    </ul>
                </li>
                <li id="customer" class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown"><i class="icon-user"></i><span><?php echo __('menu_customer'); ?><i class="icon-chevron-down"></i></span></a>
                    <ul class="submenu">
                        <li><a href="<?php echo $customer; ?>"><?php echo __('menu_customer'); ?></a></li>
                        <li><a href="<?php echo $customer_group; ?>"><?php echo __('menu_customer_group'); ?></a></li>
                        <li><a href="<?php echo $contact; ?>"><?php echo __('menu_contact'); ?></a></li>
                    </ul>
                </li>
                <li id="system" class="dropdown submenu"><a class="dropdown-toggle" data-toggle="dropdown"><i class="icon-cog"></i><span><?php echo __('menu_system'); ?><i class="icon-chevron-down"></i></span></a>
                    <ul class="submenu">
                        <li><a href="<?php echo $seo_manager; ?>"><?php echo __('menu_seo_manager'); ?></a></li>
                        <li><a href="<?php echo $setting; ?>"><?php echo __('menu_setting'); ?></a></li>
                        <li class="dropdown-submenu"><a class="dropdown-toggle" data-toggle="dropdown"><?php echo __('menu_users'); ?></a>
                            <ul class="dropdown-menu">
                                <li><a href="<?php echo $user; ?>"><?php echo __('menu_user'); ?></a></li>
                                <li><a href="<?php echo $user_group; ?>"><?php echo __('menu_user_group'); ?></a></li>
                            </ul>
                        </li>
                        <li class="dropdown-submenu"><a class="dropdown-toggle" data-toggle="dropdown"><?php echo __('menu_localisation'); ?></a>
                            <ul class="dropdown-menu">
                                <li><a href="<?php echo $language; ?>"><?php echo __('menu_language'); ?></a></li>
                                <li><a href="<?php echo $currency; ?>"><?php echo __('menu_currency'); ?></a></li>
                                <li><a href="<?php echo $stock_status; ?>"><?php echo __('menu_stock_status'); ?></a></li>

                                <li><a href="<?php echo $country; ?>"><?php echo __('menu_country'); ?></a></li>
                                <li><a href="<?php echo $zone; ?>"><?php echo __('menu_zone'); ?></a></li>
                                <li><a href="<?php echo $geo_zone; ?>"><?php echo __('menu_geo_zone'); ?></a></li>
                                <li><a href="<?php echo $tax_class; ?>"><?php echo __('menu_tax_class'); ?></a></li>
                                <li><a href="<?php echo $length_class; ?>"><?php echo __('menu_length_class'); ?></a></li>
                                <li><a href="<?php echo $weight_class; ?>"><?php echo __('menu_weight_class'); ?></a></li>
                            </ul>
                        </li>
                        <li><a href="<?php echo $backup; ?>"><?php echo __('menu_backup'); ?></a></li>
                    </ul>
                </li>
                <li id="reports" class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown"><i class="icon-list-alt"></i><span><?php echo __('menu_reports'); ?><i class="icon-chevron-down"></i></span></a>


                    <ul class="submenu">
                        <li><a href="<?php echo $report_sale; ?>"><?php echo __('menu_report_sale'); ?></a></li>
                        <li><a href="<?php echo $report_order; ?>"><?php echo __('menu_report_order'); ?></a></li>
                        <li><a href="<?php echo $report_viewed; ?>"><?php echo __('menu_report_viewed'); ?></a></li>
                        <li><a href="<?php echo $report_purchased; ?>"><?php echo __('menu_report_purchased'); ?></a></li>
                        <li><a href="<?php echo $report_online; ?>"><?php echo __('menu_report_online'); ?></a></li>
                        <li><a href="<?php echo $report_reward; ?>"><?php echo __('menu_report_reward'); ?></a></li>
                    </ul>
                </li>
            </ul>
	<?php endif; ?>   
        </div>

        <div class="content">
            <div id="main-stats">
                <div id="pad-wrapper">
                    <!--<div id="pad-wrapper">-->
		    <?php
		    $iBreadCrumb = count(QS::app()->document->breadcrumbs);
		    if (QS::app()->document->breadcrumbs && $iBreadCrumb > 1) : ?>
                    <ol class="breadcrumb">
			    <?php $i = 0; ?>
			    <?php foreach (QS::app()->document->breadcrumbs as $breadcrumb) : ?>
				<?php if (($i + 1) != $iBreadCrumb): ?>
                        <li>				    
                            <a href="<?php echo $breadcrumb['href']; ?>">
					<?php else: ?>
                                <li class="active">
					    <?php endif; ?>
					    <?php echo $breadcrumb['text']; ?>
					    <?php if (($i + 1) != $iBreadCrumb): ?>
                            </a>
				    <?php endif; ?>
                        </li>
				<?php $i++; ?>
			    <?php endforeach;   ?>
                    </ol>
		    <?php endif; //breadcrumb        ?>
		    <?php echo $content ?>
                </div>
                <!-- /container --> 
            </div>
            <!-- /main-inner --> 
        </div>
        <div class="clear"></div>
        <div class="footer">
            <div class="footer-inner">
                <div class="container">
                    <div class="row">
                        <div class="span12"><?php //echo __('text_footer'); ?></div>
                        <!-- /span12 --> 
                    </div>
                    <!-- /row --> 
                </div>
                <!-- /container --> 
            </div>
            <!-- /footer-inner --> 
        </div>

        <!-- /footer --> 
	<?php echo QS::app()->document->renderBodyEnd(); ?>
        <!-- scripts -->
        <script src="view/javascript/jquery/jquery-ui-1.10.2.custom.min.js"></script>
        <!-- knob -->
        <script src="view/javascript/jquery/jquery.knob.js"></script>
        <!-- flot charts -->
        <script src="view/javascript/jquery/theme.js"></script>
    </body>
</html>