<?php if ($frontss_status): ?>
    <div id="slideshow" class="slides" style="display: none;">
	<?php foreach ($frontss as $i => $front): //href="<?php echo $front['link']; ?>
	    <div class="item slide">
		<a class="fade-on-slideshow-hover">
		    <img src="<?php echo $image_url . $front['image']; ?>" title="Clovebuy" alt="Clovebuy" />
		</a>             
	    </div>
	<?php endforeach; ?>
    </div>
    
<?php endif; ?>