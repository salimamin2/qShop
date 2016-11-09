<?php
// Heading
$_['heading_title']         = 'Shipments';
$_['heading_title_details'] = 'Shipment Details';

$_['button_cancel']         = 'Cancel';
$_['button_save']         = 'Save';

// Text
$_['text_success']                            = 'Success: You have modified orders!';
$_['text_deleted']                            = 'Success: You have deleted orders!';
$_['text_shipment']                           = 'Shipment';
$_['text_details']                           = 'Details';
$_['text_order_id']                           = 'Order ID:';
$_['text_invoice_no']                         = 'Invoice No.:';
$_['text_invoice_date']                       = 'Invoice Date:';
$_['text_invoice_id']                         = 'Invoice ID:';
$_['text_store_name']                         = 'Store Name:';
$_['text_store_url']                          = 'Store Url:';
$_['text_customer']                           = 'Customer:';
$_['text_customer_group']                     = 'Customer Group:';
$_['text_email']                              = 'E-Mail:';
$_['text_telephone']                          = 'Telephone:';
$_['text_fax']                                = 'Fax:';
$_['text_shipping_method']                    = 'Shipping Method:';
$_['text_payment_method']                     = 'Payment Method:';
$_['text_total']                              = 'Total:';
$_['text_reward']                             = 'Reward Points:';
$_['text_order_status']                       = 'Order Status:';
$_['text_comment']                            = 'Comment:';
$_['text_affiliate']                          = 'Affiliate:';
$_['text_commission']                         = 'Commission:';
$_['text_ip']                                 = 'IP Address:';
$_['text_forwarded_ip']                       = 'Forwarded IP:';
$_['text_user_agent']                         = 'User Agent:';
$_['text_accept_language']                    = 'Accept Language:';
$_['text_date_added']                         = 'Date Added:';
$_['text_date_modified']                      = 'Date Modified:';
$_['text_firstname']                          = 'First Name:';
$_['text_lastname']                           = 'Last Name:';
$_['text_company']                            = 'Company:';
$_['text_company_id']                         = 'Company ID:';
$_['text_tax_id']                             = 'Tax ID:';
$_['text_address_1']                          = 'Address 1:';
$_['text_address_2']                          = 'Address 2:';
$_['text_postcode']                           = 'Postcode:';
$_['text_city']                               = 'City:';
$_['text_zone']                               = 'Region / State:';
$_['text_zone_code']                          = 'Region / State Code:';
$_['text_country']                            = 'Country:';
$_['text_download']                           = 'Order Downloads';
$_['text_invoice']                            = 'Invoice';
$_['text_to']                                 = 'To';
$_['text_ship_to']                            = 'Ship To (if different address)';
$_['text_missing']                            = 'Missing Orders';
$_['text_missing_orders']                     = 'Missing Orders';
$_['text_default']                            = 'Default';
$_['text_wait']                               = 'Please Wait!';
$_['text_product']                            = 'Add Product(s)';
$_['text_voucher']                            = 'Add Voucher(s)';
$_['text_order']                              = 'Order Details';
$_['text_generate']                           = 'Generate';
$_['text_order_id']                           = 'Order ID';
$_['text_shipment_id']                        = 'Shipment ID';
$_['text_shipment_date']                      = 'Shipment Date';
$_['text_products']                        	  = 'Products';
$_['text_tracking_id']                        = 'Tracking ID';
$_['text_comments']                      	  = 'Comments';
$_['text_action']                        	  = 'Action';
$_['text_product_id']                         = 'Product ID';
$_['text_product_name']                       = 'Product Name';
$_['text_shipment_qty']                       = 'Shipped QTY';





