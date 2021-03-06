<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
<div class="box table-wrapper products-table section">
	<div class="head well">
	    <h3>
        	<i class="icon-th-list"></i> <?php echo $heading_title; ?>
			<div class="pull-right">
					<a href="<?php echo $insert; ?>" class="btn-flat success"><span>+ <?php echo $button_insert; ?></span></a>
					<button type="submit" class="btn-flat btn-sm btn-danger"><span><?php echo $button_delete; ?></span></button>
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

	    <div class="table-responsive">

		<table class="table table-hover" data-rel="data-grid">

		    <thead>

			<tr>

			    <th width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></th>

			    <th class="left"><span class="line"></span><?php echo __('column_name'); ?></th>

			    <th class="left"><span class="line"></span><?php echo __('column_code'); ?></th>

			    <th class="right"><span class="line"></span><?php echo __('column_sort_order'); ?></th>

			    <th class="right"><span class="line"></span><?php echo __('column_action'); ?></th>

			</tr>

		    </thead>

		    <tbody>

			<?php if ($languages) { ?>

			    <?php foreach ($languages as $language) { ?>

				<tr>

				    <td style="text-align: center;"><?php if ($language['selected']) { ?>

	    				<input type="checkbox" name="selected[]" value="<?php echo $language['language_id']; ?>" checked="checked" />

					<?php } else { ?>

	    				<input type="checkbox" name="selected[]" value="<?php echo $language['language_id']; ?>" />

					<?php } ?></td>

				    <td class="left"><?php echo $language['name']; ?></td>

				    <td class="left"><?php echo $language['code']; ?></td>

				    <td class="right"><?php echo $language['sort_order']; ?></td>

				    <td class="right"><?php foreach ($language['action'] as $action) { ?>

	    				<a class="btn <?php echo $action['class'] ?> btn-sm" data-toggle="tooltip" href="<?php echo $action['href']; ?>" title="<?php echo $action['text']; ?>"><i class="<?php echo $action['icon']; ?>"></i></a>

					<?php } ?></td>

				</tr>

			    <?php } ?>

			<?php } else { ?>

    			<tr>

    			    <td class="center" colspan="5"><?php echo $text_no_results; ?></td>

    			</tr>

			<?php } ?>

		    </tbody>

		</table>

	    </div>

	
   </div>


</form>
</div>
