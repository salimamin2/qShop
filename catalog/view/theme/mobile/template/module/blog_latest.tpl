<?php if($blogs): ?>
	<?php foreach($blogs as $blog): ?>
		<div class="<?php echo $class; ?>">
			<div class="blog-post">
				<a href="<?php echo $blog['href']; ?>" >
					<img class="blog-image" src="<?php echo $blog['image']; ?>" alt="<?php echo $blog['alt_title']; ?>" />
				</a>
				<h3 class="blog-title">
					<a href="<?php echo $blog['href']; ?>" >
						<?php echo $blog['title']; ?>
					</a>
				</h3>
			</div>
		</div>
	<?php endforeach; ?>
<?php endif; ?>