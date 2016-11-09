<form action="" method="post" enctype="multipart/form-data" id="form">
    <div class="box">
        <div class="head well">
            <h3>
                <i class="icon-folder-open"></i> <?php echo $heading_title; ?>
                <div class="pull-right">
                    <a href="<?php echo $insert; ?>" class="btn-flat success"><span>+ <?php echo $button_insert; ?></span></a>
                    <button type="button" onClick="$('#form').attr('action', '<?php echo $delete; ?>').submit();"  class="btn-flat btn-danger btn-sm"><span><?php echo $button_delete; ?></span></button>
                </div>
            </h3>
        </div>
            <?php if ($error_warning) { ?>
                <div class="alert alert-danger"><?php echo $error_warning; ?></div>
            <?php } ?>
            <?php if ($success) { ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php } ?>
        <div class="content">
            <table class="table table-hover" data-rel="ajax-grid" data-grid-url="<?php echo $sblogCateList ?>">
                <thead>
                    <tr>
                        <th width="1%" style="text-align: center;"><input type="checkbox" onClick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></th>
                        <th class="left"><span class="line"></span><?php echo __('post id'); ?></th>
                        <th class="left"><span class="line"></span><?php echo __('column_post_title'); ?></th>
                        <th class="left"><span class="line"></span><?php echo __('column_author_fname'); ?></th>
                        <th class="right"><span class="line"></span><?php echo __('entry_sort_order'); ?></th>
                        <th class="right"><span class="line"></span><?php echo $column_action; ?></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</form>