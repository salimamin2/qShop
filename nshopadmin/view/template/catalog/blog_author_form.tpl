<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" >
    <input type="hidden" name="author_id" value="<?php echo $author_id; ?>">
    <input type="hidden" name="directory" value="<?php echo $directory; ?>" />
    <div class="box table-wrapper products-table section">
        <div class="head well">
            <h3><i class="icon-th-list"></i> <?php echo $heading_title; ?>
            <div class="pull-right">
                    <button type="submit" class="btn-flat btn-success btn-sm" ><span><?php echo __('button_save'); ?></span></button>
                    <a href="<?php echo $cancel; ?>" class="btn-flat btn-default btn-sm"><span><?php echo __('button_cancel'); ?></span></a>
             </div>
            </h3>
        </div>
        <?php if ($error_warning) { ?>
                <div class="alert alert-danger"><?php echo $error_warning; ?></div>
        <?php } ?>

                <div class="content">
                        <table class="form">

                            <tr>
                                <td><?php echo __('entry_fname'); ?><span class="required">*</span></td>
                                <td><input type="text" class="form-control name" value="<?php echo $first_name; ?>" name="first_name"> 
                                <?php if (isset($error_first_name)) { ?>

                                <span class="error"><?php echo $error_first_name; ?></span>

                                <?php } ?></td>
                            </tr>

                            <tr>
                                <td><?php echo __('entry_lname'); ?><span class="required">*</span></td>
                                <td><input type="text" class="form-control" value="<?php echo $last_name; ?>" name="last_name"> 
                                <?php if (isset($error_last_name)) { ?>

                                <span class="error"><?php echo $error_last_name; ?></span> 

                                <?php } ?></td>
                            </tr>
                            <tr>
                                <td> <?php echo __('entry_description'); ?></td>
                                <td>
                                    <textarea name="description" class="form-control required" data-rel="wyswyg" rows="5" cols="40"><?php echo $description; ?></textarea>
                                </td>
                            </tr>
                            <tr>

                                <td><?php echo __('entry_image'); ?></td>

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

                                    <input type="hidden" name="thumb" value="<?php echo $image; ?>" id="image" />

                                    <img src="<?php echo $preview_thumb; ?>" alt="" id="preview1" class="image" width="122" height="" />

                                  <span class="btn btn-success btn-sm fileinput-button"><i class="icon-camera"></i>

                                    <span>Select Image...</span>

                                    <input type="file" name="image1" id="fileupload1">

                                  </span>

                                </td>

                            </tr>
                            <tr>
                                <td><?php echo __('entry_fblink'); ?></td>
                                <td><input type="text" class="form-control" value="<?php echo $fb_link; ?>" name="fb_link"> </td>
                            </tr>

                            <tr>
                                <td><?php echo __('entry_twitterlink'); ?></td>
                                <td><input type="text" class="form-control" value="<?php echo $twitter_link; ?>" name="twitter_link"> </td>
                            </tr>
                            <tr>
                                <td><?php echo __('entry_seo_keyword'); ?></td>
                                <td>
                                    <input type="text" name="keyword" class="form-control keyword" value="<?php echo $seo_keyword; ?>">
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo __('entry_status'); ?></td>
                                <td>
                                    <div class="ui-select">
                                        <select class="form-control valid" name="status" id="status">
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
                                    <input size="1" name="sort_order" id="sort_order" type="text" value="<?php echo $sort_order; ?>">
                                </td>
                            </tr>
                        </table>

                </div>


    </div>
</form>