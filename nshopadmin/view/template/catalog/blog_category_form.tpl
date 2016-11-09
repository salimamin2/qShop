<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" >
    <input type="hidden" name="blog_categor_id" value="<?php echo $blog_categor_id; ?>">
    <div class="box table-wrapper products-table section">
        <div class="head well">
            <h3><i class="icon-th-list"></i> <?php echo $heading_title; ?>
                <div class="pull-right">
                        <button type="submit" class="btn-flat btn-success" ><span><?php echo __('button_save'); ?></span></button>
                        <a href="<?php echo $cancel; ?>" class="btn-flat btn-default"><span><?php echo __('button_cancel'); ?></span></a>
                </div>
            </h3>
        </div>
        <?php if ($error_warning) { ?>
            <div class="alert alert-danger"><?php echo $error_warning; ?></div>
        <?php } ?>
        <div class="tab-content">
            <div id="tab_general" class="tab-pane active">
                <ul id="languages" class="nav nav-tabs">
                    <?php
            			$i = 0;
                		foreach ($languages as $language) :
                    ?>
                    <li <?php if ($i == 0): ?>class="active"<?php endif; ?>>
                    <a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><span><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></span></a>
                    </li>
                    <?php
        			    $i++;
                        endforeach;
			         ?>
                </ul>
                <div class="tab-content">
                    <?php
			         foreach ($languages as $i => $language) :
                        $cat_descrip = (isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']] : array());
                    ?>
                        <div id="language<?php echo $language['language_id']; ?>" class="tab-pane <?php echo ($i == 0 ? 'active' : ''); ?>">
                            <table class="form">
                                <tr>
                                    <td><?php echo __('column_name'); ?><span class="required">*</span></td>
                                    <td><input type="text" class="form-control name" value="<?php echo $blog_category_name; ?>" name="category_description[<?php echo $language['language_id'] ?>][name]"> 
                                    <?php if (isset($error_name[$language['language_id']])) { ?>

                                    <span class="error"><?php echo $error_name[$language['language_id']]; ?></span>

                                    <?php } ?></td>
                                </tr>

                                <tr>
                                    <td><?php echo __('entry_seo_keword'); ?></td>
                                    <td><input type="text" class="form-control keyword" name="seo_keyword" value="<?php echo $blog_seo_keyword; ?>"></td>
                                </tr>

                                <tr>
                                    <td><?php echo __('entry_meta_title'); ?></td>
                                    <td><input type="text" class="form-control" value="<?php echo $blog_meta_title; ?>" name="category_description[<?php echo $language['language_id'] ?>][meta_title]"> </td>
                                </tr>

                                <tr>
                                    <td><?php echo __('entry_meta_link'); ?></td>
                                    <td><input type="text" class="form-control" value="<?php echo $blog_meta_link; ?>" name="category_description[<?php echo $language['language_id'] ?>][meta_link]"> </td>
                                </tr>

                                <tr>
                                    <td><?php echo __('entry_meta_keywords'); ?></td>
                                    <td><textarea rows="5" cols="40" class="form-control" name="category_description[<?php echo $language['language_id'] ?>][meta_keywords]"><?php echo $blog_meta_keywords; ?></textarea> </td>
                                </tr>

                                <tr>
                                    <td><?php echo __('entry_meta_desc'); ?></td>
                                    <td><textarea rows="5" cols="40" class="form-control" name="category_description[<?php echo $language['language_id'] ?>][meta_description]"><?php echo $blog_meta_description; ?></textarea> </td>
                                </tr>
                                <tr>
                                    <td><?php echo __('entry_status'); ?></td>
                                    <td>
                                        <div class="ui-select">
                                            <select class="form-control valid" name="category_description[<?php echo $language['language_id'] ?>][status]" id="status">
                                                <?php if($status=='0') { ?>
                                                <option value="0" selected="selected"><?php echo __('entry_disable'); ?></option>
                                                <option value="1"><?php echo __('entry_enable'); ?></option>
                                                <?php } else { ?>
                                                <option value="1" selected="selected"><?php echo __('entry_enable'); ?></option>
                                                <option value="0"><?php echo __('entry_disable'); ?></option>
                                                <?php } ?>
                                            </select></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo __('entry_sort_order'); ?></td>
                                    <td>
                                        <input size="1" name="category_description[<?php echo $language['language_id'] ?>][sort_order]" id="sort_order" type="text" value="<?php echo $sort_order; ?>">
                                    </td>
                                </tr>
                            </table>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</form>