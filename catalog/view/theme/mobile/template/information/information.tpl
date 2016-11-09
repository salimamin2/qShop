<?php /*if($leftcolumn == '1') : ?>
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
<?php endif;*/ ?>

<div class="container">
    <div class="col-md-12 std">
        <?php if($show_title): ?>
            <div class="title-icon text-center">
                <span class="top">&nbsp;</span>
                <h1><?php echo $heading_title; ?></h1>
                <p><?php echo $meta_title; ?></p>
                <span class="bottom">&nbsp;</span>
            </div>
            <?php /*<div class="text-center">
                <h1 class="section-title-account">
                    <span class="background-account">
                        <span class="border-account"></span>
                    </span>
                </h1>
            </div>*/ ?>
        <?php endif; ?>
        <div class="clearfix"></div>
        <div class="page-description">
            <?php echo $description; ?>
        </div>
        <?php if($show_recommended): ?>
            <div class="recommended-blog container">
                <div class="row">
                    <div class="head col-sm-3 col-md-3">
                        <h2>Other Recommended</h2>
                        <p><a href="<?php echo $blog_page; ?>">View All</a></p>
                    </div>
                    <div class="col-sm-9 col-md-9">
                        <div class="row">
                            [module name="blog_latest" class="col-sm-4" /]
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>               
</div>
