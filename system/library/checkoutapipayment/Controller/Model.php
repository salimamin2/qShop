<?php
abstract class Controller_Model extends Controller
{

    public $_methodInstance;
    public $_request;

    public function __construct($registry)
    {
        parent::__construct();
        $this->_request = $this->request;
        $this->language->load('payment/checkoutapipayment');
        $methodType = $this->config->get('checkoutapipayment_pci_enable');


        switch ($methodType)
        {
            case 'yes':
                $this->setMethodInstance(new Controller_Methods_creditcardpci($registry));
                break;

            default:
                $this->setMethodInstance(new Controller_Methods_creditcard($registry));
                break;
        }
    }

    public function index()
    {
        $this->getMethodInstance()->getIndex();
        $this->data = $this->getMethodInstance()->getData();

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/checkoutapipayment.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/payment/checkoutapipayment.tpl';
        } else {
            $this->template = 'default/template/payment/checkoutapipayment.tpl';
        }

        $this->render();

        // return $this->load->view('default/template/payment/checkoutapi/checkoutapipayment.tpl', $data);
        // return $this->loadFetch('payment/checkoutapipayment');
    }

    public function setMethodInstance($methodInstance)
    {
        $this->_methodInstance = $methodInstance;
    }

    public function getMethodInstance()
    {

        return $this->_methodInstance;
    }

    public function send($params = array())
    {
        return $this->getMethodInstance()->send($params);
    }
}