<?php if($response): ?>
<form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" id="checkout">
<?php foreach($response as $key => $val): ?>
    <input type="hidden" name="<?php echo $key ?>" value="<?php echo $val ?>" />
<?php endforeach; ?>
<?php endif; ?>
<!--div class="buttons">
	  <div class="left"><a onclick="location = '<?php echo str_replace('&', '&amp;', $back); ?>'" class="button"><span><?php echo $button_back; ?></span></a></div>
      <div class="right"><a onclick="postPayment('checkout_hbl')" class="button right"><span><?php echo $button_confirm; ?></span></a></div>
</div>-->
</form>
<!--	
<div class="clr"></div>
<script type="text/javascript">
    var sent = false;
    function postPayment(frm) {
        jQuery('#'+frm+'error').remove();
        if(!sent){
            jQuery.ajax({
                type: 'post',
                url: 'payment/hbl/callback',
                dataType: 'json',
                data: jQuery('#'+ frm + ' :input'),
                beforeSend: function(){
                    jQuery('#'+frm+' .confirmorder').before('<span class="ajax-loading"><img src="catalog/view/theme/default/image/ajax-loader.gif" /> Loading...</span>  ')
                    sent=true;
                },
                success: function (result) {
                    jQuery('.ajax-loading').remove();
                    //if($.isArray(result)){
                        if(result[0] != 'error'){
                            var html='';
                            jQuery.each(result, function(key, value) {
                                html +='<input type="hidden" name="'+key+'" value="'+value+'" />\n';
                            });

                            jQuery('#hbl').before(html);
                            jQuery('#'+frm).submit();
                        }
                        else{
                            var sError = '';
                            jQuery.each(result, function(i,error){
                                if(error != 'error' && error.length>0)
                                    sError += '<div class="warning">Error: '+error + '</div>';
                            });
                            jQuery('#'+frm).before("<div id='"+frm+"error'>"+sError+"</div>");
                            sent = false;
                        }
                    /*} else {
                        $('#'+frm).submit();
                    }*/
                },
                complete: function () {
                }
            });
        }
    }
    //</script>-->
