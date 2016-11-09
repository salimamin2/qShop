<?php if (!$hybrid_auth): ?>
    <div class="item clearer account-social">
        <div class="social-links icon-wrapper-square">
	    <?php echo $text_social; ?>
	    <?php if (!empty($hybrid_social)): ?>
		<?php foreach ($hybrid_social as $social => $status): ?>
	    	<a href="<?php echo makeUrl('module/hybrid_auth/hauth', array('provider=' . $social), true); ?>" alt="<?php echo $social ?>" title="<?php echo $social ?>" class="<?php echo strtolower($social) ?>">
	    	    <span class="icon icon-hover i-<?php echo strtolower($social) ?>-w"><?php echo $social ?></span>
	    	</a>
		<?php endforeach; ?>
	    <?php endif; ?>
        </div>
    </div>
<?php endif; ?>