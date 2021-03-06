
<?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="heading">
  <h1><?php echo $heading_title; ?></h1>
  <div class="buttons"><a onclick="$('#form').submit();" class="button"><span class="button_middle"><?php echo $button_save; ?></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span class="button_middle"><?php echo $button_cancel; ?></span></a></div>
</div>
<div class="tabs"><a tab="#tab_general"><?php echo $tab_general; ?></a></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div id="tab_general" class="page">
    <table class="form">
      <?php foreach ($languages as $language) { ?>
        <tr>
          <td width="25%"><span class="required">*</span> <?php echo $entry_title; ?></td>
          <td><input name="news_description[<?php echo $language['language_id']; ?>][title]" value="<?php echo isset( $news_description[$language['language_id']]['title']) ? $news_description[$language['language_id']]['title'] : ''; ?>" />
            <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
              <!-- <?php if (@$error_title[$language['language_id']]) { ?>
            <span class="error"><?php echo $error_title[$language['language_id']]; ?></span>
            <?php } ?>  --></td>
        </tr>
        <tr>
          <td><?php echo $entry_keyword; ?></td>
          <td><input type="text" name="keyword" value="<?php echo $keyword; ?>" /></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_description; ?></td>
          <td><textarea name="news_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>"><?php echo isset( $news_description[$language['language_id']]['description']) ? $news_description[$language['language_id']]['description'] : ''; ?></textarea>
            <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="vertical-align: top;" />
            <!-- <?php if (@$error_description[$language['language_id']]) { ?>
            <span class="error"><?php echo $error_description[$language['language_id']]; ?></span>
            <?php } ?> --></td>
        </tr>
      <?php } ?>
      <tr>
        <td width="25%"><?php echo $entry_status; ?></td>
        <td><select name="status">
          <?php if ($status) { ?>
          <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
          <option value="0"><?php echo $text_disabled; ?></option>
          <?php } else { ?>
          <option value="1"><?php echo $text_enabled; ?></option>
          <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
          <?php } ?>
        </select></td>
      </tr>
    </table>
  </div>
</form>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>
<script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
CKEDITOR.replace('description<?php echo $language['language_id']; ?>');
<?php } ?>	  
//--></script>


