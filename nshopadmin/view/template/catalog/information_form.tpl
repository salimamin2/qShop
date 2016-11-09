<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
<div class="box table-wrapper products-table section">
  <div class="head well">
    <h3><i class="icon-edit"></i> <?php echo $heading_title; ?>
      <div class="pull-right">
            <button type="submit" class="btn-flat btn-success btn-sm"><span><?php echo $button_save; ?></span></button>
            <a href="<?php echo $cancel; ?>" class="btn-flat btn-default btn-sm"><span><?php echo $button_cancel; ?></span></a>
      </div>
    </h3>
	</div>
			<?php if ($error_warning) { ?>
          <div class="alert alert-danger"><?php echo $error_warning; ?></div>
      <?php } ?>
	
  <div class="content">

      <ul class="nav nav-tabs">

        <?php foreach ($languages as $language) { ?>

        <li class="active"><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><span><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></span></a></li>

		<?php } ?>

      </ul>

    <div class="tab-content">

      <?php foreach ($languages as $language) { ?>

      <div id="language<?php echo $language['language_id']; ?>" class="tab-pane active">

        <table class="form">

          <tr>

            <td> <?php echo $entry_title; ?><span class="required">*</span></td>

            <td><input class="form-control" name="information_description[<?php echo $language['language_id']; ?>][title]" size="100" value="<?php echo isset($information_description[$language['language_id']]) ? $information_description[$language['language_id']]['title'] : ''; ?>" />

              <?php if (isset($error_title[$language['language_id']])) { ?>

              <span class="error"><?php echo $error_title[$language['language_id']]; ?></span>

              <?php } ?></td>

          </tr>

          <tr>

            <td> <?php echo $entry_description; ?><span class="required">*</span></td>

            <td><textarea class="form-control" name="information_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>" data-rel="wyswyg"><?php echo isset($information_description[$language['language_id']]) ? $information_description[$language['language_id']]['description'] : ''; ?></textarea>

              <?php if (isset($error_description[$language['language_id']])) { ?>

              <span class="error"><?php echo $error_description[$language['language_id']]; ?></span>

              <?php } ?></td>

          </tr>

          <tr>

              <td><?php echo __('entry_meta_title'); ?></td>

              <td><input class="form-control" name="information_description[<?php echo $language['language_id']; ?>][meta_title]" value="<?php echo isset($information_description[$language['language_id']]) ? $information_description[$language['language_id']]['meta_title'] : ''; ?>" /></td>

          </tr>

            <tr>

                <td><?php echo __('entry_meta_link'); ?></td>

                <td><input class="form-control" name="information_description[<?php echo $language['language_id']; ?>][meta_link]"  value="<?php echo isset($information_description[$language['language_id']]) ? $information_description[$language['language_id']]['meta_link'] : ''; ?>" /></td>

            </tr>

            <tr>

                <td><?php echo __('entry_meta_keywords'); ?></td>

                <td><textarea class="form-control" name="information_description[<?php echo $language['language_id']; ?>][meta_keywords]" rows="6" cols="50"><?php echo isset($information_description[$language['language_id']]) ? $information_description[$language['language_id']]['meta_keywords'] : ''; ?></textarea></td>

            </tr>

            <tr>

                <td><?php echo __('entry_meta_description'); ?></td>

                <td><textarea class="form-control" name="information_description[<?php echo $language['language_id']; ?>][meta_description]" rows="6" cols="50"><?php echo isset($information_description[$language['language_id']]) ? $information_description[$language['language_id']]['meta_description'] : ''; ?></textarea></td>

            </tr>

        </table>

      </div>

	  <?php } ?>

    </div>

      <table class="form">

        <tr>

          <td><?php echo $entry_keyword; ?></td>

          <td><input type="text" name="keyword" value="<?php echo $keyword; ?>" class="form-control" /></td>

        </tr>

<?php /*
        <tr>
            <td><?php echo __('Enable Left Column'); ?></td>

            <td>
              <?php if($leftcolumn == '1') : ?>
                <input class="form-contrl" checked="checked" name="leftcolumn" type="radio" value="1" />
                <label>Yes</label>
                <input class="form-control" name="leftcolumn" type="radio" value="0" />
                <label>No</label>
              <?php else : ?>
                <input class="form-contrl" name="leftcolumn" type="radio" value="1" />
                <label>Yes</label>
                <input class="form-control" checked="checked" name="leftcolumn" type="radio" value="0" />
                <label>No</label>
              <?php endif; ?>

              </td>

        </tr> */ ?>


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

        <tr>
          <td><?php echo $entry_show_title; ?></td>
          <td><?php echo CHtml::dropDownList('show_title',$show_title,array(0 => 'No',1 => 'Yes'),array('class' => 'form-control')); ?></td>
        </tr>

        <tr>
          <td><?php echo $entry_show_recommended; ?></td>
          <td><?php echo CHtml::dropDownList('show_recommended',$show_recommended,array(0 => 'No',1 => 'Yes'),array('class' => 'form-control')); ?></td>
        </tr>

      </table>

  </div>

</div>

</form>