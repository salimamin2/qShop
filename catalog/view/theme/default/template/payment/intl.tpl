<div class="buttons">
    <a onclick="location = '<?php echo str_replace('&', '&amp;', $back); ?>'" class="btn left"><span><?php echo $button_back; ?></span></a>
    <a id="checkout" class="btn right"><span><?php echo $button_confirm; ?></span></a>
</div>
<script type="text/javascript"><!--
$('#checkout').click(function() {
	$.ajax({
		type: 'GET',
		url: 'payment/intl/confirm',
		success: function() {
			location = '<?php echo $continue; ?>';
		}		
	});
});
//--></script>
