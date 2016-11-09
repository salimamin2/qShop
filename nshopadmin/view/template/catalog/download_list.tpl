
<?php if ($error_warning) { ?>
<div class="alert alert-danger"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="alert alert-success"><?php echo $success; ?></div>
<?php } ?>
<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
<div class="box">
  <div class="heading">
    <h1><i class="icon-download"></i> <?php echo $heading_title; ?></h1>
    <div class="buttons">
        <a href="<?php echo $insert; ?>" class="btn btn-primary btn-sm"><span><?php echo $button_insert; ?></span></a>
        <button type="submit" class="btn btn-danger btn-sm"><span><?php echo $button_delete; ?></span></button>
    </div>
  </div>
  <div class="content">
      <table class="list">
        <thead>
          <tr>
            <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
            <td class="left"><?php if ($sort == 'dd.name') { ?>
              <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
              <?php } ?></td>
            <td class="right"><?php if ($sort == 'd.remaining') { ?>
              <a href="<?php echo $sort_remaining; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_remaining; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_remaining; ?>"><?php echo $column_remaining; ?></a>
              <?php } ?></td>
            <td class="right"><?php echo $column_action; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php if ($downloads) { ?>
          <?php foreach ($downloads as $download) { ?>
          <tr>
            <td style="text-align: center;"><?php if ($download['selected']) { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $download['download_id']; ?>" checked="checked" />
              <?php } else { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $download['download_id']; ?>" />
              <?php } ?></td>
            <td class="left"><?php echo $download['name']; ?></td>
            <td class="right"><?php echo $download['remaining']; ?></td>
            <td class="right"><?php foreach ($download['action'] as $action) { ?>
              [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
              <?php } ?></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="6"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    <div class="pagination"><?php echo $pagination; ?></div>
  </div>
</div>
</form>