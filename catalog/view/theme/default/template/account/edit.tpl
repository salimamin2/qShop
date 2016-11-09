<form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" enctype="multipart/form-data" id="edit">
        <div class="row">
            <div class="col-md-6">
               <!--  <label for="firstname" class="required"><em>*</em><?php echo $entry_firstname; ?></label> -->
                <div class="input-box">
                    <input class="input-text required-entry validation-passed" type="text" name="firstname" id="firstname" value="<?php echo $firstname; ?>" placeholder="<?php echo $entry_firstname; ?>" />
                </div>
            </div>
            <div class="col-md-6">
               <!--  <label for="lastname" class="required"><em>*</em><?php echo $entry_lastname; ?></label> -->
                <div class="input-box">
                    <input class="input-text required-entry" type="text" name="lastname" id="lastname" value="<?php echo $lastname; ?>" palceholder="<?php echo $entry_lastname; ?>" />
                </div>
            </div>
            <div class="col-md-6">
                <!--  <label for="telephone" class="required"><em>*</em><?php echo $entry_telephone; ?></label> -->
                <div class="input-box">
                    <input class="input-text" type="text" name="telephone" id="telephone" value="<?php echo $telephone; ?>" placeholder="<?php echo $entry_telephone; ?>" />
                </div>
            </div>

            <div class="col-md-6">
                <!--  <label for="email" class="required"><em>*</em><?php echo $entry_email; ?></label> -->
                <div class="input-box">
                    <input class="input-text required-entry validate-email" type="text" name="email" id="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="skin-minimal">
                    <input type="checkbox" name="newsletter" id="newsletter" class="checkbox" <?php echo ($newsletter ? "checked" : ""); ?> >&nbsp;&nbsp;
                    <label for="newsletter"><?php echo __('Subscribe to our newsletter'); ?></label>
                </div>
            </div>
        </div>
        
        <p class="sub-heading-acount">Change Password</p>
        <div class="line"></div>
        <div class="row">
            <div class="col-md-4">
                <button type="button" class="btn btn-cntinue btn-account btn-grey edit-password">Change Password</button>
            </div>
        </div>
        <div class="row hide edit-password-box">
            <div class="col-md-6">
                <!-- <label for="fld-password" class="required"><em>*</em><?php echo $entry_password; ?></label> -->
                <div class="input-box">
                    <input type="password" name="old_password" id="fld-password" class="input-text validate-password" minlength="6" maxlength="10" placeholder="Current Password"/>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-6">
                <!-- <label for="fld-password" class="required"><em>*</em><?php echo $entry_password; ?></label> -->
                <div class="input-box">
                    <input type="password" name="password" id="fld-password" class="input-text validate-password" minlength="6" maxlength="10" placeholder="Password"/>
                </div>
            </div>
            <div class="col-md-6">
                <!-- <label for="confirm" class="required"><em>*</em><?php echo $entry_confirm; ?></label> -->
                <div class="input-box">
                    <input type="password" name="confirm" id="fld-confirm" value="<?php echo $confirm; ?>" class="input-text validate-cpassword" equalto="#fld-password"placeholder="Confirm Password" />
                </div>
            </div>
        </div>

        <div class="buttons-set">
            <div class="row">
                <div class="col-md-4">
                   <button type="submit" class="btn btn-cntinue btn-account"><span><span><?php echo __('Update'); ?></span></span></button>
                </div>
            </div>
        </div>
</form>
    