<?php if ($frontss_status): ?>
	<?php /*<div id="slideshow" class="slides" style="display: none;">
		<?php foreach ($frontss as $i => $front): // href="<?php echo $front['link']; ?>
			<div class="item slide">
				<a class="fade-on-slideshow-hover">
					<img src="<?php echo $image_url . $front['image']; ?>" title="Clovebuy" alt="Clovebuy" />
				</a>
			</div>
		<?php endforeach; ?>
	</div>*/ ?>
	<div class="slides slider-wrapper theme-default">
		<div id="slider" class="nivoSlider">
			<?php foreach ($frontss as $i => $front): // href="<?php echo $front['link']; ?>
				<img src="<?php echo $image_url . $front['image']; ?>" alt="Clovebuy" />
			<?php endforeach; ?>
		</div>
	</div>

	<script type="text/javascript" src="catalog/view/javascript/jquery/jquery-1.7.1.min.js"></script>
	<script src="catalog/view/javascript/jquery/jquery.nivo.slider.js"></script>
	<?php /*<script src="catalog/view/javascript/jquery/jquery.nivo.slider.pack.js"></script>*/ ?>
	<script type="text/javascript">
		jQuery(window).load(function(){
			jQuery('#slider').nivoSlider({
				effect: 'slideInLeft',
				controlNav: false
			});
		});
	</script>

<?php endif; ?>