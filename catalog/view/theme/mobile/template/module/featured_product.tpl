<div class="special-sell">
	<div class="img">
		<a href="<?php echo $sUrl; ?>"><img src="<?php echo ($bkg_img ? $bkg_img : $image); ?>" alt="<?php echo $name; ?>"></a>
	</div>
	<div class="col-sm-12 col-md-5 right special-sell-box">
		<p><a href="<?php echo $sUrl; ?>"><?php echo $name; ?></a></p>
		<span class="designer">designed by <?php echo $manufacturer; ?></span>
		<div class="option">
			<div class="price-col">
				<div class="price-center">
					<?php if($special): ?>
						<span class="special-price"><?php echo $special; ?></span>
					<?php endif; ?>
					<span class="price"><?php echo $price; ?></span>
				</div>
			</div>
			<form method="post" action="<?php echo $cart_url; ?>">
				<input type="hidden" name="product_id" value="<?php echo $id; ?>" />
				<input type="hidden" name="quantity" value="1" />
				<button type="submit"><?php echo $order_product_button_text; ?></button>
			</form>
		</div>
		<div class="progress"><span class="bar"></span></div>
		<span class="out-of-stock"><?php echo $stock_status; ?></span>
	</div>
</div>