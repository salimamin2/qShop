<div id="information">    
    <ul>
      <?php foreach ($informations as $information) { ?>
        <li class="<?php echo strtolower (str_replace(' ', '_', $information['title'])); ?>"><span><a href="<?php echo str_replace('&', '&amp;', $information['href']); ?>"><?php echo $information['title']; ?></a></span></li>
      <?php } ?>
            <!-- <li><a href="<?php echo str_replace('&', '&amp;', $contact); ?>"><?php echo $text_contact; ?></a></li>
      <li><a href="<?php echo str_replace('&', '&amp;', $sitemap); ?>"><?php echo $text_sitemap; ?></a></li>-->
    </ul>
    
</div>
