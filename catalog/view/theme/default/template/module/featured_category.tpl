<div class="category-promo">
	<div class="category row">
		<div class="col-sm-6">
			<div class="img"><img src="<?php echo $category['image']; ?>" alt="<?php echo $category['name']; ?>" /></div>
		</div>
		<div class="col-sm-6">
			<div class="category-title"><?php echo $category['name']; ?></div>
			<div class="category-desc"><?php echo $category['meta_description']; ?></div>
			<div class="category-link">
				<a href="<?php echo $category['href']; ?>" title="<?php echo ($title != '' ? $title : $category['name']); ?>"><?php echo $text; ?></a>
			</div>
		</div>
	</div>
</div>