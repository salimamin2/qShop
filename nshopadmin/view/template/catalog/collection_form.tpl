<?php // echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background: url('view/image/information.png') 2px 9px no-repeat;"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <div id="tabs" class="htabs">
		<a tab="#tab_general"><span><?php echo $tab_general; ?></span></a>
		<a tab="#tab_media"><span><?php echo $tab_media; ?></span></a>
	</div>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
		<div id="tab_general">
		  <div id="languages" class="htabs">
			<?php foreach ($languages as $language) { ?>
			<a tab="#language<?php echo $language['language_id']; ?>"><span><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></span></a>
			<?php } ?>
		  </div>
		  <?php foreach ($languages as $language) { ?>
		  <div id="language<?php echo $language['language_id']; ?>">
			<table class="form">
			  <tr>
				<td><span class="required">*</span> <?php echo $entry_title; ?></td>
				<td><input name="collection_description[<?php echo $language['language_id']; ?>][title]" size="100" value="<?php echo isset($collection_description[$language['language_id']]) ? $collection_description[$language['language_id']]['title'] : ''; ?>" />
				  <?php if (isset($error_title[$language['language_id']])) { ?>
				  <span class="error"><?php echo $error_title[$language['language_id']]; ?></span>
				  <?php } ?></td>
			  </tr>
			  <tr>
				<td><span class="required">*</span> <?php echo $entry_description; ?></td>
				<td><textarea name="collection_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>"><?php echo isset($collection_description[$language['language_id']]) ? $collection_description[$language['language_id']]['description'] : ''; ?></textarea>
				  <?php if (isset($error_description[$language['language_id']])) { ?>
				  <span class="error"><?php echo $error_description[$language['language_id']]; ?></span>
				  <?php } ?></td>
			  </tr>
			  <tr>
				  <td><?php echo __('entry_meta_title'); ?></td>
				  <td><input name="collection_description[<?php echo $language['language_id']; ?>][meta_title]" size="100" value="<?php echo isset($collection_description[$language['language_id']]) ? $collection_description[$language['language_id']]['meta_title'] : ''; ?>" /></td>
			  </tr>
			</table>
		  </div>
		  <?php } ?>
		  <table class="form">
			<tr>
			  <td><?php echo $entry_store; ?></td>
			  <td><div class="scrollbox">
				  <?php $class = 'even'; ?>
				  <div class="<?php echo $class; ?>">
					<?php if (in_array(0, $collection_store)) { ?>
					<input type="checkbox" name="collection_store[]" value="0" checked="checked" />
					<?php echo $text_default; ?>
					<?php } else { ?>
					<input type="checkbox" name="collection_store[]" value="0" />
					<?php echo $text_default; ?>
					<?php } ?>
				  </div>
				  <?php foreach ($stores as $store) { ?>
				  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
				  <div class="<?php echo $class; ?>">
					<?php if (in_array($store['store_id'], $collection_store)) { ?>
					<input type="checkbox" name="collection_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
					<?php echo $store['name']; ?>
					<?php } else { ?>
					<input type="checkbox" name="collection_store[]" value="<?php echo $store['store_id']; ?>" />
					<?php echo $store['name']; ?>
					<?php } ?>
				  </div>
				  <?php } ?>
				</div></td>
			</tr>
			<tr>
			  <td><?php echo $entry_keyword; ?></td>
			  <td><input type="text" name="keyword" value="<?php echo $keyword; ?>" /></td>
			</tr>
			<tr>
				<td><?php echo $entry_status; ?></td>
				<td><select name="status">
					<?php if ($status) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0"><?php echo $text_disabled; ?></option>
					<?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
					<?php } ?>
				  </select></td>
			</tr>
			<tr>
				<td><?php echo $entry_media_type; ?></td>
				<td><select name="media_type">
					<option value="image" <?php echo ($media_type == 'image') ? 'selected="selected"' : ''; ?>><?php echo __('Image'); ?></option>
					<option value="video" <?php echo ($media_type == 'video') ? 'selected="selected"' : ''; ?>><?php echo __('Video'); ?></option>
				  </select></td>
			</tr>
			<tr>
				<td><?php echo $entry_image; ?></td>
				<td><input type="hidden" name="image" value="<?php echo $image; ?>" id="image" />
					<img src="<?php echo $preview; ?>" alt="" id="preview" class="image" onclick="image_upload('image', 'preview');" /></td>
			</tr>
			<tr>
			  <td><?php echo $entry_sort_order; ?></td>
			  <td><input name="sort_order" value="<?php echo $sort_order; ?>" size="1" /></td>
			</tr>
		  </table>
		</div>
		
		<div id="tab_media">
			<table id="media" class="list">
				<thead>
					<tr>
						<td class="left"><?php echo $entry_media; ?></td>
						<td class="left"><?php echo $entry_sort_order; ?></td>
						<td class="center" width="20%"><?php echo __('column_action'); ?></td>
					</tr>
				</thead>
				
				<tbody id="media_row0">
					<tr>
						<td class="left">								
							<?php echo $entry_video; ?><br /><input type="text" name="collection_media[0][media]" value="<?php echo $collection_medias['video']['media'] ? $collection_medias['video']['media'] : ''; ?>" />
							<input type="hidden" name="collection_media[0][type]" value="<?php echo $collection_medias['video']['type'] ? $collection_medias['video']['type'] : 'video'; ?>" />
						</td>
						<td class="left"><input type="text" name="collection_media[0][sort_order]" value="<?php echo $collection_medias['video']['sort_order']; ?>" /></td>
						<td class="center"></td>
					</tr>
				</tbody>
				<?php $media_row = 1; ?>
				<?php foreach ($collection_medias['image'] as $collection_media) { ?>
					<?php if($collection_media['type'] == 'image'): ?>
						<tbody id="media_row<?php echo $media_row; ?>">
							<tr>
								<td class="left">
									<input type="hidden" name="collection_media[<?php echo $media_row; ?>][media]" value="<?php echo $collection_media['media']; ?>" id="image<?php echo $media_row; ?>" />
									<input type="hidden" name="collection_media[<?php echo $media_row; ?>][type]" value="<?php echo $collection_media['type']; ?>" />
									<img src="<?php echo $collection_media['preview']; ?>" alt="" id="preview<?php echo $media_row; ?>" class="image" onclick="image_upload('image<?php echo $media_row; ?>', 'preview<?php echo $media_row; ?>');" />
								</td>
								<td class="left"><input type="text" name="collection_media[<?php echo $media_row; ?>][sort_order]" value="<?php echo $collection_media['sort_order']; ?>" /></td>
								<td class="center"><a onclick="$('#media_row<?php echo $media_row; ?>').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>
							</tr>
						</tbody>
					<?php endif; ?>
					<?php $media_row++; ?>
				<?php } ?>
				<tfoot>
					<tr>
						<td></td>
						<td></td>
						<td class="center">
							<a onclick="addImage();" class="button"><span><?php echo $button_add_image; ?></span></a>
						</td>
					</tr>
				</tfoot>
			</table>
		</div>
    </form>
  </div>
