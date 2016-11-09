<form action="" method="post" enctype="multipart/form-data" id="form">
    <div class="box products-table">
		<div class="head well">
			<h3>
				<i class="icon-folder-open"></i> <?php echo $heading_title; ?>
				<div class="pull-right">
					<div class="buttons">
						<?php if ($product_types): ?>
							<select name='product_type' id='selPT'>
								<?php foreach ($product_types as $type): ?>
									<option value="<?php echo $type['product_type_id'] ?>"><?php echo $type['title'] ?></option>
								<?php endforeach; ?>
							</select>
							<a onClick="location.href = '<?php echo $insert; ?>&type=' + $('#selPT').val()" class="btn-flat success"><span><?php echo $button_insert; ?></span></a>
						<?php else : ?>
							<a href="<?php echo $insert; ?>" class="btn-flat success"><span>+ <?php echo $button_insert; ?></span></a>
						<?php endif; ?>
						<button type="button" onClick="$('#form').attr('action', '<?php echo $copy; ?>').submit();" class="btn-flat btn-info btn-sm"><span><?php echo $button_copy; ?></span></button>
						<button type="button" onClick="$('#form').attr('action', '<?php echo $delete; ?>').submit();"  class="btn-flat btn-danger btn-sm"><span><?php echo $button_delete; ?></span></button>
					</div>
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
			<table class="table">
				<tr class="filter">				
					<td>
              			<input type="hidden" name="filter_hdn_category" id="filter_hdn_category" class="search" data-rel="9" />
              			<input name="filter_category" id="filter_category" class="search" placeholder="Search By Category" />
                    </td>
					<td><input type="number" name="filter_id" class="search" placeholder="Search By ID" data-rel="1" /></td>
				    <td><input type="text" name="filter_name" class="search" placeholder="Search By Name" data-rel="3" /></td>
				    
				    <td>
					<div class="ui-select">
						<select name="filter_status" data-rel="7">
							<option value="">Status</option>
							<option value="1" <?php if ($filter_status) : ?>selected="selected"<?php endif; ?>><?php echo $text_enabled; ?></option>
							<option value="0" <?php if (!is_null($filter_status) && !$filter_status) : ?>selected="selected"<?php endif; ?>><?php echo $text_disabled; ?></option>
						</select>
					</div>
				    </td>
				   
				</tr>
				<tr class="filter">
				<td><input type="text" name="filter_model" class="search" placeholder="Search By Model" data-rel="4" /></td>
				    <td><input type="text" name="filter_designer" class="search" placeholder="Search By Designer" data-rel="5" /></td>
				    <td><input type="text" name="filter_quantity" class="search"  placeholder="Search By Quantity" data-rel="6" /></td>
				     <td align="right">
					<a id="filter-list" href="javascript:void(0)" class="btn-flat btn-warning btn-xs"><span><?php echo $button_filter; ?></span></a>
					<a id="reset-filter" href="javascript:void(0)" class="btn-flat btn-default btn-xs"><span><?php echo __('button_reset'); ?></span></a>
				    </td>
				</tr>
			</table>
			<table class="table table-hover" data-rel="ajax-grid" data-grid-url="<?php echo $sProductList ?>">
			    <thead>
					<tr>
					    <th data-searchable="false" width="1" style="text-align: center;"><input type="checkbox" onClick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></th>
					    <th class="left" width="10"><span class="line"></span><?php echo $column_id; ?></th>
					    <th data-searchable="false" class="center" width="10"><span class="line"></span><?php echo $column_image; ?></th>
					    <th class="left"><span class="line"></span><?php echo $column_name; ?></th>
					    <th class="left"><span class="line"></span><?php echo $column_model; ?></th>
					    <th class="right"><span class="line"></span><?php echo __('column_manufacturer'); ?></th> 
					    <th class="right"><span class="line"></span><?php echo $column_quantity; ?></th>
					    <th class="left"><span class="line"></span><?php echo $column_status; ?></th>
					    <th data-searchable="false" class="right"><span class="line"></span><?php echo $column_action; ?></th>
					    <th data-visible="false"></th>
					</tr>
			    </thead>
			</table>
		</div>
    </div>
</form>