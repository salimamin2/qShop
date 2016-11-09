<?php foreach($blocks as $block): ?>
<?php if($block['status']): ?>
	<?php echo ($block['position'] == 'home' ? '<div class="banner banner-home">' : ''); ?>
    	<?php echo $block['desc']; ?>
    <?php echo ($block['position'] == 'home' ? '</div>' : ''); ?>
<?php endif; ?>
<?php endforeach; ?>

