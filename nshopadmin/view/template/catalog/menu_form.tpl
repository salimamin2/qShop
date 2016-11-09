<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">

<div class="box table-wrapper products-table section">

<div class="head well">
	<h3><i class="icon-table"></i><?php echo $heading_title; ?>
   		<div class="pull-right">
				<button type="submit" class="btn-flat btn-sm btn-success"><span><?php echo $button_save; ?></span></button>
				<a href="<?php echo $cancel; ?>" class="btn-flat btn-sm btn-default"><span><?php echo $button_cancel; ?></span></a>
		</div>
	</h3>
</div>
	<?php if ($error_warning) { ?>
	    <div class="alert alert-danger"><?php echo $error_warning; ?></div>
	<?php } ?>
	<div class="content">

	    <ul id="tabs" class="nav nav-tabs">

		<li class="active">

		    <a href="#tab_general" data-toggle="tab"><span><?php echo $tab_general; ?></span></a>

		</li>

		<li>

		    <a href="#tab_data" data-toggle="tab"><span><?php echo $tab_data; ?></span></a>

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



			$i = 0;

			foreach ($languages as $language) :



			    ?>

    			<div id="language<?php echo $language['language_id']; ?>" class="tab-pane <?php echo ($i == 0 ? 'active' : ''); ?>">

    			    <table class="form">

    				<tr>

    				    <td> <?php echo $entry_name; ?><span class="required">*</span></td>

    				    <td><input class="form-control" name="menu_description[<?php echo $language['language_id']; ?>][name]" size="100" value="<?php echo isset($menu_description[$language['language_id']]) ? $menu_description[$language['language_id']]['name'] : ''; ?>" />

					    <?php if (isset($error_name[$language['language_id']])) { ?>

						<span class="error"><?php echo $error_name[$language['language_id']]; ?></span>

					    <?php } ?></td>

    				</tr>

    				<tr>

    				    <td> <?php echo $entry_tagline; ?></td>

    				    <td><input class="form-control" name="menu_description[<?php echo $language['language_id']; ?>][tagline]" size="100" value="<?php echo isset($menu_description[$language['language_id']]) ? $menu_description[$language['language_id']]['tagline'] : ''; ?>" />

					    <?php if (isset($error_name[$language['language_id']])) { ?>

						<span class="error"><?php echo $error_name[$language['language_id']]; ?></span>

					    <?php } ?></td>

    				</tr>

                    <tr>

                        <td><?php echo $entry_meta_link; ?></td>

                        <td><input class="form-control" name="menu_description[<?php echo $language['language_id']; ?>][meta_link]" size="100" value="<?php echo isset($menu_description[$language['language_id']]) ? $menu_description[$language['language_id']]['meta_link'] : ''; ?>" />

                    </tr>

    				<tr>

    				    <td><?php echo __('entry_attributes'); ?><br/><span class="help"><?php echo __('Enter CSS Classes only'); ?></span></td>

    				    <td><input class="form-control" name="menu_description[<?php echo $language['language_id']; ?>][attributes]" size="100" value="<?php echo isset($menu_description[$language['language_id']]) ? $menu_description[$language['language_id']]['attributes'] : ''; ?>" /></td>

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

			    <td><?php echo __('entry_place_code'); ?></td>

			    <td>
                <div class="ui-select">
				<select name="place_code" class="form-control">

				    <?php foreach ($aBlocks as $skey => $sBlock): ?>

    				    <option value="<?php echo $skey ?>" <?php echo ($place_code == $skey) ? 'selected="selected"' : ''; ?>><?php echo $sBlock; ?></option>

				    <?php endforeach; ?>

				</select>
                </div>
			    </td>

			</tr>

			<tr>

			    <td><?php echo __('entry_link'); ?></td>

			    <td>
                 
				<input type="radio" name="link_type" value="information" id="link_info" <?php echo $link_type == 'information' ? 'checked="checked"' : ''; ?> /> Information Page

				<input type="radio" name="link_type" value="product" id="link_product" <?php echo $link_type == 'product' ? 'checked="checked"' : ''; ?> /> Product

				<input type="radio" name="link_type" value="category" id="link_category" <?php echo $link_type == 'category' ? 'checked="checked"' : ''; ?> /> Category

				<input type="radio" name="link_type" value="external" id="link_external" <?php echo $link_type == 'external' ? 'checked="checked"' : ''; ?> /> External Link

				<?php if (isset($link) && $link != ''): ?>

    				<input type="hidden" name="link" value="<?php echo $link; ?>" />

				<?php endif; ?>
				<div class="ui-select" style="width:250px">
				<div class="link-area"></div>
				</div>

			    </td>

			</tr>

            <tr>

                <td><?php echo __('entry_static'); ?></td>

                <td>
                    <div class="ui-select">
                    <select name="static_block" id="static_block" class="form-control">

                        <option value="0"><?php echo $text_none; ?></option>

                        <?php foreach($aInformations as $info): ?>

                            <option value="<?php echo $info['information_id']; ?>" <?php echo ($static_block == $info['information_id'] ? 'selected' : ''); ?>><?php echo $info['title']; ?></option>

                        <?php endforeach; ?>

                    </select>
					</div>

                </td>

            </tr>

			<tr>

			    <td><?php echo $entry_menu; ?></td>

			    <td>
				<div class="ui-select"><select name="parent_id" class="form-control" id="parent_menu" <?php echo (!empty($static_block) ? 'disabled' : ''); ?> >

				    <option value="0"><?php echo $text_none; ?></option>

				    <?php foreach ($menus as $menu) { ?>

					<?php if ($menu['menu_id'] != $menu_id) { ?>

					    <?php if ($menu['menu_id'] == $parent_id) { ?>

	    				    <option value="<?php echo $menu['menu_id']; ?>" selected="selected"><?php echo $menu['name']; ?></option>

					    <?php } else { ?>

	    				    <option value="<?php echo $menu['menu_id']; ?>"><?php echo $menu['name'] . ' - ' . (isset($aBlocks[$menu['place_code']]) ? $aBlocks[$menu['place_code']] : ''); ?></option>

					    <?php } ?>

					<?php } ?>

				    <?php } ?>

				</select>
                </div>
                </td>

			</tr>

            <tr>

                <td><?php echo $entry_batch; ?></td>

                <td>
                    <div class="ui-select">
                    <select name="batch" class="form-control">

                        <option value="0"><?php echo $text_none; ?></option>

                        <?php foreach($aBatches as $batch): ?>

                            <option value="<?php echo $batch; ?>" <?php echo ($batch == $menu_batch ? 'selected' : ''); ?>><?php echo $batch; ?></option>

                        <?php endforeach; ?>

                    </select>
                    </div>
                </td>

            </tr>

			<tr>

			    <td><?php echo $entry_status; ?></td>

			    <td>
				<div class="ui-select">
				<select name="status" class="form-control">

				    <?php if ($status) { ?>

    				    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>

    				    <option value="0"><?php echo $text_disabled; ?></option>

				    <?php } else { ?>

    				    <option value="1"><?php echo $text_enabled; ?></option>

    				    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>

				    <?php } ?>

				</select>
				</div></td>

			</tr>

			<tr>

			    <td><?php echo $entry_sort_order; ?></td>

			    <td><input name="sort_order" value="<?php echo $sort_order; ?>" size="1" /></td>

			</tr>

		    </table>

		</div>



	    </div>

	</div>

    </div>

