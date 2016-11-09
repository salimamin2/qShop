<div id="Content" class="my-account container">
    <div class="col-md-12">
        <?php echo $this->load('module/account'); ?>

		<div class="page-description">
			<div class="post-content">
				<h2 class="order-date text-center"><h1><?php echo $heading_title; ?><br />Order Date: <?php echo $date_added; ?></h1></h2>
				<div class="col2-set order-info-box row">
					<div class="col-sm-6">
						<div class="box">
							<div class="box-title">
								<h3><?php echo __('Shipping Address'); ?></h3>
							</div>
							<div class="box-content">
								<?php echo $shipping_address; ?>
							</div>
						</div>
						<div class="box">
							<div class="box-title">
								<h3><?php echo __('Shipping Method'); ?></h3>
							</div>						
							<div class="box-content">
								<?php echo $shipping_method; ?>
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="box">
							<div class="box-title">
								<h3><?php echo $text_payment_address; ?></h3>
							</div>
							<div class="box-content">
								<?php echo $payment_address; ?>
							</div>
						</div>
						<div class="box">
							<div class="box-title">
								<h3><?php echo $text_payment_method; ?></h3>
							</div>
							<div class="box-content">
								<?php echo $payment_method; ?>
							</div>
						</div>
					</div>
				</div>
				<div class="clearfix"></div>
				<div class="order-items order-details">
					<h3 class="table-caption"><?php echo __('Items Ordered'); ?></h3>
					<table class="data-table">
						<colgroup>
							<col width="40%">
							<col width="20%">
							<col width="20%">
							<col width="20%">
						</colgroup>
						<thead>
							<tr class="first last">
								<th><?php echo __('Product Name'); ?></th>
								<th class="a-right"><?php echo __('Price'); ?></th>
								<th class="a-center"><?php echo __('Qty'); ?></th>
								<th class="a-right"><?php echo __('Subtotal'); ?></th>
								<th class="a-right"><?php echo __('Return'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php $length = count($products); ?>
							<?php foreach ($products as $i => $product): ?>
								<?php
									$class = ($i % 2 == 0 ? 'odd' : 'even');
									$class .= ($i == 0 ? ' first' : '');
									$class .= ($i == ($length - 1) ? ' last' : '');
								?>
								<tr class="border <?php echo $class; ?>">
									<td>
										<h5 class="product-name"><?php echo $product['name'] . " " . $product['model']; ?></h5>
										<dl class="item-options">
											<?php foreach ($product['option'] as $option) { ?>
												<dt><?php echo $option['name']; ?></dt>
												<dd><?php echo $option['value']; ?></dd>
											<?php } ?>
										</dl>
									</td>
									<td class="a-right">
										<span class="price-excl-tax">
											<span class="cart-price">
												<span class="price"><?php echo $product['price']; ?></span>
											</span>
										</span>
									</td>
									<td class="a-right">
										<span class="nobr"><?php echo $product['quantity']; ?></span>
									</td>
									<td class="a-right">
										<span class="price-excl-tax">
											<span class="cart-price">
												<span class="price">
													<?php echo $product['total']; ?>
												</span>
											</span>
										</span>
									</td>s
									<td class="a-right last">
										<a href="account/return">
											<i class="fa fa-share"></i>
										</a>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
						<tfoot>
						<?php
							$length = count($totals);
							foreach ($totals as $i => $total) {
								$class = ($i == 0 ? 'subtotal first' : '');
								$class = ($i == ($length - 1) ? 'grand_total last' : '');
							?>
								<tr class="<?php echo $class; ?>">
									<th colspan="3" class="text-right"><?php echo $total['title']; ?></th>
									<td colspan="2" class="last text-center"><span class="price"><?php echo $total['text']; ?></span></td>
								</tr>
							<?php } ?>
						</tfoot>
					</table>
					<?php if($comment): ?>
						<h3 class="table-caption"><?php echo __('Order Comments'); ?></h3>
						<table class="data-table">
							<tr>
								<td><?php echo $comment; ?></td>
							</tr>
						</table>
					<?php endif; ?>
					<?php if($histories): ?>
						<h3 class="table-caption"><?php echo __('Order History'); ?></h3>
						<table class="data-table">
							<thead>
								<tr class="first last">
									<th style="width: 15%;"><?php echo __('Date Added'); ?></th>
									<th style="width: 15%;"><?php echo __('Status'); ?></th>
									<th><?php echo __('Comments'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php
									$length = count($histories);
									foreach($histories as $i => $history):
										$class = ($i % 2 == 0 ? 'odd' : 'even');
										$class .= ($i == 0 ? ' first' : '');
										$class .= ($i == ($length - 1) ? ' last' : '');
									?>
										<tr class="border <?php echo $class; ?>">
											<td class="a-left"><?php echo $history['date_added']; ?></td>
											<td class="a-left"><?php echo $history['status']; ?></td>
											<td><?php echo $history['comment']; ?></td>
										</tr>
									<?php endforeach; 
								?>
							</tbody>
						</table>
						<br/>
					<?php endif; ?>
					<?php if (!$module): ?>
						<div class="buttons-set row">
							<div class="col-sm-3">
								<p class="go-back back-link"><a href="<?php echo str_replace('&', '&amp;', $continue); ?>"><small>&laquo; </small><?php echo __("Back"); ?></a></p>
							</div>
							<div class="col-sm-3"></div>
							<div class="col-sm-3"></div>
							<div class="col-sm-3 text-right">
								<?php /*<button type="button" onclick="location='<?php echo $reorder; ?>'" class="btn btn-checkout btn-account"><span><span><?php echo __("Re-Order"); ?></span></span></button> */ ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>