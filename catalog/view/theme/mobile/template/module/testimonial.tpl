<div class="block block-tags" id="testimonial">
  <div class="block-title">
      <h1><?php echo $heading_title; ?></h1>
  </div>
  <div class="block-content sample-block">
      <?php foreach($testimonials as $testimonial): ?>
        <span class="quote"><?php echo substr($testimonial['description'],0,1000); ?><span class="right-arrow">Right</span></span><br /><br />
        <?php 
        $title = $testimonial['title'];
        if(stristr($testimonial['title'], "|") !== false) { 
            $aArr = explode("|", $testimonial['title']);  
            $title = $aArr[0]." <span class='location'>".$aArr[1]."</span>";     } ?>
        <span class="name"><?php echo $title; ?></span>
      <?php endforeach; ?>
      <div class="actions">
          <a href="/testimonials"><?php echo $text_more; ?></a>
      </div>
  </div>
</div>
