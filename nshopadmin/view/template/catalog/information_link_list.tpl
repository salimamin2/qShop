<div class="box">
    <div class="head well">
            <h3>
                <i class="icon-th-list"></i> <?php echo $heading_title; ?>
                <div class="pull-right">
                        <a href="<?php echo $insert; ?>" class="btn-flat success">+ <span><?php echo __('button_insert'); ?></span></a> 
                        <button type="submit" class="btn-flat btn-danger"><span><?php echo __('button_delete'); ?></span></button>
                </div>
            </h3>
        </div>
    <?php if ($error_warning) { ?>
        <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>

    <div class="content holiday">

        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">

            <table class="list" cellpadding="4">

                <thead>

                    <tr>

                        <td class="left"><?php echo __('column_title') ?></td>

                        <td class="left"><?php echo __('column_information') ?></td>

                        <td class="left"><?php echo __('column_block') ?></td>

                        <td class="left"><?php echo __('column_link') ?></td>

                        <td class="left"><?php echo __('column_sort_order') ?></td>

                        <td class="left"></td>

                    </tr>

                </thead>

                <?php foreach ($aResults as $i => $aResult): ?>

                    <tbody id="row_<?php echo $i ?>">

                        <tr>

                            <td><input type="hidden" name="link[<?php echo $i ?>][id]" value="<?php echo $aResult['id'] ?>" />

                                <input type="text" name="link[<?php echo $i ?>][title]" value="<?php echo $aResult['title'] ?>" /></td>

                            <td><select name="link[<?php echo $i ?>][information_id]">

                                    <option value="0" <?php echo ($aResult['information_id'] == 0) ? 'selected="selected"' : ''; ?>><?php echo __('Select Page'); ?></option>

                                    <?php foreach ($aInformations as $aInfo): ?>

                                        <option value="<?php echo $aInfo['information_id'] ?>"  <?php echo ($aResult['information_id'] == $aInfo['information_id']) ? 'selected="selected"' : ''; ?>><?php echo $aInfo['title']; ?></option>

                                    <?php endforeach; ?>

                                </select></td>

                            <td><select name="link[<?php echo $i ?>][place_code]">

                                    <?php foreach ($aBlocks as $skey => $sBlock): ?>

                                        <option value="<?php echo $skey ?>" <?php echo ($aResult['place_code'] == $skey) ? 'selected="selected"' : ''; ?>><?php echo $sBlock; ?></option>

                                    <?php endforeach; ?>

                                </select></td>

                                <td><input type="text" name="link[<?php echo $i ?>][link]" value="<?php echo $aResult['link'] ?>" /></td>

                            <td><input type="text" name="link[<?php echo $i ?>][sort_order]" value="<?php echo $aResult['sort_order'] ?>" /></td>

                            <td><a class="button" onclick="removeRow('row_<?php echo $i ?>',<?php echo $aResult['id'] ?>)"><span><?php echo __('button_remove') ?></span></a></td>

                        </tr>

                    </tbody>

                <?php endforeach; ?>

                <tfoot>

                    <tr>

                        <td colspan="5"></td>

                        <td class="left"><a onclick="addForm();" class="button"><span><?php echo __('Add Link'); ?></span></a></td>

                    </tr>

                </tfoot>

            </table>

        </form>

    </div>

</div>



<script type="text/javascript"><!--



    function addForm() {

        var aId = [];

        var row = $('.list tbody').length;

        html  = '<tbody id="row_' + row + '">'+

            '<tr>'+

            '<td><input type="text" name="link[' + row + '][title]" /></td>'+

            '<td><select name="link[' + row + '][information_id]">'+

            '<option value="0"><?php echo __('Select Page'); ?></option>'+

<?php foreach ($aInformations as $aInfo) { ?>

            '<option value="<?php echo $aInfo['information_id'] ?>"><?php echo $aInfo['title']; ?></option>'+

<?php } ?>

        '</select></td>'+

            '<td><select name="link[' + row + '][place_code]">'+

<?php foreach ($aBlocks as $skey => $sBlock) { ?>

            '<option value="<?php echo $skey ?>"><?php echo $sBlock; ?></option>'+

<?php } ?>

        '</select></td>'+

            '<td><input type="text" name="link[' + row + '][link]" /></td>'+

            '<td><input type="text" name="link[' + row + '][sort_order]" /></td>'+

            '<td><a class="button" onclick="$(\'#row_'+row+'\').remove();"><span><?php echo __('button_remove') ?></span></a></td>'+

            '</tr>'+

            '</tbody>';

	

        $('.list tfoot').before(html);

    }

    

    function removeRow(iRow, iId){

        $.ajax({

            url: "<?php echo $delete ?>",

            type: "POST",

            dataType: "json",

            data: "link_id="+iId,

            success: function(res) {                    

                if(res.success){

                    $('.content.holiday').before('<div class="success hide">'+res.success+'</div>');

                    $('.success').slideDown(500).delay(2000).slideUp(500).queue(function() {

                        $(this).remove();

                    });

                    $('#'+iRow).remove();

                } else if(res.error){

                    $('.content.holiday').before('<div class="warning hide">'+res.error+'</div>');

                    $('.warning').slideDown(500).delay(2000).slideUp(500).queue(function() {

                        $(this).remove();

                    });

                }

            }

        });

    }

    



    //--></script>