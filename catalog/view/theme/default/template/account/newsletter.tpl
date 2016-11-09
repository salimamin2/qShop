<div id="Content" class="content-main">
    <h1><?php echo $heading_title; ?></h1>
    <?php if ($success) { ?>
        <div class="success"><?php echo $success; ?></div>
    <?php } ?>
    <?php if ($error) { ?>
        <div class="warning"><?php echo $error; ?></div>
    <?php } ?>

    <div class="module">
        <div class="middle grd">
	    <form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" enctype="multipart/form-data" id="newsletter">
		<?php if ($this->customer->isLogged()): ?>
                    <label><?php echo $entry_newsletter; ?></label>
                    <input type="radio" name="newsletter" value="1" <?php echo ($newsletter == 1) ? 'checked="checked"' : ''; ?> />
		    <?php echo $text_yes; ?>
                    &nbsp;
                    <input type="radio" name="newsletter" value="0" <?php echo ($newsletter == 0) ? 'checked="checked"' : ''; ?> />
		    <?php echo $text_no; ?>

                    <div class="gap clr"></div>
		<?php else: ?>
                    <label for="fld-firstname"><?php echo $entry_name; ?>  <span class="required">*</span></label>
                    <input type="text" id="fld-firstname" name="firstname" value="" class="required field" />
                    <div class="clr gap"></div>

                    <label for="fld-email"><?php echo $entry_email; ?>  <span class="required">*</span></label>
                    <input type="text" id="fld-email" name="email" value="" class="required email field" />
                    <div class="clr gap"></div>
		<?php endif; ?>
		<div class="buttons">
		    <a onclick="location = '<?php echo str_replace('&', '&amp;', $back); ?>'" class="btn btngap left"><span><?php echo $button_back; ?></span></a>
		    <a onclick="$('#newsletter').submit();" class="btn left"><span><?php echo $button_continue; ?></span></a>
		</div>
	    </form>
        </div>
	<div class="clear"></div>
    </div>
</div>