<?php /* echo $this->load('module/account'); */ ?>
<div id="Content" class="col-main grid12-9 grid-col2-main in-col2">
    <div class="my-account">
        <?php if ($orders): ?>
            <table class="data-table">
                <colgroup>
                    <col width="1">
                    <col width="1">
                    <col>
                    <col width="1">
                    <col width="1">
                    <col width="1">
                    <col width="1">
                </colgroup>
                <thead>
                    <tr class="first last">
                        <th style="width: 10%;"><?php echo __('Order #'); ?></th>
                        <th style="width: 10%;"><?php echo __('Date'); ?></th>
                        <th style="width: 20%;"><?php echo __("Ship To"); ?></th>
                        <th style="width: 10%;"><?php echo __('Total Products'); ?></th>
                        <th style="width: 10%"><?php echo __('Order Total'); ?></th>
                        <th style="width: 10%;"><?php echo __('Order Status'); ?></th>
                        <th style="width: 15%;">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                      $length = count($orders);
                ?>
                <?php foreach ($orders as $i => $order) { ?>
                <?php $class = ($i % 2 == 0 ? 'odd' : 'even');
                      $class .= ($i == 0 ? ' first' : '');
                      $class .= ($i == ($length-1) ? ' last' : ''); ?>
                    <tr class="<?php echo $class; ?>">
                        <td><?php echo $order['order_id']; ?></td>
                        <td><span class="nobr"><?php echo $order['date_added']; ?></span></td>
                        <td><?php echo $order['name']; ?></td>
                        <td><span class="nobr"><?php echo $order['products']; ?></span></td>
                        <td><span class="price"><?php echo $order['total']; ?></span></td>
                        <td><em><?php echo $order['status']; ?></em></td>
                        <td class="a-center last">
                            <span class="nobr">
                                <a href="<?php echo str_replace('&', '&amp;', $order['href']); ?>"><?php echo $button_view; ?></a>
                            </span>
                           
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="if-orders">
                <img src="catalog/view/theme/default/image/img/emotion-cart.png">
                <p><?php echo __('You’ve not previously ordered anything as a registered user.'); ?></p>
                <a href="<?php echo $continue ?>" class="btn btn-cntinue"><?php echo __('Continue Shopping'); ?></a>
            </div>
        <?php endif; ?>
    </div>
</div>