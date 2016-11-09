<div class="col-main grid-full in-col1">
    <div class="page-sitemap">
	<div class="page-title">
	    <h1><?php echo $heading_title; ?></h1>
	</div>
	<div id="sitemap_top_links">
	    <ul class="links">
		<?php if ($product_link): ?>
    		<li class="first"><a href="<?php echo $product_link ?>" title="Products Sitemap">Products Sitemap</a></li>
		<?php endif; ?>
		<?php if ($category_link): ?>
    		<li><a href="<?php echo $category_link ?>" title="Category Sitemap">Category Sitemap</a></li>
		<?php endif; ?>
		<?php if ($page_link): ?>
    		<li class="last"><a href="<?php echo $page_link ?>" title="Pages Sitemap">Pages Sitemap</a></li>
		<?php endif; ?>

	    </ul>
	</div>
	<div class="pager">
            <p class="amount">
		Items <?php echo $start; ?> to <?php echo $limit ?> of <?php echo $total ?> total         
	    </p>
	    <?php if ($pagination): ?>
    	    <div class="pages gen-direction-arrows1">
    		<strong>Page:</strong>
		    <?php echo $pagination; ?>
    	    </div>
	    <?php endif; ?>
        </div>
	<?php if ($categories): ?>
    	<ul class="sitemap">
		<?php echo $categories ?>
    	</ul>
	<?php endif; ?>
	<?php if ($products): ?>
    	<ul class="sitemap">
		<?php foreach ($products as $aProduct): ?>
		    <li>
			<?php $metalink = QS::getMetaLink($aProduct['meta_link'], $aProduct['name']); ?>
			<a href="<?php echo makeUrl('product/product', array('product_id=' . $aProduct['product_id']), true) ?>" alt="<?php echo $metalink ?>" title="<?php echo $metalink ?>">
			    <?php echo $aProduct['name']; ?>
			</a>
		    </li>
		<?php endforeach; ?>
    	</ul>
	<?php endif; ?>
	<?php if ($informations): ?>
    	<ul class="sitemap">
		<?php foreach ($informations as $aInfo): ?>
		    <li>
			<?php $metalink = QS::getMetaLink($aInfo['meta_link'], $aInfo['title']); ?>
			<a href="<?php echo makeUrl('information/information', array('information_id=' . $aInfo['information_id']), true) ?>" alt="<?php echo $metalink ?>" title="<?php echo $metalink ?>">
			    <?php echo $aInfo['title']; ?>
			</a>
		    </li>
		<?php endforeach; ?>
    	</ul>
	<?php endif; ?>
	<div id="sitemap_top_links">
	    <ul class="links">
		<?php if ($product_link): ?>
    		<li class="first"><a href="<?php echo $product_link ?>" title="Products Sitemap">Products Sitemap</a></li>
		<?php endif; ?>
		<?php if ($category_link): ?>
    		<li><a href="<?php echo $category_link ?>" title="Category Sitemap">Category Sitemap</a></li>
		<?php endif; ?>
		<?php if ($page_link): ?>
    		<li class="last"><a href="<?php echo $page_link ?>" title="Pages Sitemap">Pages Sitemap</a></li>
		<?php endif; ?>
	    </ul>
	</div>
	<div class="pager">
            <p class="amount">
		Items <?php echo $start; ?> to <?php echo $limit ?> of <?php echo $total ?> total         
	    </p>
	    <?php if ($pagination): ?>
    	    <div class="pages gen-direction-arrows1">
    		<strong>Page:</strong>
		    <?php echo $pagination; ?>
    	    </div>
	    <?php endif; ?>
        </div>
    </div>
</div>