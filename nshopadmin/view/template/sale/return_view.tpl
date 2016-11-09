<div class="box">
    <div class="head well">
        <h3><i class="icon-eye-open"></i> <?php echo $text_return_view; ?></h3>
    </div>
    <?php if ($error_warning) : ?>
        <div class="alert alert-danger"><?php echo $error_warning; ?></div>
    <?php endif; ?>
    <?php if ($success) : ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>        
    <div class="content">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#tab_return_details" data-toggle="tab">
                    <span><?php echo $text_return_details?></span>
                </a>
            </li>
            <li>
                <a href="#tab_history" data-toggle="tab">
                    <span><?php echo $text_history?></span>
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="tab_return_details" class="tab-pane active">
                <table class="form">
                    <tr>
                        <td><?php echo $text_return_id; ?></td>
                        <td>4</td>
                    </tr>
                    <tr>
                        <td><?php echo $text_order_id; ?></td>
                        <td>4</td>
                    </tr>
                    <tr>
                        <td><?php echo $text_order_date; ?></td>
                        <td>12/12/2013</td>
                    </tr>
                    <tr>
                        <td><?php echo $text_customer; ?></td>
                        <td>JAKE</td>
                    </tr>
                    <tr>
                        <td><?php echo $text_email; ?></td>
                        <td>JAKE@abc.com</td>
                    </tr>
                    <tr>
                        <td><?php echo $text_telephone; ?></td>
                        <td>1234567890</td>
                    </tr>
                    <tr>
                        <td><?php echo $text_return_status; ?></td>
                        <td>Awaiting products</td>
                    </tr>
                    <tr>
                        <td><?php echo $text_date_added; ?></td>
                        <td>12/12/2012</td>
                    </tr>
                    <tr>
                        <td><?php echo $text_date_modified; ?></td>
                        <td>12/12/2012</td>
                    </tr>
                </table>
                <br>
                 <div class="form">
                    <h3><?php echo $text_pinfo_reason;?></h3>
                    <hr>
                </div>
                <table class="form">
                    <tr>
                        <td><?php echo $text_return_id; ?></td>
                        <td>4</td>
                    </tr>
                    <tr>
                        <td><?php echo $text_order_id; ?></td>
                        <td>4</td>
                    </tr>
                    <tr>
                        <td><?php echo $text_order_date; ?></td>
                        <td>12/12/2013</td>
                    </tr>
                    <tr>
                        <td><?php echo $text_customer; ?></td>
                        <td>JAKE</td>
                    </tr>
                    <tr>
                        <td><?php echo $text_email; ?></td>
                        <td>JAKE@abc.com</td>
                    </tr>
                    <tr>
                        <td><?php echo $text_telephone; ?></td>
                        <td>1234567890</td>
                    </tr>
                    <tr>
                        <td><?php echo $text_return_status; ?></td>
                        <td>Awaiting products</td>
                    </tr>
                    <tr>
                        <td><?php echo $text_date_added; ?></td>
                        <td>12/12/2012</td>
                    </tr>
                    <tr>
                        <td><?php echo $text_date_modified; ?></td>
                        <td>12/12/2012</td>
                    </tr>
                </table>
            </div>
            <div id="tab_history" class="tab-pane active">
                <table class="form">
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>