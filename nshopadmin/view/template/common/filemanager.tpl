<div id="container" class="box filemanager">

    <form method="post" enctype="multipart/form-data" >

    <input type="hidden" name="directory" id="directory" value="<?php echo $directory; ?>" />



<div class="head well">
    <h3><i class="icon-folder-open"></i> <?php echo $title; ?>
        <div class="pull-right">
            <div class="buttons" id="menu">
                <a id="create" class="btn btn-success btn-sm" title="<?php echo $button_folder; ?>"><i class="icon-folder-open"></i></a>

                <a id="delete" class="btn btn-danger btn-sm" title="<?php echo $button_delete; ?>"><i class="icon-trash"></i></a>

                <a id="move" class="btn btn-info btn-sm" title="<?php echo $button_move; ?>"><i class="icon-cut"></i></a>

                <a id="copy" class="btn btn-info btn-sm" title="<?php echo $button_copy; ?>"><i class="icon-copy"></i></a>

                <a id="rename" class="btn btn-info btn-sm" title="<?php echo $button_rename; ?>"><i class="icon-repeat"></i></a>

                <span class="btn btn-info btn-sm fileinput-button" title="<?php echo $button_upload; ?>"><i class="icon-upload-alt"></i>

                    <input type="file" name="image" id="fileupload">

                </span>

                <a id="refresh" class="btn btn-info btn-sm" title="<?php echo $button_refresh; ?>"><i class="icon-refresh"></i></a>
            </div>
        </div>
    </h3>
</div>


        <div class="content">
            <div class="row">
                <div class="filter col-sm-2">
                    <div class="form-group has-primary">
                        <input type="text" id="filter_text" name="filter" value="" placeholder="<?php echo __('text_search'); ?>" class="form-control" />
                        <a id="filter" data-toggle="tooltip" title="<?php echo __('text_search'); ?>" class="feedback-icon glyphicon icon-search form-control-feedback" aria-hidden="true"></a>
                    </div>
                </div>
                <div class="link form-group col-sm-10">
                    <div class="input-group">
                        <label class="input-group-addon"><?php echo __('text_link'); ?></label>
                        <a data-toggle="tooltip" title="Select and copy the link. Use this link to add images in editor" class="feedback-icon glyphicon icon-question form-control-feedback" aria-hidden="true"></a>
                        <b class="form-control link_text"></b>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
            <input type="hidden" id="page" name="page" /><input type="hidden" id="limit" name="limit" />
            <div class="row">
                <div class="col-sm-2">
                    <div id="column_left" class="panel panel-default"></div>
                </div>
                <div class="col-sm-10">
                    <div id="column_right" class="panel panel-default"></div>
                </div>
                <div class="clear"></div>
            </div>
            <div class="pagination" id="pagination"></div>
        </div>

    </form>

</div>

