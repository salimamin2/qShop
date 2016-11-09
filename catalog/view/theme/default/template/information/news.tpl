<div id="content">
    <?php if (isset($news_info)) { ?>
        <div class="heading">
    	<h1><?php echo $heading_title; ?></h1>
        </div>
        <div class="middle">
    	<div>
    	    <p><?php echo $description; ?></p>
    	    <div style="clear: both; padding-bottom: 15px;"></div>
    	</div>
    	<div class="buttons">
    	    <table>
    		<tr>
    		    <td align="right"><a onclick="location = '<?php echo $news; ?>'" class="button"><span><?php echo $button_news; ?></span></a></td>
    		</tr>
    	    </table>
    	</div>
        </div>

    <?php } ?>
    <?php if (isset($news_data)) { ?>
        <div class="top">
    	<h1><?php echo $heading_title; ?></h1>
        </div>
        <div class="middle">
    	<div id="news_list" class="tabulation_right_main">
    	    <ul>
		    <?php foreach ($news_data as $news) { ?>
			<li>
			    <a href="<?php echo $news['href']; ?>"> <?php echo $news['title']; ?>	</a><span style="float:right;"><?php echo date("m/d/Y", strtotime($news['date_added'])); ?></span>  
			</li>
		    <?php } ?>
    	    </ul>
    	    <div class="pagination"><?php echo $pagination; ?></div>
    	</div>
        </div>
    <?php } ?>
    <div class="bottom">&nbsp;</div>
</div>