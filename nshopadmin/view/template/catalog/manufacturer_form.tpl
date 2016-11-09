<?php if ($error_warning) { ?>
<div class="alert alert-danger"><?php echo $error_warning; ?></div>
<?php } ?>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
    <input type="hidden" name="directory" value="<?php echo $directory; ?>" />
    <div class="box table-wrapper products-table section">
        <div class="head well">
    		    <h3>
                <i class="icon-edit"></i> <?php echo $heading_title; ?>
                <div class="pull-right">
                    <button type="submit" class="btn-flat btn-success btn-sm"><span><?php echo $button_save; ?></span></button>
                    <a href="<?php echo $cancel; ?>" class="btn-flat btn-default btn-sm"><span><?php echo $button_cancel; ?></span></a>
                </div>
            </h3>
    	  </div>
        <div class="content">
          <table class="form">
            <tr>
              <td> <?php echo $entry_name; ?><span class="required">*</span></td>
              <td><input name="name" value="<?php echo $name; ?>" size="100" class="form-control name required" />
                <?php if ($error_name) { ?>
                <span class="error"><?php echo $error_name; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td> <?php echo $entry_description; ?><span class="required">*</span></td>
              <td><textarea name="description" class="form-control required" data-rel="wyswyg" rows="5" cols="40"><?php echo $description; ?></textarea>
                <?php if ($error_description) { ?>
                  <span class="error"><?php echo $error_description; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td> <?php echo $entry_meta_description; ?></td>
              <td>
                <textarea name="meta_description" class="form-control" rows="5" cols="40"><?php echo $meta_description; ?></textarea>
                </td>
            </tr>
            <tr>
              <td> <?php echo $entry_category; ?><span class="required">*</span></td>
              <td>
                  <div class="ui-select required">
                  <select name="category_id" class="form-control">
                  <?php foreach($categories as $id => $sCategory): ?>
                      <option value="<?php echo $id; ?>" <?php echo ($id == $category_id ? 'selected' : ''); ?> ><?php echo $sCategory; ?></option>
                  <?php endforeach; ?>
                </select>
                      </div>
                <?php if ($error_category) { ?>
                  <span class="error"><?php echo $error_category; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo __('entry_meta_title'); ?></td>
              <td><input name="meta_title" size="100" value="<?php echo $meta_title; ?>" class="form-control" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_keyword; ?></td>
              <td><input type="text" name="keyword" value="<?php echo $keyword; ?>" class="form-control keyword" /></td>
            </tr>
            <tr>
              <td><?php echo __('entry_email'); ?><span class="required">*</span></td>
              <td><input type="text" name="email" value="<?php echo $email; ?>" class="form-control required email" />
              <?php if ($error_email) { ?>
                  <span class="error"><?php echo $error_email; ?></span>
                <?php } ?></td>
            </tr>
              <tr>
                  <td><?php echo $entry_country; ?></td>
                  <td class="left">
                      <div class="ui-select">
                      <select class="form-control valid" name="country_id">
                          <?php foreach ($countries as $country) { ?>
                          <option value="<?php echo $country['country_id']; ?>" <?php echo ($country['country_id'] == $country_id ? 'selected' : ''); ?> ><?php echo $country['name']; ?></option>
                          <?php } ?>
                      </select>
                  </div>
                  </td>
              </tr>
            <tr>

              <td><?php echo $entry_image; ?></td>
              <td>
                  <input type="hidden" name="image" value="<?php echo $image; ?>" id="image" />
                  <img src="<?php echo $preview; ?>" alt="" id="preview" class="image" width="122" height="" />
                  <span class="btn btn-success btn-sm fileinput-button"><i class="icon-camera"></i>
                    <span>Select Image...</span>
                    <!-- The file input field used as target for the file upload widget -->
                    <input type="file" name="image" id="fileupload">
                  </span>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_sort_order; ?></td>
              <td><input name="sort_order" value="<?php echo $sort_order; ?>" size="1" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_facebook; ?></td>
              <td><input name="facebook" value="<?php echo $facebook; ?>" class="form-control" /></td>
            </tr>
       
            <tr>
              <td><?php echo $entry_twitter; ?></td>
              <td><input name="twitter" value="<?php echo $twitter; ?>" class="form-control"  /></td>
            </tr>
          </table>
      </div>
    </div>
</form>
