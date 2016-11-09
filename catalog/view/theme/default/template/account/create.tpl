<div id="Content" class="container">
	
	<div class="col-sm-12">
		<div class="account-create page-description">
			
			<div class="text-center">
				<p class="section-title-account">
					<span class="background-account">
						<span class="border-account">My Account</span>
					</span>
				</p>
			</div>
			
			<div class="post-content">
				<div class="text-center intro-text-acount">
					<p>Register now and make the most of My Account. You will be able to:</p>
				</div>

				<div class="benefits">
					<div class="row">
						<div class="col-md-4 text-center">
							<div class="inner-benefits">
								<img src="catalog/view/theme/default/image/img/my-account-img1.png" />
								<p>Receive our exclusive newsletter</p>
						  	</div>
						</div>

						<div class="col-md-4 text-center">
							<div class="inner-benefits">
								<img src="catalog/view/theme/default/image/img/my-account-img2.png" />
								<p>Shop faster</p>
							</div>
						</div>

						<div class="col-md-4 text-center">
							<div class="inner-benefits">
								<img src="catalog/view/theme/default/image/img/my-account-img3.png" />
								<p>Check your orders & returns</p>
							</div>
						</div>
					</div>
				</div>

				<?php echo $this->load('module/hybrid_auth'); ?>
				<ul class="messages">
					<?php if ($error) { ?>
						<li class="error-msg">
							<ul>
								<?php foreach ($error as $err): ?>
									<li><span><?php echo $err; ?></span></li>
								<?php endforeach; ?>
							</ul>
						</li>
					<?php } ?>
				</ul>

				<form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" enctype="multipart/form-data" id="create" class="width-50">

	    			<div class="row">
						<div class="col-md-6">
							<div class="input-box">
		    					<input type="text" name="firstname" id="firstname" value="<?php echo $firstname; ?>" class="input-text required-entry" minlength="3" maxlength="64" placeholder="First Name" />
							</div>
    					</div>
    					<div class="col-md-6">
							<input type="text" name="lastname" id="lastname" value="<?php echo $lastname; ?>" class="input-text required-entry" minlength="3" maxlength="64" placeholder="<?php echo $entry_lastname; ?>" />
						</div>
						<div class="col-md-6">
							<div class="field">
								<div class="input-box">
									<input type="text" name="email" id="email" value="<?php echo $email; ?>" class="input-text validate-email required-entry" placeholder="<?php echo $entry_email; ?>" />
				 				</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="field">
								<div class="input-box">
									<input type="text" name="confirm_email" id="confirm_email" value="<?php echo $email; ?>" class="input-text validate-cemail required-entry" placeholder="<?php echo $entry_confirm_email; ?>" />
				 				</div>
							</div>
						</div>
	    			</div>			
					<div class="row">
						<div class="col-md-6">
    						<div class="input-box">
								<input type="password" name="password" id="fld-password" value="<?php echo $password; ?>" class="input-text required-entry validate-password" minlength="6" maxlength="24" placeholder="<?php echo $entry_password; ?>"/>
    						</div>
						</div>
						<div class="col-md-6">
    						<div class="input-box">
								<input type="password" name="confirm" id="confirm" value="<?php echo $confirm; ?>" class="input-text required-entry validate-cpassword" placeholder="<?php echo $entry_confirm; ?>" equalto="#fld-password" />
    						</div>
						</div>
					</div>
					<div class="row">
						<?php if ($text_agree) { ?>
							<div class="col-md-6">
								<div class="skin-minimal">
									<input type="checkbox" class="validate-select" tabindex="25" name="agree" id="agree" value="1" <?php echo ($agree ? "checked" : ""); ?>/>
									<label for="agree"><?php echo $text_agree; ?></label>
								</div>
							</div>
						<?php } ?>
						<div class="col-md-6">
							<div class="skin-minimal">
								<input type="checkbox" class="checkbox" name="newsletter" id="newsletter" value="1" <?php echo ($newsletter ? "checked" : ""); ?> />
								<label for="newsletter"><?php echo __('I would like to subscribe to the monthly newsletter'); ?></label>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4">
							<div class="buttons-set">
								<button type="submit" title="Submit" class="btn btn-register">
									<span>
										<span><?php echo __('Create Account'); ?></span>
									</span>
								</button>
							</div>
						</div>
						<div class="col-md-12">
							<p class="confirm-text">By clicking “Create Account”, I am indicating that I have read and agree to Clovebuy’s <?php echo $text_privacy; ?>.</p>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>