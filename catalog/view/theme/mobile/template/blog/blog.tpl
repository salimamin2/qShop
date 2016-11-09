<div id="Content" class="container">
    <?php if(empty($aAuthor)): ?>
        <nav id="blog_option" class="mobile">
            <div class="col-sm-3 editorial-main" style="">
                <p class="editorial-list-head"><?php echo $text_category_editorail; ?></p>
                <ul class="list-group-editorial" style="list-style: none">
                    <?php foreach($categories as $category): ?>
                        <li class="list-group-item">
                            <a href="<?php echo str_replace('&', '&amp;', $category['href']); ?>">
                                <?php echo $category['name']; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <p class="editorial-list-head"><?php echo $text_category_bytime; ?></p>
                <ul class="list-group-editorial" style="list-style: none">
                    <li class="list-group-item">
                        <a href="<?php echo str_replace('&', '&amp;', $posted_recently); ?>">
                            <?php echo $text_recent; ?>
                        </a>
                    </li>

                    <li class="list-group-item">
                        <a href="<?php echo str_replace('&', '&amp;', $posted_month); ?>">
                            <?php echo $text_month; ?>
                        </a>
                    </li>

                    <li class="list-group-item">
                        <a href="<?php echo str_replace('&', '&amp;', $posted_week); ?>">
                            <?php echo $text_week; ?>
                        </a>
                    </li>
                </ul>
                <p class="editorial-list-head"><?php echo $text_author; ?></p>
                <ul class="list-group-editorial" style="list-style: none">
                    <?php foreach($featured_authors as $authors): ?>
                        <li class="list-group-item blog-author-list">
                            <span class="col-xs-3 col-md-3 no-padding"> 
                                <span class="image-author">
                                    <a class="authrs-a" href="<?php echo str_replace('&', '&amp;', $authors['href']); ?>"><img src="<?php echo $authors['image']; ?>" alt="<?php echo $authors['username']; ?>"  title="<?php echo $authors['username']; ?>" /></a>
                                </span>
                            </span>
                            <span class="col-xs-9 col-md-9">
                                <span class="author-content">
                                    <p><a class="authrs-a" href="<?php echo str_replace('&', '&amp;', $authors['href']); ?>"><?php echo $authors['username']; ?></a></p>
                                </span>
                            </span>
                            <span class="clearfix"></span>
                        </li>
                        <div class="clearfix"></div>
                    <?php endforeach; ?>
                </ul>
                <br />
                <a class="view-all" href="<?php echo str_replace('&', '&amp;', $more_authors); ?>">
                    <?php echo $text_more_authors; ?>
                </a>
                <br /><br />
            </div>
        </nav>

        <label class="blog-filter"><a href="#blog_option">Blog Options <span class="marker">&nbsp;</span></a></label>

    <div class="col-sm-12 col-md-9">
<?php else: ?>
    <div class="col-main grid12-9 grid-col2-main in-col2 col-md-12">
    <div class="designer-profile text-center">
                <?php if ($aAuthor['im'] != ''): ?>
                    <div class="img">
                        <img src="<?php echo $aAuthor['im']; ?>" alt="<?php echo $aAuthor['name']; ?>" title="<?php echo ($aAuthor['meta_title'] != '' ? $aAuthor['meta_tile'] : $aAuthor['name']); ?>" />
                    </div>
                <?php endif; ?>
                <h1><?php echo $aAuthor['name']; ?></h1>
                <?php if(trim($aAuthor['description']) != ''): ?>
                    <p><?php echo $aAuthor['description']; ?></p>
                <?php endif; ?>
                <div class="social-icon">
                    <?php if($aAuthor['fb_link']): ?>
                        <span><a href="<?php echo ($aAuthor['fb_link'] ? $aAuthor['fb_link'] : 'http://facebook.com/'); ?>" target="_blank" class="facebook-square"></a></span>
                    <?php endif; ?>
                    <?php if($aAuthor['twitter_link']): ?>
                        <span><a href="<?php echo ($aAuthor['twitter_link'] ? $aAuthor['twitter_link'] : 'http://www.twitter.com/'); ?>" target="_blank" class="twitter-square"></a></span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="category-products">
                <div class="text-center">
                    <p class="section-title-account">
                        <span class="background-account">
                            <span class="border-account">Posts</span>
                        </span>
                    </p>
                </div>
<?php endif; ?>
        <?php if($posts) { ?>
            <ul class="editorial-image-wrapper row">
                <?php foreach ($posts as $i => $post): ?>
                    <li class="item <?php
		                echo ($i == 0) ? 'first ' : '';
                        echo ($i == 1) ? 'second ' : '';
		                echo ($i % 4 == 0) ? 'row-first ' : '';
		                echo ($i % 4 == 3) ? 'row-last ' : '';
		                echo (count($posts) - 1 == $i) ? 'last' : '';
                    ?> list-editorial">
                        <?php if ($post['thumb']): ?>
                            <div class="editorial-image-wrapper">
                                <a href="<?php echo str_replace('&', '&amp;', $post['href']); ?>" title="<?php echo $post['meta_link']; ?>" class="product-image">
                                    <img src="<?php echo $post['thumb']; ?>" alt="<?php echo $post['alt_title']; ?>"  title="<?php echo $post['alt_title']; ?>" />
                                </a>
                            </div>
                        <?php endif; ?>

                        <span class="editorial-name">
                            <a href="<?php echo str_replace('&', '&amp;', $post['href']); ?>" title="<?php echo $post['meta_link']; ?>" >
                                <?php echo $post['name']; ?>
                            </a>
                        </span>

                        <div class="read_more">
                            <a href="<?php echo str_replace('&', '&amp;', $post['href']); ?>" title="<?php echo $post['meta_link']; ?>" >
                                <span><?php echo $text_readmore; ?></span>
                            </a>
                        </div>
                    </li>
                    <?php echo ($i % 2 == 1) ? '<div class="clearfix"></div> ' : ''; ?>
                <?php endforeach; ?>
            </ul>
        <?php } else { ?>
            <h2><?php echo $text_noposts; ?></h2>
        <?php } ?>
        <?php if($aAuthor): ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>