<!doctype html>
<html>
    <head>
        <title><?php echo $title; ?></title>
	<link href="catalog/view/theme/modern/stylesheet/styles.css" type="text/css" rel="stylesheet">
    </head>
    <body>

	<div class="page-inside" id="MainWrapper">
	    <div id="HeaderWrapper">
		<a href="common/home" class="logo" style="margin-top:-5px;"><img alt="<?php echo $this->config->get('config_name'); ?>" title="<?php echo $this->config->get('config_name'); ?>" src="image/data/logo.png"></a>
	    </div>
	    <div class="center" id="BodyWrapper">
		<div id="content"><br /><br /><br /><br /><?php echo $message; ?><br /><br /><br /><br /></div>
	    </div>
	</div>
    </body>
</html>