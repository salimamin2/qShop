<form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" id="checkout_ep">
	<input type="hidden" name="storeId" value="2174" hidden = "true"/>
	<input type="hidden" name="amount" value="<?php echo $amount; ?>" hidden = "true"/>
	<input type="hidden" name="postBackURL" value="<?php echo $postBackURL; ?>" hidden = "true"/>
	<input type="hidden" name="orderRefNum" value="<?php echo $orderRefNum; ?>" hidden = "true"/>
	<input type="hidden" name="expiryDate" value="">
	<input type="hidden" name="merchantHashedReq" value="">
	<input type="hidden" name="autoRedirect" value="1">
	<input type="hidden" name="paymentMethod" value="">
	<input type="hidden" name="emailAddr" value="">
	<input type="hidden" name="mobileNum" value="">
</form>


