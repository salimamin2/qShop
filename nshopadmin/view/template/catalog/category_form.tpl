<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" >

    <input type="hidden" class="form-control" name="directory" value="<?php echo $directory; ?>" />

    <div class="box table-wrapper products-table section">

	<div class="head well">
		<h3>
			<i class="icon-th-list"></i> <?php echo $heading_title; ?>
			<div class="pull-right">
					<button type="submit" class="btn-flat btn-success btn-sm" ><span><?php echo __('button_save'); ?></span></button>
					<a href="<?php echo $cancel; ?>" class="btn-flat btn-default btn-sm"><span><?php echo __('button_cancel'); ?></span></a>	
			</div>
		</h3>
	</div>
	<?php if ($error_warning) { ?>
	    <div class="alert alert-danger"><?php echo $error_warning; ?></div>
	<?php } ?>

	<div class="content">

	    <ul id="tabs" class="nav nav-tabs">

		<li class="active">

		    <a href="#tab_general" data-toggle="tab"><span><?php echo __('tab_general'); ?></span></a>

		</li>

		<li>

		    <a href="#tab_data" data-toggle="tab"><span><?php echo __('tab_data'); ?></span></a>

		</li>

	    </ul>

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

			foreach ($languages as $i => $language) :

                $cat_descrip = (isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']] : array());

			    ?>

    			<div id="language<?php echo $language['language_id']; ?>" class="tab-pane <?php echo ($i == 0 ? 'active' : ''); ?>">

    			    <table class="form">

    				<tr>

                        <td ><?php echo CHtml::label(__("Name"),'name'); ?><span class="required">*</span></td>

                        <td>

                            <?php echo CHtml::textField('category_description['.$language['language_id'].'][name]',(!empty($cat_descrip) ? $cat_descrip['name'] : ''),array('id' => 'name','required' => 'required','class' => 'form-control name')); ?>

                        </td>

    				</tr>

    				<tr>

    				    <td><?php echo CHtml::label(__('Meta Title'), 'meta_title'); ?></td>

    				    <td><?php echo CHtml::textField('category_description['.$language['language_id'].'][meta_title]',(!empty($cat_descrip) ? $cat_descrip['meta_title'] : ''),array('id' => 'meta_title','class' => 'form-control')); ?></td>

    				</tr>

                    <tr>

                        <td><?php echo CHtml::label(__('Meta Link'), 'meta_link'); ?></td>

                        <td><?php echo CHtml::textField('category_description['.$language['language_id'].'][meta_link]',(!empty($cat_descrip) ? $cat_descrip['meta_link'] : ''),array('id' => 'meta_link','class' => 'form-control')); ?></td>

                    </tr>

                    <tr>

                        <td><?php echo CHtml::label(__('Img Alt Title'), 'img_alt'); ?></td>

                        <td><?php echo CHtml::textField('category_description['.$language['language_id'].'][img_alt]',(!empty($cat_descrip) ? $cat_descrip['img_alt'] : ''),array('id' => 'img_alt','class' => 'form-control')); ?></td>

                    </tr>

    				<tr>

    				    <td><?php echo CHtml::label(__('Meta Keywords'),'meta_keywords'); ?></td>

    				    <td><?php echo CHtml::textArea('category_description['.$language['language_id'].'][meta_keywords]',(!empty($cat_descrip) ? $cat_descrip['meta_keywords'] : ''),array('id' => 'meta_keywords','rows' => 5,'cols' => 40,'class' => 'form-control')); ?></td>

    				</tr>

    				<tr>

    				    <td><?php echo CHtml::label(__('Meta Description'),'meta_description'); ?></td>

    				    <td><?php echo CHtml::textArea('category_description['.$language['language_id'].'][meta_description]',(!empty($cat_descrip) ? $cat_descrip['meta_description'] : ''),array('id' => 'meta_description','rows' => 5,'cols' => 40,'class' => 'form-control')); ?></td>

    				</tr>

    				<tr>

    				    <td><?php echo CHtml::label(__('Description'),'description'); ?></td>

    				    <td><?php echo CHtml::textArea('category_description['.$language['language_id'].'][description]',(!empty($cat_descrip) ? $cat_descrip['description'] : ''),array('id' => 'description','data-rel' => 'wyswyg')); ?></td>

    				</tr>

    			    </table>

    			</div>

			    <?php

			endforeach;

			?>

		    </div>

		</div>

		<div id="tab_data" class="tab-pane">

		    <table class="form">

			<tr>

			    <td><?php echo CHtml::label(__('entry_category'),'parent_id'); ?></td>

			    <td><div class="ui-select"><select name="parent_id" id="parent_id" class="form-control">

				    <option value="0"><?php echo __('text_none'); ?></option>

				    <?php foreach ($categories as $category) { ?>

					    <option value="<?php echo $category['category_id']; ?>" <?php echo ($category['category_id'] == $model->parent_id ? 'selected' : '');?> ><?php echo $category['name']; ?></option>

				    <?php } ?>

				</select></div></td>

			</tr>

			<tr>

			    <td><?php echo CHtml::activeLabel($model,'ref_category_code'); ?> <span class="required">*</span></td>

			    <td>

                    <?php echo CHtml::activeTextField($model,'ref_category_code',array('class' => 'form-control')); ?>

                </td>

			</tr>

			<tr>

			    <td><?php echo CHtml::label(__('entry_keyword'),'keyword'); ?></td>

			    <td><?php echo CHtml::textField('keyword',$keyword,array('class' => 'form-control keyword')); ?></td>

			</tr>

			<tr>

			    <td><?php echo CHtml::activelabel($model,'image'); ?></td>

			    <td valign="top">

                    <?php echo CHtml::activeHiddenField($model,'image'); ?>

				    <img src="<?php echo $preview; ?>" alt="" id="preview" width="122" height="122" style="border: 1px solid #EEEEEE;" />

                    <span class="btn btn-success btn-sm fileinput-button"><i class="icon-camera"></i>

                    <span>Select Image...</span>

                    <!-- The file input field used as target for the file upload widget -->

                    <input type="file" name="image" id="fileupload">

                    </span>

                </td>

			</tr>

			<tr>

			    <td><?php echo CHtml::activelabel($model,'status'); ?></td>

			    <td><div class="ui-select"><?php echo CHtml::activeDropDownList($model,'status',$status,array('class' => 'form-control')); ?></div></td>

			</tr>

			<tr>

			    <td><?php echo CHtml::activelabel($model,'sort_order'); ?></td>

			    <td><?php echo CHtml::activeTextField($model,'sort_order',array('size' => 1)); ?></td>

			</tr>



		    </table>

		</div>

	    </div>



	</div>

    </div>

</form>