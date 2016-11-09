<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
    <html xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml" >
        <head>
            <title><?php echo $coupon_box_desc; ?></title>
            <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
            <base href="<?php echo $base; ?>" />
            <script language="javascript" type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
            <script src="//platform.linkedin.com/in.js" type="text/javascript"></script>
            <script>
                var coupon_url = '<?php echo $social_promotion_code; ?>';
            </script>
            <script language="javascript" type="text/javascript" src="catalog/view/javascript/jquery/coupon-iframe.js"></script>

            <script>
                var text = "Enter your email";
                var fb_share_url = "<?php echo $coupon_fb_link ?>";
                var currentcp = 0;
                $(document).ready(function() {
                    $("#field_share_email").attr("value", text);
                    wibiyaaction(100)
                    currentcp = $('#couponcode0').attr('cp');
                    // logofferview(currentcp);
                });
            </script>
            <link rel="stylesheet" type="text/css" href="catalog/view/theme/modern/stylesheet/all_style.css" />
            <script src="//connect.facebook.net/en_US/all.js"></script>
        </head>

        <body id="notBody">
            <div id="fb-root"></div>
            <script>
                var hasliked = false;
                // Init the SDK upon load
                window.fbAsyncInit = function() {
                    FB.init({
                        appId      : '269601666425322', // App ID
                        channelUrl : '<?php echo $baseUrl ?>channel.html', // Channel File
                        status     : true, // check login status
                        cookie     : true, // enable cookies to allow the server to access the session
                        xfbml      : true  // parse XFBML
                    });
                    FB.Event.subscribe('edge.create', function(response) {
                        console.log(response);
                        if(!hasliked){
                            hasliked = true;
                            getpromocode('1','',currentcp);
                            wibiyaaction(101)
                        }
                    });
                    FB.Event.subscribe('auth.prompt', function(response){
                        console.log(response);
                        if(!hasliked){
                            hasliked = true;
                            getpromocode('1','',currentcp);
                            wibiyaaction(101)
                        }
                    });
                };
                function likeButton(){
                    
                }
                var waitani = false;
  
                $(document).ready(function() {
                    $('.share_button').live('click', function(e){
                        e.preventDefault();
                        FB.ui({
                            display: 'popup', 
                            method: 'feed',
                            name: $(this).attr('name'),
                            link: $(this).attr('link'),
                            picture: $(this).attr('image'),
                            caption: $(this).attr('caption'),
                            description: $(this).attr('description')
                        },
                        function(response){
                            if(response && response.post_id) {
                                //self.location.href = 'http://www.thomaspynchon.com/inherent-vice.html'
                            }
                            else {
                                //self.location.href = 'http://www.google.com/'
                            }
                        });
                    });
	
                    $('.next_offer').live('click', function(e){
                        if (!waitani){
                            waitani = true;
                            $('#coupons ul').animate({
                                left: (parseFloat($('#coupons ul').css('width'))-320+parseFloat($('#coupons ul').css('left'))==0) ? '0' : '-=320'
                            }, 400, function() {
                                waitani = false;
                                currentcp = $('#couponcode'+parseFloat($('#coupons ul').css('left'))/-320).attr('cp');
                                logofferview(currentcp);
                            });
                        };
                    });
	
                });

            </script>
            <div id="ju_Con">
                <h1>SELECT TO REDEEM INSTANTLY</h1>
                <div id="share_options">
                    <?php if ($social_promotion_fb_link): ?>
                        <div id="share_facebook">
                            <fb:like href="https://www.facebook.com/<?php echo $social_promotion_fb_link ?>" send="false" layout="box_count" show_faces="false" ref="<?php echo $social_promotion_fb_link ?>"></fb:like>
                        </div>
                    <?php endif; ?>
                    <?php if ($social_promotion_gp_link): ?>
                        <div id="share_google">
                            <div class="g-plusone" data-size="tall" data-callback="plusone_vote" data-href="<?php echo $social_promotion_gp_link ?>" data-recommendations="false"></div>
                        </div>
                    <?php endif; ?>
                    <?php if ($social_promotion_tt_link): ?>
                        <div id="share_twitter"> <a href="http://twitter.com/share" class="twitter-share-button"
                                                    data-url="<?php echo $this->config->get('config_url') ?>"
                                                    data-counturl="<?php echo $this->config->get('config_url') ?>"
                                                    data-via="<?php echo $social_promotion_tt_link ?>"
                                                    data-text="<?php echo $this->config->get('config_title') ?>"

                                                    data-count="vertical">Tweet</a> </div>

                        <div id="share_twitter_follow"> <a href="https://twitter.com/https://twitter.com/<?php echo $social_promotion_tt_link ?>" class="twitter-follow-button" data-show-count="false" data-count="vertical" data-show-screen-name="false" data-dnt="true">Follow</a> </div>
                    <?php endif; ?>
                </div>

                <div id="coupons_container">

                    <div id="coupons">
                        <ul style="width:320px;">

                            <li>
                                <div class="li_inner">
                                    <?php echo $social_promotion_desc; ?>
                                    <div class="couponcode_container"><img src="image/peel.png" class="peel" /><div id="couponcode0" class="couponcode" cp="8851">Promo code will show here</div></div>
                                </div>
                            </li>

                        </ul>
                    </div>
                    <a class="info" href="#">
                        coupon not showing?
                        <span>First, did you do one of the following above? If so, were you already a fan of us? If so, try clicking to un-fan us and retry it.</span>
                    </a>
                </div>
            </div>
            <script src="//platform.twitter.com/widgets.js" type="text/javascript"></script> 
            <script type="text/javascript">
                (function() {
                    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
                    po.src = 'https://apis.google.com/js/plusone.js';
                    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
                })();
            </script>
            <script>

                var ac_guid = "{062ADE7D-00EB-4668-8A18-68BEBA83B312}";

                twttr.events.bind('tweet', function(event) {
                    getpromocode('3','',currentcp);
                    wibiyaaction(103)
                });

                twttr.events.bind('follow', function(event) {
                    getpromocode('6','',currentcp);
                    wibiyaaction(103)
                });

                function plusone_vote( obj ) {
                    if(obj.state=="on"){
                        getpromocode('2','',currentcp);
                        wibiyaaction(102)
                    }
                    if(obj.state=="off"){
                        //getpromocode();
                    }
                }

                function linked_vote() {
                    getpromocode('5','',currentcp);
                    //wibiyaaction(102)
                }

                function wibiyaaction(id){
	
                }

                $(document).ready(function() {
                    getpromocode('1000','',currentcp);
                })

            </script>

            <script type="text/javascript" charset="utf-8">
                //  var _gaq = _gaq || [];
                //  _gaq.push(['_setAccount', 'UA-30196155-1']);
                //  _gaq.push(['_trackPageview']);
                //  
                //  (function() {
                //    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                //    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                //    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
                //  })();
            </script>
        </body>
    </html>