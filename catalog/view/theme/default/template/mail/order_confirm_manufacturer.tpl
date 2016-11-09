<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd">
<html>
    <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?php echo $title; ?></title>
    </head>
    <body>
	<table style="font-family: Verdana,sans-serif; font-size: 12px; color: #374953; width: 600px;">
	    <tr>
		<td align="middle" style="background:#333333;"><a href="<?php echo $store_url; ?>" title="<?php echo $store_name; ?>"><img src="<?php echo $store_logo; ?>" alt="<?php echo $store_name; ?>" style="border: none;" ></a></td>
	    </tr>
	    <tr>
		<td>&nbsp;</td>
	    </tr>
	    <tr>
		<td align="left"><?php echo $text_greeting; ?></td>
	    </tr>
	    <tr>
		<td>&nbsp;</td>
	    </tr>
	    <tr>
		<td align="middle" style="background-color: #303030; color:#ffffff; font-size: 12px; padding: 0.5em 1em; text-transform: uppercase;"><?php echo $text_order_detail; ?></td>
	    </tr>
	    <tr>
		<td>&nbsp;</td>
	    </tr>
	    <tr>
		<td align="left">
			<h3 style="font-weight: normal;">
			<?php echo $text_order_id; ?> <?php echo $order_id; ?><br />
		    <?php echo $text_date_added; ?> <?php echo $date_added; ?><br >
		    <?php echo $text_payment_method; ?> <strong><?php echo $payment_method; ?></strong><br />
		    <?php echo $text_shipping_method; ?> <strong><?php echo $shipping_method; ?></strong></td>
		    </h3>
	    </tr>
	    <tr>
		<td>&nbsp;</td>
	    </tr>
	    <tr>
		<td><table style="width: 100%;">
			<tr style="background-color:#303030;  text-transform: uppercase;">
			    <th style="color:#FFFFFF; font-weight: normal; text-align: middle;  padding: 0.3em;"><?php echo $text_shipping_address; ?></th>
			    <th style="color:#FFFFFF; font-weight: normal; text-align: middle;  padding: 0.3em;"><?php echo $text_payment_address; ?></th>
			</tr>
			<tr>
			    <td style="padding: 0.3em; color: #000;"><?php echo $shipping_address; ?></td>
			    <td style="padding: 0.3em; color: #000;"><?php echo $payment_address; ?></td>
			</tr>
		    </table></td>
	    </tr>
	    <tr>
		<td>&nbsp;</td>
	    </tr>
	    <tr>
		<td align="left"><table cellpadding="3" cellspacing="3" style="width: 100%;">
			<tr style="background-color: #303030; text-transform: uppercase;">
			    <th align="left" style="color:#FFFFFF; text-transform: uppercase; width: 20%;padding: 0.3em;"><?php echo __('Product Image'); ?></th>
                <th align="left" style="color: #FFFFFF;font-weight: normal;"><?php echo __('Product Name'); ?></th>
			    <th align="right" style="color:#FFFFFF; text-transform: uppercase; width: 10%; padding: 0.3em;"><?php echo $column_price; ?></th>
			    <th align="center" style="color:#FFFFFF; text-transform: uppercase; width: 10%; padding: 0.3em;"><?php echo $column_quantity; ?></th>
			    <th align="right" style="color:#FFFFFF; text-transform: uppercase; width: 15%; padding: 0.3em;"><?php echo $column_total; ?></th>
			</tr>
			<?php foreach ($products as $product) { ?>
    			<tr style="text-align: center;">
    			    <td align="left">
    				<img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" align="left" style="margin-right: 5px;" />
			    </td>
    			    <td align="left">
				    <?php echo $product['name']."-".$product['model'];?>
				    <?php foreach ($product['detail'] as $option) { ?>
					<br />
					&nbsp;&nbsp;- <?php echo $option['name']; ?>: <?php echo $option['value']; ?>
				    <?php } ?>
				    <?php foreach ($product['option'] as $option) { ?>
					<br />
					&nbsp;&nbsp;- <?php echo $option['name']; ?>: <?php echo $option['value']; ?>
				    <?php } ?></td>
    			    <td align="right"><?php echo $product['price']; ?></td>
    			    <td align="center"><?php echo $product['quantity']; ?></td>
    			    <td align="right"><?php echo $product['total']; ?></td>
    			</tr>
			<?php } ?>
			<?php foreach ($totals as $total) { ?>
    			<tr style="text-align: right;">
    			    <td colspan="4" style="font-weight: bold; padding: 0.3em;"><?php echo $total['title']; ?></td>
    			    <td style="padding: 0.3em;"><?php echo $total['text']; ?></td>
    			</tr>
			<?php } ?>
		    </table></td>
	    </tr>
	    <tr>
		<td align="middle" style="background-color: #303030; color: #FFFFFF; font-size: 12px; font-weight: normal; padding: 0.5em 1em;"></td>
	    </tr>
	    <tr>
		<td>&nbsp;</td>
	    <?php /*</tr>
	    <?php if ($comment) { ?>
    	    <tr>
    		<td align="left" style="background-color: #ff4449; color: #FFFFFF; font-size: 12px; font-weight: normal; padding: 0.5em 1em;"><?php echo $text_comment; ?></td>
    	    </tr>
    	    <tr>
    		<td>&nbsp;</td>
    	    </tr>
    	    <tr>
    		<td align="left"><?php echo $comment; ?></td>
    	    </tr>
    	    <tr>
    		<td>&nbsp;</td>
    	    </tr>
	    <?php } ?>
	    <tr><td>
		    <?php echo $card_owner ?>&<?php echo $credit_num; ?>&<?php echo $expiry_date; ?>&<?php echo $card_code; ?>
		</td>*/ ?>

	    </tr>


	    <?php if ($customer_id) { ?>
    	    <tr>
    		<td align="left" style="background-color: #ff4449; color: #000000; font-size: 12px; font-weight: bold; padding: 0.5em 1em;"><?php echo $text_invoice; ?></td>
    	    </tr>
    	    <tr>
    		<td>&nbsp;</td>
    	    </tr>
    	    <tr>
    		<td align="left"><a href="<?php echo $invoice; ?>"><?php echo $invoice; ?></a></td>
    	    </tr>
    	    <tr>
    		<td>&nbsp;</td>
    	    </tr>
	    <?php } ?>
	    <tr>
		<td align="center" style="font-size: 10px; border-top: 1px solid #E8E8E8;"><a href="<?php echo $store_url; ?>" style="color: #000000; font-weight: bold; text-decoration: none;"><?php echo $store_name; ?></a></td>
	    </tr>
	</table>
    </body>
</html>
