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

  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <div style="display: inline-block; width: 100%;">
        <div id="tabs" class="vtabs"><a tab="#tab_general"><?php echo $tab_general; ?></a>
          <?php foreach ($geo_zones as $geo_zone) { ?>
          <a tab="#tab_geo_zone<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></a>
          <?php } ?>
        </div>
        <div id="tab_general" class="vtabs_page">
          <table class="form">
            <tr>
              <td><?php echo $entry_status; ?></td>
              <td><select name="ups_qty_status">
                  <?php if ($ups_qty_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_sort_order; ?></td>
              <td><input type="text" name="ups_qty_sort_order" value="<?php echo $ups_qty_sort_order; ?>" size="1" /></td>
            </tr>
          </table>
        </div>
        <?php $row=0; ?>
        <?php foreach ($geo_zones as $geo_zone) { ?>
        <div id="tab_geo_zone<?php echo $geo_zone['geo_zone_id']; ?>" class="vtabs_page">
          <table class="form">
            <tr>
              <td><?php echo $entry_status; ?></td>
              <td><select name="ups_qty_<?php echo $geo_zone['geo_zone_id']; ?>_status">
                  <?php if (${'ups_qty_' . $geo_zone['geo_zone_id'] . '_status'}) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
                <td colspan="2" width="100%">
                    <table width="100%" class="list" id="ups_rates<?php echo $geo_zone['geo_zone_id']; ?>">
                        <thead>
                            <tr>
                                <td width="25%" align="center">UPS Method</td><td width="25%" align="center">Qty</td><td width="25%" align="center">Rate</td><td width="25%" align="center">Action</td>
                            </tr>
                        </thead>
                        <?php foreach(${'ups_qty_' . $geo_zone['geo_zone_id'] . '_rate'} as $rate): ?>
                        <tbody id="ups_rate_row_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $row; ?>">
                            <tr>
                                <td>
                                    <select name="rate[ups_qty_<?php echo $geo_zone['geo_zone_id']; ?>_rate][<?php echo $row; ?>][method]">
                                        <?php foreach($ups_methods as $method): ?>
                                        <option value="<?php echo $method['code']; ?>" <?php echo ($method['code'] == $rate['method']?'selected="selected"':''); ?> ><?php echo $method['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php echo $method['ups']; ?>
                                </td>
                                <td><input type="text" name="rate[ups_qty_<?php echo $geo_zone['geo_zone_id']; ?>_rate][<?php echo $row; ?>][qty]" value="<?php echo $rate['qty']; ?>" /></td>
                                <td><input type="text" name="rate[ups_qty_<?php echo $geo_zone['geo_zone_id']; ?>_rate][<?php echo $row; ?>][rate]" value="<?php echo $rate['rate']; ?>" /></td>
                                <td><a onclick="$('#ups_rate_row_<?php echo $geo_zone['geo_zone_id']; ?>_<?php echo $row; ?>').remove();" class="button"><span>Remove</span></a></td>
                            </tr>
                        </tbody>
                        <?php $row++; ?>
                        <?php endforeach; ?>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="right"></td>
                                <td><a onclick="addMethod(<?php echo $geo_zone['geo_zone_id']; ?>);" class="button"><span>Add Method</span></a></td>
                            </tr>
                        </tfoot>
                    </table>
                </td>
            </tr>
          </table>
        </div>
        <?php } ?>
      </div>
    </form>
  </div>
</div>
<script type="text/javascript"><!--
$.tabs('#tabs a');

var ups_rate_row = <?php echo $row; ?>;

function addMethod(geo_zone_id) {
    html  = '<tbody id="ups_rate_row_' + geo_zone_id + '_' + ups_rate_row + '">';
	html += '<tr>';
	html += '<td>';
	html += '<select name="rate[ups_qty_' + geo_zone_id + '_rate][' + ups_rate_row  + '][method]">';
        <?php foreach($ups_methods as $method): ?>
	html += '<option value="<?php echo $method['code']; ?>"><?php echo $method['name']; ?></option>';
        <?php endforeach; ?>
	html += '</select>';
	html += '</td>';
	html += '<td><input type="text" name="rate[ups_qty_' + geo_zone_id + '_rate][' + ups_rate_row  + '][qty]" value="" /></td>';
	html += '<td><input type="text" name="rate[ups_qty_' + geo_zone_id + '_rate][' + ups_rate_row  + '][rate]" value="" /></td>';
	html += '<td class="left"><a onclick="$(\'#ups_rate_row' + ups_rate_row  + '\').remove();" class="button"><span>Remove</span></a></td>';
	html += '</tr>';
	html += '</tbody>';
	
	$('#ups_rates' + geo_zone_id + ' tfoot').before(html);
	
	ups_rate_row++;
}
//--></script>