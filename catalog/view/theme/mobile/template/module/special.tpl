<?php if ($products) { ?>
<div class="module module-right red">
    <h2><span><span><?php echo $heading_title; ?></span></span></h2>
    <div class="grd">
    <ul class="product-list">
    <?php $i = 0; foreach ($products as $product) { ?>
        <li>
            <?php if($product['image']): ?>
                <div class="product-img"><a href="<?php echo str_replace('&', '&amp;', $product['href']); ?>" title="<?php echo $product['meta_link'] ?>" alt="<?php echo $product['meta_link'] ?>"><div class="sale"></div><img src="<?php echo $product['image']; ?>" title="<?php echo $product['alt_title'] ?>" alt="<?php echo $product['alt_title'] ?>"/></a></div>
            <?php endif; ?>
            <div class="product-dtl">
                <div class="product-name"><a href="<?php echo str_replace('&', '&amp;', $product['href']); ?>" title="<?php echo $product['meta_link'] ?>" alt="<?php echo $product['meta_link'] ?>"><?php echo $product['name']; ?></a></div>
                <?php if ($display_price) { ?>
                    <div class="product-price">
                    <?php echo __('text_price') ?>
                    <?php if (!$product['special']) { ?>
                        <?php echo $product['price']; ?>
                    <?php } else { ?>
                        <span class="old-price"><?php echo $product['price']; ?></span>
                        <span class="special-price"><?php echo $product['special']; ?></span>
                    <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </li>
    <?php $i++; } ?>
    </ul>
    </div>
    <div class="bottom"><span></span></div>
</div>
<?php } ?>