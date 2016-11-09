<?php

// including environment of the site
	require_once ('init_app.php');
	Make::a('catalog/product_index')->create()->insertProducts();
	if(isset($_SERVER['HTTP_HOST'])){
		$registry->session->data['success'] = 'Successfully indexed products';
    	header('location: '.makeUrl('setting/setting'));
	}
?>
