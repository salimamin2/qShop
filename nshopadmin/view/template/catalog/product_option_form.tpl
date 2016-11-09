<div class="modal" aria-labelledby="myLargeModalLabel" id="dialog-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Add/Select Option Values</h4>
            </div>
            <form id="general_form_modal" action="<?php echo makeUrl('catalog/product_option/addGeneral', array( 'product_id=' . $product_id), true) ?>" method="post">
                <input type="hidden" name="option_id" id="option_id" value="">
                <div class="modal-body">
                    <div class="abc" id="error_message"></div>
                </div>
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                    <button class="btn-info btn-sm" id="save_general_option" onclick="validateAGeneral()">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="box table-wrapper products-table section">
    <div class="head well">
        <h3><i class="icon-edit"></i> <?php echo $heading_title; ?>
            <div class="pull-right">
                <a class="option_button btn btn-info btn-sm hide" onclick="validateGeneral()"><span><?php echo __('Add Option'); ?></span></a>
                <a onclick="validateOptionForm('value')" class="btn-flat btn-success btn-sm"><span><?php echo $button_save; ?></span></a>
                <a href="<?php echo $cancel; ?>" class="btn-flat btn-default btn-sm"><span><?php echo __('Back'); ?></span></a>
            </div>
        </h3>
    </div>
    <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><?php echo $error_warning; ?></div>
    <?php } ?>
    <?php if ($success) { ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php } ?>
    <div class="alert alert-warning hide" id="processing"></div>
    <div class="content">
        <div class="alert alert-info">
            <?php if ($product_id != -1) { ?>
                <div><?php echo __('text_note_general') ?></div>
            <?php } ?>
            <div><?php echo __('text_note_sort_order') ?></div>
        </div>
        <div class="add_general_option messages_layout">
            <form id="general_form" action="<?php echo makeUrl('catalog/product_option/addGeneral', array('product_id=' . $product_id), true) ?>" method="post">
                <?php if ($product_id != -1) : ?>
                <label><?php echo __('Add General Option'); ?>:</label>
                <select name="option_id" id="general_option_name">
                    <?php foreach ($general_options as $id => $option): ?>
                    <option value="<?php echo $id ?>" data-toggle="modal" data-target="#dialog-modal"><?php echo $option ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="clearfix"></div>
            <?php endif; ?>
                <div id="detail_general_option" class="message_wrap hide">
                    <div class="block-head info"><?php echo __('Add/Select Option Values') ?></div>
                    <div class="text">
                        <label><?php echo __('Search option values'); ?>:</label>
                        <input type="text" name="general_option_value" id="general_option_value_name" autocomplete="on" />
                        <div class="general_option dropdown"></div>
                        <div class="block no-display"></div>
                    </div>
                </div>
            </form>
        </div>
        <div class="clear"></div>
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" autocomplete="off">

            <input type="hidden" name="directory" value="<?php echo $directory; ?>" />

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <th><?php echo $entry_language; ?></th>
                            <th><?php echo $entry_option; ?></th>
                            <th><?php echo $entry_option_type; ?></th>
                            <th><?php echo $entry_parent_type; ?></th>
                            <th><?php echo $entry_sort_order; ?></th>
                            <th><?php echo $entry_action; ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $option_value_row = 0; ?>
                        <?php foreach ($options as $i => $option): ?>
                            <tr id="row_<?php echo $option['product_option_id']; ?>">
                                <td>
                                    <div class="list-down" id="list-<?php echo $option['product_option_id']; ?>">
                                        <a class="btn btn-default btn-xs" href="javascript:void(0);" onclick="slideList(<?php echo $option['product_option_id']; ?>)">
                                            <i class="icon-chevron-up"></i>
                                        </a>
                                    </div>
                                </td>
                                <td>
                                    <select class="form-control" name="option[old][<?php echo $option['product_option_id']; ?>][language_id]">
                                        <?php foreach ($languages as $language): ?>
                                            <?php if ($language['language_id'] == $option['language_id']): ?>
                                                <option value="<?php echo $language['language_id']; ?>" selected="selected"><?php echo $language['name']; ?></option>
                                            <?php else: ?>
                                                <option value="<?php echo $language['language_id']; ?>"><?php echo $language['name']; ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="option[old][<?php echo $option['product_option_id']; ?>][name]" value="<?php echo $option['name']; ?>" class="form-control option-name" /></td>
                                <td>
                                    <select class="form-control option_type" name="option[old][<?php echo $option['product_option_id']; ?>][product_option_type_id]">
                                        <?php foreach ($option_types as $type_id => $type): ?>
                                            <option value="<?php echo $type_id; ?>" <?php if ($option['product_option_type_id'] == $type_id): ?>selected="selected"<?php endif; ?> ><?php echo $type ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <?php if ($option['product_option_type_id'] != 4): ?>
                                        <select name="option[old][<?php echo $option['product_option_id']; ?>][parent_id]" class="form-control parent_option_id">
                                            <?php foreach ($parent_options as $id => $name): ?>
                                            <option value="<?php echo $id; ?>" <?php if ($option['parent_id'] == $id): ?>selected="selected"<?php endif; ?> ><?php echo $name ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php endif; ?>
                                </td>
                                <td><input type="text" name="option[old][<?php echo $option['product_option_id']; ?>][sort_order]" value="<?php echo $option['sort_order']; ?>" class="form-control" size="5" /></td>
                                <td>
                                    <a class="btn btn-danger btn-xs" onclick="location = '<?php echo $removeOption . '&option_id=' . $option['product_option_id']; ?>';" data-toggle="tooltip" title="Remove"><i class="icon-remove"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8">
                                    <div class="child-list" id="child-<?php echo $option['product_option_id']; ?>">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr class="filter">
                                                    <td><?php echo $entry_language; ?></td>
                                                    <td><?php echo $entry_option_value; ?></td>
                                                    <td><?php echo $entry_option_help; ?></td>
                                                    <td><?php echo $entry_sort_order; ?></td>
                                                    <td><?php echo $entry_quantity; ?></td>
                                                    <td><?php echo $entry_subtract; ?></td>
                                                    <td><?php echo __('Thumb'); ?></td>
                                                    <td><?php echo __('Image'); ?></td>
                                                    <td><?php echo $entry_price; ?></td>
                                                    <td><?php echo $entry_prefix; ?></td>
                                                    <td><?php echo $entry_min_text; ?></td>
                                                    <td><?php echo $entry_max_text; ?></td>
                                                    <td><?php echo $entry_action; ?></td>
                                                </tr>
                                            </thead>
                                            <tbody id="opt_<?php echo $option['product_option_id']; ?>">
                                                <?php if ($option['option_values']): ?>
                                                    <?php foreach ($option['option_values'] as $option_value): ?>
                                                        <tr>
                                                           <td>
                                                                <input type="hidden" class="form-control" name="option_value[old][<?php echo $option_value['product_option_value_id']; ?>][product_option_id]" value="<?php echo $option_value['product_option_id']; ?>" />
                                                                <select class="form-control" name="option_value[old][<?php echo $option_value['product_option_value_id']; ?>][language_id]">
                                                                    <?php foreach ($languages as $language): ?>
                                                                        <?php if ($language['language_id'] == $option_value['language_id']): ?>
                                                                            <option value="<?php echo $language['language_id']; ?>" selected="selected"><?php echo $language['name']; ?></option>
                                                                        <?php else: ?>
                                                                            <option value="<?php echo $language['language_id']; ?>"><?php echo $language['name']; ?></option>
                                                                        <?php endif; ?>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="option_value[old][<?php echo $option_value['product_option_value_id']; ?>][name]" value="<?php echo $option_value['name']; ?>" class="value-name" />
                                                            </td>
                                                            <td class="hint">
                                                                <span>
                                                                    <input type="text" name="option_value[old][<?php echo $option_value['product_option_value_id']; ?>][help]" value="<?php echo $option_value['help']; ?>" class="form-control value-help" />
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="option_value[old][<?php echo $option_value['product_option_value_id']; ?>][sort_order]" value="<?php echo $option_value['sort_order']; ?>" size="5" />
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="option_value[old][<?php echo $option_value['product_option_value_id']; ?>][quantity]" value="<?php echo $option_value['quantity']; ?>" size="5" />
                                                            </td>
                                                            <td class="subtract">
                            	    						    <span>
                                                                    <select class="form-control" name="option_value[old][<?php echo $option_value['product_option_value_id']; ?>][subtract]">
                                                                        <?php if ($option_value['subtract']) { ?>
                                                                        <option value="0"><?php echo $text_no; ?></option>
                                                                        <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                                                                        <?php } else { ?>
                                                                        <option value="0" selected="selected"><?php echo $text_no; ?></option>
                                                                        <option value="1"><?php echo $text_yes; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </span>
                                                            </td>
                                                            <td class="thumb">
                                                                <span class="fileinput-button btn">
                                	    							<input type="hidden" id="option_value_<?php echo $option['product_option_id']; ?>_<?php echo $option_value['product_option_value_id']; ?>_thumb" name="option_value[old][<?php echo $option_value['product_option_value_id']; ?>][thumb]" value="<?php echo $option_value['thumb']; ?>" size="10" class="value-thumb" />
                                	    							<img src="<?php echo $option_value['thumb_src']; ?>" id="option_value_<?php echo $option['product_option_id']; ?>_<?php echo $option_value['product_option_value_id']; ?>_thumb_preview" class="value-thumb-preview" width="20" height="20" />
                                                                    <input type="file" rel="thumb" data-option-id="<?php echo $option['product_option_id']; ?>_<?php echo $option_value['product_option_value_id']; ?>" name="image" class="fileupload" />
                                                                </span>
                                                            </td>
                                                            <td class="image-opt">
                                                                <span class="fileinput-button btn">
                                                                    <input type="hidden" id="option_value_<?php echo $option['product_option_id']; ?>_<?php echo $option_value['product_option_value_id']; ?>_image" name="option_value[old][<?php echo $option_value['product_option_value_id']; ?>][image]" value="<?php echo $option_value['image']; ?>" size="10" class="value-image" />
                                                                    <img src="<?php echo $option_value['image_src']; ?>" id="option_value_<?php echo $option['product_option_id']; ?>_<?php echo $option_value['product_option_value_id']; ?>_image_preview" class="value-image-preview" width="20" height="20" />
                                                                    <input type="file" rel="image" data-option-id="<?php echo $option['product_option_id']; ?>_<?php echo $option_value['product_option_value_id']; ?>" name="image" class="fileupload" />
                                                                </span>
                                                            </td>
                                                            <td class="price">
                             	    						    <span>
                                	    							<input type="text" name="option_value[old][<?php echo $option_value['product_option_value_id']; ?>][price]" value="<?php echo $option_value['price']; ?>" size="10" class="form-control value-price" />
                            	    						    </span>
                                                            </td>
                                                            <td class="prefix">
                            	    						    <span>
                                	    							<select class="form-control" name="option_value[old][<?php echo $option_value['product_option_value_id']; ?>][prefix]">
                                                                        <?php if ($option_value['prefix'] != '-') { ?>
                                                                            <option value="+" selected="selected"><?php echo $text_plus; ?></option>
                                                                            <option value="-"><?php echo $text_minus; ?></option>
                                                                        <?php } else { ?>
                                                                            <option value="+"><?php echo $text_plus; ?></option>
                                                                            <option value="-" selected="selected"><?php echo $text_minus; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                            	    						    </span>
                                                            </td>
                                                            <td class="min_size">
                                                                <span>
                                                                    <input type="text" class="form-control" name="option_value[old][<?php echo $option_value['product_option_value_id']; ?>][min_size]" value="<?php echo $option_value['min_size'] ?>" size="3" />
                                                                </span>
                                                            </td>
                                                            <td class="max_size">
                                                                <span>
                                                                    <input type="text" class="form-control" name="option_value[old][<?php echo $option_value['product_option_value_id']; ?>][max_size]" value="<?php echo $option_value['max_size'] ?>" size="3" />
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <a class="btn btn-danger btn-xs" onclick="location = '<?php echo $removeOptionValue . '&option_value_id=' . $option_value['product_option_value_id']; ?>';" data-toggle="tooltip" title="Remove"><i class="icon-remove"></i></a>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                                <tr class="last" id="option_value_row_<?php echo $option_value_row; ?>">
                                                    <td>
                                                        <input type="hidden" name="option_value[new][<?php echo $option_value_row; ?>][product_option_id]" value="<?php echo $option['product_option_id']; ?>" />
                                                        <select class="form-control" name="option_value[new][<?php echo $option_value_row; ?>][language_id]">
                                                            <?php foreach ($languages as $language): ?>
                                                                <option value="<?php echo $language['language_id']; ?>"><?php echo $language['name']; ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="option_value[new][<?php echo $option_value_row; ?>][name]"  autocomplete="off" value="" class="form-control value-name" />
                                                    </td>
                                                    <td class="hint">
                                                        <span>
                                                            <input type="text" name="option_value[new][<?php echo $option_value_row; ?>][help]"  autocomplete="off" value="" class="form-control value-help" />
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="option_value[new][<?php echo $option_value_row; ?>][sort_order]" value="" size="5" />
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="option_value[new][<?php echo $option_value_row; ?>][quantity]" value="" size="5" />
                                                    </td>
                                                    <td class="subtract">
                                                        <span>
                                                    		<select class="form-control" name="option_value[new][<?php echo $option_value_row; ?>][subtract]">
                                                                <option value="0"><?php echo $text_no; ?></option>
                                                                <option value="1"><?php echo $text_yes; ?></option>
                                                            </select>
                                            	        </span>
                                                    </td>
                                                    <td class="thumb">
                                                        <span class="fileinput-button btn">
                                                            <input type="hidden" id="option_value_<?php echo $option['product_option_id']; ?>_<?php echo $ption_value_row; ?>_thumb" name="option_value[new][<?php echo $option_value_row; ?>][thumb]" value="" size="10" class="value-thumb" />
                                                            <img src="" id="option_value_<?php echo $option['product_option_id']; ?>_<?php echo $option_value_row; ?>_thumb_preview" class="value-thumb-preview" width="30" height="30" />
                                                            <input type="file" rel="thumb" data-option-id="<?php echo $option['product_option_id']; ?>_<?php echo $option_value_row; ?>" name="image" class="fileupload" />
                                                        </span>
                                                    </td>
                                                    <td class="image-opt">
                                                        <span class="fileinput-button btn">
                                                            <input type="hidden" id="option_value_<?php echo $option['product_option_id']; ?>_<?php echo $option_value_row; ?>_image" name="option_value[new][<?php echo $option_value_row; ?>][image]" value="" size="10" class="value-image" />
                                                            <img src="" id="option_value_<?php echo $option['product_option_id']; ?>_<?php echo $option_value_row; ?>_image_preview" class="value-image-preview" width="30" height="30" />
                                                            <input type="file" rel="image" data-option-id="<?php echo $option['product_option_id']; ?>_<?php echo $option_value_row; ?>" name="image" class="fileupload" />
                                                        </span>
                                                    </td>
                                                    <td class="price">
                                                        <span>
                                                            <input type="text" name="option_value[new][<?php echo $option_value_row; ?>][price]" value="0.0000" size="10" class="form-control value-price" />
                                                        </span>
                                                    </td>
                                                    <td class="prefix">
                                                        <span>
                                							<select class="form-control" name="option_value[new][<?php echo $option_value_row; ?>][prefix]">
                                                                <option value="+"><?php echo $text_plus; ?></option>
                                                                <option value="-"><?php echo $text_minus; ?></option>
                                                            </select>
                             						    </span>
                                                    </td>
                                                    <td class="min_size">
                                                        <span>
                                                            <input class="form-control" type="text" name="option_value[new][<?php echo $option_value_row; ?>][min_size]" value="" size="3" />
                                                        </span>
                                                    </td>
                                                    <td class="max_size">
                                                        <span>
                                                            <input class="form-control" type="text" name="option_value[new][<?php echo $option_value_row; ?>][max_size]" value="" size="3" />
                                                        </span>
                                                    </td>
                                                    <td id="option_value_<?php echo $option_value_row; ?>">
                                                        <a onclick="addOptionValue('<?php echo $option['product_option_id']; ?>', '<?php echo $option_value_row; ?>');" class="add_option btn btn-info btn-xs" title="Add Value" data-toggle="tooltip"><i class="icon-plus"></i></a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                            <?php $option_value_row++; ?>
                        <?php endforeach; ?>
                        <tr id="row_<?php echo $i ?>">
                            <td></td>
                            <td>
                                <select class="form-control" name="option[new][0][language_id]">
                                    <?php foreach ($languages as $language): ?>
                                        <option value="<?php echo $language['language_id']; ?>"><?php echo $language['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td class="suggest"><input type="text" name="option[new][0][name]" id="option_name" value="" class="form-control option-name" /></td>
                            <td>
                                <select class="form-control" name="option[new][0][product_option_type_id]">
                                    <?php foreach ($option_types as $type_id => $type): ?>
                                        <option value="<?php echo $type_id; ?>" ><?php echo $type ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <select name="option[new][0][parent_id]" class="form-control parent_option_id">
                                    <?php foreach ($parent_options as $id => $name): ?>
                                        <option value="<?php echo $id; ?>" ><?php echo $name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td><input class="form-control" type="text" name="option[new][0][sort_order]" value="" size="5" /></td>
                            <td><a onclick="validateOptionForm('option',this)" class="btn btn-primary btn-xs" data-toggle="tooltip" title="Add Option"><i class="icon-plus"></i></a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
        <div id="myDialog" title="Size Selection">
            <div class="box" id="box_dialog" style="display:none;">
                I am Dailog
            </div>
        </div>
    </div>
</div>

<script type="text/javascript"><!--

jQuery(document).ready(function() {

    var product_id = <?php echo $product_id; ?>;

    //if (product_id != -1) {

    jQuery('#general_option_value_name').autoComplete();

    //}



    jQuery('.option_type').each(function() {

        toggleFields(jQuery(this));

    });

    /*  jQuery('#dialog-modal').dialog({
     modal: true,
     autoOpen: false
     });*/

    jQuery("#general_option_name").change(function(){
        jQuery('.modal-body').empty();
        jQuery('#option_error').empty();
        jQuery('#error_message').empty();

        var selection = jQuery('#general_option_name').val();

        jQuery('#option_id').val(selection);

        jQuery.ajax({

            type: "POST",

            url: "<?php echo makeUrl('catalog/product_option/getGeneralOption', array('token=' . $this->session->data['token'], 'product_id=' . $product_id, 'product_type=' . $product_type), true) ?>",

            dataType: "json",

            data: "name=" + jQuery(obj).val() + "&option_id=" + jQuery('#general_option_name').val(),

            success: function(res) {

                if (res.options != undefined) {
                    //jQuery('.modal-content').modal('show');
                    jQuery('#dialog-modal').modal('show');
                    var result = res.options;
                    html='<table class="table table-hover" id="general_option_table">';

                    html += '<thead>';

                    html += '<tr>';

                    html += '<th>Select</th>';

                    html += '<th>';

                    html += 'Name';

                    html += '</th>';

                    html += '</tr>';

                    html += '</thead>';

                    html +='<tbody>';

                    var result = res.options;

                    for (i = 0; i < result.length; i++) {

                        html += '<tr>';

                        html += '<td><input type="checkbox" name="values[]" value=' + result[i].id + '></td>';

                        html += '<td>' + result[i].value + ' </td>';

                        html += '</tr>';
                    }

                    html += '</tbody>';

                    html += '</table>';

                    jQuery('.modal-body').append(html);


                } else {

                    alert('Not found');

                }
                jQuery('#general_option_table').dataTable();


            }

        });



    });

});

jQuery('.value-thumb-preview,.value-image-preview').on('click', function() {

    var aId = jQuery(this).attr('id').split('_');

    var hdnId = aId.splice(0, aId.length - 1);

    //image_upload(hdnId.join('_'), jQuery(this).attr('id'));

});



jQuery('.option_type').change(function() {

    toggleFields(jQuery(this));

});



function toggleFields(obj) {

    var parentId = obj.parent().parent().attr('id');

    if (obj.val() == 2) {

        jQuery('#' + parentId).next().find('.min_size span, .max_size span, .hint span').show();

        jQuery('#' + parentId).next().find('.subtract span, .price span, .prefix span').hide();

    } else {

        jQuery('#' + parentId).next().find('.min_size span, .max_size span, .hint span').hide();

        jQuery('#' + parentId).next().find('.subtract span, .price span, .prefix span').show();

    }

}



(function($)

{

    jQuery.fn.autoComplete = function(id, options)

    {

        obj = this;

        var defaults = {

            capitalize_on: 'keyup'

        };



        var opts = jQuery.extend(defaults, options);

        var line = 0;

        jQuery(this).bind('keypress', function(event) {

            var keyCode = event.keyCode || event.which;

            if (keyCode == 13) {

                //selectValue(jQuery('.general_option ul li.selected a').attr('rel'), jQuery('.general_option ul li.selected a').text());

                event.preventDefault();

                event.cancelBubble;

                event.returnValue = false;

                return false;

            }

        });

        jQuery(this).bind('blur', function(event) {

            jQuery('.general_option').fadeOut();

        });

        jQuery(this).bind('keyup', function(event) {

            var keyCode = event.keyCode || event.which;



            if (keyCode == 40) {

                if(jQuery('.general_option ul li').length > line) {

                    jQuery('.general_option ul li:nth-child(' + line + ')').removeClass('active');

                    line++;

                    jQuery('.general_option ul li:nth-child(' + line + ')').addClass('active');

                }

            } else if (keyCode == 38) {

                if(line > 0) {

                    jQuery('.general_option ul li:nth-child(' + line + ')').removeClass('active');

                    line--;

                    jQuery('.general_option ul li:nth-child(' + line + ')').addClass('active');

                }

            } else if (keyCode == 13) {

                selectValue(jQuery('.general_option ul li.active a').attr('rel'), jQuery('.general_option ul li.active a').text());

                event.preventDefault();

                event.cancelBubble;

                event.returnValue = false;

                return false;

            } else if (keyCode == 37 || keyCode == 39) {



            } else {

                if (jQuery('.general_option').length != 0) {

                    line = 0;

                    jQuery('.general_option ul li').removeClass('active');

                }

                if (jQuery(obj).val().length > 0) {

                    jQuery.ajax({

                        type: "POST",

                        url: "<?php echo makeUrl('catalog/product_option/generalOption', array('token=' . $this->session->data['token'], 'product_id=' . $product_id, 'product_type=' . $product_type), true) ?>",

                        dataType: "json",

                        data: "name=" + jQuery(obj).val() + "&option_id=" + jQuery('#general_option_name').val(),

                        beforeSend: function() {

                            jQuery('.general_option').html('<span class="loader"></span>');

                        },

                        success: function(res) {

                            jQuery('.general_option').html('<ul class="dropdown-menu"></ul>');

                            console.log('here');

                            if (res.options != undefined) {

                                var result = res.options;

                                for (i = 0; i < result.length; i++) {

                                    jQuery('.general_option ul').append('<li><a rel="' + result[i].id + '">' + result[i].value + '</a></li>');

                                }

                            } else {

                                jQuery('.general_option ul').append("<li>No Option Value Found.</li>");

                            }

                            jQuery('.general_option').show();

                            jQuery('.general_option ul').show();

                            jQuery('.general_option li').on('mouseenter', function() {

                                jQuery('.general_option li').removeClass('active');

                                jQuery(this).addClass('active');

                            });

                            jQuery(document).on('click','.general_option li a',function(e) {

                                jQuery(this).parent().addClass('active');

                                selectValue(jQuery(this).attr('rel'), jQuery(this).text());

                            });

                            jQuery('#general_option_value_name').on('focus', function() {

                                jQuery('.general_option').show();

                            });

                        }

                    });



                }



            }

        });

        var selectValue = function(id, name) {

            jQuery('.general_option').hide();

            jQuery('.option_button').removeClass('hide');

            jQuery('#detail_general_option .block').show();

            if (jQuery('#detail_general_option .block ul').length < 1) {

                jQuery('#detail_general_option .block').html('<ul></ul>');

                jQuery('#detail_general_option .block ul').before('<a class="select-all">Select All</a> | <a class="deselect">Select None</a>');

            }

            if(jQuery('#value_'+id).length == 0) {

                jQuery('#detail_general_option .block ul').append('<li><input type="checkbox" name="values[]" id="value_' + id + '" value="' + id + '" checked="checked" /> <label for="value_' + id + '">' + name + '</label></li>');

                jQuery('.deselect').click(function() {

                    jQuery('#detail_general_option ul input').attr('checked', false);

                });

                jQuery('.select-all').click(function() {

                    jQuery('#detail_general_option ul input').attr('checked', true);

                });

            }

            jQuery('#general_option_value_name').blur();



            return false;

        }



    }



})(jQuery);

jQuery('#Ageneral_option_name').bind('change', function() {

    jQuery('.option_button').addClass('hide');

    jQuery('#detail_general_option .block').html('').hide();

    if (jQuery(this).val() != 0) {

        jQuery(this).parent().addClass('selected');

        //selectValue(jQuery(this).val(), jQuery(this).text());

        jQuery('#detail_general_option').removeClass('hide');

    } else {

        jQuery('#detail_general_option').addClass('hide');



    }

});

function validateGeneral() {

    bValid = true;

    if (jQuery('#hdn_option_id').val() == '') {

        alert('Enter option name or option not found in general options');

        bValid = false;

    }

    if (bValid)

        jQuery('#general_form').submit();

}
function validateAGeneral() {

    bValid = true;

    if (jQuery('#hdn_option_id').val() == '') {

        alert('Enter option name or option not found in general options');

        bValid = false;

    }

    if (jQuery('#general_form_modal :checkbox:checked').length > 0){
        bValid = true;
    }
    else{
        bValid = false;
        jQuery('#error_message').html('<div class="alert alert-warning" id="option_error">Select Atleast one value</div>');
    }

    if (bValid)

        jQuery('#general_form_modal').submit();

}


function slideList(id) {

    jQuery('#child-' + id).slideToggle("slow", function() {

        if (jQuery(this).css('display') == 'none') {

            jQuery('#list-' + id + ' a i').removeClass('icon-chevron-up');

            jQuery('#list-' + id + ' a i').addClass('icon-chevron-down');

        } else {

            jQuery('#list-' + id + ' a i').removeClass('icon-chevron-down');

            jQuery('#list-' + id + ' a i').addClass('icon-chevron-up');

        }

    });

}



var option_value_row = <?php echo $option_value_row; ?>;

var html;

function addOptionValue(product_option_id, row_id) {

    html = '';

    html += '<tr class="last" id="option_value_row_' + option_value_row + '">';

    html += '<td>';

    html += '<input type="hidden" name="option_value[new][' + option_value_row + '][product_option_id]" value="' + product_option_id + '" />';

    html += '<select class="form-control" name="option_value[new][' + option_value_row + '][language_id]">';

<?php foreach ($languages as $language): ?>

    html += '<option value="<?php echo $language['language_id']; ?>"><?php echo $language['name']; ?></option>';

<?php endforeach; ?>

    html += '</select>';

    html += '</td>';

    html += '<td><input type="text" name="option_value[new][' + option_value_row + '][name]" value="" class="form-control value-name" /></td>';

    html += '<td class="hint"><span><input type="text" name="option_value[new][' + option_value_row + '][help]" value="" class="form-control value-help" /></span></td>';

    html += '<td><input class="form-control" type="text" name="option_value[new][' + option_value_row + '][sort_order]" value="" size="5" /></td>';

    html += '<td><input class="form-control" type="text" name="option_value[new][' + option_value_row + '][quantity]" value="" size="5" /></td>';

    html += '<td class="subtract"><span>';

    html += '<select class="form-control" name="option_value[new][' + option_value_row + '][subtract]">';

    html += '<option value="0"><?php echo $text_no; ?></option>';

    html += '<option value="1"><?php echo $text_yes; ?></option>';

    html += '</select>';

    html += '</span></td>';

    html += '<td class="thumb"><span class="fileinput-button btn">' +

    '<input type="hidden" id="option_value_' + product_option_id + '_' + option_value_row + '_thumb" name="option_value[new][' + option_value_row + '][thumb]" value="" size="10" class="value-thumb" />' +

    '<img src="" id="option_value_' + product_option_id + '_' + option_value_row + '_thumb_preview" value="" class="value-thumb-preview" width="30" height="30" />' +

    '<input type="file" rel="thumb" data-option-id="'+product_option_id + '_' + option_value_row +'" name="image" class="fileupload" />' +

    '</span></td>';

    html += '<td class="image-opt"><span class="fileinput-button btn">' +

    '<input type="hidden" id="option_value_' + product_option_id + '_' + option_value_row + '_image" name="option_value[new][' + option_value_row + '][image]" value="" size="10" class="value-image" />' +

    '<img src="" id="option_value_' + product_option_id + '_' + option_value_row + '_image_preview" value="" class="value-image-preview" width="30" height="30" />' +

    '<input type="file" rel="image" data-option-id="'+product_option_id + '_' + option_value_row + '" name="image" class="fileupload" />' +

    '</span></td>';

    html += '<td class="price"><span><input type="text" name="option_value[new][' + option_value_row + '][price]" value="0.00" size="10" class="form-control value-price" /></span></td>';

    html += '<td class="prefix"><span>';

    html += '<select class="form-control" name="option_value[new][' + option_value_row + '][prefix]">';

    html += '<option value="+"><?php echo $text_plus; ?></option>';

    html += '<option value="-"><?php echo $text_minus; ?></option>';

    html += '</select>';

    html += '</span></td>';

    html += '<td class="min_size"><span><input class="form-control" type="text" name="option_value[new][' + option_value_row + '][min_size]" value="" size="3" /></span></td>';

    html += '<td class="max_size"><span><input class="form-control" type="text" name="option_value[new][' + option_value_row + '][max_size]" value="" size="3" /></span></td>';

    html += '<td id="option_value_' + option_value_row + '">';

    html += '<a onclick="addOptionValue(\'' + product_option_id + '\',\'' + option_value_row + '\');" class="btn btn-xs btn-info" data-toggle="tooltip" title="Add Value"><i class="icon-plus"></i></a>';

    html += '</td>';

    html += '</tr>';


    jQuery('#child-'+product_option_id+'.child-list tr').removeClass('last');

    jQuery('#opt_' + product_option_id).append(html);

    jQuery('#option_value_' + row_id).html('<a onclick="removeOptionValue(\'' + row_id + '\')" class="btn btn-danger btn-xs" title="Remove" data-toggle="tooltip"><i class="icon-remove"></i></a>');

    toggleFields(jQuery('#row_' + product_option_id + ' .option_type'));
    option_value_row++;

}

function removeOptionValue(row_id) {
    jQuery('#option_value_row_' + row_id).remove();
}

function validateOptionForm(type, ele) {
    jQuery('#processing').addClass('hide');
    var valid = true;
    var obj = jQuery(this);
    var parentOption = jQuery(ele).parents('tr');
    jQuery('span.error,.warning').remove();
    if (type == 'value') {
        jQuery('.child-list').each(function() {
            var objx = jQuery(this);
            objx.find('tr:not(.last) .value-name').each(function() {
                if (jQuery.trim(jQuery(this).val()) != '') {
                    if (jQuery.trim(jQuery(this).val()).length < 1 || jQuery.trim(jQuery(this).val()).length > 64) {
                        valid = false;
                        jQuery(this).after('<span class="error"><?php echo __('error_value_name'); ?></span>');
                    }
                } else if (jQuery.trim(jQuery(this).val()) == '' && objx.find('tr:not(.last) .value-name').length != (jQuery(this).parent().parent().index() + 1)) {
                    valid = false;
                    jQuery(this).after('<span class="error"><?php echo __('error_value_name'); ?></span>');
                }
            });

            objx.find('tr:not(.last) .value-price').each(function() {
                if (isNaN(jQuery.trim(jQuery(this).val()))) {
                    valid = false;
                    jQuery(this).after('<span class="error"><?php echo __('error_value_price'); ?></span>');
                }
            });

            if (jQuery.trim(objx.parent().parent().prev().find('.option_type').val()) == 2) {
                objx.find('tr:not(.last) .min_size input').each(function() {
                    //if (jQuery.trim(jQuery(this).parent().parent().parent().find('.value-name').val()) != '') {
                        if (isNaN(jQuery.trim(jQuery(this).val())) || jQuery.trim(jQuery(this).val()) == '') {
                            valid = false;
                            jQuery(this).after('<span class="error"><?php echo __('error_min_size'); ?></span>');
                        }
                    //}
                });

                objx.find('tr:not(.last) .max_size input').each(function() {
                    //if (jQuery(this).parent().parent().parent().find('.value-name').val() != '') {
                        if (isNaN(jQuery.trim(jQuery(this).val())) || jQuery.trim(jQuery(this).val()) == '') {
                            valid = false;
                            jQuery(this).after('<span class="error"><?php echo __('error_max_size'); ?></span>');
                        } else
                        if (parseInt(jQuery.trim(jQuery(this).val())) <= parseInt(jQuery.trim(jQuery(this).parent().parent().prev().find('input').val()))) {
                            valid = false;
                            jQuery(this).after('<span class="error"><?php echo __('error_size'); ?></span>');
                        }
                    //}
                });
            }
        });

        jQuery('.table .option-name').each(function() {
            if (jQuery('.table .option-name').length == 1 && jQuery.trim(jQuery(this).val()) == '') {
                valid = false;
                jQuery(this).after('<span class="error"><?php echo __('error_name'); ?></span>');
            }
            if (jQuery(this).val() != '') {
                if (jQuery.trim(jQuery(this).val()).length < 3 || jQuery.trim(jQuery(this).val()).length > 64) {
                    valid = false;
                    jQuery(this).after('<span class="error"><?php echo __('error_name'); ?></span>');
                }
            } else if (jQuery.trim(jQuery(this).val()) == '' && jQuery('.table .option-name').length != ((jQuery(this).parent().parent().index() / 2) + 1)) {
                valid = false;
                jQuery(this).after('<span class="error"><?php echo __('error_name'); ?></span>');
            }
        });
    } else {
        if(jQuery.trim(jQuery('.option-name',parentOption).val()) == ''){
            valid = false;
            jQuery('.option-name',parentOption).after('<span class="error"><?php echo __('error_name'); ?></span>');
        } else {
            if (jQuery.trim(jQuery('.option-name',parentOption).val()).length < 3 || jQuery.trim(jQuery('.option-name',parentOption).val()).length > 64) {
                valid = false;
                jQuery('.option-name',parentOption).after('<span class="error"><?php echo __('error_name'); ?></span>');
            }
        }
    }

    if (valid) {
        jQuery('#form').submit();
    } else {
        jQuery('#processing').html('<?php echo __('error_warning') ?>').removeClass('hide');
        return false;
    }

}

jQuery(document).on('focus','.child-list .last .value-name',function(){
    var row_id = jQuery(this).parents('tr').attr('id');
    var child_id = jQuery(this).parents('.child-list').attr('id');
    var option_value_row = '';
    var product_option_id = '';
    if(child_id){
        product_option_id = child_id.replace('child-','');
    }
    if(row_id){
        option_value_row = row_id.replace('option_value_row_','');
    }
    addOptionValue(product_option_id,option_value_row);
});

//--></script>

