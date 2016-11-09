
  <div id="latest-news" class="box">
    <div class="heading"><?php echo $heading_title; ?></div>
    <div class="middle">
    <?php foreach ($news as $news_story) { ?>
      <div style="border-bottom: 1px solid #DDDDDD; margin-bottom: 8px;">
          <div class="date"><?php echo $news_story['date']?></div>
          <div class="desc"><?php echo $news_story['short_description'][0]; ?></div>
          <a href="<?php echo $news_story['href']; ?>">» <?php echo $text_read_more; ?></a>
      </div>
    <?php } ?>
    </div>
    <div class="bottom"><a href="<?php echo $news_info; ?>">» <?php echo $text_news_more; ?></a></div>
  </div>
