<?php if ( $information_page['text'] ) { ?>
	<div class="did_you_know">
		<div class="close-icon"><span>&nbsp;</span></div>
		<div class="block block-tags" id="testimonial">
			<p>DID YOU KNOW</p>
			<div class="block-content sample-block">
				<h3><?php echo $information_page['text']; ?></h3>
				<div class="actionsd">
				<?php if($information_page['url']): ?>
					<a href="<?php echo str_replace('&', '&amp;', $information_page['url']); ?>">
						<?php echo $text_more; ?>
					</a>
				<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		jQuery(".close-icon span").click(function(){
			//jQuery(".did_you_know").hide("slow");
			var know_height = jQuery('.did_you_know').height();
			jQuery(".did_you_know").slideUp();
			// jQuery(".did_you_know").animate({
			// 	opacity: 0,
			// }, 1000, function() {

			// 	// Animation complete.
			// });
		})
	</script>
<?php } ?>