<div class="container">
	<div class="col-md-12">
		<div class="row">
			<div class="slider">
				<?php echo $this->load('module/front_slideshow'); ?>
			</div>
		</div>
	</div>
	
	 <?php echo $home_page; ?>
	
</div>
<script type="text/javascript" src="catalog/view/javascript/jquery/twitter.js"></script>
<script type="text/javascript">
	
	twitterFetcher.fetch('659276366358126592', 'recent_tweets', 1, true, false);

	function dateFormatter(date) {
        return date.toTimeString();
    }

    /*function handleTweets(tweets){
        var x = tweets.length;
        var n = 0;
        var element = jQuery('.recent_tweets');
        var html = '<ul>';
        while(n < x) {
            html += '<li>' + tweets[n] + '</li>';
            n++;
        }
        html += '</ul>';
        element.html(html);
    }*/
</script>