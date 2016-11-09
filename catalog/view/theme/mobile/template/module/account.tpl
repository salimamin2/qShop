<?php if($is_log) : ?>

<div id="ColumnLeft">
    <div class="col-left sidebar grid12-3 grid-col2-sidebar in-sidebar">
        <div class="block block-account">

           <div class="text-center">
                <p class="section-title-account">
                    <span class="background-account">
                        <span class="border-account">My Account</span>
                    </span>
                </p>
            </div>
            
           <!--  <div class="block-content">
                <ul>
                    <li class="current"><a href="<?php echo str_replace('&', '&amp;', $detail); ?>"><?php echo $text_detail; ?></a></li>
                    <li><a href="<?php echo str_replace('&', '&amp;', $information); ?>"><?php echo $text_information; ?></a></li>
                    <li><a href="<?php echo str_replace('&', '&amp;', $ship_address); ?>"><?php echo $text_ship_address; ?></a></li>
                    <li><a href="<?php echo str_replace('&', '&amp;', $order_history); ?>"><?php echo $text_order_history; ?></a></li>
                    <li><a href="<?php echo str_replace('&', '&amp;', $wishlist); ?>"><?php echo $text_wishlist; ?></a></li>
                    <?php if($reward): ?>
                        <li><a href="<?php echo str_replace('&', '&amp;', $reward); ?>"><?php echo $text_reward; ?></a></li>
                    <?php endif; ?>
                    <li><a href="<?php echo str_replace('&', '&amp;', $logout); ?>"><?php echo $text_logout; ?></a></li>
                </ul>
            </div> -->
        </div>
    </div>
</div>

<?php endif; ?>