<div id="Content" class="col-md-9" style="margin:0px auto; float:none;">
    <div class="success-main">

        <p><img src="catalog/view/theme/default/image/success-truck.jpg" alt="Checkout Success"/></p>

        <div class="checkout-success page-title"><h1><?php echo $heading_title; ?></h1></div>
        
        <?php echo $text_message; ?>
        <br />
        
        <?php
            if (isset($order_id) && $order_id && $this->request->get['decision'] == 'ACCEPT') {
	           echo $this->load('account/invoice', array('order_id' => $order_id, 'module' => 1));
            }
        ?>

        <?php if (isset($checkout_invoice) && $checkout_invoice == true): ?>


            <div class="table-responsive" id="cart">
                <table class="cart table table-striped">
                    <thead>
                        <tr>
                            <th align="center"><?php echo $text_product; ?></th>
                            <?php if(isset($text_model)){ ?>
                                <th align="center"><?php echo $text_model; ?></th>
                            <?php } ?>
                            <th align="center"><?php echo $text_quantity; ?></th>
                            <th align="center" width="15%"><?php echo $text_price; ?></th>
                            <th align="center" width="18%"><?php echo $text_total; ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product) { ?>
                            <tr>
                                <td align="left" valign="top">
                                    <div class="col-xs-4 col-sm-4 col-md-4">
                                        <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" />
                                    </div>
                                    <div class="col-xs-8 col-sm-8 col-md-8">
                                        <div class="cartwidth">
                                            <?php echo $product['name']; ?>
                                            <?php foreach ($product['detail'] as $option) { ?>
                                                <br />
                                                <small> - <strong><?php echo $option['name']; ?></strong> <?php echo $option['value']; ?></small>
                                            <?php } ?>
                                            <?php foreach ($product['option'] as $option) { ?>
                                                <br />
                                                <small> - <strong><?php echo $option['name']; ?></strong> <?php echo $option['value']; ?></small>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </td>
                                <?php if(isset($text_model)){ ?>
                                <td align="center" valign="top"><?php echo $product['model']; ?></td>
                                <?php } ?>
                                <td align="center" valign="top"><?php echo $product['quantity']; ?></td>
                                <td align="center" valign="top"><?php echo $product['price']; ?></td>
                                <td align="center" valign="top"><?php echo $product['total']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            
            <div class="totals">
                <div class="col-md-12">
                    <div class="row">
                        <?php foreach ($totals as $total) { ?>
                            <div class="col-xs-6 col-sm-9 col-md-9 text-right <?php echo $total['key'] ?> sum_price"><?php echo $total['title']; ?></div>
                            <div class="col-xs-6 col-sm-3 col-md-3 text-right sum_price"><?php echo $total['text']; ?></div>
                        
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="col-md-12">
	       <button onclick="location = '<?php echo str_replace('&', '&amp;', $continue); ?>'" class="button btn-checkout">
                <span>
                    <span><?php echo $button_continue; ?></span>
                </span>
            </button>
        </div>

        <script type="text/javascript"><!--
            <?php if ($route == 'checkout/success'): ?>
                $(document).ready(function() {
                    ga('require', 'ecommerce', 'ecommerce.js');

                <?php if (isset($order_id) && $order_id) : ?>
                    ga('ecommerce:addTransaction', {
                        'id': '<?php echo $order_id ?>',
                        'affiliation': '<?php echo $this->config->get('config_name') ?>',
                        'revenue': '<?php echo $iTotal ?>',
                        'shipping': '<?php echo $shipping ?>',
                        'tax': '<?php echo $tax ?>'
                    });

                    <?php foreach ($products as $aProduct): ?>
                        ga('ecommerce:addItem', {
                            'id': '<?php echo $order_id ?>',
                            'name': '<?php echo $aProduct['name'] ?>',
                            'sku': '<?php echo $aProduct['model'] ?>',
                            'category': '',
                            'price': '<?php echo $aProduct['vprice'] ?>',
                            'quantity': '<?php echo $aProduct['quantity'] ?>'
                        });
                    <?php endforeach; ?>
                <?php endif; ?>

                ga('ecommerce:send');
                });
            <?php endif; ?>
    //--></script>
    </div>
</div>