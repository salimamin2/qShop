<div id="Content" class="my-account container">

    <div class="col-md-12 page-description">

        <div class="text-center">
            <h1 class="section-title-account">
                <span class="background-account">
                    <span class="border-account"><?php echo $heading_title; ?></span>
                </span>
            </h1>
        </div>

        <ul class="messages">
            <?php if ($error) { ?>
            <li class="error-msg">
                <ul>
                    <li><span><?php echo $error; ?></span></li>
                </ul>
            </li>
            <?php } ?>
        </ul>

        <form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" enctype="multipart/form-data" id="forgotten" class="width-50 post-content">
            <div class="fieldset">
                <h4 class="legend"><?php echo __('Retreive your password here'); ?></h4>
                <p><?php echo $text_email; ?></p>
                <ul class="form-list">
                    <li>
                        <label for="email" class="required"><em>*</em>&nbsp;<?php echo $text_your_email; ?></label>
                        <div class="input-box">
                            <input type="text" name="email" id="email" value="<?php echo $email; ?>" class="input-text required-entry validate-email" size="48" />
                        </div>
                    </li>
                </ul>
            </div>
            <div class="buttons-set">
                <p class="required color-red">* Required Fields</p>
                <div class="row">
                    <div class="col-xs-12 col-sm-4">
                        <p class="go-back back-link">
                            <a href="<?php echo str_replace('&', '&amp;', $back); ?>"><small>&laquo; </small><?php echo __('Back to login'); ?></a>
                        </p>
                    </div>
                    <div class="col-xs-12 col-sm-4 right">
                        <button type="submit" class="btn btn-cntinue btn-account"><span><span><?php echo __('Submit'); ?></span></span></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>