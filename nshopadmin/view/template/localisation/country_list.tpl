<div class="box table-wrapper products-table section">
	<div class="head well">
    <h4><i class="icon-globe"></i> <?php echo $heading_title; ?>
			<div class="pull-right">
        <a onclick="location = '<?php echo $insert; ?>'" class="btn-flat success"><span>+ <?php echo $button_insert; ?></span></a>
        <a onclick="$('form').submit();" class="btn-flat btn-danger btn-sm"><span><?php echo $button_delete; ?></span></a>
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

              <td>&nbsp;</td>

              <th class="left"><span class="line"></span><?php echo $column_name; ?></th>

              <th class="left"><span class="line"></span><?php echo $column_iso_code_2; ?></th>

              <th class="left"><span class="line"></span><?php echo $column_iso_code_3; ?></th>

              <th class="left"><span class="line"></span><?php echo $column_action;  ?></th>

          </tr>

        </thead>

        <tbody>

          <?php if ($countries) { ?>

          <?php foreach ($countries as $country) { ?>

          <tr>

            <td style="text-align: center;"><?php if ($country['selected']) { ?>

              <input type="checkbox" name="selected[]" value="<?php echo $country['country_id']; ?>" checked="checked" />

              <?php } else { ?>

              <input type="checkbox" name="selected[]" value="<?php echo $country['country_id']; ?>" />

              <?php } ?></td>

            <td class="left"><?php echo $country['name']; ?></td>

            <td class="left"><?php echo $country['iso_code_2']; ?></td>

            <td class="left"><?php echo $country['iso_code_3']; ?></td>

            <td class="right"><?php foreach ($country['action'] as $action) { ?>

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

