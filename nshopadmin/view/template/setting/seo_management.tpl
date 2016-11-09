<div class="box table-wrapper products-table section">
    <div class="head well">
        <h3><i class="icon-cog"></i> <?php echo $heading_title; ?></h3>
    </div>
    <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><?php echo $error_warning; ?></div>
    <?php } ?>
    <?php if ($success) { ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php } ?>

        <div class="content">

        <ul class="nav nav-tabs">

            <?php $i = 0;

            foreach($groups as $sGroup): ?>

            <li <?php echo ($sGroup == $group ? "class='active'" : ''); ?>><a href="#tab_<?php echo strtolower($sGroup); ?>" data-toggle="tab" style="text-transform: capitalize;"><span><?php echo str_replace('_', ' ',$sGroup); ?></span></a></li>

            <?php $i++;

            endforeach; ?>

        </ul>



        <form action="<?php echo $form_action; ?>" method="post" enctype="multipart/form-data" id="form_seo">

            <table class="form">

                <tr>

                    <td> <?php echo $entry_query; ?><span class="required">*</span></td>

                    <td>

                        <input type="text" class="form-control" name="query" value="<?php echo (isset($query)) ? $query : ''; ?>" size="40" />

                        <?php if ($error_query) { ?>

                        <span class="error"><?php echo $error_query; ?></span>

                        <?php } ?>

                    </td>

                </tr>

                <tr>

                    <td> <?php echo $entry_keyword; ?><span class="required">*</span></td>

                    <td><input type="text" class="form-control" name="keyword" value="<?php echo (isset($keyword) ) ? $keyword : ''; ?>" size="40" />

                        <?php if ($error_keyword) { ?>

                        <span class="error"><?php echo $error_keyword; ?></span>

                        <?php } ?>

                    </td>

                </tr>

                <tr>

                    <td colspan="3">

                        <button type="submit" class="btn-flat btn-success btn-sm"><span><?php echo $button_save; ?></span></button>

                        <button type="reset" class="btn-flat btn-default btn-sm"><span><?php echo $button_cancel; ?></span></button>

                    </td>

                </tr>

            </table>

            <div class="tab-content">

        <?php $i = 0;

        foreach($seo_urls as $key=>$seo_url): ?>

                <div id="tab_<?php echo strtolower($key); ?>" class="tab-pane <?php echo ($key == $group ? 'active' : ''); ?>">

                    <div class="table-responsive">

                        <table class="table table-hover" data-rel="data-grid">

                            <thead>

                                <tr class="filter">

                                    <th class="left"><?php echo $entry_query; ?></th>

                                    <th class="left"><?php echo $entry_keyword; ?></th>

                                    <th class="center"><?php echo $column_action; ?></th>

                                </tr>

                            </thead>

                            <tbody>

                    <?php foreach($seo_urls[$key] as $common): ?>

                                <tr>

                                    <td class="left"><?php echo $common['query']; ?></td>

                                    <td class="left"><?php echo $common['keyword']; ?></td>

                                    <td class="center">

                                        <a data-toggle="tooltip" href="javascript:void(0)" class="btn btn-info edit_seo" title="<?php echo $text_edit; ?>" data-rel="<?php echo $common['url_alias_id']; ?>"><i class="icon-pencil"></i></a>  <a data-toggle="tooltip" href="#" class="btn btn-danger delete_seo" title="<?php echo $text_delete; ?>" data-rel="<?php echo $common['url_alias_id']; ?>"><i class="icon-trash"></i></a>


                                    </td>

                                </tr>

                    <?php endforeach; ?>

                            </tbody>

                        </table>
                    </div>
                </div>

        <?php $i++;

        endforeach; ?>

            </div>

            <input type="hidden" id="group" name="group" value="<?php echo $group; ?>" />

        </form>

    </div>

</div>

<script type="text/javascript">

    $(document).on('click','.edit_seo', function(e) {

        e.preventDefault();

        var name = $(this).closest('tr').find('td:nth-child(1)').text();

        var keyword = $(this).closest('tr').find('td:nth-child(2)').text();

        var alias_id = $(this).attr('data-rel');

        $('input[name="query"]').val(name);

        $('input[name="keyword"]').val(keyword);

        $('form#form_seo').attr('action', '<?php echo $edit_action; ?>' + '&url_alias_id=' + alias_id);

        return false;

    });



    $(document).on('click', '.delete_seo',function(e) {

        e.preventDefault();

        var group = $('#group').val();

        var alias_id = $(this).attr('data-rel');

//        $('form#form_seo').attr('action','<?php echo $delete_action; ?>' + '&url_alias_id=' + alias_id + '&group=' + group);

        location.href = '<?php echo $delete_action; ?>' + '&url_alias_id=' + alias_id + '&group=' + group;

        return false;

    });



    $('a[data-toggle="tab"]').on('shown', function(e) {

        var group = $(e.target).attr('href').replace('#tab_', '');

        $('input[name="query"]').val('');

        $('input[name="keyword"]').val('');

        $('#group').val(group);

        if (group != "custom") {
            var sQuery =  group + "_id=";
            /*if(group == "category"){
                sQuery = "path=";
            }*/
            if(group == "blog_authors"){
                sQuery = "author_id=";
            }
            $('input[name="query"]').val(sQuery);

        }

    });

</script>

