 	<form action="<?php echo $action; ?>" class="form-horizontal" method="post" enctype="multipart/form-data" id="form">

    <div class="box">


	<div class="head well">
		<h3><i class="icon-columns"></i> <?php echo $heading_title; ?>
			<div class="pull-right">

			<button type="submit" class="btn btn-success btn-sm"><span><?php echo $button_save; ?></span></button>

			<a href="<?php echo $cancel; ?>" class="btn btn-default btn-sm"><span><?php echo $button_cancel; ?></span></a>

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

	    <fieldset>

		<div class="row">

		    <div class="form-group col-md-5">

			<label class="col-md-2" for="product_filter_status"><?php echo __('entry_status'); ?></label>

			<select class="" name="product_filter_status" id="product_filter_status">

			    <?php if ($product_filter_status) { ?>

    			    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>

    			    <option value="0"><?php echo $text_disabled; ?></option>

			    <?php } else { ?>

    			    <option value="1"><?php echo $text_enabled; ?></option>

    			    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>

			    <?php } ?>

			</select>

		    </div>

		</div>

		<div class="row">

		    <div class="col-md-3">

			<select id="option" size="20" class="form-control">

			    <?php $option_row = 0; ?>

			    <?php $option_value_row = 0; ?>

			    <?php if (isset($filters)): ?>

				<?php foreach ($filters as $filter) : ?>

				    <option value="option<?php echo $option_row; ?>"><?php echo $filter['name']; ?></option>

				    <?php if (isset($filter['filter_value'])) : ?>

					<?php foreach ($filter['filter_value'] as $filter_value) : ?>

					    <option value="option<?php echo $option_row; ?>_<?php echo $option_value_row; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $filter_value['name']; ?></option>

					    <?php $option_value_row++; ?>

					    <?php



					endforeach;

				    endif;



				    ?>

				    <?php $option_row++; ?>

				    <?php



				endforeach;

			    endif;



			    ?>

			</select>

		    </div>

		    <div id="options" class="col-md-9">

			<button  type="button" id="add_option" class="btn btn-sm btn-warning pull-right"><span><?php echo __('Add Filter'); ?></span></button>



			<div class="clearfix"></div>

			<?php $option_row = 0; ?>

			<?php $option_value_row = 0; ?>

			<?php if (isset($filters)): ?>

			    <?php foreach ($filters as $filter) : ?>

				<div id="option<?php echo $option_row; ?>" class="option">

				    <table class="form">

					<tr>

					    <td><?php echo __('Filter Name') ?>:</td>

					    <td>

						<input type="text" name="product_filter[<?php echo $option_row; ?>][name]" value="<?php echo $filter['name']; ?>" onkeyup="$('#option option[value=\'option<?php echo $option_row; ?>\']').text(this.value);" />

					    </td>

					</tr>

					<tr>

					    <td><?php echo __('Fillter Type'); ?>:</td>

					    <td>

						<select name="product_filter[<?php echo $option_row; ?>][type]">

						    <?php foreach ($filter_types as $i => $type): ?>

	    					    <option value="<?php echo $i; ?>" <?php if ($filter['type'] == $i): ?>selected="selected"<?php endif; ?>><?php echo $type; ?></option>

						    <?php endforeach; ?>

						</select>

					    </td>

					</tr>

					<tr>

					    <td><?php echo __('Filter On'); ?>:</td>

					    <td>

						<select name="product_filter[<?php echo $option_row; ?>][field]">

						    <?php foreach ($fields as $key => $field): ?>

	    					    <option value="<?php echo $key; ?>" <?php if ($filter['field'] == $key): ?>selected="selected"<?php endif; ?>><?php echo $field; ?></option>

						    <?php endforeach; ?>

						</select>

					    </td>

					</tr>

					<tr>

					    <td><?php echo __('Value'); ?>:</td>

					    <td>

						<input type="radio" id="defined_<?php echo $option_row; ?>_0" class="filter_value value_<?php echo $option_row; ?>" name="product_filter[<?php echo $option_row; ?>][value_defined]" value="0" <?php if (!$filter['value_defined']): ?>checked="checked"<?php endif; ?> /> <?php echo __('Auto'); ?>

						<input type="radio" id="defined_<?php echo $option_row; ?>_1" class="filter_value value_<?php echo $option_row; ?>" name="product_filter[<?php echo $option_row; ?>][value_defined]" value="1" <?php if ($filter['value_defined']): ?>checked="checked"<?php endif; ?> /> <?php echo __('Defined'); ?>

					    </td>

					</tr>

					<tr>

					    <td><?php echo __('Sort Order') ?>:</td>

					    <td><input type="text" name="product_filter[<?php echo $option_row; ?>][sort_order]" value="<?php echo $filter['sort_order']; ?>" size="2" /></td>

					</tr>

					<tr>

					    <td colspan="2"><a id="value_<?php echo $option_row; ?>" onclick="addOptionValue('<?php echo $option_row; ?>');" class="btn btn-xs btn-primary"><span><?php echo __('Add Filter Value'); ?></span></a> <a onclick="removeOption('<?php echo $option_row; ?>');" class="btn btn-xs btn-danger"><span><?php echo __('Remove Filter'); ?></span></a></td>

					</tr>

				    </table>

				</div>

				<?php if (isset($filter['filter_value'])) : ?>

				    <?php foreach ($filter['filter_value'] as $filter_value) : ?>

					<div id="option<?php echo $option_row; ?>_<?php echo $option_value_row; ?>" class="option">

					    <table class="form">

						<tr>

						    <td><?php echo __('Value'); ?>:</td>

						    <td>

							<input type="text" name="product_filter[<?php echo $option_row; ?>][filter_value][<?php echo $option_value_row; ?>][name]" value="<?php echo $filter_value['name']; ?>" onkeyup="$('#option option[value=\'option<?php echo $option_row; ?>_<?php echo $option_value_row; ?>\']').text('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' + this.value);" />

						    </td>

						</tr>

						<tr>

						    <td><?php echo __('Sort Order'); ?></td>

						    <td><input type="text" name="product_filter[<?php echo $option_row; ?>][filter_value][<?php echo $option_value_row; ?>][sort_order]" value="<?php echo $filter_value['sort_order']; ?>" size="2" /></td>

						</tr>

						<tr>

						    <td colspan="2"><a onclick="removeOptionValue('<?php echo $option_row; ?>_<?php echo $option_value_row; ?>');" class="btn btn-xs btn-danger"><span><?php echo __('Remove Value'); ?></span></a></td>

						</tr>

					    </table>

					</div>

					<?php $option_value_row++; ?>

					<?php



				    endforeach;

				endif;



				?>

				<?php $option_row++; ?>

				<?php



			    endforeach;

			endif;



			?>

		    </div>

	    </fieldset>



	</div>



    </div>