</form>

<script type="text/javascript"><!--

$(document).ready(function() {

	var link_html = ''

	$('input[name=link_type]').click(function() {

	    if ($(this).val() == 'information') {

		link_html = '<select name="link" class="form-control">';

		link_html += '<option value="0"><?php echo $text_none; ?></option>';

<?php foreach ($aInformations as $result) { ?>

    		link_html += '<option value="<?php echo $result["link"]; ?>" <?php echo ($link == str_replace('&', '&amp;', 'information/information&information_id=' . $result["information_id"])) ? 'selected="selected"' : ''; ?>><?php echo $result["title"]; ?></option>';

<?php } ?>

		link_html += '</select>';

	    }

	    if ($(this).val() == 'product') {

		link_html = '<select name="link" class="form-control">';

		link_html += '<option value="0"><?php echo $text_none; ?></option>';

<?php foreach ($aProducts as $result) { ?>

    		link_html += '<option value="<?php echo $result["link"]; ?>" <?php echo ($link == str_replace('&', '&amp;', 'product/product&product_id=' . $result["product_id"])) ? 'selected="selected"' : ''; ?>><?php echo str_replace("'", "\'", $result["title"]); ?></option>';

<?php } ?>

		link_html += '</select>';

	    }

	    if ($(this).val() == 'category') {

		link_html = '<select name="link" class="form-control">';

		link_html += '<option value="0"><?php echo $text_none; ?></option>';

<?php foreach ($aCategories as $result) { ?>

    		link_html += '<option value="<?php echo $result["link"]; ?>" <?php echo ($link == str_replace('&', '&amp;', 'product/category&path=' . $result["category_id"])) ? 'selected="selected"' : ''; ?>><?php echo str_replace("'", "\'", $result["title"]); ?></option>';

<?php } ?>

		link_html += '</select>';

	    }

	    if ($(this).val() == 'external') {

		link_html = '<input class="form-control" type="text" name="link" value="<?php echo (isset($link) && $link != '') ? $link : '' ?>" />';

	    }

	    $('.link-area').html(link_html);

	});

	if ($('input[name=link_type]:checked')) {

	    $('input[name=link_type]:checked').click();

	}

    });



    $(document).on('change','#static_block',function(e) {

        var val = $(this).val();

        if(val != "0") {

            $('#parent_menu').val("0").attr('disabled',true);

        }

        else {

            $('#parent_menu').attr('disabled',false);

        }

    });



    $(document).on('submit','form#form',function(e) {

       $('#parent_menu').attr('disabled',false);

    });

//--></script>

