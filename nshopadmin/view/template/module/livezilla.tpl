<?php
/**
 * @version		$Id: livezilla.tpl 714 2010-03-11 15:32:10Z mic $
 * @package		FileZilla - Module 4 OpenCart - Admin Controller
 * @copyright	(C) 2010 mic [ http://osworx.net ]. All Rights Reserved.
 * @license		http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

echo $header;

if( !empty( $success ) ) { ?>
<div class="success"><?php echo $success; ?></div>
	<?php
}
if( $error_warning ) { ?>
	<div class="warning"><?php echo $error_warning; ?></div>
	<?php
} ?>
<div class="box">
	<div class="left"></div>
	<div class="right"></div>
	<div class="heading">
		<h1 style="background-image: url('view/image/module.png');"><?php echo $heading_title; ?></h1>
		<div class="buttons">
			<a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a>
			<a onclick="addMode();" class="button"><span><?php echo $button_apply; ?></span></a>
			<a onclick="location='<?php echo $link['cancel']; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a>
			<a onclick="window.open('<?php echo $link['help']; ?>','help','width=1024,height=620,left=20,scrollbars=yes,location=no');" class="button"><span><?php echo $button_help; ?></span></a>
		</div>
	</div>
	<div class="content">
		<div id="tabs" class="htabs">
			<a tab="#tab_common"><span><?php echo $tab_common; ?></span></a>
			<a tab="#tab_advanced"><span><?php echo $tab_advanced; ?></span></a>
		</div>
		<form action="<?php echo $link['action']; ?>" method="post" enctype="multipart/form-data" id="form">
			<!--<input type="hidden" id="mode" name="mode" value="apply" />-->
			<div id="tab_common">
				<table class="form">
		        <tr>
					<td>
						<span class="ttip" title="<?php echo $help_position; ?>"><?php echo $entry_position; ?></span>
					</td>
					<td><select name="livezilla_position">
						<option value="left"<?php echo ( $livezilla_position == 'left' ? ' selected="selected"' : '' ); ?>><?php echo $text_left; ?></option>
						<option value="right"<?php echo ( $livezilla_position == 'right' ? ' selected="selected"' : '' ); ?>><?php echo $text_right; ?></option>
						<?php
						if( !empty( $text_header ) && !empty( $text_footer ) ) { ?>
							<option value="header"<?php echo ( $livezilla_position == 'header' ? ' selected="selected"' : '' ); ?>><?php echo $text_header; ?></option>
							<option value="footer"<?php echo ( $livezilla_position == 'footer' ? ' selected="selected"' : '' ); ?>><?php echo $text_footer; ?></option>
							<?php
						} ?>
					</select></td>
				</tr>
		        <tr>
					<td><?php echo $entry_status; ?></td>
					<td><select name="livezilla_status">
						<?php if ($livezilla_status) { ?>
						<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
						<option value="0"><?php echo $text_disabled; ?></option>
						<?php } else { ?>
						<option value="1"><?php echo $text_enabled; ?></option>
						<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
						<?php } ?>
					</select></td>
				</tr>
		        <tr>
					<td><?php echo $entry_sort_order; ?></td>
					<td><input type="text" name="livezilla_sort_order" value="<?php echo $livezilla_sort_order; ?>" size="1" /></td>
				</tr>
				<tr>
					<td style="vertical-align:top;">
						<span class="required">*</span>
						<span class="ttip" title="<?php echo $help_code; ?>"><?php echo $entry_code; ?></span>
					</td>
					<td>
						<textarea name="livezilla_code" cols="120" rows="10"><?php echo $livezilla_code; ?></textarea>
						<?php if ($error_code) { ?>
						<span class="error"><?php echo $error_code; ?></span>
						<?php } ?>
					</td>
		        </tr>
				</table>
			</div>
			<div id="tab_advanced">
				<table class="form">
					<td style="vertical-align:top;">
						<span class="ttip" title="<?php echo $help_visibility; ?>"><?php echo $entry_visibility; ?></span>
					</td>
					<td>
						<textarea name="livezilla_visibility" cols="80" rows="4"><?php echo $livezilla_visibility; ?></textarea>
					</td>
				</table>
				<div class="info" style="margin:10px; padding:5px; border-top:1px solid #336699; border-bottom:1px solid #336699; background-color:#ECF6FF;">
					<img src="view/image/information.png" style="margin:5px;" height="22" width="22" title="Info" alt="Info" />
					<span style="vertical-align:top; color:#336699;"><?php echo $help_title; ?></span>
				</div>
				<table class="form">
				<tr>
					<td><?php echo $entry_header; ?></td>
					<td>
						<?php
						if( $livezilla_header ) {
							$checked1 = ' checked="checked"';
							$checked0 = '';
						}else{
							$checked1 = '';
							$checked0 = ' checked="checked"';
						} ?>
						<label for="livezilla_header_1"><?php echo $entry_yes; ?></label>
						<input type="radio"<?php echo $checked1; ?> id="livezilla_header_1" name="livezilla_header" value="1" />
						<label for="livezilla_header_0"><?php echo $entry_no; ?></label>
						<input type="radio"<?php echo $checked0; ?> id="livezilla_header_0" name="livezilla_header" value="0" />
					</td>
				</tr>
				<?php
				foreach( $localeLangs as $lang ) { ?>
					<tr>
						<td>
							<span class="ttip" title="<?php echo $lang['name']; ?>"><img src="view/image/flags/<?php echo $lang['image']; ?>" title="" alt="" style="vertical-align: top;" /></span>
						</td>
						<td>
							<input type="text" name="livezilla_title<?php echo $lang['language_id']; ?>" id="livezilla_title<?php echo $lang['language_id']; ?>" size="30" value="<?php echo ${'livezilla_title' . $lang['language_id']}; ?>" />
						</td>
					</tr>
					<?php
				} ?>
				</table>
			</div>
		</form>
	</div>
	<?php echo $oxfooter; ?>
</div>
<div id="showtip">&nbsp;</div>
<script type="text/javascript">
	/* <![CDATA[ */
	$.tabs('#tabs a');

	$(document).ready(function() {
		$("span[title]").tooltip({
			tip:			'#showtip',
			effect:			'fade',
			fadeOutSpeed:	100,
			predelay:		400,
			position:		"bottom right",
			offset:			[-10, 0]
		});
	});
	function addMode() {
		$('#form').append('<input type="hidden" name="mode" value="apply" />');
		$('#form').submit();
	};
	/* ]]> */
</script>
