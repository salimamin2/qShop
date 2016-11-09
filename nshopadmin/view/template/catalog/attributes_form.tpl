
<?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
    <div class="left"></div>
    <div class="right"></div>
    <div class="heading">
        <h1 style="background-image: url('view/image/product.png');"><?php echo $heading_title; ?></h1>
        <div class="buttons">
            <a onclick="validateForm()" class="button"><span><?php echo __('button_save'); ?></span></a>
        </div>
    </div>
    <div class="content">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
            <div id="attributes">
                <?php foreach ($aOptionTypes as $iType => $sType): ?>
                    <div class="block-head"><?php echo $iType ?> - <?php echo $sType ?>
                        <!-- Controls -->
                        <div id="sheepItForm<?php echo $iType ?>_controls" class="pull-right controls">
                            <div id="sheepItForm<?php echo $iType ?>_add" class="pull-left" ><a ><span><?php echo __('Add Row') ?></span></a></div>
                            <div id="sheepItForm<?php echo $iType ?>_remove_last" class="pull-left"><a ><span>Remove</span></a></div>
                            <div id="sheepItForm<?php echo $iType ?>_remove_all" class="pull-left"><a><span>Remove all</span></a></div>
                            <div class="clear"></div>
                        </div><!-- /Controls -->
                        <div class="clear"></div>
                    </div>
                    <div class="block">
                        <table id="detail" class="list">
                            <thead>
                                <tr>
                                    <td class="left" widht="3%"><?php echo __('S.No.'); ?></td>
                                    <td class="left" widht="2%"><?php echo __('Id'); ?></td>
                                    <td class="left" widht="5%"><?php echo __('Name'); ?></td>
                                    <td class="left" widht="10%"><?php echo __('Sort Order'); ?></td>
                                    <td class="left" widht="5%"><?php echo __('column_status'); ?></td>
                                    <td class="left" widht="5%"><?php echo __('Action'); ?></td>
                                </tr>
                            </thead><!-- sheepIt Form -->
                            <tbody id="sheepItForm<?php echo $iType ?>">
                                <!-- Form template-->
                                <tr id="sheepItForm<?php echo $iType ?>_template">
                                    <td>
                                        <span id="sheepItForm<?php echo $iType ?>_label"></span>
                                    </td>
                                    <td>
                                        <span id="sheepItForm<?php echo $iType ?>_#index#_product_hdn_type_option_value_id" class="option_id"></span>
                                        <input type="hidden" name="product_detail[#index#][<?php echo $iType ?>][product_type_option_value_id]" id="sheepItForm<?php echo $iType ?>_#index#_product_type_option_value_id" />
                                    </td>
                                    <td class="left"><input type="text" id="sheepItForm<?php echo $iType ?>_#index#_name" name="product_detail[#index#][<?php echo $iType ?>][name]" value="" class="value-name" size="20" /></td>
                                    <td class="left"><input type="text" id="sheepItForm<?php echo $iType ?>_#index#_sort_order" name="product_detail[#index#][<?php echo $iType ?>][sort_order]" value="" size="2" /></td>
                                    <td class="left">
                                        <select id="sheepItForm<?php echo $iType ?>_#index#_status" name="product_detail[#index#][<?php echo $iType ?>][status]" >
                                            <option value="1"><?php echo __('Enable') ?></option>
                                            <option value="0"><?php echo __('Disable') ?></option>
                                        </select>
                                    </td>
                                    <td><a id="sheepItForm<?php echo $iType ?>_remove_current" class="remove-current"><?php echo __('Remove') ?></a></td>
                                </tr><!-- /Form template-->
                                <!-- No forms template -->
                                <tr id="sheepItForm<?php echo $iType ?>_noforms_template">
                                    <td colspan="4">
                                        No records
                                    </td>
                                </tr><!-- /No forms template-->
                            </tbody><!-- /sheepIt Form -->
                        </table>
                        <div class="clear"></div>
                    </div>
                <?php endforeach; ?>

            </div>
        </form>
    </div>
</div>
<script type="text/javascript" src="view/javascript/jquery/jquery.sheepItPlugin.min.js"></script>
<script type="text/javascript"><!--    
    var aRemove = [];
    $(document).ready(function() {
        var aTypes = <?php echo json_encode($aOptionTypes); ?>;
        var aData = <?php echo json_encode($aProductDetails); ?>;
        $.each(aTypes, function(i, val){
            var sheepItForm = $('#sheepItForm'+i).sheepIt({
                separator: '',
                allowRemoveLast: false,
                allowRemoveCurrent: true,
                allowRemoveAll: false,
                allowAdd: true,
                allowAddN: false,
                maxFormsCount: 10,
                minFormsCount: 1,
                iniFormsCount: 1,
                continuousIndex: true,
                data: aData[i],
                afterFill: function(source, form, values){
                    if(values){
                        $('#'+form.attr('id')+' .option_id').text(values.product_type_option_value_id);
                    }
                },
                removeCurrentConfirmation: true,
                removeCurrentConfirmationMsg: 'Are you sure you want to delete this attribute?',
                beforeRemoveCurrent: function(source){
                    $('#'+source.attr('id')+' tr .option_id').each(function(i, val){
                        var id = $(this).parents('tr').attr('id');
                        if($(this).text()){
                            aRemove[id] =  $(this).text();
                        }
                    });
                }
            });
        });
    });
    $('.remove-current').live('click',function(){
        var iRemove= $(this).parents('tr').attr('id');
        if(iRemove && $('#'+iRemove).length < 1 && aRemove[iRemove] != undefined){
            $.ajax({
                type: 'GET',
                url: '<?php echo makeUrl('catalog/attributes/delete', array('token=' . $this->session->data['token'])) ?>',
                dataType: 'json',
                data: 'option_id=' + aRemove[iRemove],
                success: function(data) {
                    iRemove = '';
                    if(data.error != undefined){
                        alert(data.error);
                    }
                }
            });
                    
        }
    });
    function validateForm(){
        var bValid = true;
        $('div.error').remove();
        $('.value-name').each(function(){
            if($.trim($(this).val()).length < 3){
                $(this).after('<div class="error">Feild is required. Must be greater then two characters');
                bValid = false;
            }
        })
        if(bValid){
            $('#form').submit();
        }
        return false;
    }

    //--></script>
