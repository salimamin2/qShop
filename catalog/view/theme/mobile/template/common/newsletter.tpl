<div id="module_newsletter">
    <?php if ($success) { ?>
        <div class="alert alert-success"><?php echo $msg_success ?></div>
    <?php } ?>
    <?php if ($error_warning) { ?>
        <div class="alert alert-warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    <?php if ($error_email) { ?>
        <div class="alert alert-warning"><?php echo $error_email; ?></div>
    <?php } ?>
    <form action="" method="post" id="form-newsletter">
        <label for="email"><?php echo $text_label; ?></label>
        <input type="text" name="newsletter_email" value="your email address here..." id="email" onclick="this.value = ''" />
        <a class="button" onclick="$('#form-newsletter').submit()" ><?php echo $text_button ?></a>
        &nbsp;&nbsp;&nbsp;<?php echo $text_detail; ?>
    </form>
</div>