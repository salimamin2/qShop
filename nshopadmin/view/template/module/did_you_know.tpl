<div class="box">
    <div class="left"></div>
    <div class="right"></div>
    <div class="head well">
        <h3 style="background-image: url('view/image/module.png');"><?php echo $heading_title; ?>
            <div class="pull-right">
                <div class="buttons">
                    <button  type="button" id="add_option" class="btn-flat btn-sm btn-warning"><span><?php echo __('Add'); ?></span></button>
                    <a onclick="$('#form').submit();" class="btn-flat btn-success btn-sm"><span><?php echo $button_save; ?></span></a>
                    <a onclick="location = '<?php echo $cancel; ?>';" class="btn-flat btn-default btn-sm"><span><?php echo $button_cancel; ?></span></a>
                </div>
            </div>
        </h3>
    </div>

            
<?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php } ?>
    <div class="content">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
            <table class="form">
                <tbody>
                <tr>
                    <td>Status</td>
                    <td><div class="ui-select">
                            <select name="know_status">
                                <?php if ($know_status) { ?>
                                <option value="1" selected="selected">Enabled</option>
                                <option value="0">Disabled</option>
                                <?php } else { ?>
                                <option value="1">Enabled</option>
                                <option value="0" selected="selected">Disabled</option>
                                <?php } ?>
                            </select>
                        </div>
                        </td>
                    <td>
                        Order</td>
                    <td colspan="2"><input type="text" name="know_sort_order" value="<?php echo $know_sort_order; ?>" size="8" />
                    </td>
                </tr>
                <?php if($aData) { ?>
                    <?php foreach($aData as $key=>$value) { ?>

                    <tr class="data-row" id="<?php echo $key ?>">
                        <td >Comments</td>
                        <td><textarea cols="5" rows="3" class="form-control" name="know_data[<?php echo $key ?>][comment]"><?php echo $value['comment']; ?></textarea></td>
                        <td>Links</td>
                        <td>
                            <input class="form-control" type="text" value="<?php echo $value['link']; ?>" name="know_data[<?php echo $key ?>][link]" />
                        </td>
                        <td>
                            <button type="button" data-key="<?php echo $key; ?>" class="btn-flat btn-sm btn-danger btn-delete" alt="Delete" title="Delete"><i class="icon-remove"></i></button>
                        </td>
                    </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr class="data-row" id="0">
                        <td>Comments</td>
                        <td><textarea cols="5" rows="3" class="form-control" name="know_data[0][comment]"></textarea></td>
                        <td>Link</td>
                        <td colspan="2"><input type="text" class="form-control" name="know_data[0][link]"></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </form>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#add_option').on('click',function(){
            var iRow = $('table.form tr.data-row').length;
            html = '<tr id="'+iRow+'" class="data-row new">';
            html += '<td>Comment:</td>';
            html += '<td>';
            html += '<textarea cols="5" rows="3" class="form-control" name="know_data['+iRow+'][comment]"></textarea>';
            html += '</td>';
            html += '<td>Link:</td>';
            html += '<td>';
            html +=' <input type="text" class="form-control" value="" name="know_data['+iRow+'][link]">';
            html += '</td>';
            html += '<td><button type="button" data-key="'+iRow+'" class="btn-flat btn-sm btn-danger btn-delete" alt="Delete" title="Delete"><i class="icon-remove"></i></button></td>';
            html += '</tr>';
            //$('#form').append('<input type="text" name="test">');
            $('table.form tbody').append(html);

        });

        $(document).on('click','.btn-delete',function(){
            var key=$(this).attr('data-key');
            if($('#'+key).hasClass('new')){
                $('#'+key).remove();
                return false;
            }
            $.ajax({
                type: 'POST',
                url: '<?php echo makeUrl('module/know/deleteModuleKnow') ?>',
                dataType: 'json',
                data: 'id='+key,
                success: function(data) {
                    if(data.success != undefined){
                        alert('Row deleted successfully');
                        location.reload();
                    }
                }
            });
        });


    });

</script>