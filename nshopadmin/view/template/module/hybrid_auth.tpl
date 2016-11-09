

<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">

    <div class="box">

	<div class="head well">
		<h3><i class="icon icon-cloud"></i> <?php echo $heading_title; ?>
			<div class="pull-right">
				<div class="buttons"> 
					<button class="btn-flat btn-success btn-sm"><span><?php echo $button_save; ?></span></button>
				 	<a href="<?php echo $cancel; ?>" class="btn-flat btn-default btn-sm"><span><?php echo $button_cancel; ?></span></a>
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



	    <table class="form">

		<tr>

		    <td><?php echo $entry_status; ?></td>

		    <td colspan="4"><div class="ui-select"><select name="hybrid_status">

			    <?php if ($hybrid_status) { ?>

    			    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>

    			    <option value="0"><?php echo $text_disabled; ?></option>

			    <?php } else { ?>

    			    <option value="1"><?php echo $text_enabled; ?></option>

    			    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>

			    <?php } ?>

			</select></div></td>

		</tr>

		<tr>

		    <td><?php echo $entry_open_id; ?></td>

		    <td colspan="4"><div class="ui-select"><select name="hybrid_open_id">

			    <?php if ($hybrid_open_id) { ?>

    			    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>

    			    <option value="0"><?php echo $text_disabled; ?></option>

			    <?php } else { ?>

    			    <option value="1"><?php echo $text_enabled; ?></option>

    			    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>

			    <?php } ?>

			</select></div></td>

		</tr>

		<tr>

		    <td><?php echo $entry_yahoo; ?></td>

		    <td colspan="4"><div class="ui-select"><select name="hybrid_yahoo_status">

			    <?php if ($hybrid_yahoo_status) { ?>

    			    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>

    			    <option value="0"><?php echo $text_disabled; ?></option>

			    <?php } else { ?>

    			    <option value="1"><?php echo $text_enabled; ?></option>

    			    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>

			    <?php } ?>

			</select></div></td>

		</tr>

		<tr>

		    <td><?php echo $entry_aol; ?></td>

		    <td colspan="4"><div class="ui-select"><select name="hybrid_aol_status">

			    <?php if ($hybrid_aol_status) { ?>

    			    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>

    			    <option value="0"><?php echo $text_disabled; ?></option>

			    <?php } else { ?>

    			    <option value="1"><?php echo $text_enabled; ?></option>

    			    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>

			    <?php } ?>

			</select></div></td>

		</tr>

		<tr>

		    <td><?php echo $entry_google; ?></td>

		    <td width="5%"><div class="ui-select"><select name="hybrid_google_status">

			    <?php if ($hybrid_google_status) { ?>

    			    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>

    			    <option value="0"><?php echo $text_disabled; ?></option>

			    <?php } else { ?>

    			    <option value="1"><?php echo $text_enabled; ?></option>

    			    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>

			    <?php } ?>

			</select>
            </div>
		    </td>

		    <td><?php echo $text_id ?><input class="form-control" type="text" name="hybrid_google_id" value="<?php echo $hybrid_google_id; ?>" size="30" /></td>

		    <td><?php echo $text_secret ?><input class="form-control" type="text" name="hybrid_google_secret" value="<?php echo $hybrid_google_secret; ?>" size="30" /></td>

		    <td><?php echo $text_scope ?><input class="form-control" type="text" name="hybrid_google_scope" value="<?php echo $hybrid_google_scope; ?>" size="30" /></td>

		</tr>

		<tr>

		    <td rowspan="2"><?php echo $entry_facebook; ?></td>

		    <td><div class="ui-select"><select name="hybrid_fb_status">

			    <?php if ($hybrid_fb_status) { ?>

    			    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>

    			    <option value="0"><?php echo $text_disabled; ?></option>

			    <?php } else { ?>

    			    <option value="1"><?php echo $text_enabled; ?></option>

    			    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>

			    <?php } ?>

			</select></div></td>

		    <td><?php echo $text_id ?><input class="form-control" type="text" name="hybrid_fb_id" value="<?php echo $hybrid_fb_id; ?>" size="30" /></td>

		    <td><?php echo $text_secret ?><input class="form-control" type="text" name="hybrid_fb_secret" value="<?php echo $hybrid_fb_secret; ?>" size="30" /></td>

		    <td><?php echo $text_scope ?><input class="form-control" type="text" name="hybrid_fb_scope" value="<?php echo $hybrid_fb_scope; ?>" size="30" /></td>

		</tr>

		<tr>

		    <td colspan="4"><?php echo $text_display ?><input class="form-control" type="text" name="hybrid_fb_display" value="<?php echo $hybrid_fb_display; ?>" size="40" /></td>

		</tr>

		<tr>

		    <td><?php echo $entry_twitter; ?></td>

		    <td><div class="ui-select"><select name="hybrid_twitter_status">

			    <?php if ($hybrid_twitter_status) { ?>

    			    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>

    			    <option value="0"><?php echo $text_disabled; ?></option>

			    <?php } else { ?>

    			    <option value="1"><?php echo $text_enabled; ?></option>

    			    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>

			    <?php } ?>

			</select></div></td>

		    <td><?php echo $text_id ?><input class="form-control" type="text" name="hybrid_twitter_id" value="<?php echo $hybrid_twitter_id; ?>" size="30" /></td>

		    <td colspan="2"><?php echo $text_secret ?><input class="form-control" type="text" name="hybrid_twitter_secret" value="<?php echo $hybrid_twitter_secret; ?>" size="30" /></td>

		</tr>



		<tr>

		    <td><?php echo $entry_live; ?></td>

		    <td><div class="ui-select"><select name="hybrid_live_status">

			    <?php if ($hybrid_live_status) { ?>

    			    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>

    			    <option value="0"><?php echo $text_disabled; ?></option>

			    <?php } else { ?>

    			    <option value="1"><?php echo $text_enabled; ?></option>

    			    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>

			    <?php } ?>

			</select></div></td>

		    <td><?php echo $text_id ?><input class="form-control" type="text" name="hybrid_live_id" value="<?php echo $hybrid_live_id; ?>" size="30" /></td>

		    <td colspan="2"><?php echo $text_secret ?><input class="form-control" type="text" name="hybrid_live_secret" value="<?php echo $hybrid_live_secret; ?>" size="30" /></td>

		</tr>

		<tr>

		    <td><?php echo $entry_myspace; ?></td>

		    <td><div class="ui-select"><select name="hybrid_myspace_status">

			    <?php if ($hybrid_myspace_status) { ?>

    			    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>

    			    <option value="0"><?php echo $text_disabled; ?></option>

			    <?php } else { ?>

    			    <option value="1"><?php echo $text_enabled; ?></option>

    			    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>

			    <?php } ?>

			</select></div></td>

		    <td><?php echo $text_id ?><input class="form-control" type="text" name="hybrid_myspace_id" value="<?php echo $hybrid_myspace_id; ?>" size="30" /></td>

		    <td colspan="2"><?php echo $text_secret ?><input class="form-control" type="text" name="hybrid_myspace_secret" value="<?php echo $hybrid_myspace_secret; ?>" size="30" /></td>

		</tr>

		<tr>

		    <td><?php echo $entry_linkedin; ?></td>

		    <td><div class="ui-select"><select name="hybrid_linkedin_status">

			    <?php if ($hybrid_linkedin_status) { ?>

    			    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>

    			    <option value="0"><?php echo $text_disabled; ?></option>

			    <?php } else { ?>

    			    <option value="1"><?php echo $text_enabled; ?></option>

    			    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>

			    <?php } ?>

			</select></div></td>

		    <td><?php echo $text_id ?><input class="form-control" type="text" name="hybrid_linkedin_id" value="<?php echo $hybrid_linkedin_id; ?>" size="30" /></td>

		    <td colspan="2"><?php echo $text_secret ?><input class="form-control" type="text" name="hybrid_linkedin_secret" value="<?php echo $hybrid_linkedin_secret; ?>" size="30" /></td>

		</tr>

		<tr>

		    <td><?php echo $entry_foursquare; ?></td>

		    <td><div class="ui-select"><select name="hybrid_foursquare_status">

			    <?php if ($hybrid_foursquare_status) { ?>

    			    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>

    			    <option value="0"><?php echo $text_disabled; ?></option>

			    <?php } else { ?>

    			    <option value="1"><?php echo $text_enabled; ?></option>

    			    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>

			    <?php } ?>

			</select></div></td>

		    <td><?php echo $text_id ?><input class="form-control" type="text" name="hybrid_foursquare_id" value="<?php echo $hybrid_foursquare_id; ?>" size="30" /></td>

		    <td colspan="2"><?php echo $text_secret ?><input class="form-control" type="text" name="hybrid_foursquare_secret" value="<?php echo $hybrid_foursquare_secret; ?>" size="30" /></td>

		</tr>

	    </table>



	</div>

    </div>

</form>