<?php if ($manufacturers) : ?>
    <!--<h1 class="section-title padding-right"><span><?php echo $heading_title; ?></span></h1>-->
    <div class="itemslider-wrapper brand-slider-wrapper slider-arrows1 slider-arrows1-pos-top-right slider-pagination1 slider-pagination1-centered">
        <div id="itemslider-brands" class="itemslider itemslider-responsive brand-slider">
	    <?php $i = 1;
	    foreach ($manufacturers as $manufacturer) :

		?>
		<div class="item">
		    <img class="lazyOwl" src="<?php echo $manufacturer['image']; ?>" alt="<?php echo $manufacturer['name']; ?>" title="<?php echo $manufacturer['name']; ?>" />
        </div>
    <?php endforeach; ?>

        </div> <!-- end: itemslider -->
    </div> <!-- end: itemslider-wrapper -->
    <script type="text/javascript">
    //<![CDATA[
        jQuery(function($) {

    	var owl = $('#itemslider-brands');
    	owl.owlCarousel({
    	    lazyLoad: true,
    	    itemsCustom: [[0, 1], [320, 2], [480, 2], [768, 3], [960, 4], [1280, 4]],
    	    responsiveRefreshRate: 50,
    	    slideSpeed: 200,
    	    paginationSpeed: 500,
    	    autoPlay: 6000,
    	    stopOnHover: true,
    	    rewindNav: true,
    	    rewindSpeed: 600,
    	    pagination: true,
    	    paginationSpeed: 600,
   		    navigation: true

    	}); //end: owl

        });
    //]]>
    </script>
<?php endif; ?>
