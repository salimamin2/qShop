

<?php if ($error_warning) { ?>



<div class="alert alert-danger"><?php echo $error_warning; ?></div>

<?php } ?>

<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">

<div class="box table-wrapper products-table section">

    <div class="head">
		<h4><i class="icon-tags"></i> <?php echo $heading_title; ?></h4>
	</div>
	
	<div class="row filter-block">
		<div class="col-md-12">
			<div class="pull-right">
				<div class="buttons">
					<button type="submit" class="btn-flat btn-success btn-sm"><?php echo $button_save; ?></button>
					<a href="<?php echo $cancel; ?>" class="btn-flat btn-default btn-sm"><?php echo $button_cancel; ?></a>
				</div>
			</div>
		</div>
	</div>

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

            <td><input class="form-control" name="testimonial_description[<?php echo $language['language_id']; ?>][title]" value="<?php echo isset($testimonial_description[$language['language_id']]) ? $testimonial_description[$language['language_id']]['title'] : ''; ?>" size="40" />

              <?php if (isset($error_title[$language['language_id']])) { ?>

              <span class="error"><?php echo $error_title[$language['language_id']]; ?></span>

              <?php } ?></td>

          </tr>

          <tr>

            <td> <?php echo $entry_description; ?><span class="required">*</span></td>

            <td><textarea class="form-control" name="testimonial_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>" data-rel="wyswyg"><?php echo isset($testimonial_description[$language['language_id']]) ? $testimonial_description[$language['language_id']]['description'] : ''; ?></textarea>

              <?php if (isset($error_description[$language['language_id']])) { ?>

              <span class="error"><?php echo $error_description[$language['language_id']]; ?></span>

              <?php } ?></td>

          </tr>

        </table>

      </div>

      <?php } ?>

      </div>

      <div class="clear"></div>

      <table class="form">

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
			</div>
			</td>

        </tr>

        <tr>

          <td><?php echo $entry_sort_order; ?></td>

          <td><input name="sort_order" value="<?php echo $sort_order; ?>" size="1" /></td>

        </tr>

      </table>

  </div>

    </form>

</div>



