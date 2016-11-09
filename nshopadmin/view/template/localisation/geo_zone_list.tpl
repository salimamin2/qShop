<div class="box table-wrapper products-table section">
	<div class="head well">
    <h3><i class="icon-globe"></i> <?php echo $heading_title; ?>
			<div class="pull-right">
          <a onclick="location = '<?php echo $insert; ?>'" class="btn-flat success"><span>+ <?php echo $button_insert; ?></span></a> <a onclick="$('form').submit();" class="btn-flat btn-danger btn-sm"><span><?php echo $button_delete; ?></span></a>
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

    <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">

     <div class="table-responsive">

		<table class="table table-hover" data-rel="data-grid">

        <thead>

          <tr>

              <th>&nbsp;</th>

              <th><span class="line"></span><?php echo $column_name; ?></th>

              <th><span class="line"></span><?php echo $column_description; ?></th>

              <th><span class="line"></span><?php echo $column_action; ?></th>

          </tr>

        </thead>

        <tbody>

          <?php if ($geo_zones) { ?>

          <?php foreach ($geo_zones as $geo_zone) { ?>

          <tr>

            <td style="text-align: center;"><?php if ($geo_zone['selected']) { ?>

              <input type="checkbox" name="selected[]" value="<?php echo $geo_zone['geo_zone_id']; ?>" checked="checked" />

              <?php } else { ?>

              <input type="checkbox" name="selected[]" value="<?php echo $geo_zone['geo_zone_id']; ?>" />

              <?php } ?></td>

            <td class="left"><?php echo $geo_zone['name']; ?></td>

            <td class="left"><?php echo $geo_zone['description']; ?></td>

            <td class="right"><?php foreach ($geo_zone['action'] as $action) { ?>

              <a class="btn <?php echo $action['class'] ?> btn-sm" data-toggle="tooltip" href="<?php echo $action['href']; ?>" title="<?php echo $action['text']; ?>"><i class="<?php echo $action['icon']; ?>"></i></a>

              <?php } ?></td>

          </tr>

          <?php } ?>

          <?php } ?>

        </tbody>

      </table>
     </div>
    </form>

  </div>

</div>

