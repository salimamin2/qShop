<?php
// Heading
$_['heading_title']         = 'HBL';

// Text 
$_['text_payment']          = 'Payment';
$_['text_success']          = 'Success: You have modified HBL account details!';

// Entry
$_['entry_username']        = 'Access Key:<br /><span class="help">Authentication with Secure Acceptance. </span>';
$_['entry_password']        = 'Profile Id:<br /><span class="help">Identifies the profile to use with each transaction.</span>';
$_['entry_test_status']     = 'Test Mode:';
$_['entry_order_status']    = 'Order Status:';
$_['entry_cctypes']         = 'Credit Card Types:<br /><span class="help">Types of Cridit Card accepted</span>';
$_['entry_cvv2_indicator']  = 'CVV2 / AVS Verification:<br /><span class="help">Indicator of existence of method of card verification.</span>';
$_['entry_use_ssl']         = 'SSL Used for Post and Return Pages?';
$_['entry_mode']            = 'Transaction Mode:<br /><span class="help">Select the way in which transaction is executed Production, the transaction is processed in real mode Approved, simulation way, the transaction is accepted Reject, simulation way, the transaction is rejected Random, way of simulation, the transaction is accepted or rejected randomly</span>';
$_['entry_transtype']       = 'Transaction Type<br /><span class="help">The type of transaction: <br />- authorization <br />- sale (combined auth & settle)</span>';
$_['entry_response_path']   = 'Response Path:<br /><span class="help">Route of the file where Payworks returns the values that it obtains from the card processor.</span>';
$_['entry_geo_zone']        = 'Geo Zone:';
$_['entry_status']          = 'Status:';
$_['entry_clientid']        = 'Signature:<br /><span class="help">Merchant-generated Base64 signature. This is generated using the signing method for the access_key field supplied.</span>';

$_['entry_sort_order']      = 'Sort Order:';

// Error
$_['error_permission']      = 'Warning: You do not have permission to modify payment Payworks!';
$_['error_username']        = 'Username Required!';
$_['error_clientid']        = 'Client Id Required!';
$_['error_password']        = 'Password Required!';
?>