$_['text_reward_add']                         = 'Add Reward Points';
$_['text_reward_added']                       = 'Success: Order Status has been changed and Reward points added to customers!';
$_['text_reward_remove']                      = 'Remove Reward Points';
$_['text_reward_removed']                     = 'Success: Reward points removed!';
$_['text_commission_add']                     = 'Add Commission';
$_['text_commission_added']                   = 'Success: Commission added!';
$_['text_commission_remove']                  = 'Remove Commission';
$_['text_commission_removed']                 = 'Success: Commission removed!';
$_['text_credit_add']                         = 'Add Credit';
$_['text_credit_added']                       = 'Success: Account credit added!';
$_['text_credit_remove']                      = 'Remove Credit';
$_['text_credit_removed']                     = 'Success: Account credit removed!';
$_['text_upload']                             = 'Your file was successfully uploaded!';
$_['text_country_match']                      = 'Country Match:<br /><span class="help">Whether country of IP address matches billing address country (mismatch = higher risk).</span>';
$_['text_country_code']                       = 'Country Code:<br /><span class="help">Country Code of the IP address.</span>';
$_['text_high_risk_country']                  = 'High Risk Country:<br /><span class="help">Whether IP address or billing address country is in Ghana, Nigeria, or Vietnam.</span>';
$_['text_distance']                           = 'Distance:<br /><span class="help">Distance from IP address to Billing Location in kilometers (large distance = higher risk).</span>';
$_['text_free_mail']                          = 'Free Mail:<br /><span class="help">Whether e-mail is from free e-mail provider (free e-mail = higher risk).</span>';
$_['text_carder_email']                       = 'Carder Email:<br /><span class="help">Whether e-mail is in database of high risk e-mails.</span>';
$_['text_ship_forward']                       = 'Shipping Forward:<br /><span class="help">Whether shipping address is in database of known mail drops.</span>';
$_['text_city_postal_match']                  = 'City Postal Match:<br /><span class="help">Whether billing city and state match zipcode. Currently available for US addresses only, returns empty string outside the US.</span>';
$_['text_ship_city_postal_match']             = 'Shipping City Postal Match:<br /><span class="help">Whether shipping city and state match zipcode. Currently available for US addresses only, returns empty string outside the US.</span>';
$_['text_score']                              = 'Score:<br /><span class="help">Overall fraud score based on outputs listed above. This is the original fraud score, and is based on a simple formula. It has been replaced with risk score (see below), but is kept for backwards compatibility.</span>';
$_['text_explanation']                        = 'Explanation:<br /><span class="help">A brief explanation of the score, detailing what factors contributed to it, according to our formula. Please note this corresponds to the score, not the riskScore.</span>';
$_['text_risk_score']                         = 'Risk Score:<br /><span class="help">New fraud score representing the estimated probability that the order is fraud, based off of analysis of past minFraud transactions. Requires an upgrade for clients who signed up before February 2007.</span>';
$_['text_queries_remaining']                  = 'Queries Remaining:<br /><span class="help">Number of queries remaining in your account, can be used to alert you when you may need to add more queries to your account.</span>';
$_['text_maxmind_id']                         = 'Maxmind ID:<br /><span class="help">Unique identifier, used to reference transactions when reporting fraudulent activity back to MaxMind. This reporting will help MaxMind improve its service to you and will enable a planned feature to customize the fraud scoring formula based on your chargeback history.</span>';
$_['text_error']                              = 'Error:<br /><span class="help">Returns an error string with a warning message or a reason why the request failed.</span>';


// Column
$_['column_order']          = 'Order ID';
$_['column_name']           = 'Customer Name';
$_['column_status']         = 'Status';
$_['column_date_added']     = 'Date Added';
$_['column_total']          = 'Total';
$_['column_image']        = 'Image';
$_['column_product']        = 'Product';
$_['column_model']          = 'Model';
$_['column_quantity']       = 'Quantity';
$_['column_price']          = 'Unit Price';
$_['column_download']       = 'Download Name';
$_['column_filename']       = 'Filename';
$_['column_remaining']      = 'Remaining';
$_['column_notify']         = 'Customer Notified';
$_['column_notify_manufacturer']         = 'Designer Notified';
$_['column_comment']        = 'Comment';
$_['column_action']         = 'Action';

// Entry 
$_['entry_order_id']        = 'Order ID:';
$_['entry_invoice_id']      = 'Invoice ID:';
$_['entry_customer']        = 'Customer:';
$_['entry_firstname']       = 'First Name:';
$_['entry_lastname']        = 'Last Name:';
$_['entry_customer_group']  = 'Customer Group:';
$_['entry_email']           = 'E-Mail:';
$_['entry_ip']              = 'IP Address:';
$_['entry_telephone']       = 'Telephone:';
$_['entry_fax']             = 'Fax:';
$_['entry_store_name']      = 'Store Name:';
$_['entry_store_url']       = 'Store Url:';
$_['entry_date_added']      = 'Date Added:';
$_['entry_shipping_method'] = 'Shipping Method:';
$_['entry_payment_method']  = 'Payment Method:';
$_['entry_total']           = 'Original Order Total:';
$_['entry_order_status']    = 'Order Status:';
$_['entry_comment']         = 'Comment:';
$_['entry_company']         = 'Company:';
$_['entry_address_1']       = 'Address 1:';
$_['entry_address_2']       = 'Address 2:';
$_['entry_postcode']        = 'Post Code:';
$_['entry_city']            = 'City:';
$_['entry_zone']            = 'Region / State:';
$_['entry_zone_code']       = 'Region / State Code:';
$_['entry_country']         = 'Country:';
$_['entry_status']          = 'Order Status:';
$_['entry_notify']          = 'Notify Customer:';
$_['entry_notify_manufacturer']          = 'Notify Designer:';
$_['entry_append']          = 'Append Comments:';
$_['entry_add_product']     = 'Add Product(s):';
$_['entry_reward']                            = 'Reward:';
$_['entry_order_ship']                            = 'Order Shipment:';

// Error
$_['error_warning']                           = 'Warning: Please check the form carefully for errors!';
$_['error_permission']      = 'Warning: You do not have permission to modify orders!';
$_['error_action']                            = 'Warning: Could not complete this action!';
?>