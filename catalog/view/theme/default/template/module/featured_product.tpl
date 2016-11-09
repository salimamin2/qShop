<div class="special-sell">
	<div class="img">
		<img src="<?php echo ($bkg_img ? $bkg_img : $image); ?>" alt="<?php echo $name; ?>">
	</div>
	<div class="col-sm-5 right special-sell-box">
		<p><a href="<?php echo $sUrl; ?>"><?php echo $name; ?></a></p>
		<span class="designer">designed by <?php echo $manufacturer; ?></span>
		<span class="price-col">
			<?php if($special): ?>
				<span class="special-price price"><?php echo $special; ?></span>
				<span class="old-price"><?php echo $price; ?></span>
			<?php else : ?>
				<span class="price"><?php echo $price; ?></span>
			<?php endif; ?>
		</span>
		<form method="post" action="<?php echo $cart_url; ?>">
			<input type="hidden" name="product_id" value="<?php echo $id; ?>" />
			<input type="hidden" name="quantity" value="1" />
			<button type="submit"><?php echo $order_product_button_text; ?></button>
		</form>
		<div class="progress"><span class="bar"></span></div>
		<span class="out-of-stock"><?php echo $stock_status; ?></span>
	</div>
</div>