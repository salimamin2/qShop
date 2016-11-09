<?php
/* 
 * NOTICE OF LICENSE
 * 
 *  This source file is subject to the Open Software License (OSL 3.0)
 *  that is bundled with this package in the file LICENSE.txt.
 *  It is also available through the world-wide-web at this URL:
 *  http://opensource.org/licenses/osl-3.0.php
 *  If you did not receive a copy of the license and are unable to
 *  obtain it through the world-wide-web, please send an email
 *  to license@q-sols.com so we can send you a copy immediately.
 * 
 * 
 *  @copyright   Copyright (c) 2010 Q-Solutions. (www.q-sols.com)
 *  @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Description of order
 *
 * @author Qasim Shabbir <qasim@q-sols.com>
 */
class Service_Controller_Sale_Order extends Controller implements RestController {
     public function execute(RestServer $server)
    {
        return $server;
    }

    /*
     * Get Latest Order List
     * @param RestServer $server
     * @return RestResponse
     */
    public function getLatestSoldQuantity(RestServer $server){
        $this->load->model('checkout/order');
        $rOrders = $this->model_checkout_order->getLatestOrderToImport();
        
        // Lets say no user was found
        if(count($rOrders) < 1) {
            $server->getResponse()->setError('Not found');
        }
        else{
            $sXML = ArrayToXML::toXML($rOrders,"orders");
            $server->getResponse()->setResponse($sXML);
        }
        return $server;
    }

    /*
     * Confirmed the batch updated on remport
     * @param RestServer $server
     * @return RestResponse
     */
    public function confirmLatestSaleBatch(RestServer $server){
        $this->load->model('checkout/order');
         // If an ID is specified
        $id = $server->getRequest()->getURI(3); // Third part of the URI
        if(!$id){
            $server->getResponse()->setError('Missing required parameters');
            return $server;
        }
        $rOrders = $this->model_checkout_order->getBatch($id);
        // Lets say no user was found
        if(count($rOrders) < 1) {
            $server->getResponse()->setError('Invalid Batch Id');
        }
        else{
            $this->model_checkout_order->confirmBatch($id);
        }
        return $server;
    }
    
}
?>
