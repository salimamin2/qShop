
<?php if ($warning) { ?>
<div class="warning"><?php echo $warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<div class="box">
    <div class="left"></div>
    <div class="right"></div>
    <div class="heading">
        <h1 style="background-image: url('view/image/product.png');"><?php echo __('Product Type'); ?></h1>
        <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
    </div>
    <div class="content">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
            <table class="form">
                <tr>
                    <td><span class="required">*</span> <?php echo __('Name'); ?></td>
                    <td><input name="title" size="100" value="<?php echo isset($title) ? $title : ''; ?>" /></td>
                </tr>
                <tr>
                    <td><?php echo __('Status'); ?></td>
                    <td>
                        <select name="status">
                            <?php if ($status) : ?>
                                <option value="1" selected="selected"><?php echo __('Enabled'); ?></option>
                                <option value="0"><?php echo __('Disabled'); ?></option>
                            <?php else: ?>
                                <option value="1"><?php echo __('Enabled'); ?></option>
                                <option value="0" selected="selected"><?php echo __('Disabled'); ?></option>
                            <?php endif; ?>
                        </select>
                    </td>
                </tr>
            </table>
        </form>
        <?php if($product_type_id): ?>
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form_option">
            <div id="div_option">
            <label><?php echo __('Options'); ?></label>
            <table class="list" id="tbl_option">
                <thead>
                <tr>
                    <td style="padding:5px;" height="25px;"><?php echo __('language'); ?></td><td><?php echo __('Option'); ?></td><td><?php echo __('Sort Order'); ?></td><td><?php echo __('Action'); ?></td>
                </tr>
                </thead>
                <tbody>
                <?php foreach($product_type_options as $product_type_option): ?>
                <tr style="padding:5px;" id="<?php echo $product_type_option['product_type_option_id'] . '_' . $product_type_option['language_id']; ?>" >
                    <td style="padding:5px;" id="<?php echo 'lang_' .$product_type_option['product_type_option_id'] . '_' . $product_type_option['language_id']; ?>"><?php echo $product_type_option['lang']; ?></td>
                    <td id="<?php echo 'name_' .$product_type_option['product_type_option_id'] . '_' . $product_type_option['language_id']; ?>">
                        <a onclick="getOptionValues('<?php echo $product_type_option['product_type_option_id'] . '_' . $product_type_option['language_id']; ?>');" ><span><?php echo $product_type_option['name']; ?></span></a>
                        <span id="div_loader_<?php echo $product_type_option['product_type_option_id'] . '_' . $product_type_option['language_id']; ?>" style="width: 50px; margin-left: 10px;"></span>
                    </td>
                    <td id="<?php echo 'sort_' .$product_type_option['product_type_option_id'] . '_' . $product_type_option['language_id']; ?>"><?php echo $product_type_option['sort_order']; ?></td>
                    <td>
                        [
                        <a onclick="editOption('<?php echo $product_type_option['product_type_option_id'] . '_' . $product_type_option['language_id']; ?>');" ><span>Edit</span></a>
                        ] [
                        <a onclick="location = '<?php echo $action_remove_option . '&product_type_option_id=' . $product_type_option['product_type_option_id']; ?>';" ><span>Remove</span></a>
                        ]
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="filter">
                        <td>
                            <select name="product_type_option[new][language_id]">
                            <?php foreach($languages as $language): ?>
                                <option value="<?php echo $language['language_id']; ?>"><?php echo $language['name']; ?></option>
                            <?php endforeach; ?>
                            </select>
                        </td>
                        <td><input type="text" name="product_type_option[new][name]" value="" /></td>
                        <td><input type="text" name="product_type_option[new][sort_order]" value="" /></td>
                        <td><a onclick="$('#form_option').submit();" class="button"><span>Add New</span></a></td>
                    </tr>
                </tfoot>
            </table>
            </div>
            <div id="div_option_value"></div>
        </form>
        <?php endif; ?>
    </div>
</div>
<script type="text/javascript" src="view/javascript/jquery/jquery.dataTables.js"></script>
<script type="text/javascript"><!--
    function getOptionValues(attribute) {
        var arr_attribute = attribute.split('_');
        var option_id = arr_attribute[0];
        var language_id = arr_attribute[1];
        var option_name = $('#name_' + attribute).text();
        $.ajax({
            url: 'catalog/product_type/getOptionValues&token=<?php echo $token; ?>&product_type_id=<?php echo $product_type_id; ?>',
            type: 'POST',
            data: 'option_id=' + option_id + '&language_id=' + language_id + '&option_name=' + option_name,
            dataType: 'html',
            beforeSend: function() {
                $('#div_loader_' + attribute).html('<img src="view/image/ajax-loader.gif" />');
            },
            success: function(data) {
                $('#div_option_value').html(data);
                $('.tablesorter').dataTable({
                    "iDisplayLength":50
                });
            },
            complete: function() {
                $('#div_loader_' + attribute).html('');
            }
        });
    }
    
    function editOption(attribute) {
        var arr_attribute = attribute.split('_');
        var option_id = arr_attribute[0];
        var language_id = arr_attribute[1];
        var option_name = $('#name_' + attribute).text();
        var option_lang = $('#lang_' + attribute).text();
        var option_sort = $('#sort_' + attribute).text();
        
        html = '<td id="lang_'+attribute+'">';
        html += '<select name="product_type_option['+option_id+'][language_id]">';
        <?php foreach($languages as $language): ?>
            html += '<option value="<?php echo $language['language_id']; ?>"><?php echo $language['name']; ?></option>';
        <?php endforeach; ?>
        html += '</select>';
        html += '</td>';
        html += '<td id="name_'+attribute+'"><input type="text" name="product_type_option['+option_id+'][name]" value="'+option_name+'" /></td>';
        html += '<td id="sort_'+attribute+'"><input type="text" name="product_type_option['+option_id+'][sort_order]" value="'+option_sort+'" /></td>';
        html += '<td><a onclick="$(\'#form_option\').submit();" class="button"><span>Update</span></a></td>';
        
        $('#' + attribute).html(html);
    }
    
    function editOptionValue(attribute) {
        var arr_attribute = attribute.split('_');
        var option_value_id = arr_attribute[0];
        var language_id = arr_attribute[1];
        var option_name = $('#name_' + attribute).text();
        var option_lang = $('#lang_' + attribute).text();
        var option_sort = $('#sort_' + attribute).text();
        
        html = '<td id="lang_'+attribute+'">';
        html += '<select name="product_type_option_value['+option_value_id+'][language_id]">';
        <?php foreach($languages as $language): ?>
            html += '<option value="<?php echo $language['language_id']; ?>"><?php echo $language['name']; ?></option>';
        <?php endforeach; ?>
        html += '</select>';
        html += '</td>';
        html += '<td id="name_'+attribute+'"><input type="text" name="product_type_option_value['+option_value_id+'][name]" value="'+option_name+'" /></td>';
        html += '<td id="sort_'+attribute+'"><input type="text" name="product_type_option_value['+option_value_id+'][sort_order]" value="'+option_sort+'" /></td>';
        html += '<td><a onclick="$(\'#form_option\').submit();" class="button"><span>Update</span></a></td>';
        
        $('#' + attribute).html(html);
    }
    
//--></script>
<script type="text/javascript"><!--
    $.tabs('#tabs a'); 
    //--></script>
