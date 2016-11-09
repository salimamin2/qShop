<div id="Content" class="container">
    <div class="blog-page">
        <div class="post-img">
            <div class="blog-img-bg"></div>
            <img src="<?php echo $posts['image']; ?>" alt="<?php echo $posts['meta_link']; ?>"  title="<?php echo $posts['meta_link']; ?>" />
            <h1><?php echo  $posts['title']; ?></h1>
        </div>
        <div class="container">
            <div class="col-md-12">
                <div class="post-info">
                    <p>article by <a href="<?php echo $posts['author_link']; ?>" class="text-posted"><?php echo $posts['author']; ?></a></p>
                    <p>posted on <span class="text-posted"><?php echo $posts['date_craeted']; ?></span></p>
                        <!-- <div class="addthis_sharing_toolbox"></div>-->
                        <div class="socials">

                            <a class="btn btn-social-icon btn-facebook" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" href="http://www.facebook.com/share.php?s=100&amp;p[url]=<?php echo $product_link; ?>&amp;p[title]=<?php echo $heading_title; ?>&amp;p[images][0]=<?php echo $thumb; ?>">
                                <img class="img-social-fb" src="catalog/view/theme/default/image/icons/social_fb.png">
                            </a>

                            <a class="btn btn-social-icon btn-twitter" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" href="http://twitter.com/share?url=<?php echo $product_link; ?>&amp;text=<?php echo $heading_title; ?>">
                                <img class="img-social-twitter" src="catalog/view/theme/default/image/icons/social_twitter.png">
                            </a>

                            <a class="btn btn-social-icon btn-twitter" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" href="https://www.pinterest.com/share?url=<?php echo $product_link; ?>&amp;text=<?php echo $heading_title; ?>">
                                <img class="img-social-pin" src="catalog/view/theme/default/image/icons/social_pinit.png">
                            </a>

                            <?php /*<a class="btn btn-social-icon btn-email" href="mailto:<?php echo $email; ?>">
                            <img src="catalog/view/theme/default/image/icons/email.png">
                            </a>*/ ?>
                        </div>
                </div>
                <div class="post-description"><?php echo  $posts['description']; ?></div>
            </div>
        </div>

    </div>
    <div class="col-md-12">
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
    </div>
</div>
<!--<div class="col-sm-9 col-md-9 panelbox" style="padding-bottom: 15px; padding-top: 15px;">
    <?php if($this->customer->isLogged()) { ?>
    [module name="recommended" /]
    <?php } ?>
    <div class="right-banner"><div class="row">[module name="right_banner" /]</div></div>
</div>-->