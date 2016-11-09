<?php
 class ModelPaymentGoogleCheckout extends Model
{
    /*public function log($text, $nl=true)
    {
        error_log(print_r($text,1).($nl?"\n":''), 3, Mage::getBaseDir('log').DS.'callback.log');
        return $this;
    }*/

    public function getMethod($address) {
        $this->load->language('payment/google_checkout');

        if ($this->config->get('google_checkout_status')) {
            /* $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('pp_express_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

              if (!$this->config->get('pp_express_geo_zone_id')) {
              $status = TRUE;
              } elseif ($query->num_rows) {
              $status = TRUE;
              } else {
              $status = FALSE;
              } */
            $status = TRUE;
        } else {
            $status = FALSE;
        }

        $method_data = array();

        if ($status) {
            $method_data = array(
                'id' => 'google_checkout',
                'title' => $this->language->get('text_title'),
                'sort_order' => ($this->config->get('google_checkout_sort_order')?$this->config->get('google_checkout_sort_order'):0)
            );
        }

        return $method_data;
    }


    public function getMerchantId()
    {
        return $this->config->get('google_checkout_merchant_id');
    }

    public function getMerchantKey()
    {
        return $this->config->get('google_checkout_merchant_key');
    }

    public function getServerType()
    {

        return $this->config->get('google_checkout_test')?"sandbox":"";
    }

    public function getCurrency()
    {
        return $this->currency->getCode();
    }

    /**
     * Google Checkout Request instance
     *
     * @return GoogleRequest
     */
    public function getGRequest()
    {
        if (!$this->hasData('g_request')) {
            $this->setData('g_request', new GoogleRequest(
                $this->getMerchantId(),
                $this->getMerchantKey(),
                $this->getServerType(),
                $this->getCurrency()
            ));
        }
        return $this->getData('g_request');
    }

    /**
     * Google Checkout Response instance
     *
     * @return GoogleResponse
     */
    public function getGResponse()
    {
        if (!$this->hasData('g_response')) {
            $this->setData('g_response', new GoogleResponse(
                $this->getMerchantId(),
                $this->getMerchantKey()
            ));

        }
        return $this->getData('g_response');
    }

    protected function _getBaseApiUrl()
    {
        $url = 'https://';
        if ($this->getServerType()=='sandbox') {
            $url .= 'sandbox.google.com/checkout/api/checkout/v2/checkout/Merchant/' . $this->config->get('google_checkout_merchant_id');
        } else {
            $url .= 'checkout.google.com/api/checkout/v2/checkout/Merchant/' . $this->config->get('google_checkout_merchant_id');
        }

        return $url;
    }

    public function _call($xml)
    {
        $auth = 'Basic '.base64_encode($this->getMerchantId().':'.$this->getMerchantKey());

        $headers = array(
            'Authorization: '.$auth,
            'Content-Type: application/xml;charset=UTF-8',
            'Accept: application/xml;charset=UTF-8',
        );

        $url = $this->_getBaseApiUrl();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\r\n".$xml;

        /*if (Mage::getStoreConfig('payment/qs_googlecheckout_shared/debug_flag', $this->getStoreId())) {
            $debug = Mage::getModel('qs_googlecheckout/api_debug');
            $debug->setDir('out')->setUrl($url)->setRequestBody($xml)->save();
        }*/

       // $http = new Varien_Http_Adapter_Curl();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        $response = curl_exec ($ch);
       /* $http->write('POST', $url, '1.1', $headers, $xml);
        $response = $http->read();*/
        $response = preg_split('/^\r?$/m', $response, 2);
        $response = trim($response[1]);
/*
        if (!empty($debug)) {
            $debug->setResponseBody($response)->save();
        }*/

        $result = @simplexml_load_string($response);
        if (!$result) {
            $result = simplexml_load_string('<error><error-message>Invalid response from Google Checkout server</error-message></error>');
        }
        if ($result->getName()=='error') {
            $this->log->write(printf('Google Checkout: %s', (string)$result->{'error-message'}));
            $this->log->write((array)$result->{'warning-messages'});
        } else {
            
        }

        //$this->setResult($result);

        return $result;
    }
/*
    protected function _getCallbackUrl()
    {
        return Mage::getUrl('qs_googlecheckout/api', array('_forced_secure'=>Mage::getStoreConfig('payment/qs_googlecheckout_shared/use_ssl', $this->getStoreId())));
    }
 *
 */
}

?>