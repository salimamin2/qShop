<div class="box table-wrapper products-table section">
	<div class="head well">
    <h3>
        <i class="icon-th-list"></i> <?php echo $heading_title; ?>
			<div class="pull-right">
	          <a onclick="$('#form').submit();" class="btn-flat btn-success btn-sm"><span><?php echo $button_save; ?></span></a> <a onclick="location = '<?php echo $cancel; ?>';" class="btn-flat btn-default btn-sm"><span><?php echo $button_cancel; ?></span></a>
			</div>
	  </h3>
  </div>
    <?php if ($error_warning) { ?>
        <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
  <div class="content">

    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">

      <table class="form">

        <tr>

          <td><?php echo $entry_title; ?><span class="required">*</span></td>

          <td><input class="form-control" type="text" name="title" value="<?php echo $title; ?>" />

            <?php if ($error_title) { ?>

            <span class="error"><?php echo $error_title; ?></span>

            <?php } ?></td>

        </tr>

        <tr>

          <td> <?php echo $entry_description; ?><span class="required">*</span></td>

          <td><input class="form-control" type="text" name="description" value="<?php echo $description; ?>" />

            <?php if ($error_description) { ?>

            <br />

            <span class="error"><?php echo $error_description; ?></span>

            <?php } ?></td>

        </tr>

      </table>

      <br />

      <table id="tax_rate" class="table table-hover">

        <thead>

          <tr>

            <th class="left"><span class="line"></span><?php echo $entry_geo_zone; ?></th>

            <th class="left"><span class="line"></span><?php echo $entry_description; ?><span class="required">*</span></th>

            <th class="left"><span class="line"></span><?php echo $entry_rate; ?><span class="required">*</span></th>

            <th class="left"><span class="line"></span><?php echo $entry_priority; ?><span class="required">*</span></th>

            <th></th>

          </tr>

        </thead>

        <?php $tax_rate_row = 0; ?>

        <?php foreach ($tax_rates as $tax_rate) { ?>

        <tbody id="tax_rate_row<?php echo $tax_rate_row; ?>">

          <tr>

            <td class="left"><div class="ui-select"><select name="tax_rate[<?php echo $tax_rate_row; ?>][geo_zone_id]" id="geo_zone_id<?php echo $tax_rate_row; ?>">

                <?php foreach ($geo_zones as $geo_zone) { ?>

                <?php  if ($geo_zone['geo_zone_id'] == $tax_rate['geo_zone_id']) { ?>

                <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>

                <?php } else { ?>

                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>

                <?php } ?>

                <?php } ?>

              </select></div></td>

            <td class="left"><input class="form-control" type="text" name="tax_rate[<?php echo $tax_rate_row; ?>][description]" value="<?php echo $tax_rate['description']; ?>" /></td>

            <td class="left"><input class="form-control" type="text" name="tax_rate[<?php echo $tax_rate_row; ?>][rate]" value="<?php echo $tax_rate['rate']; ?>" /></td>

            <td class="left"><input class="form-control" type="text" name="tax_rate[<?php echo $tax_rate_row; ?>][priority]" value="<?php echo $tax_rate['priority']; ?>" size="1" /></td>

            <td class="left"><a onclick="$('#tax_rate_row<?php echo $tax_rate_row; ?>').remove();" class="btn-flat btn-danger"><span><?php echo $button_remove; ?></span></a></td>

          </tr>

        </tbody>

        <?php $tax_rate_row++; ?>

        <?php } ?>

        <tfoot>

          <tr>

            <td colspan="2"></td>

            <td class="left"><a onclick="addRate();" class="btn-flat btn-success"><span><?php echo $button_add_rate; ?></span></a></td>

          </tr>

        </tfoot>

      </table>

    </form>

  </div>

</div>

<script type="text/javascript"><!--

var tax_rate_row = <?php echo $tax_rate_row; ?>;



function addRate() {

	html  = '<tbody id="tax_rate_row' + tax_rate_row + '">';

	html += '<tr>';

	html += '<td class="left"><div class="ui-select"><select name="tax_rate[' + tax_rate_row + '][geo_zone_id]">';

    <?php foreach ($geo_zones as $geo_zone) { ?>

    html += '<option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>';

    <?php } ?>

	html += '</select></div></td>';

	html += '<td class="left"><input class="form-control" type="text" name="tax_rate[' + tax_rate_row + '][description]" value="" /></td>';

	html += '<td class="left"><input class="form-control" type="text" name="tax_rate[' + tax_rate_row + '][rate]" value="" /></td>';

	html += '<td class="left"><input class="form-control" type="text" name="tax_rate[' + tax_rate_row + '][priority]" value="" size="1" /></td>';

	html += '<td class="left"><a onclick="$(\'#tax_rate_row' + tax_rate_row + '\').remove();" class="btn-flat btn-danger"><span><?php echo $button_remove; ?></span></a></td>';

	html += '</tr>';

	html += '</tbody>';

	

	$('#tax_rate > tfoot').before(html);

	

	tax_rate_row++;

}

//--></script>