</form>

<script type="text/javascript"><!--

$('#option').bind('change', function() {

	$('.option').hide();



	$('#' + $('#option option:selected').attr('value')).show();

    });

    $(document).ready(function() {

	$('.filter_value').each(function() {

	    defineValue($(this));

	});

    });



    $('.filter_value').live('change', function() {

	defineValue($(this));

    });

    

    $('#add_option').on('click',function(){

	addOption();

    });



    function defineValue(obj) {

	var ids = obj.attr('id').split('_');

	$('#value_' + ids[1]).hide();

	if (obj.is(':checked') && obj.val() == 1) {

	    $('#value_' + ids[1]).show();

	}

    }



    $('#option option:first').attr('selected', 'selected');



    $('#option').trigger('change');

    $('.filter_value').trigger('change');

//--></script>

<script type="text/javascript"><!--

    var option_row = <?php echo $option_row; ?>;



    function addOption() {

	html = '<div id="option' + option_row + '" class="option">';

	html += '<table class="form">';

	html += '<tr>';

	html += '<td><?php echo __('Filter Name'); ?>:</td>';

	html += '<td>';

	html += '<input type="text" name="product_filter[' + option_row + '][name]" value="Filter ' + option_row + '" onkeyup="$(\'#option option[value=option' + option_row + ']\').text(this.value);" />';

	html += '</td>';

	html += '</tr>';

	html += '<tr>';

	html += '<td><?php echo __('Fillter Type'); ?>:</td>';

	html += '<td>';

	html += '<select name="product_filter[' + option_row + '][type]">';

<?php foreach ($filter_types as $i => $type): ?>

    	html += '<option value="<?php echo $i; ?>"><?php echo $type; ?></option>';

<?php endforeach; ?>

	html += '</select>';

	html += '</td>';

	html += '</tr>';

	html += '<tr>';

	html += '<td><?php echo __('Filter On'); ?>:</td>';

	html += '<td>';

	html += '<select name="product_filter[' + option_row + '][field]">';

<?php foreach ($fields as $key => $field): ?>

    	html += '<option value="<?php echo $key; ?>" ><?php echo $field; ?></option>';

<?php endforeach; ?>

	html += '</select>';

	html += '</td>';

	html += '</tr>';

	html += '<tr>';

	html += '<td><?php echo __('Value'); ?>:</td>';

	html += '<td>';

	html += '<input type="radio" id="defined_' + option_row + '_0" class="filter_value value_' + option_row + '" name="product_filter[' + option_row + '][value_defined]" value="0" /> <?php echo __('Auto'); ?>';

	html += '<input type="radio" id="defined_' + option_row + '_1" class="filter_value value_' + option_row + '" name="product_filter[' + option_row + '][value_defined]" value="1" /> <?php echo __('Define'); ?>';

	html += '</td>';

	html += '</tr>';

	html += '<tr>';

	html += '<td><?php echo __('Sort Order'); ?>:</td>';

	html += '<td><input type="text" name="product_filter[' + option_row + '][sort_order]" value="" size="2" /></td>';

	html += '</tr>';

	html += '<tr>';

	html += '<td colspan="2"><a id="value_' + option_row + '" onclick="addOptionValue(\'' + option_row + '\');" class="btn btn-xs btn-primary"><span><?php echo __('Add Filter Value'); ?></span></a> <a onclick="removeOption(\'' + option_row + '\');" class="btn btn-xs btn-danger"><span><?php echo __('Remove Filter'); ?></span></a></td>';

	html += '</tr>';

	html += '</table>';

	html += '</div>';

	$('#options').append(html);



	$('#option').append('<option value="option' + option_row + '"><?php echo __('Filter'); ?> ' + option_row + '</option>');

	$('#option option[value=\'option' + option_row + '\']').attr('selected', 'selected');

	$('#option').trigger('change');

	defineValue($('.value_' + option_row));

	option_row++;

    }



    function removeOption(option_row) {

	$('#option option[value=\'option' + option_row + '\']').remove();

	$('#option option[value^=\'option' + option_row + '_\']').remove();



	$('#options div[id=\'option' + option_row + '\']').remove();

	$('#options div[id^=\'option' + option_row + '_\']').remove();

    }



    var option_value_row = <?php echo $option_value_row; ?>;



    function addOptionValue(option_id) {

	html = '<div id="option' + option_id + '_' + option_value_row + '" class="option">';

	html += '<table class="form">';

	html += '<tr>';

	html += '<td><?php echo __('Value'); ?>:</td>';

	html += '<td>';

	html += '<input type="text" name="product_filter[' + option_id + '][filter_value][' + option_value_row + '][name]" value="" onkeyup="$(\'#option option[value=option' + option_id + '_' + option_value_row + ']\').text(\'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\' + this.value);" />';

	html += '</td>';

	html += '</tr>';

	html += '<tr>';

	html += '<td><?php echo __('Sort Order'); ?></td>';

	html += '<td><input type="text" name="product_filter[' + option_id + '][filter_value][' + option_value_row + '][sort_order]" value="" size="2" /></td>';

	html += '</tr>';

	html += '<tr>';

	html += '<td colspan="2"><a onclick="removeOptionValue(\'' + option_id + '_' + option_value_row + '\');" class="btn btn-xs btn-danger"><span><?php echo __('Remove Value'); ?></span></a></td>';

	html += '</tr>';

	html += '</table>';

	html += '</div>';

	$('#options').append(html);



	option = $('#option option[value^=\'option' + option_id + '_\']:last');



	if (option.size()) {

	    option.after('<option value="option' + option_id + '_' + option_value_row + '">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo __('Filter Value'); ?> ' + option_value_row + '</option>');

	} else {

	    $('#option option[value=\'option' + option_id + '\']').after('<option value="option' + option_id + '_' + option_value_row + '">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo __('Filter Value'); ?> ' + option_value_row + '</option>');

	}



	$('#option option[value=\'option' + option_id + '_' + option_value_row + '\']').attr('selected', 'selected');



	$('#option').trigger('change');



	option_value_row++;

    }



    function removeOptionValue(option_value_row) {

	$('#option option[value=\'option' + option_value_row + '\']').remove();



	$('#option' + option_value_row).remove();

    }

//--></script>

