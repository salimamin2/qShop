<?php

class ControllerPaymentGoogleCheckout extends Controller {

    protected $_currency;
    protected $_shippingCalculated = false;

    public function index() {

        if (!$this->config->get('google_checkout_test')) {
            $this->data['action'] = 'https://checkout.google.com/api/checkout/v2/checkout/Merchant/' . $this->config->get('google_checkout_merchant_id');
        } else {
            $this->data['action'] = 'https://sandbox.google.com/checkout/api/checkout/v2/checkout/Merchant/' . $this->config->get('google_checkout_merchant_id');
        }

        $this->data['callback'] = $this->config->get('google_checkout_merchant_calculation_url');
        // $quote = $this->getQuote();
        $this->load->model('payment/google_checkout');
        $this->load->model('checkout/order');
        $this->load->model('checkout/extension');

        $order = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        /*
          if (empty($order)) {
          return 'Invalid order';
          } */

        // $order = $this->getOrder();

        /* if (!($quote instanceof Mage_Sales_Model_Quote)) {
          Mage::throwException('Invalid quote');
          } */
        $xml = <<<EOT
<checkout-shopping-cart xmlns="http://checkout.google.com/schema/2">
    <shopping-cart>
{$this->_getItemsXml($order)}
{$this->_getMerchantPrivateDataXml($order)}
    </shopping-cart>
    <checkout-flow-support>
{$this->_getMerchantCheckoutFlowSupportXml()}
    </checkout-flow-support>
    <order-processing-support>
        <request-initial-auth-details>true</request-initial-auth-details>
    </order-processing-support>
</checkout-shopping-cart>
EOT;
//d($xml,true);
        //echo "<xmp>".$xml."</xmp>";
        //$result = $this->model_payment_google_checkout->_call($xml);
        //  $this->model_payment_google_checkout->setRedirectUrl($this->config->get{'gcheckout_merchant_calculation_url'});

        if (!$this->config->get('google_checkout_test')) {
            $this->data['button'] = 'http://checkout.google.com/checkout/buttons/checkout.gif?merchant_id=' . $this->config->get('google_checkout_merchant_id') . '&w=180&h=46&style=white&variant=text&loc=en_US';
        } else {
            $this->data['button'] = 'http://sandbox.google.com/checkout/buttons/checkout.gif?merchant_id=' . $this->config->get('google_checkout_merchant_id') . '&w=180&h=46&style=white&variant=text&loc=en_US';
        }
        $key = $this->config->get('google_checkout_merchant_key');

        $blocksize = 64;
        $hashfunc = 'sha1';

        if (strlen($key) > $blocksize) {
            $key = pack('H*', $hashfunc($key));
        }

        $key = str_pad($key, $blocksize, chr(0x00));
        $ipad = str_repeat(chr(0x36), $blocksize);
        $opad = str_repeat(chr(0x5c), $blocksize);
        $hmac = pack('H*', $hashfunc(($key ^ $opad) . pack('H*', $hashfunc(($key ^ $ipad) . $xml))));

        $this->data['cart'] = base64_encode($xml);
        $this->data['signature'] = base64_encode($hmac);

        $this->id = 'payment';

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/google_checkout.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/payment/google_checkout.tpl';
        } else {
            $this->template = 'default/template/payment/google_checkout.tpl';
        }

