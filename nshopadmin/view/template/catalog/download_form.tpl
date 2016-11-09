
<?php if ($error_warning) { ?>
<div class="alert alert-danger"><?php echo $error_warning; ?></div>
<?php } ?>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
<div class="box">
  <div class="heading">
    <h1><i class="icon-edit"></i> <?php echo $heading_title; ?></h1>
    <div class="buttons">
        <button type="submit" class="btn btn-success btn-sm"><span><?php echo $button_save; ?></span></button>
        <a href="<?php echo $cancel; ?>" class="btn btn-default btn-sm"><span><?php echo $button_cancel; ?></span></a>
    </div>
  </div>
  <div class="content">
      <table class="form">
        <?php foreach ($languages as $language) { ?>
        <?php if ($language['status']) { ?>
		<tr>
          <td><span class="required">*</span> <?php echo $entry_name; ?></td>
          <td><input name="download_description[<?php echo $language['language_id']; ?>][name]" size="100" value="<?php echo isset($download_description[$language['language_id']]) ? $download_description[$language['language_id']]['name'] : ''; ?>" />
            <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
            <?php if (isset($error_name[$language['language_id']])) { ?>
            <span class="error"><?php echo $error_name[$language['language_id']]; ?></span><br />
            <?php } ?>
			<?php } ?>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_filename; ?></td>
          <td><input type="file" name="download" value="" />
            <br/><span class="help" style="font-style: italic;"><?php echo $filename; ?></span>
            <?php if ($error_download) { ?>
            <span class="error"><?php echo $error_download; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_remaining; ?></td>
          <td><input type="input" name="remaining" value="<?php echo $remaining; ?>" size="6" /></td>
        </tr>
        <?php if ($show_update) { ?>
        <tr>
          <td><?php echo $entry_update; ?></td>
          <td>
          <?php if ($update) { ?>
          <input type="checkbox" name="update" value="1" checked="checked" />
          <?php } else { ?>
          <input type="checkbox" name="update" value="1" />
          <?php } ?>
          </td>
        </tr>
        <?php } ?>
      </table>
  </div>
</div>
</form>
