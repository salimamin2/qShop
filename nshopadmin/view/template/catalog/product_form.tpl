<?php if ($error_warning) { ?>
    <div class="alert alert-danger"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>
<div class="alert alert-success" id="success" style="display: none;">
    <button type="button" class="close" onclick="$('#success').hide();">&times;</button>
    <span id="success_message"></span>
</div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
    <input type="hidden" name="directory" value="<?php echo $directory; ?>" />
    <div class="box products-table section">
	<div class="head well">
		<h3><i class="icon-folder-open"></i> <?php echo $heading_title; ?>
	<div class="pull-right">
				<div class="buttons">
					<?php if ($product_id && $product_id != -1 && $product_type_id != 2): ?>
						<a href="<?php echo $inventory; ?>" class="btn-flat btn-success btn-sm"><span><?php echo __('Add Options'); ?></span></a>
					<?php endif; ?>
					<button type="submit" class="btn-flat btn-success btn-sm"><span><?php echo $button_save; ?></span></button>
					<button type="button" class="btn-flat btn-primary btn-sm" onclick="$('#form').attr('action', '<?php echo $action_continue ?>').submit();" class="button"><span><?php echo __('Save & Continue'); ?></span></button>
					<?php if ($product_type_id != 2): ?>
						<button type="button" class="btn-flat btn-primary btn-sm" onclick="$('#form').attr('action', '<?php echo $action_option ?>').submit();" class="button"><span><?php echo __('Save & Add Option'); ?></span></button>
					<?php endif; ?> 
					<a href="<?php echo $cancel; ?>" class="btn-flat btn-default btn-sm"><span><?php echo $button_cancel; ?></span></a>
				</div>
			</div>
		</h3>
	</div>
	<div class="row filter-block">
		<div class="col-md-12">
			
		</div>
	</div>
	<div class="content">
	    <ul id="tabs" class="nav nav-tabs">
		<li class="active">
		    <a href="#tab_general" data-toggle="tab" class="<?php echo ($error_name ? 'red' : ''); ?>"><span><?php echo $tab_general; ?></span></a>
		</li>
		<li>
		    <a href="#tab_data" data-toggle="tab" class="<?php echo ($error_model || $error_cost || $error_price ? 'red' : ''); ?>"><span><?php echo $tab_data; ?></span></a>
		</li>
		<?php if ($product_type_id != 2): ?>
    		<li>
    		    <a href="#tab_links" data-toggle="tab"><span><?php echo $tab_links; ?></span></a>
    		</li>
    		<li>
    		    <a href="#tab_discount" data-toggle="tab" class="<?php echo ($error_discount ? 'red' : ''); ?>"><span><?php echo $tab_discount; ?></span></a>
    		</li>
    		<li>
    		    <a href="#tab_special" data-toggle="tab" class="<?php echo ($error_special ? 'red' : ''); ?>"><span><?php echo $tab_special; ?></span></a>
    		</li>
    		<li>
    		    <a href="#tab_image" data-toggle="tab"><span><?php echo $tab_image; ?></span></a>
    		</li>
    		<li>    
    		    <a href="#tab-reward" data-toggle="tab"><span><?php echo $tab_reward; ?></span></a>
    		</li>
		<?php /* else: ?>
		  <a tab="#tab_attributes"><span><?php echo __('Attributes'); ?></span></a>
		  <?php */ endif; ?>
	    </ul>
	    <?php if (count($product_details)): ?>
    	    <input type='hidden' name='product_type_id' value='<?php echo $product_type_id ?>' />
	    <?php endif; ?>
	    <div class="tab-content">
		<div id="tab_general" class="tab-pane active">
		    <ul id="languages" class="nav nav-tabs">
			<?php
			$i = 0;
			foreach ($languages as $language) :
			    ?>
    			<li <?php if ($i == 0): ?>class="active"<?php endif; ?>>
    			    <a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><span><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></span></a>
    			</li>
			    <?php
			    $i++;
			endforeach;
			?>
		    </ul> 
		    <div class="tab-content">
			<?php
			$i = 0;
			foreach ($languages as $language) :
			    ?>
    			<div id="language<?php echo $language['language_id']; ?>" class="tab-pane<?php if ($i == 0): ?> active<?php endif; ?>">
    			    <table class="form">
    				<tr>
    				    <td><?php echo $entry_name; ?><span class="required">*</span> </td>
    				    <td><input class="form-control name" type="text" name="product_description[<?php echo $language['language_id']; ?>][name]" size="100" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['name'] : ''; ?>" />
					    <?php if (isset($error_name[$language['language_id']])) { ?>
						<span class="error"><?php echo $error_name[$language['language_id']]; ?></span>
					    <?php } ?>
    				    </td>
    				</tr>
    				<tr>
    				    <td><?php echo __('entry_meta_title'); ?></td>
    				    <td><input class="form-control" type="text" name="product_description[<?php echo $language['language_id']; ?>][meta_title]" size="100" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['meta_title'] : ''; ?>" /></td>
    				</tr>
                    <tr>
                        <td><?php echo __('entry_meta_link'); ?></td>
                        <td><input class="form-control" type="text" name="product_description[<?php echo $language['language_id']; ?>][meta_link]" size="100" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['meta_link'] : ''; ?>" /></td>
                    </tr>
                    <tr>
                        <td><?php echo __('entry_img_alt'); ?></td>
                        <td><input class="form-control" type="text" name="product_description[<?php echo $language['language_id']; ?>][img_alt]" size="100" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['img_alt'] : ''; ?>" /></td>
                    </tr>
    				<tr>
    				    <td><?php echo $entry_meta_keywords; ?></td>
    				    <td><textarea class="form-control" name="product_description[<?php echo $language['language_id']; ?>][meta_keywords]" cols="40" rows="5"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['meta_keywords'] : ''; ?></textarea></td>
    				</tr>
                    <tr>
                        <td><?php echo $entry_meta_description; ?></td>
                        <td><textarea class="form-control" name="product_description[<?php echo $language['language_id']; ?>][meta_descript]" cols="40" rows="5"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['meta_descript'] : ''; ?></textarea></td>
                    </tr>
    				<tr>
    				    <td><?php echo __('entry_short_description'); ?></td>
    				    <td><textarea class="form-control" name="product_description[<?php echo $language['language_id']; ?>][meta_description]" cols="40" rows="5"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['meta_description'] : ''; ?></textarea></td>
    				</tr>
    				<tr>
    				    <td><?php echo $entry_description; ?></td>
    				    <td><textarea class="form-control" name="product_description[<?php echo $language['language_id']; ?>][description]" data-rel="wyswyg" id="description<?php echo $language['language_id']; ?>"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['description'] : ''; ?></textarea></td>
    				</tr>
    				<tr>
    				    <td><?php echo $entry_tags; ?></td>
    				    <td><input class="form-control" type="text" name="product_tags[<?php echo $language['language_id']; ?>]" value="<?php echo isset($product_tags[$language['language_id']]) ? $product_tags[$language['language_id']] : ''; ?>" size="80"/></td>
    				</tr>
    			    </table>
    			</div>
			    <?php
			    $i++;
			endforeach;
			?>
		    </div>
		</div>
		<div id="tab_data" class="tab-pane">
		    <table class="form">
			<tr>
			    <td> <?php echo $entry_model; ?><span class="required">*</span></td>
			    <td><?php echo CHtml::activeTextField($model, 'model',array('class' => 'form-control')); ?>
				<?php if ($error_model) { ?>
    				<span class="error"><?php echo $error_model; ?></span>
				<?php } ?></td>
			</tr>
			<tr>
			    <td><?php echo $entry_status; ?></td>
			    <td><div class="ui-select"><?php echo CHtml::activeDropDownList($model, 'status', $aStatus,array('class' => 'form-control')); ?></div></td>
			</tr>
            <tr>
                <td><?php echo __('entry_size_chart'); ?></td>
                <td><div class="ui-select">
                        <select name="size_chart">
                            <?php if($AsizeChart=='0') { ?>
                            <option value="0" selected="selected"><?php echo __('text_disabled'); ?></option>
                            <option value="1"><?php echo __('text_enabled'); ?></option>
                            <?php } else { ?>
                            <option value="0"><?php echo __('text_disabled'); ?></option>
                            <option value="1" selected="selected"><?php echo __('text_enabled'); ?></option>
                            <?php } ?>
                        </select>
                </div></td>
            </tr>
			<?php if (isset($fabric_categories) && $fabric_categories): ?>
    			<tr>
    			    <td><?php echo $entry_category; ?></td>
    			    <td>
					<div class="ui-select">
						<select name="product_category[]">
	
						<?php foreach ($fabric_categories as $category) { ?>
	
							<option value="<?php echo $category['category_id']; ?>" <?php if (in_array($category['category_id'], $product_category)) : ?>selected="selected"<?php endif; ?>>
	
							<?php echo $category['name']; ?></option>
	
						<?php } ?>
	
						</select>
					</div>
    			    </td>
    			</tr>
			<?php endif; ?>
			<tr>
			    <td><?php echo $entry_price; ?></td>
			    <td>
			    	<?php echo CHtml::activeTextField($model, 'price',array('class' => 'form-control')); ?>
			    	<?php if ($error_price) { ?>
    					<span class="error"><?php echo $error_price; ?></span>
					<?php } ?></td>
			    </td>
			</tr>
			<?php /*<tr>
			    <td><?php echo $entry_price_standard; ?></td>
			    <td><?php echo CHtml::activeTextField($model, 'price_standard',array('class' => 'form-control')); ?></td>
			</tr>
			<tr>
			    <td><?php echo $entry_price_custom; ?></td>
			    <td><?php echo CHtml::activeTextField($model, 'price_custom',array('class' => 'form-control')); ?></td>
			</tr> */?>
			<tr>
			    <td><?php echo $entry_cost; ?></td>
			    <td>
			    	<?php echo CHtml::activeTextField($model, 'cost',array('class' => 'form-control')); ?>
			    	<?php if ($error_cost) { ?>
    					<span class="error"><?php echo $error_cost; ?></span>
					<?php } ?></td>
		    	</td>
			</tr>
			<tr>
			    <td><?php echo $entry_tax_class; ?></td>
			    <td>
				<div class="ui-select">
				<select name="tax_class_id" class="form-control">
	
						<option value="0"><?php echo $text_none; ?></option>
	
						<?php foreach ($tax_classes as $tax_class) { ?>
	
							<option value="<?php echo $tax_class['tax_class_id']; ?>" <?php echo ($tax_class['tax_class_id'] == $model->tax_class_id ? "selected" : ""); ?>><?php echo $tax_class['title']; ?></option>
	
						<?php } ?>
	
					</select>
                  </div>
				</td>
			</tr>
            <tr>
                <td>
                    <?php echo $entry_delivery_days; ?><br/>
                    <span class="help"><?php echo __('Days after product to deliver'); ?></span>
                </td>
                <td><input type="text" name="delivery_days" value="<?php echo $delivery_days; ?>" size="2" /></td>
            </tr>
            <?php /* <tr>
                <td>
                    <?php echo $entry_delivery_days_standard; ?><br/>
                    <span class="help"><?php echo __('Days after Standard stitched product to deliver'); ?></span>
                </td>
                <td><input type="text" name="delivery_days_standard" value="<?php echo $delivery_days_standard; ?>" size="2" /></td>
            </tr> */ ?>
             <tr>
                <td>
                    <?php echo $entry_delivery_days_custom; ?><br/>
                    <span class="help"><?php echo __('Days after Custom Tailored product to deliver'); ?></span>
                </td>
                <td><input type="text" name="delivery_days_custom" value="<?php echo $delivery_days_custom; ?>" size="2" /></td>
            </tr>
            <tr>
                <td>
                    <?php echo $entry_cutoff_time; ?>
                </td>
                <td>
                    <div class="ui-select">
                    <select name="cutoff_time" class="form-control">
                        <option value=""><?php echo $text_select; ?></option>
                        <?php for($i = 0; $i <= 23; $i++):
                            $time = date('H:i',mktime($i,0,0));
                        ?>
                            <option value="<?php echo $time; ?>" <?php echo ($time == $cutoff_time ? 'selected' : ''); ?>><?php echo $time; ?></option>
                        <?php endfor; ?>
                    </select>
                  </div>
                </td>
            </tr>
			<tr>
			    <td>
                    <?php echo $entry_quantity; ?><br/>
                    <span class="help"><?php echo __('Calculated qty from product options'); ?></span>
                </td>
                <td>
                    <input type="text" name="quantity" value="<?php echo $quantity; ?>" size="3" />
                    <?php if ($error_quantity_numbers) { ?>
                    <span class="error"><?php echo $error_quantity_numbers; ?></span>
                    <?php } ?>
                </td>
			</tr>
			<tr>
			    <td><?php echo $entry_minimum; ?></td>
			    <td><?php echo CHtml::activeTextField($model, 'minimum'); ?></td>
			</tr>
			<tr>
			    <td><?php echo $entry_subtract; ?></td>
			    <td><div class="ui-select">
				<select name="subtract" >
                        <option value="1" <?php echo ($model->subtract ? "selected" : ""); ?> ><?php echo $text_yes; ?></option>
                        <option value="0" <?php echo (!$model->subtract ? "selected" : ""); ?> ><?php echo $text_no; ?></option>
				</select>
				</div></td>
			</tr>
			<tr>
			    <td><?php echo $entry_stock_status; ?></td>
			    <td><div class="ui-select">
				<select name="stock_status_id">
				    <?php foreach ($stock_statuses as $stock_status) { ?>
                        <option value="<?php echo $stock_status['stock_status_id']; ?>" <?php echo ($stock_status['stock_status_id'] == $model->stock_status_id ? "selected" : ""); ?> ><?php echo $stock_status['name']; ?></option>
				    <?php } ?>
				</select>
				</div></td>
			</tr>
			<tr>
			    <td><?php echo $entry_sku; ?></td>
			    <td><?php echo CHtml::activeTextField($model, 'sku',array('class' => 'form-control')); ?></td>
			</tr>
			<?php /* <tr>
			    <td><?php echo $entry_location; ?></td>
			    <td><?php echo CHtml::activeTextField($model, 'location',array('class' => 'form-control')); ?></td>
			</tr> */ ?>
			<tr>
			    <td><?php echo $entry_keyword; ?></td>
			    <td><input type="text" name="keyword" value="<?php echo $keyword; ?>" class="form-control keyword" /></td>
			</tr>
			<tr>
			    <td><?php echo $entry_image; ?></td>
			    <td><input type="hidden" name="image" value="<?php echo $image; ?>" id="image" />
				<img src="<?php echo $preview; ?>" alt="" id="preview_image" class="image" width="122" height="122" />
                    <!-- <span class="btn btn-success btn-sm fileinput-button top"><i class="icon-camera"></i>
                    <span>Select Image...</span>
                    <!-- The file input field used as target for the file upload widget
                    <input type="file" name="image" rel="image" class="fileupload">
                    </span> -->
                    <div class="fileinput-button">
                        <button type="button" class="btn btn-success btn-sm top"><i class="icon-camera"></i> <span>Select Image....</span></button>
                        <input type="file" name="image" rel="image" class="fileupload" />
                    </div>
                </td>
			</tr>
			<tr>
			    <td><?php echo $entry_thumb; ?></td>
			    <td><input type="hidden" name="thumb" value="<?php echo $thumb; ?>" id="thumb" />
				    <img src="<?php echo $preview_thumb; ?>" alt="" id="preview_thumb" class="image" width="122" height="122" />
                    <div class="fileinput-button">
                        <button type="button" class="btn btn-success btn-sm top"><i class="icon-camera"></i> <span>Select Image....</span></button>
                        <input type="file" name="image" rel="thumb" class="fileupload" />
                    </div>
                </td>
			</tr>
			<tr>
			    <td><?php echo $entry_date_available; ?></td>
			    <td><?php echo CHtml::activeTextField($model, 'date_available',array('class' => 'form-control', "data-provide" => "datepicker-inline","value" => QS::formatPHPDate($model->date_available))); ?></td>
			</tr>
			<tr>
			    <td><?php echo $entry_sort_order; ?></td>
			    <td><?php echo CHtml::activeTextField($model, 'sort_order', array('size' => 2)); ?></td>
			</tr>
			<tr>
			    <td><?php echo $entry_dimension; ?></td>
			    <td>
				<?php echo CHtml::activeTextField($model, 'length', array('size' => 4)); ?>
				<?php echo CHtml::activeTextField($model, 'width', array('size' => 4)); ?>
				<?php echo CHtml::activeTextField($model, 'height', array('size' => 4)); ?>
			    </td>
			</tr>
			<tr>
			    <td><?php echo $entry_length; ?></td>
			    <td><div class="ui-select"><select name="length_class_id" class="form-control">
				    <?php foreach ($length_classes as $length_class) { ?>
					<?php if ($length_class['length_class_id'] == $length_class_id) { ?>
					    <option value="<?php echo $length_class['length_class_id']; ?>" selected="selected"><?php echo $length_class['title']; ?></option>
					<?php } else { ?>
					    <option value="<?php echo $length_class['length_class_id']; ?>"><?php echo $length_class['title']; ?></option>
					<?php } ?>
				    <?php } ?>
				</select>
				</div></td>
			</tr>
			<tr>
			    <td><?php echo $entry_weight; ?></td>
			    <td><?php echo CHtml::activeTextField($model, 'weight',array('class' => 'form-control')); ?></td>
			</tr>
			<tr>
			    <td><?php echo $entry_weight_class; ?></td>
			    <td><div class="ui-select">
				<select name="weight_class_id" class="form-control" class="form-control">
				    <?php foreach ($weight_classes as $weight_class) { ?>
					<?php if ($weight_class['weight_class_id'] == $weight_class_id) { ?>
					    <option value="<?php echo $weight_class['weight_class_id']; ?>" selected="selected"><?php echo $weight_class['title']; ?></option>
					<?php } else { ?>
					    <option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
					<?php } ?>
				    <?php } ?>
				</select>
				</div></td>
			</tr>
		    </table>
            <input type="hidden" name="shipping" value="1" />
		</div>
		<?php if ($product_type_id != 2): ?>
    		<div id="tab_links" class="tab-pane">
    		    <table class="form">
    			<tr>
    			    <td><?php echo $entry_manufacturer; ?></td>
    			    <td>
                       <div class="ui-select">
                        <select name="manufacturer_id" class="form-control">
    				    <option value="0" selected="selected"><?php echo $text_none; ?></option>
                        <?php foreach ($manufacturers as $manufacturer): ?>
                            <option value="<?php echo $manufacturer['manufacturer_id']; ?>" <?php echo ($manufacturer['manufacturer_id'] == $model->manufacturer_id ? "selected" : ""); ?> ><?php echo $manufacturer['name']; ?></option>
                        <?php endforeach; ?>
    				</select>
					</div></td>
    			</tr>         
    			<tr>
    			    <td><?php echo $entry_category; ?></td>
    			    <td><div class="scrollbox">
					<?php $class = 'odd'; ?>
					<?php foreach ($categories as $category) { ?>
					    <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
					    <div class="<?php echo $class; ?>">
						<?php if (in_array($category['category_id'], $product_category)) { ?>
	    					<input type="checkbox" name="product_category[]" value="<?php echo $category['category_id']; ?>" checked="checked" />
						    <?php echo $category['name']; ?>
						<?php } else { ?>
	    					<input type="checkbox" name="product_category[]" value="<?php echo $category['category_id']; ?>" />
						    <?php echo $category['name']; ?>
						<?php } ?>
					    </div>
					<?php } ?>
    				</div>
    			    </td>
    			</tr>
    			<tr>
    			    <td><?php echo $entry_related; ?></td>
    			    <td>
                        <table>
                        <thead>
                            <col width="1" />
                            <col width="1" />
                            <col />
                        </thead>
    				    <tr>
    					<td style="padding: 0;" colspan="3"><div class="ui-select"><select id="category_related" class="category" data-rel="related" style="margin-bottom: 5px;" onchange="getProducts(this,false);">
						    <?php foreach ($categories as $category) { ?>
							<option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
						    <?php } ?>
    					    </select>
							</div></td>
    				    </tr>
    				    <tr>
    					<td>
                          
                            <select multiple="multiple" class="product" id="type_related" size="10" style="width: 350px;"></select>
							
                        </td>
    					<td style="vertical-align: middle;">
                            <button class="btn-glow" type="button" onclick="addRelated(this);" data-rel="related" ><i class="icon-arrow-right"></i></button>
                            <br/>
    					    <button class="btn-glow" type="button" onclick="removeRelated(this);" data-rel="related" ><i class="icon-arrow-left"></i></button>
                        </td>
    					<td style="padding: 0;">
                         
                            <select multiple="multiple" id="related" size="10" style="width: 350px;">
                                <?php foreach ($product_related as $related): ?>
                                    <option value="<?php echo $related['related_id']; ?>"><?php echo $related['name']." (".$related['model'].") "; ?></option>
                                <?php endforeach; ?>
    					    </select>
                          
                        </td>
    				    </tr>
    				</table>
    				<div id="product_related">
					<?php foreach ($product_related as $related) { ?>
					    <input type="hidden" name="product_related[]" value="<?php echo $related['related_id']; ?>" />
					<?php } ?>
    				</div>
                    </td>
    			</tr>
                <tr>
                    <td><?php echo $entry_cross_sell; ?></td>
                    <td>
                        <table>
                            <thead>
                                <col width="1" />
                                <col width="1" />
                                <col />
                            </thead>
                            <tr>
                                <td style="padding: 0;" colspan="3">
								<div class="ui-select">
								<select id="category_cross_sell" class="category" data-rel="cross_sell" style="margin-bottom: 5px;" onchange="getProducts(this,false);">
                                        <?php foreach ($categories as $category) { ?>
                                        <option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
                                        <?php } ?>
                                    </select>
									</div></td>
                            </tr>
                            <tr>
                                <td style="padding: 0;">
                                    
                                    <select multiple="multiple" class="product" id="type_cross_sell" size="10" style="width: 350px;"></select>
                                </td>
                                <td style="vertical-align: middle;">
                                    <button class="btn-glow" type="button" onclick="addRelated(this);" data-rel="cross_sell" ><i class="icon-arrow-right"></i></button>
                                    <br/>
                                    <button class="btn-glow" type="button" onclick="removeRelated(this);" data-rel="cross_sell" ><i class="icon-arrow-left"></i></button>
                                </td>
                                <td style="padding: 0;">
                                     
                                    <select multiple="multiple" id="cross_sell" size="10" style="width: 350px;">
                                        <?php foreach ($product_cross_sell as $related): ?>
                                        <option value="<?php echo $related['related_id']; ?>"><?php echo $related['name']." (".$related['model'].") "; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                  
                                </td>
                            </tr>
                        </table>
                        <div id="product_cross_sell">
                            <?php foreach ($product_cross_sell as $related) { ?>
                            <input type="hidden" name="product_cross_sell[]" value="<?php echo $related['related_id']; ?>" />
                            <?php } ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><?php echo $entry_upsell; ?></td>
                    <td>
                        <table>
                            <thead>
                            <col width="1" />
                            <col width="1" />
                            <col />
                            </thead>
                            <tr>
                                <td style="padding: 0;" colspan="3"><div class="ui-select"><select id="category_up_sell" class="category" data-rel="up_sell" style="margin-bottom: 5px;" onchange="getProducts(this,false);">
                                        <?php foreach ($categories as $category) { ?>
                                        <option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
                                        <?php } ?>
                                    </select></div></td>
                            </tr>
                            <tr>
                                <td style="padding: 0;">
                                   
                                    <select multiple="multiple" class="product" size="10" id="type_up_sell" style="width: 350px;"></select>
                                </td>
                                <td style="vertical-align: middle;">
                                    <button class="btn-glow" type="button" onclick="addRelated(this);" data-rel="up_sell" ><i class="icon-arrow-right"></i></button>
                                    <br/>
                                    <button class="btn-glow" type="button" onclick="removeRelated(this);" data-rel="up_sell" ><i class="icon-arrow-left"></i></button>
                                </td>
                                <td style="padding: 0;">
                                  
                                    <select multiple="multiple" id="up_sell" size="10" style="width: 350px;">
                                        <?php foreach ($product_up_sell as $related): ?>
                                        <option value="<?php echo $related['related_id']; ?>"><?php echo $related['name']." (".$related['model'].") "; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                  
                                </td>
                            </tr>
                        </table>
                        <div id="product_up_sell">
                            <?php foreach ($product_up_sell as $related) { ?>
                            <input type="hidden" name="product_up_sell[]" value="<?php echo $related['related_id']; ?>" />
                            <?php } ?>
                        </div>
                    </td>
                </tr>
    		    </table>
    		</div>
    		<div id="tab_discount" class="tab-pane">
                <br/>
                <?php if($error_discount): ?>
            		<div class="alert alert-danger"><?php echo $error_discount; ?></div>
            	<?php endif; ?>
    		    <table id="discount" class="table table-hover">
    			<thead>
    			    <tr>
    				<th class="left"><?php echo $entry_customer_group; ?></th>
    				<th class="left"><span class="line"></span><?php echo $entry_quantity; ?></th>
    				<th class="left"><span class="line"></span><?php echo $entry_priority; ?></th>
    				<th class="left"><span class="line"></span><?php echo $entry_price; ?></th>
    				<th class="left"><span class="line"></span><?php echo $entry_date_start; ?></th>
    				<th class="left"><span class="line"></span><?php echo $entry_date_end; ?></th>
    				<th></th>
    			    </tr>
    			</thead>
			    <?php $discount_row = 0; ?>
			    <?php foreach ($product_discounts as $product_discount) {
			        $date_start = $product_discount['date_start'] == '0000-00-00' ? str_replace(array('d','m','y'),0,$this->config->get('config_date_format')) : date($this->config->get('config_date_format_php'),strtotime($product_discount['date_start']));
			        $date_end = $product_discount['date_end'] == '0000-00-00' ? str_replace(array('d','m','y'),0,$this->config->get('config_date_format')) : date($this->config->get('config_date_format_php'),strtotime($product_discount['date_end']));
			    ?>
				<tbody id="discount_row<?php echo $discount_row; ?>">
				    <tr>
					<td class="left"><div class="ui-select"><select name="product_discount[<?php echo $discount_row; ?>][customer_group_id]" class="form-control">
						<?php foreach ($customer_groups as $customer_group) { ?>
						    <?php if ($customer_group['customer_group_id'] == $product_discount['customer_group_id']) { ?>
							<option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
						    <?php } else { ?>
							<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
						    <?php } ?>
						<?php } ?>
					    </select></div></td>
					<td class="left"><input class="form-control" type="text" name="product_discount[<?php echo $discount_row; ?>][quantity]" value="<?php echo $product_discount['quantity']; ?>" size="2" /></td>
					<td class="left"><input class="form-control" type="text" name="product_discount[<?php echo $discount_row; ?>][priority]" value="<?php echo $product_discount['priority']; ?>" size="2" /></td>
					<td class="left"><input class="form-control" type="text" name="product_discount[<?php echo $discount_row; ?>][price]" value="<?php echo $product_discount['price']; ?>" /></td>
					<td class="left"><input class="form-control" type="text" name="product_discount[<?php echo $discount_row; ?>][date_start]" value="<?php echo $date_start; ?>" data-provide="datepicker-inline" /></td>
					<td class="left"><input class="form-control" type="text" name="product_discount[<?php echo $discount_row; ?>][date_end]" value="<?php echo $date_end; ?>" data-provide="datepicker-inline" /></td>
					<td class="left"><a class="btn-flat btn-danger" onclick="$('#discount_row<?php echo $discount_row; ?>').remove();" ><span><?php echo $button_remove; ?></span></a></td>
				    </tr>
				</tbody>
				<?php $discount_row++; ?>
			    <?php } ?>
    			<tfoot>
    			    <tr>
    				<td colspan="5"></td>
    				<td class="left"><a onclick="addDiscount();" class="btn-flat btn-success"><span><?php echo $button_add_discount; ?></span></a></td>
    			    </tr>
    			</tfoot>
    		    </table>
    		</div>
    		<div id="tab_special" class="tab-pane">
                <br/>
                <?php if($error_special): ?>
            		<div class="alert alert-danger"><?php echo $error_special; ?></div>
            	<?php endif; ?>
    		    <table id="special" class="table table-hover">
    			<thead>
    			    <tr>
    				<th class="left"><?php echo $entry_customer_group; ?></th>
    				<th class="left"><span class="line"></span><?php echo $entry_priority; ?></th>
    				<th class="left"><span class="line"></span><?php echo $entry_price; ?></th>
    				<th class="left"><span class="line"></span><?php echo $entry_date_start; ?></th>
    				<th class="left"><span class="line"></span><?php echo $entry_date_end; ?></th>
    				<th></th>
    			    </tr>
    			</thead>
			    <?php $special_row = 0; ?>
			    <?php foreach ($product_specials as $product_special) {
                    $date_start = $product_special['date_start'] == '0000-00-00' ? str_replace(array('d','m','y'),0,$this->config->get('config_date_format')) : date($this->config->get('config_date_format_php'),strtotime($product_special['date_start']));
                    $date_end = $product_special['date_end'] == '0000-00-00' ? str_replace(array('d','m','y'),0,$this->config->get('config_date_format')) : date($this->config->get('config_date_format_php'),strtotime($product_special['date_end']));
                    ?>
				<tbody id="special_row<?php echo $special_row; ?>">
				    <tr>
					<td class="left"><div class="ui-select"><select name="product_special[<?php echo $special_row; ?>][customer_group_id]" >
						<?php foreach ($customer_groups as $customer_group) { ?>
						    <?php if ($customer_group['customer_group_id'] == $product_special['customer_group_id']) { ?>
							<option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
						    <?php } else { ?>
							<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
						    <?php } ?>
						<?php } ?>
					    </select></div></td>
					<td class="left"><input  class="form-control" type="text" name="product_special[<?php echo $special_row; ?>][priority]" value="<?php echo $product_special['priority']; ?>" size="2" /></td>
					<td class="left"><input class="form-control" type="text" name="product_special[<?php echo $special_row; ?>][price]" value="<?php echo $product_special['price']; ?>" /></td>
					<td class="left"><input class="form-control" type="text" name="product_special[<?php echo $special_row; ?>][date_start]" value="<?php echo $date_start; ?>" data-provide="datepicker-inline" /></td>
					<td class="left"><input class="form-control" type="text" name="product_special[<?php echo $special_row; ?>][date_end]" value="<?php echo $date_end; ?>" data-provide="datepicker-inline" /></td>
					<td class="left"><a onclick="$('#special_row<?php echo $special_row; ?>').remove();" class="btn-flat btn-danger"><span><?php echo $button_remove; ?></span></a></td>
				    </tr>
				</tbody>
				<?php $special_row++; ?>
			    <?php } ?>
    			<tfoot>
    			    <tr>
    				<td colspan="4"></td>
    				<td class="left"><a onclick="addSpecial();" class="btn-flat btn-success"><span><?php echo $button_add_special; ?></span></a></td>
    			    </tr>
    			</tfoot>
    		    </table>
    		</div>
    		<div id="tab-reward" class="tab-pane">
    		    <table class="form">
    			<tr>
    			    <td><?php echo $entry_points; ?></td>
    			    <td><?php echo CHtml::activeTextField($model, 'points',array('class' => 'form-control')); ?></td>
    			</tr>
    		    </table>
    		    <table class="table table-hover">
    			<thead>
    			    <tr>
    				<th class="left"><span class="line"></span><?php echo $entry_customer_group; ?></th>
    				<th class="right"><span class="line"></span><?php echo $entry_reward; ?></th>
    			    </tr>
    			</thead>
			    <?php foreach ($customer_groups as $customer_group) { ?>
				<tbody>
				    <tr>
					<td class="left"><?php echo $customer_group['name']; ?></td>
					<td class="right"><input class="form-control" type="text" name="product_reward[<?php echo $customer_group['customer_group_id']; ?>][points]" value="<?php echo isset($product_reward[$customer_group['customer_group_id']]) ? $product_reward[$customer_group['customer_group_id']]['points'] : ''; ?>" /></td>
				    </tr>
				</tbody>
			    <?php } ?>
    		    </table>
    		</div>
    		<div id="tab_image" class="tab-pane">
                <br/>
                <div id="fileupload_images">
                <div class="fileupload-buttonbar">
                    <div class="col-lg-7">
                        <!-- The fileinput-button span is used to style the file input field as button -->
                        <div class="fileinput-button" style="">
                            <button type="button" class="btn-flat btn-success"><i class="icon-plus"></i> <span>Add Files....</span></button>
                            <input type="file" name="image" multiple />
                        </div>
                        <button type="reset" class="btn-flat btn-warning cancel">
                            <i class="icon-remove-circle"></i>
                            <span>Cancel upload</span>
                        </button>
                        <button type="button" class="btn-flat btn-danger delete">
                            <i class="icon-trash"></i>
                            <span>Delete</span>
                        </button>
                        <!-- The global file processing state -->
                        <span class="fileupload-process"></span>
                    </div>
                    <!-- The global progress state -->
                    <div class="col-lg-5 fileupload-progress fade">
                        <!-- The global progress bar -->
                        <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                        </div>
                        <!-- The extended global progress state -->
                        <div class="progress-extended">&nbsp;</div>
                    </div>
                </div>
    		    <table role="presentation" id="images" class="table table-hover image-table table-bordered">
    			<thead>
    			    <tr>
    			    	<th width="3%" class="text-center"><input type="checkbox" class="toggle"></th>
	    				<th class="left"><?php echo __('Image'); ?></th>
	    				<th width="30%" class="text-center" colspan="2">Actions</th>
    			    </tr>
    			</thead>
                    <tbody class="files"></tbody>
    		    </table>
                </div>
    		</div>
		<?php endif; ?>
	    </div>
	</div>
    </div>
</form>
<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
    {% row = (parseInt($('#images tbody tr').length) + 1); %}
    {% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade" data-rel="{%=row%}">
    	<td>&nbsp;</td>
        <td>
            <input type="hidden" name="product_image[{%=row%}]" value="" id="image{%=row%}"  />
        	<span class="preview"></span>
            <div class="clearfix"></div>
            <p class="name">{%=file.name%}</p>
            <strong class="error text-danger"></strong>
        </td>
        <td width="10%">
        	<input type="number" name="sort_order[{%=row%}]" value="{%=row%}" class="form-control" placeholder="Sort Order" />
        </td>
        <td>
            <p class="size">Processing...</p>
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
            {% if (!i && !o.options.autoUpload) { %}
                <button class="btn btn-primary btn-sm start" data-rel="{%=row%}" disabled><i class="icon-upload"></i><span>Start</span></button>
            {% } %}
            {% if (!i) { %}
                <button class="btn btn-warning btn-sm cancel"><i class="icon-remove-circle"></i><span>Cancel</span></button>
            {% } %}
        </td>
    </tr>
    {% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
    {% j = (parseInt($('#images tbody tr.template-download').length) + 1); %}
    {% if(o.files.length > 0) { %}
    {% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
    	<td class="text-center">
            <input type="checkbox" name="delete[]" value="{%= file.name + "|" + file.id %}" class="toggle">
		</td>
        <td>
            <input type="hidden" name="product_image[{%= j %}][image]" value="{%=file.url%}" id="image{%= j %}"  />
            <input type="hidden" name="product_image[{%= j %}][sort_order]" value="{%=file.sort_order%}" />
             <span class="preview">
                <img src="{%=file.preview%}"  width="100" height="100" />
             </span>
        </td>
        <td class="text-center" colspan="2">
            {% if (file.deleteUrl) { %}
            <button type="button" class="btn btn-danger btn-sm delete-image" rel={%= file.name %} value={%= file.id %} >
            <i class="icon-trash"></i>
            <span>Delete</span>
            </button>
            {% } %}
        </td>
    </tr>
    {%
     j++;
    } %}
    {% } %}
</script>

<script type="text/javascript">
    function addRelated(e) {
        var rel = $(e).attr('data-rel');
        $('#type_'+rel+' :selected').each(function() {
            $(this).remove();
            $('#'+rel+' option[value=\'' + $(this).attr('value') + '\']').remove();
            $('#'+rel).append('<option value="' + $(this).attr('value') + '">' + $(this).text() + '</option>');
            $('#product_'+rel+' input[value=\'' + $(this).attr('value') + '\']').remove();
            $('#product_'+rel).append('<input type="hidden" name="product_'+rel+'[]" value="' + $(this).attr('value') + '" />');
        });
    }
    function removeRelated(e) {
        var rel = $(e).attr('data-rel');
        $('#'+rel+' :selected').each(function() {
            $('#product_'+rel+' input[value=\'' + $(this).attr('value') + '\']').remove();
            $('#type_'+rel+' option[value="' + $(this).attr('value') + '"]').remove();
            $('#type_'+rel).append('<option value="' + $(this).attr('value') + '">' + $(this).text() + '</option>');
            $(this).remove();
        });
    }
    function getProducts(e,all) {
        if(all) {
            var selector = '.product';
            var category = '.category';
        }
        else {
            var rel = $(e).attr('data-rel');
            var selector = '#type_'+rel;
            var category = '#category_'+rel;
        }
        $(selector+' option').remove();
        $.ajax({
            url: 'catalog/product/category&category_id=' + $(category).attr('value') + '&product_id=<?php echo $product_id; ?>',
            dataType: 'json',
            success: function(data) {
                for (i = 0; i < data.length; i++) {
                    $(selector).append('<option value="' + data[i]['product_id'] + '">' + data[i]['name'] + ' (' + data[i]['model'] + ') </option>');
                }
            }
        });
    }
    getProducts(undefined,true);

    $(document).on('click','.delete',function(e) {
    	if(window.confirm('Are you sure you want to remove all the selected images?')) {
	        $.ajax({
	            url: '<?php echo makeUrl("catalog/product/deleteImage"); ?>',
	            type: 'POST',
	            data: $('.toggle:checked').serialize(),
	            success: function(res) {
	                $('.toggle:checked').each(function(e) {
	                    $(this).closest('tr').remove();
	                });
	                $('#success_message').html("Product Image(s) Successfully deleted");
	                $('#success').show();
	            }
	        });
    	}
    });

    var discount_row = <?php echo $discount_row ? $discount_row : 0; ?>;
    var date = '<?php echo date("d-m-Y"); ?>';
    function addDiscount() {
		html = '<tbody id="discount_row' + discount_row + '">';
		html += '<tr>';
		html += '<td class="left"><div class="ui-select"><select name="product_discount[' + discount_row + '][customer_group_id]" style="margin-top: 3px;">';
		<?php foreach ($customer_groups as $customer_group) { ?>
		    	html += '<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>';
		<?php } ?>
		html += '</select></div></td>';
		html += '<td class="left"><input class="form-control" type="text" name="product_discount[' + discount_row + '][quantity]" value="" size="2" required /></td>';
		html += '<td class="left"><input class="form-control" type="text" name="product_discount[' + discount_row + '][priority]" value="" size="2" /></td>';
		html += '<td class="left"><input class="form-control" type="text" name="product_discount[' + discount_row + '][price]" value="" required /></td>';
		html += '<td class="left"><input class="form-control" type="text" name="product_discount[' + discount_row + '][date_start]" value="' + date + '" data-provide="datepicker-inline" required /></td>';
		html += '<td class="left"><input class="form-control" type="text" name="product_discount[' + discount_row + '][date_end]" value="' + date + '"  data-provide="datepicker-inline" required /></td>';
		html += '<td class="left"><a onclick="$(\'#discount_row' + discount_row + '\').remove();" class="btn-flat btn-danger"><span><?php echo $button_remove; ?></span></a></td>';
		html += '</tr>';
		html += '</tbody>';
		$('#discount tfoot').before(html);
		$('#discount_row' + discount_row + ' input[data-provide="datepicker-inline"]').datepicker({format: 'dd-mm-yyyy',autoclose:true});
		discount_row++;
    }

    var special_row = <?php echo $special_row ? $special_row : 0; ?>;
    function addSpecial() {
		html = '<tbody id="special_row' + special_row + '">';
		html += '<tr>';
		html += '<td class="left"><div class="ui-select"><select name="product_special[' + special_row + '][customer_group_id]">';
		<?php foreach ($customer_groups as $customer_group) { ?>
		    	html += '<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>';
		<?php } ?>
		html += '</select></td>';
		html += '<td class="left"><input class="form-control" type="text" name="product_special[' + special_row + '][priority]" value="" size="2" /></td>';
		html += '<td class="left"><input class="form-control" type="text" name="product_special[' + special_row + '][price]" value="" required /></td>';
		html += '<td class="left"><input class="form-control" type="text" name="product_special[' + special_row + '][date_start]" value="' + date + '" data-provide="datepicker-inline" required /></td>';
		html += '<td class="left"><input class="form-control" type="text" name="product_special[' + special_row + '][date_end]" value="' + date + '" data-provide="datepicker-inline" required /></td>';
		html += '<td class="left"><a onclick="$(\'#special_row' + special_row + '\').remove();" class="btn-flat btn-danger"><span><?php echo $button_remove; ?></span></a></td>';
		html += '</tr>';
		html += '</tbody>';
		$('#special tfoot').before(html);
		$('#special_row' + special_row + ' input[data-provide="datepicker-inline"]').datepicker({format: 'dd-mm-yyyy',autoclose:true});
		special_row++;
    }

    var image_row = <?php echo $image_row ? $image_row : 0; ?>;
    function addImage() {
		html = '<tbody id="image_row' + image_row + '">';
		html += '<tr>';
		html += '<td class="left"><input type="hidden" name="product_image[' + image_row + ']" value="" id="image' + image_row + '" /><img src="<?php echo $no_image; ?>" alt="" id="preview' + image_row + '" class="image" onclick="image_upload(\'image' + image_row + '\', \'preview' + image_row + '\');" /></td>';
		html += '<td class="left"><a onclick="$(\'#image_row' + image_row + '\').remove();" class="btn-flat btn-danger"><span><?php echo $button_remove; ?></span></a></td>';
		html += '</tr>';
		html += '</tbody>';
		$('#images tfoot').before(html);
		image_row++;
    }

    var detail_row = <?php echo $detail_row ? $detail_row : 0; ?>;
    function addProductDetail() {
		html = '<tbody id="detail_row' + detail_row + '">';
		html += '<tr>';
		html += '<td class="left"><input type="text" name="product_detail[' + detail_row + '][code]" value="" size="20" /></td>';
		<?php foreach ($product_type_options as $options): ?>
	    	html += '<td class="left">';
	    	html += '  <select name="product_detail[' + detail_row + '][desc][<?php echo $options['product_type_option_id'] ?>]">';
	    <?php foreach ($options['values'] as $value): ?>
			html += '    <option value="<?php echo $value['product_type_option_value_id'] ?>" <?php echo $value['product_type_option_value_id'] == $product_detail['color'] ? 'selected' : '' ?>><?php echo $value['name'] ?></option>';
	    <?php endforeach; ?>
	    	html += '  </select>';
	    	html += '</td>';
		<?php endforeach; ?>
		html += '</select></td>';
		html += '<td class="left"><input type="text" name="product_detail[' + detail_row + '][color_code]" value="" size="2" /></td>';
		html += '<td class="left"><input type="text" name="product_detail[' + detail_row + '][quantity]" value="" size="2" /></td>';
		html += '<td class="left"><input type="text" name="product_detail[' + detail_row + '][price]" value="" size="7" /></td>';
		html += '<td class="left"><input type="text" name="product_detail[' + detail_row + '][sort_order]" value="" size="2" /></td>';
		html += '<td class="left"><select name="product_detail[' + detail_row + '][status]" >';
		html += '   <option value="1" ><?php echo __("Enable") ?></option>';
		html += '   <option value="0" ><?php echo __("Disable") ?></option>';
		html += '</select> ';
		html += '<td><a onclick="addProductDetail()" class="button"><span><?php echo __('Add'); ?></span></a></td>';
		html += '</tr>';
		html += '</tbody>';
		$('#detail thead').after(html);
		detail_row++;
    }

    $(document).on('click','.delete-image',function(e) {
    	if(window.confirm('Are you sure you want to remove this image?')) {
            $(this).closest('tr').remove();
            var name = $(this).attr('rel');
            var id = $(this).attr('value');
            $.ajax({
                url: '".makeUrl("catalog/product/deleteImage")."',
                type: 'GET',
                data: {name: name,id: id},
                success: function(res) {
                    $('#success_message').html("Product Image(s) Successfully deleted");
                    $('#success').show();
                }
            });
		}
        return false;
    });
</script>
