<?php echo $this->load('module/account'); ?>
<div id="Content"  class="col-main grid12-9 grid-col2-main in-col2">
    <div class="my-account">
        <div class="page-title">
            <h2><?php echo __('Edit Password Information'); ?></h2>
        </div>
        <form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" enctype="multipart/form-data" id="password">
            <div class="fieldset">
                <h2 class="legend"><?php echo $heading_title; ?></h2>
                <ul class="form-list">
                    <li>
                        <label for="fld-password" class="required"><em>*</em><?php echo $entry_password; ?></label>
                        <div class="input-box">
                            <input type="password" name="password" id="fld-password" class="input-text validate-password required-entry" minlength="6" maxlength="10" size="40" />
                        </div>
                    </li>
                    <li>
                        <label for="confirm" class="required"><em>*</em><?php echo $entry_confirm; ?></label>
                        <div class="input-box">
                            <input type="password" name="confirm" id="fld-confirm" value="<?php echo $confirm; ?>" class="input-text validate-cpassword required-entry" equalto="#fld-password" size="40" />
                        </div>
                    </li>
                </ul>
            </div>
            <div class="buttons-set">
                <p class="required">* Required Fields</p>
                <p class="back-link">
                    <a href="<?php echo str_replace('&', '&amp;', $back); ?>"><small>&laquo; </small><?php echo $button_back; ?></a>
                </p>
                <button type="submit" class="button"><span><span><?php echo __('Save'); ?></span></span></button>
            </div>
        </form>
    </div>

    <div class="module lgray">
        <div class="grd">

<br />
                <div class="clr gap"></div>


                <div class="clr gap"></div>

                <div class="clr"></div>

        </div>
        <div class="bottom"><span></span></div>
        <div class="buttons">
            <label>&nbsp;</label>

        </div>
    </div>
</div>