<script type="text/javascript"><!--
var sUrl = '<?php echo $directory_url; ?>';
$(document).ready(function () {

    $('#column_left').tree({

        data: {

            type: 'json',

            async: true,

            opts: {

                method: 'POST',

                url: 'common/filemanager/directory&token=<?php echo $token; ?>'

            }

        },

        selected: 'top',

        ui: {

            theme_name: 'classic',

            animation: 700

        },

        types: {

            'default': {

                clickable: true,

                creatable: false,

                renameable: false,

                deletable: false,

                draggable: false,

                max_children: -1,

                max_depth: -1,

                valid_children: 'all'

            }

        },

        callback: {

            beforedata: function(NODE, TREE_OBJ) {

                if (NODE == false) {

                    TREE_OBJ.settings.data.opts.static = [

                        {

                            data: 'image',

                            attributes: {

                                'id': 'top',

                                'directory': ''

                            },

                            state: 'closed'

                        }

                    ];



                    return { 'directory': '' }

                } else {

                    TREE_OBJ.settings.data.opts.static = false;



                    return { 'directory': $(NODE).attr('directory') }

                }

            },

            onselect: function (NODE, TREE_OBJ) {

                directory = encodeURIComponent($(NODE).attr('directory'));

                filter = encodeURIComponent($('#filter_text').val());

                page = 1;

                limit = 20;

                $('#directory').val((directory ? directory : ''));

                getFiles(directory,filter,page,limit);

            }

        }

    });



    $('#column_right a').live('click', function () {

        if ($(this).attr('class') == 'selected') {
            $('.link_text').html('');
            $(this).removeAttr('class');

        } else {

            $('#column_right a').removeAttr('class');
            $('.link_text').html(sUrl + $(this).attr('file'));


            $(this).attr('class', 'selected');

        }

    });



    $('#column_right a').live('dblclick', function () {

        <?php if ($fckeditor) { ?>

        window.opener.CKEDITOR.tools.callFunction(1, '<?php echo $directory; ?>' + $(this).attr('file'));

        self.close();

        <?php } else { ?>

        parent.$('#<?php echo $field; ?>').attr('value', 'data/' + $(this).attr('file'));

        parent.$('#dialog').dialog('close');

        parent.$('#dialog').remove();

        <?php } ?>

    });



    $('#create').bind('click', function () {

        var tree = $.tree.focused();

        if (tree.selected) {

            $('#dialog').remove();

            html  = '<div id="dialog">';

            html += '<?php echo $entry_folder; ?> <input type="text" name="name" value="" /> <input type="button" value="Submit" />';

            html += '</div>';

            $('#column_right').prepend(html);

            $('#dialog').dialog({

                title: '<?php echo $button_folder; ?>',

                resizable: false

            });



            $('#dialog input[type=\'button\']').bind('click', function () {

                $.ajax({

                    url: 'common/filemanager/create&token=<?php echo $token; ?>',

                    type: 'POST',

                    data: 'directory=' + encodeURIComponent($(tree.selected).attr('directory')) + '&name=' + encodeURIComponent($('#dialog input[name=\'name\']').val()),

                    dataType: 'json',

                    success: function(json) {

                        if (json.success) {

                            $('#dialog').remove();



                            tree.refresh(tree.selected);



                            alert(json.success);

                        } else {

                            alert(json.error);

                        }

                    }

                });

            });

        } else {

            alert('<?php echo $error_directory; ?>');

        }

    });



    $('#delete').bind('click', function () {

        path = $('#column_right a.selected').attr('file');



        if (path) {

            $.ajax({

                url: 'common/filemanager/delete&token=<?php echo $token; ?>',

                type: 'POST',

                data: 'path=' + path,

                dataType: 'json',

                success: function(json) {

                    if (json.success) {

                        var tree = $.tree.focused();



                        tree.select_branch(tree.selected);



                        alert(json.success);

                    }



                    if (json.error) {

                        alert(json.error);

                    }

                }

            });

        } else {

            var tree = $.tree.focused();



            if (tree.selected) {

                $.ajax({

                    url: 'common/filemanager/delete&token=<?php echo $token; ?>',

                    type: 'POST',

                    data: 'path=' + encodeURIComponent($(tree.selected).attr('directory')),

                    dataType: 'json',

                    success: function(json) {

                        if (json.success) {

                            tree.select_branch(tree.parent(tree.selected));



                            tree.refresh(tree.selected);



                            alert(json.success);

                        }



                        if (json.error) {

                            alert(json.error);

                        }

                    }

                });

            } else {

                alert('<?php echo $error_select; ?>');

            }

        }

    });



    $('#move').bind('click', function () {

        $('#dialog').remove();



        html  = '<div id="dialog">';

        html += '<?php echo $entry_move; ?> <select name="to"></select> <input type="button" value="Submit" />';

        html += '</div>';



        $('#column_right').prepend(html);



        $('#dialog').dialog({

            title: '<?php echo $button_move; ?>',

            resizable: false

        });



        $('#dialog select[name=\'to\']').load('common/filemanager/folders&token=<?php echo $token; ?>');



        $('#dialog input[type=\'button\']').bind('click', function () {

            path = $('#column_right a.selected').attr('file');



            if (path) {

                $.ajax({

                    url: 'common/filemanager/move&token=<?php echo $token; ?>',

                    type: 'POST',

                    data: 'from=' + encodeURIComponent(path) + '&to=' + encodeURIComponent($('#dialog select[name=\'to\']').val()),

                    dataType: 'json',

                    success: function(json) {

                        if (json.success) {

                            $('#dialog').remove();



                            var tree = $.tree.focused();



                            tree.select_branch(tree.selected);



                            alert(json.success);

                        }



                        if (json.error) {

                            alert(json.error);

                        }

                    }

                });

            } else {

                var tree = $.tree.focused();



                $.ajax({

                    url: 'common/filemanager/move&token=<?php echo $token; ?>',

                    type: 'POST',

                    data: 'from=' + encodeURIComponent($(tree.selected).attr('directory')) + '&to=' + encodeURIComponent($('#dialog select[name=\'to\']').val()),

                    dataType: 'json',

                    success: function(json) {

                        if (json.success) {

                            $('#dialog').remove();



                            tree.select_branch('#top');



                            tree.refresh(tree.selected);



                            alert(json.success);

                        }



                        if (json.error) {

                            alert(json.error);

                        }

                    }

                });

            }

        });

    });



    $('#copy').bind('click', function () {

        $('#dialog').remove();



        html  = '<div id="dialog">';

        html += '<?php echo $entry_copy; ?> <input type="text" name="name" value="" /> <input type="button" value="Submit" />';

        html += '</div>';



        $('#column_right').prepend(html);



        $('#dialog').dialog({

            title: '<?php echo $button_copy; ?>',

            resizable: false

        });



        $('#dialog select[name=\'to\']').load('common/filemanager/folders&token=<?php echo $token; ?>');



        $('#dialog input[type=\'button\']').bind('click', function () {

            path = $('#column_right a.selected').attr('file');



            if (path) {

                $.ajax({

                    url: 'common/filemanager/copy&token=<?php echo $token; ?>',

                    type: 'POST',

                    data: 'path=' + encodeURIComponent(path) + '&name=' + encodeURIComponent($('#dialog input[name=\'name\']').val()),

                    dataType: 'json',

                    success: function(json) {

                        if (json.success) {

                            $('#dialog').remove();



                            var tree = $.tree.focused();



                            tree.select_branch(tree.selected);



                            alert(json.success);

                        }



                        if (json.error) {

                            alert(json.error);

                        }

                    }

                });

            } else {

                var tree = $.tree.focused();



                $.ajax({

                    url: 'common/filemanager/copy&token=<?php echo $token; ?>',

                    type: 'POST',

                    data: 'path=' + encodeURIComponent($(tree.selected).attr('directory')) + '&name=' + encodeURIComponent($('#dialog input[name=\'name\']').val()),

                    dataType: 'json',

                    success: function(json) {

                        if (json.success) {

                            $('#dialog').remove();



                            tree.select_branch(tree.parent(tree.selected));



                            tree.refresh(tree.selected);



                            alert(json.success);

                        }



                        if (json.error) {

                            alert(json.error);

                        }

                    }

                });

            }

        });

    });



    $('#rename').bind('click', function () {

        $('#dialog').remove();



        html  = '<div id="dialog">';

        html += '<?php echo $entry_rename; ?> <input type="text" name="name" value="" /> <input type="button" value="Submit" />';

        html += '</div>';



        $('#column_right').prepend(html);



        $('#dialog').dialog({

            title: '<?php echo $button_rename; ?>',

            resizable: false

        });



        $('#dialog input[type=\'button\']').bind('click', function () {

            path = $('#column_right a.selected').attr('file');



            if (path) {

                $.ajax({

                    url: 'common/filemanager/rename&token=<?php echo $token; ?>',

                    type: 'POST',

                    data: 'path=' + encodeURIComponent(path) + '&name=' + encodeURIComponent($('#dialog input[name=\'name\']').val()),

                    dataType: 'json',

                    success: function(json) {

                        if (json.success) {

                            $('#dialog').remove();



                            var tree = $.tree.focused();



                            tree.select_branch(tree.selected);



                            alert(json.success);

                        }



                        if (json.error) {

                            alert(json.error);

                        }

                    }

                });

            } else {

                var tree = $.tree.focused();



                $.ajax({

                    url: 'common/filemanager/rename&token=<?php echo $token; ?>',

                    type: 'POST',

                    data: 'path=' + encodeURIComponent($(tree.selected).attr('directory')) + '&name=' + encodeURIComponent($('#dialog input[name=\'name\']').val()),

                    dataType: 'json',

                    success: function(json) {

                        if (json.success) {

                            $('#dialog').remove();



                            tree.select_branch(tree.parent(tree.selected));



                            tree.refresh(tree.selected);



                            alert(json.success);

                        }



                        if (json.error) {

                            alert(json.error);

                        }

                    }

                });

            }

        });

    });



    $('#fileupload').fileupload({

        url: 'common/filemanager/upload',

        dataType: 'json',

        onChange: function(file, extension) {

            var tree = $.tree.focused();



            if (tree.selected) {

                this.setData({'directory': $(tree.selected).attr('directory')});

            } else {

                this.setData({'directory': ''});

            }



            this.submit();

        },

        start: function(file, extension) {

            $('#upload').append('<img src="view/image/loading.gif" id="loading" style="padding-left: 5px;" />');

        },

        done: function(e, data) {

            var result = data.result;

            if (result.success) {

                var tree = $.tree.focused();



                tree.select_branch(tree.selected);



                alert(result.success);

            }



            if (result.error) {

                alert(result.error);

            }



            $('#loading').remove();

        }

    });



    $('#refresh').bind('click', function () {

        var tree = $.tree.focused();



        tree.refresh(tree.selected);

    });



    $('#filter').bind('click', function () {

        var tree = $.tree.focused();

        directory = encodeURIComponent($(tree.selected).attr('directory'));

        filter = encodeURIComponent($('#filter_text').val());

        page = encodeURIComponent($('#page').val());

        limit = encodeURIComponent($('#limit').val());

        getFiles(directory,filter,page,limit);

    });

});



function getFiles(directory,filter,page,limit) {

    $.ajax({

        url: 'common/filemanager/files&token=<?php echo $token; ?>',

        type: 'POST',

        data: 'directory=' + directory + '&filter=' + filter + '&page=' + page + '&limit=' + limit,

        dataType: 'json',

        success: function(data) {

            html = '<div>';

            json = data['json'];

            pagination = data['pagination'];

            if (json) {

                for (i = 0; i < json.length; i++) {



                    name = '';



                    filename = json[i]['filename'];



                    for (j = 0; j < filename.length; j = j + 15) {

                        name += filename.substr(j, 15) + '<br />';

                    }



                    name += json[i]['size'];



                    html += '<a file="' + json[i]['file'] + '"><img src="' + json[i]['thumb'] + '" title="' + json[i]['filename'] + '" /><br />' + name + '</a>';

                }

            }

            html += '</div>';

            $('#column_right').html(html);



            if(pagination) {

                $('#pagination').html(pagination);

            }

        }

    });



}

$('.page').live('click',

function () {

    var rel = $(this).attr('rel');

    var id = rel.split('-');

    $('#page').val(id[0]);

    $('#limit').val(id[1]);

    $('#filter').trigger('click');

});

//--></script>