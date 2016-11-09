<?php echo $this->load('module/account'); ?>
<div id="Content" class="content-75 reward account">
    <h1><span><?php echo $heading_title; ?></span></h1>
    <p><?php echo $text_total; ?><b> <?php echo $total; ?></b>.</p>
    <table class="cart">
	<thead>
	    <tr>
		<th class="text-left"><?php echo $column_date_added; ?></th>
		<th class="text-left"><?php echo $column_description; ?></th>
		<th class="text-right"><?php echo $column_points; ?></th>
	    </tr>
	</thead>
	<tbody>
	    <?php if ($rewards) { ?>
		<?php foreach ($rewards as $reward) { ?>
		    <tr>
			<td class="text-left"><?php echo $reward['date_added']; ?></td>
			<td class="text-left"><?php if ($reward['order_id']) { ?>
	    		    <a href="<?php echo $reward['href']; ?>"><?php echo $reward['description']; ?></a>
			    <?php } else { ?>
				<?php echo $reward['description']; ?>
			    <?php } ?></td>
			<td class="text-right"><?php echo $reward['points']; ?></td>
		    </tr>
		<?php } ?>
	    <?php } else { ?>
    	    <tr>
    		<td class="center" colspan="5"><?php echo $text_empty; ?></td>
    	    </tr>
	    <?php } ?>
	</tbody>
    </table>
    <div class="pagination"><?php echo $pagination; ?></div>
    <div class="buttons">
	<a href="<?php echo $continue; ?>" class="button right"><span><?php echo $button_continue; ?></span></a>
    </div>
</div>