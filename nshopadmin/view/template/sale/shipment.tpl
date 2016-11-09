<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" >
<input type="hidden" class="form-control" name="directory" value="<?php echo $directory; ?>" />
<div class="box table-wrapper products-table section">
    <div class="head well">
        <h3>
            <i class="icon-th-list"></i> <?php echo $heading_title; ?>
        </h3>
    </div>

    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><?php echo $error_warning; ?></div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
    <?php } ?>
    <div class="content">
        <div class="table-responsive">

            <table class="table table-hover" data-rel="data-grid">

                <thead>
                <tr>

                    <th width="1" style="text-align: center;"> <input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></th>

                    <th class="left"><?php echo $column_order; ?></th>

                    <th class="left"><?php echo $column_name; ?></th>

                    <th class="left"><?php echo $column_status; ?></th>

                    <th class="left"><?php echo $column_date_added; ?></th>

                    <th class="left"><?php echo $column_total; ?></th>

                    <th class="left"><span class="line"></span><?php echo $column_action; ?></th>

                </tr>

                </thead>

                <tbody>



                <?php if ($shipments) { ?>

                <?php foreach ($shipments as $order) { ?>

                <tr>

                    <td style="text-align: center;"><?php if ($order['selected']) { ?>

                        <input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" checked="checked" />

                        <?php } else { ?>

                        <input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" />

                        <?php } ?></td>

                    <td class="right"><?php echo $order['order_id']; ?></td>

                    <td class="left"><?php echo $order['name']; ?></td>

                    <td class="left"><?php echo $order['status']; ?></td>

                    <td class="left"><?php echo $order['date_added']; ?></td>

                    <td class="right"><?php echo $order['total']; ?></td>

                    <td class="right btn-group">
                        <button type="button" class="btn btn-info btn-sm order_shipment" data-order-id="<?php echo $order['order_id']; ?>" title="Shipment"><i class="icon-shopping-cart"></i></button>
                        <a class="btn btn-warning btn-sm" href="<?php echo $link_details.'&order_id='.$order['order_id'] ?>" title="Details">
                            <i class="icon-eye-open"></i>
                        </a>
                    </td>

                </tr>

                <?php } ?>

                <?php } else { ?>

                <tr>

                    <td class="center" colspan="7"><?php echo $text_no_results; ?></td>

                </tr>

                <?php } ?>

                </tbody>

            </table>
        </div>

    </div>

</div>
    <div id="myModal" class="modal fade" tabindex="-1" aria-labelledby="myLargeModalLabel" role="dialog">
        <div class="modal-dialog" id="mdialog">
            <div class="modal-content" id="mcontent">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?php echo __('entry_order_ship'); ?></h4>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $button_cancel; ?></button>
                    <button type="button" class="btn btn-primary" id="save_shippments"><?php echo $button_save; ?></button>
                </div>
            </div>
        </div>
    </div>
</form>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.datepicker.js"></script>
<script type="text/javascript"><!--
    jQuery(document).on('click','.order_shipment',function(){
        jQuery('.modal-body').empty();
        var order_id=$(this).attr('data-order-id');
        $.ajax({
            type: 'POST',
            url: 'sale/shipment/getOrderProducts',
            data: 'order_id='+order_id,
            dataType: 'json',
            success: function(res){
                if (res.options != undefined) {
                    $("#myModal").modal('show');
                    var result = res.options;
                    console.log(result);
                    html='<input type="hidden" id="ship_order" name="ship_order" value="'+order_id+ '">';
                    html +='<b>ORDER ID:</b>'+order_id;
                    html +='<table class="table table-hover" id="order_products_table">';
                    html += '<thead>';
                    html += '<tr>';
                    html += '<th>Select</th>';
                    html += '<th>Product ID</th>';
                    html += '<th>Name</th>';
                    html += '<th>Qty</th>';
                    html += '<th>Qty Shipped</th>';
                    html += '<th>Qty for ship</th>';
                    html += '</tr>';
                    html += '</thead>';
                    html +='<tbody>';
                    for (i = 0; i < result.length; i++) {
                        var disabled = '';
                        if (result[i].quantity_remaining == 0) {
                            disabled = 'disabled';
                        }
                        html += '<tr>';
                        html += '<td><input type="checkbox" '+disabled+' name="shipment_selected" id="shipment_selected" value=' + result[i].id + '></td>';
                        html += '<td>' + result[i].id + ' </td>';
                        html += '<td>' + result[i].name + ' </td>';
                        html += '<td>' + result[i].quantity_ordered + ' </td>';
                        html += '<td>' + result[i].quantity_shipped + ' </td>';
                        html += '<td><input type="number" id="Qty" '+disabled+' min="1" max='+ result[i].quantity_remaining +' class="form-control" name="shipment_quantity[]" value=' + result[i].quantity_remaining + '></td>';
                        html += '</tr>';
                    }
                    html += '</tbody>';
                    html += '</table>';
                    html += '<b>Tracking # :</b><input type="text" class="form-control" name="shipment_tracking">';
                    html += '<b>Shipment Date :</b><input type="date" data-provide="datepicker-inline" class="form-control" name="shipment_date">';
                    html += '<b>Comments :</b><textarea class="form-control" name="shipment_comments"/>';
                    jQuery('.modal-body').append(html);
                }

            }
        });
    });
    jQuery(document).on('click','#save_shippments' ,function(){
        var ProductID = [];
        var quantity = [];
        jQuery("input:checkbox[name=shipment_selected]:checked").each(function(){
            var txtValue=$(this).closest("tr").find("input[type=number]").val();
            ProductID.push(jQuery(this).val());
            quantity.push(txtValue);
        });
            $.ajax({
                type: 'POST',
                url: 'sale/shipment/addPartialShipments',
                data: 'OrderID=' + $('#ship_order').val() + '&ProductID=' + ProductID + '&quantity=' + quantity + '&' + jQuery('#form').serialize(),
                dataType: 'json',
                success: function (res) {
                    if (res.success != undefined) {
                        alert(res.success);
                        location.reload();
                    }

                }
            });

    });

    jQuery(document).on('change','#Qty' ,function(){
            var max = parseInt($(this).attr('max'));
            var min = parseInt($(this).attr('min'));
            if ($(this).val() > max)
            {
                $(this).val(max);
            }
            else if ($(this).val() < min)
            {
                $(this).val(min);
            }
        });


    //--></script>

