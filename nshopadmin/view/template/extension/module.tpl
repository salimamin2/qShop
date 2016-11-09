<div class="box table-wrapper products-table section">

    <div class="head well">
		<h3><i class="icon-columns"></i> <?php echo $heading_title; ?></h3>
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

			<th class="left"><?php echo $column_name; ?></th>

			<th class="left"><span class="line"></span><?php echo $column_position; ?></th>

			<th class="left"><span class="line"></span><?php echo $column_status; ?></th>

			<th class="text-right"><span class="line"></span><?php echo $column_sort_order; ?></th>

			<th class="text-right" width="20%"><span class="line"></span><?php echo $column_action; ?></th>

		    </tr>

		</thead>

		<tbody>

		    <?php if ($extensions) { ?>

			<?php foreach ($extensions as $extension) { ?>

			    <tr>

				<td class="left"><?php echo $extension['name']; ?></td>

				<td class="left"><?php echo $extension['position'] ?></td>

				<td class="left"><?php echo $extension['status'] ?></td>

				<td class="text-right"><?php echo $extension['sort_order']; ?></td>

				<td class="text-right"><?php foreach ($extension['action'] as $action) { ?>
				<a class="btn <?php echo $action['class'] ?> btn-sm" data-toggle="tooltip" href="<?php echo $action['href']; ?>" title="<?php echo $action['text']; ?>"><i class="<?php echo $action['icon']; ?>"></i></a>

				    <?php } ?></td>

			    </tr>

			<?php } ?>

		    <?php } else { ?>

    		    <tr>

    			<td class="center" colspan="8"><?php echo $text_no_results; ?></td>

    		    </tr>

		    <?php } ?>

		</tbody>

	    </table>

	</div>

    </div>

</div>

