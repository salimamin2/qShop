<?php echo $this->load('module/account'); ?>
<div id="Content" class="col-main grid12-9 grid-col2-main in-col2">
    <div class="my-account">
        <div class="page-title title-buttons">
            <h1><?php echo $heading_title; ?></h1>
            <button type="button" onclick="location.href='<?php echo str_replace('&', '&amp;', $insert); ?>'" class="button"><span><span><?php echo __("Add New Address"); ?></span></span></button>
        </div>
        <ul class="messages">
            <?php if ($success) { ?>
            <li class="success-msg">
                <ul>
                    <li><?php echo $success; ?></li>
                </ul>
            </li>
            <?php } ?>
            <?php if ($error_warning) { ?>
            <li class="error-msg">
                <ul>
                    <li><?php echo $error_warning; ?></li>
                </ul>
            </li>
            <?php } ?>
        </ul>

        <h2><?php echo __('Address Entries'); ?></h2>
        <?php foreach($addresses as $i => $address):
            $col = ($i % 2 == 0 ? 1 : 0);
            ?>
        <?php if($col): ?>
        <div class="col2-set addresses-list">
        <?php endif; ?>
            <div class="<?php echo ($col ? 'col-1' : 'col-2'); ?> addresses-additional">
                <ol>
                    <li class="item">
                        <address>
                            <?php echo $address['address']; ?>
                        </address>
                        <p>
                            <a href="<?php echo str_replace('&', '&amp;', $address['update']); ?>"><?php echo __('Edit Address'); ?></a>
                            <span class="separator">|</span>
                            <a href="<?php echo str_replace('&', '&amp;', $address['delete']); ?>"><?php echo __('Delete Address'); ?></a>
                        </p>
                    </li>
                </ol>
            </div>
            <?php if(!$col): ?>
        </div>
        <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>