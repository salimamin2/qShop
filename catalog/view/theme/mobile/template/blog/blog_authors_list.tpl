<div id="Content" class="container">

    <div class="text-center col-md-12">
        <p class="section-title-account">
            <span class="background-account">
                <span class="border-account"><?php echo __('text_for_author'); ?></span>
            </span>
        </p>
    </div>

    <div class="col-md-12 blog_post">
        <ul class="authors-list">
            <?php if($authors) { ?>
                <?php foreach ($authors as $i => $post): ?>
                    <li class="item <?php
            			/*echo ($i == 0) ? 'first ' : '';
                        echo ($i == 1) ? 'first ' : '';
            			echo ($i % 4 == 0) ? 'row-first ' : '';
            			echo ($i % 4 == 3) ? 'row-last ' : '';
            			echo (count($authors) - 1 == $i) ? 'last' : '';*/
        			?>">
                        <div class="content">
                            <?php if ($post['image']): ?>
                                <div class="col-sm-5 col-md-5 second-col">
                                    <div class="authors-image" style="">
                                        <a href="<?php echo $post['href']; ?>"><img src="<?php echo $post['image']; ?>" alt="<?php echo $post['name']; ?>"  title="<?php echo $post['name']; ?>" /></a>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="col-sm-7 col-md-7 first-col">
                                <div class="authors-details">
                                    <p class="title"><a href="<?php echo $post['href']; ?>"><?php echo $post['name']; ?></a></p>
                                    <a href="<?php echo $post['href']; ?>"><?php echo $post['total_posts']; ?>&nbsp;<?php echo 'Articles'; ?></a>
                                    <p class="social-icon">
                                        <?php if($post['fb_link']) : ?>
                                            <span><a href="<?php echo ($post['fb_link'] ? $post['fb_link'] : 'http://www.facebook.com/'); ?>" class="facebook"></a></span>
                                        <?php endif; ?>
                                        <?php if($post['twitter_link']): ?>
                                            <span><a href="<?php echo ($post['twitter_link'] ? $post['twitter_link'] : 'http://www.twitter.com/'); ?>" class="twitter"></a></span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </li>
                    <?php echo ($i == 1) ? '<div class="clearfix"></div> ' : ''; ?>
                <?php endforeach; ?>
            <?php } else { ?>
                <div class="block_category_above_empty_collection std text-center">
                    <div class="note-msg empty-catalog">
                        <h3><?php echo $text_noposts; ?></h3>
                    </div>
                </div>
            <?php } ?>
        </ul>
    </div>
    <div class="col-md-12">
        <div class="recommended-blog container">
            <div class="row">
                <div class="head col-sm-3 col-md-3">
                    <h2>from our Editorial</h2>
                    <p><a href="<?php echo $blog_page; ?>">View All</a></p>
                </div>
                <div class="col-sm-9 col-md-9">
                    <div class="row">
                        [module name="blog_latest" class="col-sm-4" limit="3" /]
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>