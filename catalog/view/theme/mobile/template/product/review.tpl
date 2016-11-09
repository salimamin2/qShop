<?php if ($reviews) { ?>
    <?php foreach ($reviews as $i => $review) { ?>
<div class="review-container">
        <strong class="left"><?php echo $review['author']; ?></strong>
        <img src="catalog/view/theme/<?php echo $template ?>/image/stars_<?php echo $review['rating'] . '.png'; ?>" alt="<?php echo $review['stars']; ?>" class="right" />
        <div class="clear"></div>
        <p class="review-txt"><?php echo $review['text']; ?></p>
        <div class="clear"></div>
</div>
    <?php } ?>
    <?php if($pagination): ?>
    <div class="review-pagination">
        <div class="pagination"><?php echo $pagination; ?></div>
        <div class="clear"></div>
    </div>
    <?php endif; ?>
<?php } else { ?>
<div class="review-container"><?php echo $text_no_reviews; ?></div>
<?php } ?>