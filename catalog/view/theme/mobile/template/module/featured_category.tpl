<div class="category-promo">
	<div class="category row">
		<div class="col-sm-12">
			<a href="<?php echo $category['href']; ?>" title="<?php echo ($title != '' ? $title : $category['name']); ?>"><img src="<?php echo $category['image']; ?>" alt="<?php echo $category['name']; ?>" /></a>
		</div>
		<div class="col-sm-12 category-box">
			<div class="category-title"><?php echo $category['name']; ?></div>
			<div class="category-desc"><?php echo $category['meta_description']; ?></div>
			<div class="category-link">
				<a href="<?php echo $category['href']; ?>" title="<?php echo ($title != '' ? $title : $category['name']); ?>">
					<?php echo $text; ?>&nbsp;<span></span>
				</a>
			</div>
		</div>
	</div>
</div>