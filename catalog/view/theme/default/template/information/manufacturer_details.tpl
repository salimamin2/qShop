<ul class="products-grid" id="scroll-container">
    <?php if($manufacturer_info){ ?>
        <li>
            <?php if ($manufacturer_info['image']): ?>
                <div class="product-img">
                    <a href="<?php echo str_replace('&', '&amp;', $manufacturer_info['href']); ?>">
                        <img src="<?php echo $manufacturer_info['image']; ?>" alt="<?php echo $manufacturer_info['name']; ?>" />
                    </a>
                </div>
            <?php endif; ?>
            <div class="product-dtl">
                <div class="col-sm-6 right">
                    <div class="product-name">
                        <a href="<?php echo str_replace('&', '&amp;', $manufacturer_info['href']); ?>">
                            Meet<br /><?php echo html_entity_decode($manufacturer_info['name']); ?>
                        </a></div>
                    <div class="product-desc">
                        <a href="<?php echo str_replace('&', '&amp;', $manufacturer_info['href']); ?>">
                            Browse through some of<br/> <?php echo $manufacturer_info['name'] ;?>'s design<?php //echo html_entity_decode($manufacturer_info['description']); ?>&nbsp;<span></span>
                        </a>
                    </div>
                </div>

            </div>
        </li>
    <?php }else{ ?>
        <li> No record found! </li>
    <?php } ?>
</ul>