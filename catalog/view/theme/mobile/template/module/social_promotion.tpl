<?php if ($social_promotion_status): ?>
    <script type="text/javascript" src="catalog/view/javascript/jquery/jquery.cookie.js"></script>
    <script type="text/javascript"><!--
        var text = "Enter your email";
             
        jQuery(document).ready(function(){
            if(!jQuery.fn.fancybox){
                jQuery('head').append('<script type="text/javascript" src="catalog/view/javascript/jquery/fancybox/jquery.fancybox-1.3.4.pack.js"><\/script>');
                jQuery('head').append('<script type="text/javascript" src="catalog/view/javascript/jquery/fancybox/jquery.easing-1.3.pack.js"><\/script>');
                jQuery('head').append('<script type="text/javascript" src="catalog/view/javascript/jquery/fancybox/jquery.mousewhee1.3.0.2.pack.js"><\/script>');
                jQuery('head').append('<link rel="stylesheet" href="catalog/view/javascript/jquery/fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />');
            }
            jQuery('#ju_preview_arrow,#ju_close_btn').bind('click',function(){
                jQuery('#ju_preview').animate({'right':'-320px'});
            });
            
                jQuery('#ju_bbox,#ju_preview_coupon').fancybox({
                    type: "iframe",
                    padding: 0,
                    height: 454,
                    autoScale : false,
                    onComplete: function () {
                        jQuery('#ju_preview').fadeOut(); 
                    }
                });   
            jQuery('#ju_bbox').animate({'right':'-8px'});
            if(!jQuery.cookie('promotion_preveiw')){
                jQuery('#ju_preview').animate({'right':'55px'});
                jQuery.cookie('promotion_preveiw','1');
            }
            //logofferview(currentcp);
        });
//--></script>
    <a href="<?php echo $social_promotion_box ?>" id="ju_bbox">
        <span>
            <img src="<?php echo $social_promotion_title ?>" alt="Click for promotion" width="24" height="140">
        </span>
    </a>
    <div id="ju_preview">
        <a href="Javascript:void(0);" id="ju_close_btn">X</a>
        <span id="ju_preview_arrow">›</span>
        <span id="ju_preview_instant"><?php echo $social_promotion_box_title; ?></span>
        <a href="<?php echo $social_promotion_box ?>" id="ju_preview_coupon"><?php echo $social_promotion_box_desc ?><strong>Click here</strong>
        </a>
    </div>
<?php endif; ?>