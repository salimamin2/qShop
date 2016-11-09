<div id="Content" class="container">
<?php if($information_id): ?> 
   <div class="col-main grid12-9 grid-col2-main in-col2 col-md-12">
        <div class="text-center">
          
         <p>[information id="<?php echo $information_id; ?>" title="0" /]</p>
          
        </div>
    </div>
<?php endif; ?>
    <div class="text-center col-md-12">
        <p class="section-title-account">
            <span class="background-account">
                <span class="border-account"><?php echo $heading_title; ?></span>
            </span>
            
    </div>


	<div class="col-md-12 blog_post">
		<ul class="authors-list">
            <?php if($manufacturers): ?>
 				<?php foreach($manufacturers as $i => $manufacturer): ?>
 					<li class="item <?php
            			echo ($i == 0) ? 'first ' : '';
                        echo ($i == 1) ? 'first ' : '';
            			echo ($i % 4 == 0) ? 'row-first ' : '';
            			echo ($i % 4 == 3) ? 'row-last ' : '';
            			echo (count($manufacturers) - 1 == $i) ? 'last' : '';
        			?>">
                        <div class="content manufacturer_listing">
                            <div class="col-md-7 first-col">
                                <div class="authors-details">
                                	<p><a href="<?php echo $manufacturer['href']; ?>"><?php echo $manufacturer['name']; ?></a></p>
                                	<a href="<?php echo $manufacturer['href']; ?>"><?php echo $manufacturer['total_products']; ?>&nbsp;<?php echo 'Products'; ?></a>
                                	<p class="social-icon">
                                        <?php if($manufacturer['facebook']): ?>
                                    		<span>
                                    			<a href="<?php echo ($manufacturer['facebook'] ? $manufacturer['facebook'] : 'http://www.facebook.com/'); ?>" target="_blank" class="facebook"></a>
                                    		</span>
                                        <?php endif; ?>
                                        <?php if($manufacturer['twitter']): ?>
    										<span>
    											<a href="<?php echo ($manufacturer['twitter'] ? $manufacturer['twitter'] : 'http://www.twitter.com/'); ?>" target="_blank" class="twitter"></a>
    										</span>
                                        <?php endif; ?>
                                	</p>
                                </div>
                            </div>
                            <?php if ($manufacturer['image_url'] != ''): ?>
                                <div class="gradient-div"></div>
                            	<div class="col-md-5 second-col">
                                    <div class="authors-image" style="">
                                    	<a href="<?php echo $manufacturer['href']; ?>">
                                            <img src="<?php echo HTTP_IMAGE . $manufacturer['image_url']; ?>" alt="<?php echo $manufacturer['name']; ?>" title="<?php echo ($manufacturer['meta_title'] != '' ? $manufacturer['meta_tile'] : $manufacturer['name']); ?>" />
                                        </a>
                                    </div>
								</div>
							<?php endif; ?>
						</div>
					</li>
					<?php echo ($i == 1) ? '<div class="clearfix"></div> ' : ''; ?>
				<?php endforeach; ?>
			<?php else: ?>
				<div class="block_category_above_empty_collection std text-center">
					<div class="note-msg empty-catalog">
						<h3>There are no designers matching the selection</h3>
					</div>
				</div>
			<?php endif; ?>
		</ul>
	</div>
</div>