</div>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.draggable.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.resizable.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.dialog.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/external/bgiframe/jquery.bgiframe.js"></script>
<script type="text/javascript"><!--
function image_upload(field, preview) {
	$('#dialog').remove();

	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');

	$('#dialog').dialog({
		title: '<?php echo $text_image_manager; ?>',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>',
					type: 'POST',
					data: 'image=' + encodeURIComponent($('#' + field).attr('value')),
					dataType: 'text',
					success: function(data) {
						$('#' + preview).replaceWith('<img src="' + data + '" alt="" id="' + preview + '" class="image" onclick="image_upload(\'' + field + '\', \'' + preview + '\');" />');
					}
				});
			}
		},	
		bgiframe: false,
		width: 700,
		height: 400,
		resizable: false,
		modal: false
	});
};
//--></script>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>
<script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
CKEDITOR.replace('description<?php echo $language['language_id']; ?>', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
<?php } ?>

var media_row = <?php echo $media_row; ?>;

function addImage() {
	html  = '<tbody id="media_row' + media_row + '">';
	html += '<tr>';
	html += '<td class="left"><input type="hidden" name="collection_media[' + media_row + '][media]" value="" id="image' + media_row + '" /><input type="hidden" name="collection_media[' + media_row + '][type]" value="image" /><img src="<?php echo $no_image; ?>" alt="" id="preview' + media_row + '" class="image" onclick="image_upload(\'image' + media_row + '\', \'preview' + media_row + '\');" /></td>';
	html += '<td class="left"><input type="text" name="collection_media[' + media_row + '][sort_order]" value="" /></td>';
	html += '<td class="left"><a onclick="$(\'#media_row' + media_row  + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
	html += '</tr>';
	html += '</tbody>';

	$('#media tfoot').before(html);

	media_row++;
}
function addVideo() {
	html  = '<tbody id="media_row' + media_row + '">';
	html += '<tr>';
	html += '<td class="left"><input type="text" name="collection_media[' + media_row + '][media]" value="" /><input type="hidden" name="collection_media[' + media_row + '][type]" value="video" /></td>';
	html += '<td class="left"><input type="text" name="collection_media[' + media_row + '][sort_order]" value="" /></td>';
	html += '<td class="left"><a onclick="$(\'#media_row' + media_row  + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
	html += '</tr>';
	html += '</tbody>';

	$('#media tfoot').before(html);

	media_row++;
}
//--></script>
<script type="text/javascript"><!--
	$.tabs('#tabs a');
    $.tabs('#languages a');
//--></script>
<?php// echo $footer; ?>