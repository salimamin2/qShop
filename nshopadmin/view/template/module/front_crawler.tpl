

<?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php } ?>

<div class="box">

    <div class="left"></div>

    <div class="right"></div>

    <div class="heading">

        <h1 style="background-image: url('view/image/module.png');"><?php echo $heading_title; ?></h1>

        <div class="buttons"><a onclick="addRow();" class="button"><span><?php echo $button_add_row; ?></span></a><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>

    </div>

    <div class="content">

        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">

            <table class="form">

                <tr>

                    <td><?php echo $entry_status; ?></td>

                    <td>

                        <select name="frontcrlr_status">

                            <?php if ($frontcrlr_status) { ?>

                                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>

                                <option value="0"><?php echo $text_disabled; ?></option>

                            <?php } else { ?>

                                <option value="1"><?php echo $text_enabled; ?></option>

                                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>

                            <?php } ?>

                        </select>

                    </td>

                    <td><?php echo $entry_position; ?></td>

                    <td>

                        <select name="frontcrlr_position">

                            <?php foreach ($positions as $position) { ?>

                                <?php if ($frontcrlr_position == $position['position']) { ?>

                                    <option value="<?php echo $position['position']; ?>" selected="selected"><?php echo $position['title']; ?></option>

                                <?php } else { ?>

                                    <option value="<?php echo $position['position']; ?>"><?php echo $position['title']; ?></option>

                                <?php } ?>

                            <?php } ?>

                        </select>

                    </td>

                </tr>

                <tr>

                    <td colspan="4">

                        <table class="list" id="frontss_image">

                            <thead>

                                <tr>

                                    <td class="center" width="15%"><?php echo $entry_s_no; ?></td>

                                    <td class="left" width="25%"><?php echo $entry_image; ?></td>

                                    <td class="left" width="25%"><?php echo $entry_link; ?></td>

                                    <td class="left" width="20%"><?php echo $entry_sort_order; ?></td>

                                    <td class="center" width="15%"><?php echo $entry_action; ?></td>

                                </tr>

                            </thead>

                            <tbody>

                                <?php $i = 1;

                                foreach ($frontcrlr as $front): ?>

                                    <tr class="<?php echo $i; ?>">

                                        <?php if ($front['image_info'] == 'image'): ?>

                                            <td class="center"><?php echo $i . ". "; ?></td>

                                            <td class="left">

                                                <input type="hidden" name="frontcrlr_image[<?php echo $i ?>]" value="<?php echo $front['image_value'] ?>" id="image<?php echo $i ?>" />

                                                <img src="<?php echo $image_url . $front['image_value'] ?>" alt="" id="preview_image<?php echo $i ?>" style="border: 1px solid #EEEEEE;" width="100" height="100" />&nbsp;<img src="view/image/image.png" alt="" style="cursor: pointer;" align="top" onclick="image_upload('image<?php echo $i ?>', 'preview_image<?php echo $i ?>');" />

                                            </td>

                                            <td class="left"><input type="text" name="frontcrlr_link[<?php echo $i ?>]" value="<?php echo $front['link_value'] ?>" /></td>

                                        <?php endif; ?>

                                        <td class="left"><input type="text" style="width:100px;" name="frontcrlr_sort_order[<?php echo $i ?>]" value="<?php echo $front['sort_order_value'] ?>" /></td>

                                        <td class="center">[ <a href="javascript:void(0);" onclick="removeRow(<?php echo $i ?>);"><?php echo $button_remove; ?></a> ]</td>

                                    </tr>

                                <?php $i++;

                                endforeach; ?>

                            </tbody>

                        </table>

                    </td>

                </tr>

            </table>

        </form>

    </div>

</div>

<script type="text/javascript" src="view/javascript/jquery/ui/ui.draggable.js"></script>

<script type="text/javascript" src="view/javascript/jquery/ui/ui.resizable.js"></script>

<script type="text/javascript" src="view/javascript/jquery/ui/ui.dialog.js"></script>

<script type="text/javascript" src="view/javascript/jquery/ui/external/bgiframe/jquery.bgiframe.js"></script>

<script type="text/javascript"><!--

    var init = <?php echo $i ?>;

    var cnt=0;

    function removeRow(i) {

        $('tr.'+i).remove();

        cnt = init-1;

    }

    function addRow(){

        if(cnt != 0 && init != cnt){

            init = cnt;

        }

        var html ="<tr class="+cnt+">"+

            "<td class=\"center\">"+ init +".</td>"+

            "<td class=\"left\"><input type=\"hidden\" name=\"frontcrlr_image["+ init +"]\" value=\"data/logo.png\" id=\"image"+init+"\" />"+

            "<img src=\"\" alt=\"\" id=\"preview_image"+init+"\" style=\"border: 1px solid #EEEEEE;\" width=\"100\" height=\"100\" />&nbsp;<img src=\"view/image/image.png\" alt=\"\" style=\"cursor: pointer;\" align=\"top\" onclick=\"image_upload('image"+init+"', 'preview_image"+init+"');\" /></td>"+

            "<td class=\"left\"><input type=\"text\" name=\"frontcrlr_link["+init+"]\" value=\"\" /></td>"+

            "<td class=\"left\"><input type=\"text\" name=\"frontcrlr_sort_order["+init+"]\" value=\"\" style=\"width:100px;\"  /></td>"+

            "<td class=\"center\">[ <a href=\"javascript:void(0);\" onclick=\"removeRow("+init+");\"><?php echo $button_remove; ?></a> ]</td>"+

            "</tr>";

        $('#frontss_image').append(html);

        cnt = init+1;

    }

    //   function addRow(){

    //        

    //       if(cnt != 0 && init != cnt){

    //           init = cnt;

    //       }

    //        var html ="<tr>"+

    //                    "<td><?php echo $entry_image ?> "+ init +"</td>"+

    //                    "<td><input type=\"hidden\" name=\"front_slideshow_image["+ init +"]\" value=\"data/logo.png\" id=\"image"+init+"\" />"+

    //                      "<img src=\"\" alt=\"\" id=\"preview_image"+init+"\" style=\"border: 1px solid #EEEEEE;\" width=\"100\" height=\"100\" />&nbsp;<img src=\"view/image/image.png\" alt=\"\" style=\"cursor: pointer;\" align=\"top\" onclick=\"image_upload('image"+init+"', 'preview_image"+init+"');\" /></td>"+

    //                "</tr>"+

    //                "<tr>"+

    //                    "<td><?php echo $entry_link ?> "+init+"</td>"+

    //                    "<td><input type=\"text\" name=\"front_slideshow_link["+init+"]\" value=\"\" /></td>"+

    //                "</tr>";

    //        $('#front_slideshow_image').append(html);

    //        cnt = init+1;

    //    }

    function image_upload(field, preview) {

        $('#dialog').remove();



        $('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="common/filemanager&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');



        $('#dialog').dialog({

            title: '<?php echo $text_image_manager; ?>',

            close: function (event, ui) {

                if ($('#' + field).attr('value')) {

                    $.ajax({

                        url: 'common/filemanager/image',

                        type: 'POST',

                        data: 'image=' + encodeURIComponent($('#' + field).val()),

                        dataType: 'text',

                        success: function(data) {

                            $('#' + preview).replaceWith('<img src="' + data + '" alt="" id="' + preview + '" style="border: 1px solid #EEEEEE;" />');

                        }

                    });

                }

            },

            bgiframe: false,

            width: 700,

            height: 400,

            resizable: false,

            modal: false

        });

    };

    //--></script>

