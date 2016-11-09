<div id="Content" class="col-main grid12-12  in-col2 contacts-index-index ">
    <ul class="messages">
        <?php if ($success) { ?>
        <li class="success-msg">
            <ul>
                <li><span><?php echo $success; ?></span></li>
            </ul>
        </li>
        <?php } ?>
        <?php if ($error_warning) { ?>
        <li class="error-msg">
            <ul>
                <li><span><?php echo $error_warning; ?></span></li>
            </ul>
        </li>
        <?php } ?>
    </ul>

    <div class="page-title">
        <h1><span><?php echo $heading_title; ?></span></h1>
    </div>
        <form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" enctype="multipart/form-data" id="feeeback">
        <div class="fieldset">
            <h2 class="legend"><?php echo __('Contact Information'); ?></h2>
            <ul class="form-list">
                <li class="fields">
                    <div class="field">
                        <label for="name" class="required"><em>*</em><?php echo $entry_name; ?></label>
                        <div class="input-box">
                            <input type="text" id="name" name="name" value="<?php echo $name; ?>" class="input-text required-entry" size="53" />
                        </div>
                    </div>
                    <div class="field">
                        <label for="email" class="required"><em>*</em><?php echo $entry_email; ?></label>
                        <div class="input-box">
                            <input type="text" id="email" name="email" value="<?php echo $email; ?>" class="input-text required-entry validate-email" size="53" />
                        </div>
                    </div>
                </li>
                <li>
                    <label for="telephone">Telephone</label>
                    <div class="input-box">
                        <input name="telephone" id="telephone" title="Telephone" value="" class="input-text" type="text" />
                    </div>
                </li>
                <li class="wide">
                    <label for="enquiry" class="required"><em>*</em><?php echo $entry_enquiry; ?></label>
                    <div class="input-box">
                        <textarea id="enquiry" name="enquiry" cols="5" rows="10" class="required-entry input-text"><?php echo $enquiry; ?></textarea>
                    </div>
                </li>
            </ul>
        </div>

        <div class="buttons-set">
            <p class="required">* Required Fields</p>
            <input type="text" name="hideit" id="hideit" value="" style="display:none !important;" />
            <button type="submit" class="button"><span><span><?php echo $button_continue; ?></span></span></button>
        </div>
	</form>
</div>