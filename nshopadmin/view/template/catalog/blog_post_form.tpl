<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" >
    <input type="hidden" name="post_id" value="<?php echo (isset($post_id) ? $post_id : ''); ?>">
    <input type="hidden" name="directory" value="<?php echo $directory; ?>" />
    <div class="box table-wrapper products-table section">
        <div class="head well">
            <h3>
                <i class="icon-th-list"></i> <?php echo $heading_title; ?>
                <div class="pull-right">
                    <button type="submit" class="btn-flat btn-success btn-sm" ><span><?php echo __('button_save'); ?></span></button>
                    <a href="<?php echo $cancel; ?>" class="btn-flat btn-default btn-sm"><span><?php echo __('button_cancel'); ?></span></a>
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
                    $cat_descrip = (isset($blogs[$language['language_id']]) ? $blogs[$language['language_id']] : array());
                    ?>
                    <div id="language<?php echo $language['language_id']; ?>" class="tab-pane <?php echo ($i == 0 ? 'active' : ''); ?>">
                        <table class="form">
                            <tr>
                                <td><?php echo __('entry_post_title'); ?><span class="required">*</span></td>
                                <td><input type="text" class="form-control name" value="<?php echo $cat_descrip['title']; ?>" name="description[<?php echo $language['language_id'] ?>][title]">
                                <?php if (isset($error_name[$language['language_id']])) { ?>
                                    <span class="error"><?php echo $error_name[$language['language_id']]; ?></span>
                                <?php } ?>
                                </td>
                                
                            </tr>
                            <tr>
                                <td><?php echo __('entry_post_description'); ?><span class="required">*</span></td>
                                <td><textarea rows="5" cols="40" class="form-control" name="description[<?php echo $language['language_id'] ?>][description]" data-rel="wyswyg"><?php echo $cat_descrip['description']; ?></textarea>
                                <?php if (isset($error_description[$language['language_id']])) { ?>
                                <span class="error"><?php echo $error_description[$language['language_id']]; ?></span>
                                <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo __('entry_post_meta_title'); ?></td>
                                <td><input type="text" class="form-control" value="<?php echo $cat_descrip['meta_title']; ?>" name="description[<?php echo $language['language_id'] ?>][meta_title]">
                                </td>
                                
                            </tr>
                            <tr>
                                <td><?php echo __('entry_post_meta_link'); ?></td>
                                <td><input type="text" class="form-control" value="<?php echo $cat_descrip['meta_link']; ?>" name="description[<?php echo $language['language_id'] ?>][meta_link]"> </td>
                            </tr>
                            <tr>
                                <td><?php echo __('entry_post_meta_keywords'); ?></td>
                                <td><textarea rows="5" cols="40" class="form-control" name="description[<?php echo $language['language_id'] ?>][meta_keywords]"><?php echo $cat_descrip['meta_keywords']; ?></textarea> </td>
                            </tr>
                            <tr>
                                <td><?php echo __('entry_post_meta_description'); ?></td>
                                <td><textarea rows="5" cols="40" class="form-control" name="description[<?php echo $language['language_id'] ?>][meta_description]"><?php echo $cat_descrip['meta_description']; ?></textarea> </td>
                            </tr>
                        </table>
                    </div>
                    <table class="form">
                        <tr>
                            <td><?php echo __('entry_post_blog_category'); ?></td>
                            <td>
                                <div class="ui-select">
                                    <select class="form-control valid" name="blog[blog_category_id]" id="category">
                                        <?php foreach($blog_category as $category) { ?>
                                        <option <?php echo ($blog_category_id == $category['id'] ? 'selected' : ''); ?> value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo __('entry_post_blog_author'); ?></td>
                            <td>
                                <div class="ui-select">
                                    <select class="form-control valid" name="blog[author_id]" id="author">
                                        <?php foreach($blog_author as $author) { ?>
                                       <?php if($author_id==$author['author_id']) { ?>
                                        <option value="<?php echo $author['author_id']; ?>" selected="selected"><?php echo $author['first_name'].' '.$author['last_name']; ?></option>
                                        <?php } else { ?>
                                        <option value="<?php echo $author['author_id']; ?>"><?php echo $author['first_name'].' '.$author['last_name']; ?></option>
                                        <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo __('entry_post_seo_keyword'); ?></td>
                            <td><input type="text" class="form-control keyword" value="<?php echo $seo_keyword; ?>" name="keyword"> </td>
                        </tr>
                        <tr>
                            <td><?php echo __('entry_post_image'); ?></td>
                            <td>
                                <input type="hidden" name="image" value="<?php echo $image; ?>" id="image" />
                                <img src="<?php echo $preview; ?>" alt="" id="preview" class="image" width="122" height="" />
                              <span class="btn btn-success btn-sm fileinput-button"><i class="icon-camera"></i>
                                <span>Select Image...</span>
                                <input type="file" name="image" id="fileupload">
                              </span>
                            </td>
                        </tr>
                        <tr>
                            <td>Thumb</td>
                            <td>
                                <input type="hidden" name="thumb" value="<?php echo $thumb; ?>" id="image" />
                                <img src="<?php echo $preview_thumb; ?>" alt="" id="preview1" class="image" width="122" height="" />
                              <span class="btn btn-success btn-sm fileinput-button"><i class="icon-camera"></i>
                                <span>Select Image...</span>
                                <input type="file" name="image1" id="fileupload1">
                              </span>
                            </td>
                        </tr>
                            <td><?php echo __('entry_post_status'); ?></td>
                            <td>
                                <div class="ui-select">
                                    <?php echo CHtml::dropDownList('blog[status]',$status,
                                    array(__('entry_status_disable'),__('entry_status_enable'))); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo __('entry_featured'); ?></td>
                            <td>
                                <div class="ui-select">
                                    <?php echo CHtml::dropDownList('blog[featured]',$featured,array(1 => __('featured_right'),2 => __('featured_left')),array('prompt' => 'Select Option')); ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo __('entry_sort_order'); ?></td>
                            <td>
                                <input size="1" name="blog[sort_order]" id="sort_order" type="text" value="<?php echo $sort_order; ?>">
                            </td>
                        </tr>
                    </table>
                    <?php
			endforeach;
			?>
                </div>
            </div>
        </div>
    </div>
</form>