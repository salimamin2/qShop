
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">

<div class="box">
	<div class="head well">
		<h3>
        	<i class="icon-th-list"></i> <?php echo $heading_title; ?>
	    	<div class="pull-right">
				<a id="addRow" class="btn btn-info btn-sm"><span><?php echo __('Add Banner'); ?></span></a>

				<button type="submit" class="btn btn-success btn-sm"><span><?php echo $button_save; ?></span></button>

				<a onclick="location = '<?php echo $cancel; ?>';" class="btn btn-default btn-sm"><span><?php echo $button_cancel; ?></span></a>
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

	    <div class="table-responsive">

		<table class="list table-condensed" id="banner-table">

		    <thead>

			<tr>

			    <th><?php echo $entry_position; ?></th>

			    <th><?php echo $entry_status; ?></th>

			    <th><?php echo $entry_description; ?></th>

			    <th><?php echo $entry_sort_order; ?></th>

			    <th><?php echo __('Action'); ?></th>

			</tr>

		    </thead>

		    <tbody>

			<?php for ($i = 0; $i < $iTotal; $i++) : ?>

    			<tr id="row-<?php echo $i ?>">

    			    <td>

    				<select name="home_banner[<?php echo $i ?>][position]">

					<?php foreach ($positions as $position) { ?>

					    <?php if ($home_banner[$i]['position'] == $position['position']) { ?>

	    				    <option value="<?php echo $position['position']; ?>" selected="selected"><?php echo $position['title']; ?></option>

					    <?php } else { ?>

	    				    <option value="<?php echo $position['position']; ?>"><?php echo $position['title']; ?></option>

					    <?php } ?>

					<?php } ?>

    				</select>

    			    </td>

    			    <td>

    				<select name="home_banner[<?php echo $i ?>][status]">

					<?php if ($home_banner[$i]['status']) { ?>

					    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>

					    <option value="0"><?php echo $text_disabled; ?></option>

					<?php } else { ?>

					    <option value="1"><?php echo $text_enabled; ?></option>

					    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>

					<?php } ?>

    				</select>

    			    </td>

    			    <td>

    				<textarea name="home_banner[<?php echo $i ?>][desc]" id="description" data-rel="wyswyg"><?php echo isset($home_banner[$i]) && isset($home_banner[$i]['desc']) ? $home_banner[$i]['desc'] : ''; ?></textarea>

    			    </td>

    			    <td><input type="text" name="home_banner[<?php echo $i ?>][sort_order]" value="<?php echo $home_banner[$i]['sort_order']; ?>" size="1" /></td>

    			    <td><a class="remove-row btn btn-danger btn-sm" rel="<?php echo $i ?>">Remove</a></td>

    			</tr>

			<?php endfor; ?>

		    </tbody>

		</table>

	    </div>

	</div>

    </div>

</form>

<script type="text/javascript" >

    var positions = <?php echo json_encode($positions); ?>;

    var statuses = <?php echo json_encode($statuses); ?>;

    $(document).on('click', '#addRow', function() {

	iRow = $('#banner-table tbody tr').length;

	html = '<tr id="row-' + iRow + '">' +

		'<td>' +

		'<select name="home_banner[' + iRow + '][position]">';

	$.each(positions, function(i, val) {

	    html += '<option value="' + val['position'] + '">' + val['title'] + '</option>';

	});

	html += '</select>' +

		'</td>' +

		'<td>' +

		'<select name="home_banner[' + iRow + '][status]">' +

		'<option value="1" selected="selected"><?php echo $text_enabled; ?></option>' +

		'<option value="0"><?php echo $text_disabled; ?></option>' +

		'</select>' +

		'</td>' +

		'<td>' +

		'<textarea name="home_banner[' + iRow + '][desc]" id="description" data-rel="wyswyg"></textarea>' +

		'</td>' +

		'<td><input type="text" name="home_banner[' + iRow + '][sort_order]" value="" size="1" /></td>' +

		'<td><a class="remove-row btn btn-danger btn-sm" rel="' + iRow + '">Remove</a></td>' +

		'</tr>';



	$('#banner-table tbody').append(html);

	initWysiwyg('#row-'+ iRow+' textarea');

    });

</script>