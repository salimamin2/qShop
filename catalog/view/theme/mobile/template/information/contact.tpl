<div id="ColumnLeft">
    <div class="leftmenu grid12-3">
        <ul>
            <li>
                <a href="/about-us">
                    <strong>About us</strong><span>LSM is Pakistan's premier fashion brand since 1952</span>
                </a>
            </li>
            <li>
                <a href="/testimonials">
                    <strong>Testimonials</strong><span>Hear it all-from our customers across the world</span>
                </a>
            </li>
            <li>
                <a href="/terms-and-conditions">
                    <strong>Terms &amp; Conditions</strong><span>Some things you might want to know before placing the order</span>
                </a>
            </li>
            <li>
                <a href="/faqs">
                    <strong>FAQs</strong><span>Have questions to ask? We have the answers ready</span>
                </a>
            </li>
            <li>
                <a href="/how-to-place-an-order">
                    <strong>How to Place an Order</strong><span>Step by step guide on placing the order on Sticherry</span>
                </a>
            </li>
            <li>
                <a href="/ordering-and-payment">
                    <strong>Payment</strong><span>What mode would you like to pay in? Find them all here</span>
                </a>
            </li>
            <li>
                <a href="/shipping">
                    <strong>Shipping</strong><span>Check out our shipping policy</span>
                </a>
            </li>
            <li>    
                <a href="/returns-and-exchange">
                    <strong>Returns</strong><span>We offer no-questions-asked hassle free returns</span>
                </a>
            </li>
            <lI>
                <a href="/contact-us">
                    <strong>Contact us</strong><span>Reach us Instantly</span>
                </a>
            </lI>
        </ul>
    </div>
</div>
<div id="Content" class="col-main grid12-9  in-col2 contacts-index-index ">
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
    <p>Getting in touch with us is easy. So if you have a query, a doubt or an opinion to share, there are three easy ways to contact us. Pick up the phone and call us. We are available all 24 hours for live chat. You can also email to us for a prompt reply or write to us at our mailing address. Whichever way you choose to do it, we are only a click away!</p>
        <br /><br />
        <div class="grid12-4 contact-blocks">
            <img src="image/icons/icon-email.png" alt="Email Us" title="Email Us" />
            <p><span><a href="mailto:info@sticherry.com" title="Email Us">info@sticherry.com</a></span></p>
        </div>
         <div class="grid12-4 contact-blocks">
            <img src="image/icons/icon-tel.png" alt="Call Us" title="Call Us" />
            <p><span><?php echo $telephone; ?></span></p>
        </div>
         <div class="grid12-4 contact-blocks">
            <img src="image/icons/icon-livechat.png" alt="LiveChat" title="LiveChat" />
            <p><span><a href="https://v2.zopim.com/widget/livechat.html?key=2nlQWLXuApsJTmlaTTT0kU1mu4B4gMEu" target="_blank">Live Chat</a></span></p>
        </div>
        <div class="clr"></div><br />
        <form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" enctype="multipart/form-data" id="contact">
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
                </li>
                <li>
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
                        <textarea id="enquiry" name="enquiry" cols="5" rows="11" class="required-entry input-text"><?php echo $enquiry; ?></textarea>
                    </div>
                </li>
                <li class="wide">
                    <label for="security" class="required"><em>*</em>How many is 5 + 2:</label>
                    <div class="input-box">
                        <input name="security" id="security" title="Security" value="" class="input-text" type="text" />
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