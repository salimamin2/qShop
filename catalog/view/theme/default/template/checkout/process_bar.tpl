<div id="ColumnLeft" class="process_bar">
    <!--<img src="<?php echo $image ?>tab_<?php echo $current_page; ?>.jpg" alt="Checkout Status" />-->
    <div class="module module-left red">
        <h2><span><span><?php echo __('CHECKOUT'); ?></span></span></h2>
        <div id="account" class="links">
            <ul>
            <?php foreach($links as $link): ?>
                <li class="<?php echo $link['class']; ?>"><a href="<?php echo $link['href']; ?>"><?php echo $link['name']; ?></a></li>
            <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>