        $this->render();
    }

    protected function _getItemsXml($order) {
        $this->load->model('account/order');
        $products = $this->model_account_order->getOrderProducts($this->session->data['order_id']);

        $xml = <<<EOT
        <items>

EOT;
        $weightUnit = 'LB';
        if (count($products) > 1) {
            foreach ($products as $item) {
                $taxClass = ($item['tax'] == 0 ? 'none' : $item['tax']);
                //$weight = (float) $item->getWeight();

                $xml .= <<<EOT
	            <item>
	                <merchant-item-id><![CDATA[{$item['model']}]]></merchant-item-id>
	                <item-name><![CDATA[{$item['name']}]]></item-name>
	                <item-description><![CDATA[{$item['name']}]]></item-description>
	                <unit-price currency="{$order['currency']}">{$item['price']}</unit-price>
	                <quantity>{$item['quantity']}</quantity>
	            </item>

EOT;
            }//end of foreach
        }//endif
        else {
            // $amount = number_format($this->getOrder()->getBaseGrandTotal(), 2, '.', '');
            $xml .= <<<EOT
        		<item>
	                <merchant-item-id><![CDATA[zonnix]]></merchant-item-id>
	                <item-name><![CDATA[Zonnix Product]]></item-name>
	                <item-description><![CDATA[Zonnix Products]]></item-description>
	                <unit-price currency="{$order['currency']}">{$order['total']}</unit-price>
	                <quantity>1</quantity>
	            </item>
EOT;
        }
        $xml .= <<<EOT
        </items>
EOT;
        return $xml;
    }

    protected function _getMerchantPrivateDataXml($order) {
        $xml = <<<EOT
            <merchant-private-data>
                <order-id><![CDATA[{$order['order_id']}]]></order-id>
            </merchant-private-data>
EOT;
        return $xml;
    }

    protected function _getMerchantCheckoutFlowSupportXml() {
        //<edit-cart-url><![CDATA[{$this->_getEditCartUrl()}]]></edit-cart-url>
        /* {$this->_getPlatformIdXml()}
          {$this->_getAnalyticsDataXml()} */
        $sUrl = $this->config->get("google_checkout_merchant_calculation_url");
        $xml = <<<EOT
        <merchant-checkout-flow-support>
            <continue-shopping-url><![CDATA[{$sUrl}]]></continue-shopping-url>
        </merchant-checkout-flow-support>
EOT;
        return $xml;
    }

    /**
     * Callback handler for Google Checkout.
     *
     * This handler receives messages from Google about submitted orders and their states.
     *
     * It implements the Notification API and Merchant Calculations API according to
     * http://code.google.com/apis/checkout/developer/
     *
     */
    function callback() {
        // make sure its posted
        if (!strtoupper($this->request->server['REQUEST_METHOD']) == 'POST') {
            $this->response->redirect(HTTPS_SERVER . 'common/home');
        }

        // include the Google libraries
        chdir('system/google');
        require_once('library/googleresponse.php');
        require_once('library/googlemerchantcalculations.php');
        require_once('library/googleresult.php');
        require_once('library/googlerequest.php');

        $merchantId = $this->config->get('google_checkout_merchant_id');
        $merchantKey = $this->config->get('google_checkout_merchant_key');
        $serverType = ($this->config->get('google_checkout_test')) ? 'sandbox' : 'production';
        $currency = $this->currency->getCode();
        $response = new GoogleResponse($merchantId, $merchantKey);
        //$request = new GoogleRequest( $merchantId, $merchantKey, $serverType, $currency );
        // Setup the log files
        define('RESPONSE_HANDLER_ERROR_LOG_FILE', '../logs/googleerror.log');
        define('RESPONSE_HANDLER_LOG_FILE', '../logs/googlemessage.log');
        //$response->SetLogFiles( RESPONSE_HANDLER_ERROR_LOG_FILE, RESPONSE_HANDLER_LOG_FILE, L_ALL);
        $response->SetLogFiles(RESPONSE_HANDLER_ERROR_LOG_FILE, RESPONSE_HANDLER_LOG_FILE);

        // Retrieve the XML sent in the HTTP POST request to the ResponseHandler
        $xmlResponse = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : file_get_contents("php://input");
        if (get_magic_quotes_gpc ()) {
            $xmlResponse = stripslashes($xmlResponse);
        }
        $parsedXML = $response->GetParsedXML($xmlResponse);
        $root = $parsedXML[0];
        $data = $parsedXML[1];

        // Do the HTTP authentication
        $gSerialNumber = $data[$root]['serial-number'];
        $response->SetMerchantAuthentication($merchantId, $merchantKey);
        /* $status = $response->HttpAuthentication();
          if (!$status) {
          error_log("ControllerCheckoutGCheckout::callback authentication failed", 3, DIR_LOGS . "error.txt");
          die('authentication failed');
          } */
        $this->log->write($response);
        $this->load->model('checkout/order');
        // Process the message from Google

        switch ($root) {
            case "request-received": {
                    break;
                }
            case "error": {
                    break;
                }
            case "diagnosis": {
                    break;
                }
            case "checkout-redirect": {
                    break;
                }
            case "new-order-notification": {
                    $iGoogleOrder = $data[$root]['google-order-number']['VALUE'];
                    $iOrder = $data[$root]['shopping-cart']['merchant-private-data']['order-id']['VALUE'];
                    if ($iOrder)
                        $this->model_checkout_order->confirm($iOrder, 1, $iGoogleOrder);
                    else
                        $this->model_checkout_order->confirm(0, 1, $iGoogleOrder);
                    $response->SendAck($gSerialNumber);
                    break;
                }
            case "order-state-change-notification": {
                    $response->SendAck($gSerialNumber);

                    $new_financial_state = $data[$root]['new-financial-order-state']['VALUE'];
                    $new_fulfillment_order = $data[$root]['new-fulfillment-order-state']['VALUE'];
                    $iGoogleOrder = $data[$root]['google-order-number']['VALUE'];
                    switch ($new_financial_state) {

                        case 'REVIEWING': {
                                $this->model_checkout_order->confirm(0, 1, $iGoogleOrder);
                                break;
                            }
                        case 'CHARGEABLE': {
                                //$Grequest->SendProcessOrder($data[$root]['google-order-number']['VALUE']);
                                //$Grequest->SendChargeOrder($data[$root]['google-order-number']['VALUE'],'');
                                break;
                            }
                        case 'CHARGING': {
                                $this->model_checkout_order->confirm(0, 1, $iGoogleOrder);
                                break;
                            }
                        case 'CHARGED': {
                                $this->model_checkout_order->confirm(0, 5, $iGoogleOrder);
                                break;
                            }
                        case 'PAYMENT_DECLINED': {
                                break;
                            }
                        case 'CANCELLED': {
                                $this->model_checkout_order->confirm(0, 7, $iGoogleOrder);
                                break;
                            }
                        case 'CANCELLED_BY_GOOGLE': {
                                //$Grequest->SendBuyerMessage($data[$root]['google-order-number']['VALUE'],
                                //"Sorry, your order is cancelled by Google", true);
                                $this->model_checkout_order->confirm(0, 7, $iGoogleOrder);
                                break;
                            }
                        default:
                            break;
                    }

                    /* switch($new_fulfillment_order) {
                      case 'NEW': {
                      break;
                      }
                      case 'PROCESSING': {
                      break;
                      }
                      case 'DELIVERED': {
                      break;
                      }
                      case 'WILL_NOT_DELIVER': {
                      break;
                      }
                      default:
                      break;
                      } */
                    break;
                }

            default:
                $response->SendBadRequestStatus("Invalid or not supported Message");
                break;
        }

        $this->response->redirect(HTTPS_SERVER . 'checkout/success');
    }

}

?>