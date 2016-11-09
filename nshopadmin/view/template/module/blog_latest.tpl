<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
  <div class="box">
      <div class="head well">
        <h3>
        <i class="icon-th-list"></i> <?php echo $heading_title; ?>
          <div class="pull-right">
          <a onclick="$('#form').submit();" class="btn btn-success"><span><?php echo $button_save; ?></span></a>
          <a onclick="location = '<?php echo $cancel; ?>';" class="btn btn-default"><span><?php echo $button_cancel; ?></span></a>
          </div>
        </h3>
      </div>
      <?php if ($error_warning) { ?>
        <div class="warning"><?php echo $error_warning; ?></div>
      <?php } ?>
      <?php if ($success) { ?>
         <div class="alert alert-success"><?php echo $success; ?></div>
      <?php } ?> 
      <div class="content">
          <div class="form-horizontal">
              <div class="form-group">
                  <label for="blog_latest_limit" class="col-sm-2 control-label text-left"><?php echo $entry_limit; ?></label>
                  <div class="col-sm-2">
                      <input type="number" id="blog_latest_limit" name="blog_latest_limit" class="form-control" step="1" value="<?php echo $blog_latest_limit; ?>" />
                  </div>
              </div>
              <hr/>
              <div class="form-group">
                  <label for="blog_latest_position" class="col-sm-2 control-label text-left"><?php echo $entry_position; ?></label>
                  <div class="col-sm-2">
                      <select name="blog_latest_position" id="blog_latest_position" class="form-control">
                            <?php foreach ($positions as $position): ?>
                            <option value="<?php echo $position['position']; ?>" <?php echo ($blog_latest_position == $position['position'] ? 'selected' : ''); ?> >
                              <?php echo $position['title']; ?>
                            </option>
                            <?php endforeach; ?>
                      </select>
                  </div>
              </div>
              <hr/>
              <div class="form-group">
                  <label for="blog_latest_status" class="col-sm-2 control-label"><?php echo $entry_status; ?></label>
                  <div class="col-sm-2">
                      <select name="blog_latest_status" id="blog_latest_status" class="form-control">
                        <option value="0"><?php echo $text_disabled; ?></option>
                        <option value="1" <?php echo ($blog_latest_status ? 'selected' : ''); ?>><?php echo $text_enabled; ?></option>
                      </select>
                  </div>
              </div>
              <hr/>
              <div class="form-group">
                  <label for="blog_latest_sort_order" class="col-sm-2 control-label"><?php echo $entry_sort_order; ?></label>
                  <div class="col-sm-2">
                      <input type="number" class="form-control" name="blog_latest_sort_order" id="blog_latest_sort_order" step="1" 
                      size="1" value="<?php echo $blog_latest_sort_order; ?>" />
                  </div>
              </div>
          </div>
      </div>
  </div>
</form>

