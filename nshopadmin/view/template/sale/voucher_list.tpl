<div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
</div>
<div class="box">
    <div class="head well">
        <h3>
            <i class="icon-th-list"></i> <?php echo $heading_title; ?>
            <div class="pull-right">
                <a onclick="$('#form').submit();" class="btn btn-success"><?php echo $button_insert; ?></a>
                <a onclick="document.getElementById('form').submit();" class="btn btn-default"><?php echo $button_delete; ?></a>
            </div>
        </h3>
    </div>
    <?php if ($error_warning) { ?>
        <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    <?php if ($success) { ?>
        <div class="success"><h4 style="color: green"><?php echo $success; ?></h4></div>
    <?php } ?>
    <div class="content">
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
            <table class="table table-striped table-bordered" data-rel="data-grid">
                <thead>
                <tr>
                    <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
                    <td class="left" style="width: 17%"><?php echo $column_code; ?></td>
                    <td class="left"><?php echo $column_from; ?></td>
                    <td class="left"><?php echo $column_to; ?></td>
                    <td class="right" style="width: 12%"><?php echo $column_amount; ?></td>
                    <td class="left" style="width: 10%"><?php echo $column_theme; ?></td>
                    <td class="left" style="width: 7%"><?php echo $column_status; ?></td>
                    <td class="left"><?php echo $column_created_on; ?></td>
                    <td class="right" style="width: 10%"><?php echo $column_action; ?></td>
                </tr>
                </thead>
                <tbody>
                <?php if ($vouchers) { ?>
                <?php foreach ($vouchers as $voucher) { ?>
                <tr>
                    <td style="text-align: center;"><?php if ($voucher['selected']) { ?>
                        <input type="checkbox" name="selected[]" value="<?php echo $voucher['voucher_id']; ?>" checked="checked" />
                        <?php } else { ?>
                        <input type="checkbox" name="selected[]" value="<?php echo $voucher['voucher_id']; ?>" />
                        <?php } ?></td>
                    <td class="left" style="width: 10%"><?php echo $voucher['code']; ?></td>
                    <td class="left"><?php echo $voucher['from']; ?></td>
                    <td class="left"><?php echo $voucher['to']; ?></td>
                    <td class="right"><?php echo $voucher['amount']; ?></td>
                    <td class="left"><?php echo $voucher['theme']; ?></td>
                    <td class="left"><?php echo $voucher['status']; ?></td>
                    <td class="left"><?php echo $voucher['created_on']; ?></td>
                    <td class="right">
                        <a class="btn btn-success btn-xs" data-toggle="tooltip" onclick="sendVoucher('<?php echo $voucher['voucher_id']; ?>');" title="<?php echo $text_send; ?>"><i class="icon-share-alt"></i></a> <?php foreach ($voucher['action'] as $action) { ?>
                        <a class="btn <?php echo $action['class'] ?> btn-xs" data-toggle="tooltip" href="<?php echo $action['href']; ?>" title="<?php echo $action['text']; ?>"><i class="<?php echo $action['icon']; ?>"></i></a>
                        <?php } ?></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                    <td class="center" colspan="9"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
                </tbody>
            </table>
        </form>
       <?php /* <div class="pagination"><?php echo $pagination; ?></div> */ ?>
    </div>
</div>
<script type="text/javascript"><!--
    function sendVoucher(voucher_id) {
        $.ajax({
            url: 'index.php?route=sale/voucher/send&token=<?php echo $token; ?>&voucher_id=' + voucher_id,
            type: 'post',
            dataType: 'json',
            beforeSend: function() {
                $('.success, .warning').remove();
                $('.box').before('<div class="attention"><img src="view/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
            },
            complete: function() {
                $('.attention').remove();
            },
            success: function(json) {
                if (json['error']) {
                    $('.box').before('<div class="warning">' + json['error'] + '</div>');
                }

                if (json['success']) {
                    $('.box').before('<div class="success">' + json['success'] + '</div>');
                }
            }
        });
    }
    //--></script>
