<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" xml:lang="<?php echo $lang; ?>">
    <head>
        <title><?php echo $title; ?></title>
        <base href="<?php echo $base; ?>" />
        <?php foreach ($links as $link) { ?>
            <link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
        <?php } ?>
        <link rel="stylesheet" type="text/css" href="view/stylesheet/stylesheet.css" />

        <?php foreach ($styles as $style) { ?>
            <link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
        <?php } ?>
        <!-- jQuery -->
        <script type="text/javascript" src="view/javascript/jquery/jquery-1.4.2.min.js"></script>
        <!-- jQuery theme -->
        <link rel="stylesheet" type="text/css" href="view/javascript/jquery/ui/themes/ui-lightness/ui.all.css" />
        <!-- Pines notifier -->
        <!--<link rel="stylesheet" type="text/css" href="http://pines.sourceforge.net/pnotify/jquery.pnotify.default.css"/>
        <link rel="stylesheet" type="text/css" href="http://pines.sourceforge.net/pnotify/jquery.pnotify.default.icons.css"/>
        <script type="text/javascript" src="http://pines.sourceforge.net/pnotify/jquery.pnotify.min.js"></script>-->
        <!-- jQuery UI -->
       <!-- bootstrap -->
		<link href="view/stylesheet/bootstrap.css" rel="stylesheet" />
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

    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
	   <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/themes/smoothness/jquery-ui.css" media="all" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="view/javascript/jquery/ui/ui.core.js"></script>
        <!-- jQuery Super Fish Menu-->
        <script type="text/javascript" src="view/javascript/jquery/superfish/js/superfish.js"></script>
        <script type="text/javascript" src="view/javascript/jquery/tab.js"></script>
        <?php foreach ($scripts as $script) : ?>
            <script type="text/javascript" src="<?php echo $script; ?>"></script>
        <?php endforeach; ?>
        <script type="text/javascript">
            function showAlert(message,title){
                /*$.pnotify({
                    pnotify_opacity: .8,
                    pnotify_title: (title==undefined?'Alert':title),
                    pnotify_text: message
                });*/
            }
            //-----------------------------------------
            // Confirm Actions (delete, uninstall)
            //-----------------------------------------
            $(document).ready(function(){
                $("h1").each(function(){
                    $(this).html($('<span>'+$(this).text()+'</span>'));
                });
                // Confirm Delete
                $('#form').submit(function(){
                    if ($(this).attr('action').indexOf('delete',1) != -1) {
                        if (!confirm ('<?php echo $text_confirm; ?>')) {
                            return false;
                        }
                    }
                });
                function showError(message,title){
                    /*$.pnotify({
                    pnotify_type:'error',
                    pnotify_opacity: .8,
                    pnotify_title: (title==undefined?'Error':title),
                    pnotify_text: message
                });*/
                }
                // Confirm Uninstall
                $('a').click(function(){
                    if ($(this).attr('href') != null && $(this).attr('href').indexOf('uninstall',1) != -1) {
                        if (!confirm('<?php echo $text_confirm; ?>')) {
                            return false;
                        }
                    }
                });   
            });
        </script>
    </head>
    <body>
	<header class="navbar navbar-inverse" role="banner">
        <div id="container">
           <div id="header">
                <div class="div1">
                    <a href="<?php echo $home; ?>" title="<?php echo $heading_title; ?>">Administration Panel</a>
                </div>
                <?php if ($logged) { ?>
                    <div class="div2"><img src="view/image/lock.png" alt="" style="position: relative; top: 3px;" />&nbsp;<?php echo $logged; ?></div>
                <?php } ?>
            </div>
		</div>
	</header>
	<?php if ($logged) { ?>
		<!--<div id="menu">
			<ul class="nav left" style="display: none;">-->
	<?php } ?>
	<div id="content">
		<?php if ($breadcrumbs) { ?>
			<div class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
					<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
				<?php } ?>
			</div>
		<?php } ?>