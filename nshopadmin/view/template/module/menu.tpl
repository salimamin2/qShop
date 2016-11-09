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
        <div class="alert alert-danger"><?php echo $error_warning; ?></div>
    <?php } ?>
    <?php if ($success) { ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php } ?>
    <div class="content">
	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
	    <table class="list">
		<thead>
		    <tr>
			<th>Menu</th>
			<th>Position</th>
			<th>status</th>
		    </tr>
		</thead>
		<tbody>
		    <?php foreach ($aPlaceCodes as $aCode): ?>
    		    <tr>
    			<td>
    			    <input type="hidden" name="menu_data[<?php echo $aCode['place_code']; ?>][place_code]" value="<?php echo $aCode['place_code']; ?>" />
				<?php echo $aCode['place_code']; ?>
    			</td>
    			<td>
    			    <select name="menu_data[<?php echo $aCode['place_code']; ?>][position]">
				    <?php foreach ($aPositions as $sPosition): ?>
					<option value="<?php echo $sPosition ?>" <?php if ($sPosition == $menu_data[$aCode['place_code']]['position']): ?>selected='selected'<?php endif; ?>><?php echo strtocamel(str_replace('_', '  ', $sPosition)) ?></option>
				    <?php endforeach; ?>
    			    </select>
    			</td>
    			<td>
    			    <select name="menu_data[<?php echo $aCode['place_code']; ?>][status]">
				    <?php foreach ($aStatus as $i => $sStatus): ?>
					<option value="<?php echo $i ?>" <?php if ($i == $menu_data[$aCode['place_code']]['status']): ?>selected='selected'<?php endif; ?>><?php echo $sStatus ?></option>
				    <?php endforeach; ?>
    			    </select>
    			</td>
    		    </tr>
		    <?php endforeach; ?>
		</tbody>

	    </table>
	</form>
    </div>
</div>
