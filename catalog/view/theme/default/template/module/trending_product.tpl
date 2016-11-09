<div class="pomotion-block">
	<div class="promotion-box">
		<div class="image"><img src="<?php echo $image; ?>" alt="<?php echo $name; ?>" /></div>
		<div class="content">
			<h4>Now Trending</h4>
			<span class="product-title"><a href="<?php echo $sUrl; ?>"><?php echo $name; ?></a></span>
			<span class="product-link">Buy now for <?php echo $price; ?></span>
			<?php if($purchased_today): ?>
				<span class="purchase"><?php echo $purchased_today; ?></span>
			<?php endif; ?>
		</div>
	</div>
</div>