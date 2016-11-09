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
<div id="Content" class="page-testimonials grid12-9">
    <h1><span><?php echo $heading_title; ?></span></h1>    
        <script type="text/javascript">
            jQuery(document).ready(function() {
                jQuery ('ul.testimonials-page li:even').addClass('even');
                jQuery ('ul.testimonials-page li:odd').addClass('odd');
            });
        </script>
    <ul class="testimonials-page masonry">
        <?php foreach ($testimonials as $testimonial) { ?>
        <?php 
        $title = $testimonial['title'];
        if(stristr($testimonial['title'], "|") !== false) { 
            $aArr = explode("|", $testimonial['title']);  
            $title = $aArr[0]." <span class='location'>".$aArr[1]."</span>";     } ?>
            <li>
            	<h4><span><?php echo $title; ?></span></h4>
                    <div class="desc">
            		  <?php echo $testimonial['description']; ?>
                	</div>
            </li>
    <?php } ?>
    </ul>
    <?php if($pagination): ?>
    <div class="toolbar-bottom">
        <div class="toolbar">
            <div class="pager">
                <div class="pages gen-direction-arrows1">
                    <strong>Page:</strong>
                    <?php echo $pagination; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>