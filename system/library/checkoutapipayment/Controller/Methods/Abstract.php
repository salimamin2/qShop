<?php
abstract class Controller_Methods_Abstract extends Controller
{
    const AUTOCAPUTURE_CAPTURE = 'y';
    const AUTOCAPUTURE_AUTH = 'n';

    public function index()
    {
        $this->language->load('payment/checkoutapipayment');
        // $this->data = $this->getData();

        // if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/checkoutapi/checkoutapipayment.tpl')) {

        //     return $this->load->view ($this->config->get('config_template') . '/template/payment/checkoutapi/checkoutapipayment.tpl',$data);

        // } else {
        //     return $this->load->view ( 'default/template/payment/checkoutapi/checkoutapipayment.tpl',$data);
        // }
        return $this->loadFetch('payment/checkoutapipayment');

    }

    public function  getIndex()
    {
        $this->index();
    }

    public function setMethodInstance($methodInstance)
    {
        $this->_methodInstance = $methodInstance;
    }

    public function getMethodInstance()
    {
        return $this->_methodInstance;
    }

    public function send($params)
    {
        return $this->_placeorder($params);
    }

    protected function _placeorder($params)
    {
        $this->load->model('checkout/order');
        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

        //building charge
        $respondCharge = (array) $this->_createCharge($order_info,$params);
        $this->log->write_payment($respondCharge,'checkoutapipaymentresponse');
        
        if(!isset($respondCharge['errorCode']) && isset($respondCharge['id'])) {

            if($respondCharge['responseCode'] == 10000) {
                $json['message'] = 'Your transaction has been successfully authorized with transaction id : '.$respondCharge['id'];
                $json['continue'] = makeUrl('checkout/success', array('order_id='.$this->session->data['order_id'],'customer_id=' . $this->session->data['hdn_customer_id']), true);
            }
            else {
                $json['error'] = $respondCharge['responseMessage'];
            }
        } else  {
            $json['error'] = implode(',',$respondCharge['errors']);
        }
        return $json;
        // $this->response->setOutput(json_encode($json));
    }
    protected function _createCharge($order_info,$params)
    {
        $secretKey = $this->config->get('checkoutapipayment_secret_key');
        $amountCents = (int) $order_info['total'] * 100;
        $mode = $this->config->get('checkoutapipayment_test_mode');
        $url = '';
        if($mode == 'sandbox') {
            $url = 'https://sandbox.checkout.com/api2/v2/charges/token';
        }
        $config = array (
            'email'              =>  $order_info['email'],
            'description'        =>  "Order number::" . $order_info['order_id'],
            'value'              =>  $amountCents,
            'currency'           =>  $this->currency->getCode(),
            'trackId'            => $order_info['order_id'],
            'cardToken'          =>  $params['id']
        );
        $this->log->write_payment($config,'checkoutapipaymentrequest');
        $headers = array();
        $headers[] = "Authorization: " . $secretKey;
        $headers[] = "Content-Type:application/json;charset=UTF-8";
        $ch = curl_init($url);
        curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
        curl_setopt($ch,CURLOPT_POST,true);
        curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($config));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        return json_decode($response);
    }

    protected function _captureConfig()
    {
        $to_return['postedParam'] = array (
            'autoCapture' => AUTOCAPUTURE_CAPTURE,
            'autoCapTime' => $this->config->get('checkoutapipayment_autocapture_delay')
        );

        return $to_return;
    }

    protected function _authorizeConfig()
    {
        $to_return['postedParam'] = array (
            'autoCapture' => AUTOCAPUTURE_AUTH,
            'autoCapTime' => 0
        );

        return $to_return;
    }

    protected function _getCharge($config)
    {
        // $Api = CheckoutApi_Api::getApi(array('mode'=> $this->config->get('checkoutapipayment_test_mode')));

        // return $Api->createCharge($config);
    }
}