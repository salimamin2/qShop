<?php if ($products) { ?>
<div class="module module-right featured_product">
    <h2><span class="left"><?php echo $heading_title; ?></span>
	<a class="right" href="<?php echo str_replace('&', '&amp;', $href_latest); ?>" class="link-latest"><?php echo __('SEE ALL'); ?></a>
	</h2>
    <div class="grd">
    <ul id="product_slider_1" class="list-product">
    <?php $i = 0; foreach ($products as $product) { ?>
        <li <?php echo ($i == 0) ? 'class="first"' : ''; echo (count($products)-1 == $i) ? 'class="last"' : ''; ?>>
            <?php if($product['image']): ?>
                <div class="product-img">
                <a href="<?php echo str_replace('&', '&amp;', $product['href']); ?>">
                    <?php if ($product['special']) : ?><div class="icon-sale"></div><?php endif; ?>
                    <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" />
                </a>
                </div>
            <?php endif; ?>
            <div class="product-dtl">
                <div class="product-name"><a href="<?php echo str_replace('&', '&amp;', $product['href']); ?>"><?php echo $product['name']; ?></a></div>
				<div class="product-desc"><?php echo substr( $product['description'],0,120); ?>...</div>
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
                <?php $act = ($product['options']) ? $product['href'] : $action; ?>
                <form action="<?php echo str_replace('&', '&amp;', $act); ?>" name="latest_product_<?php echo $i ?>" method="post" enctype="multipart/form-data" id="latest_product_<?php echo $i ?>">
                    <input type="hidden" name="quantity" value="1" />
                    <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>" />
                    <input type="hidden" name="redirect" value="<?php echo str_replace('&', '&amp;', $product['href']); ?>" />
                    <?php /* ?><a onclick="$('#latest_product_<?php echo $i ?>').submit();" href="javascript:void(0)" class="button button button_cart"><span><span class="icon-cart"><?php echo $button_add_to_cart; ?></span></span></a><?php */ ?>
                </form>
			</div>
        </li>
    <?php $i++; } ?>
    </ul>
    </div>
	<div class="bottom"><span></span></div>
	<div class="clr"></div>
</div>
<?php } ?>