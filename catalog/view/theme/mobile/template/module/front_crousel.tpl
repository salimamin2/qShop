<script src="catalog/view/javascript/jquery/jcarousellite_1.0.1.js" type="text/javascript"></script>
<script type="text/javascript">
$(function() {
    $("#slides").jCarouselLite({
        auto: 800,
        speed: 2000,
        visible: 4,
        scroll:1,
        pause:1000
    });
});

</script>
<div id="slides">
    <ul>
    <?php foreach ($frontc as $front) : ?>
    <li>
        <?php if ($front['link_value']) : ?><a href="<?php echo $front['link_value'] ?>"><?php endif; ?>
            <img src="image/<?php echo $front['image']; ?>" />
        <?php if ($front['link_value']) : ?></a><?php endif; ?>
    </li>
    <?php endforeach; ?>
    </ul>
    <div class="clear"></div>
</div>