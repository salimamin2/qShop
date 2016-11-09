<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of product
 *
 * @author qasim
 */
class Service_Controller_Catalog_Product extends Controller implements RestController {

    public function execute(RestServer $server)
    {
            return $server;

    }
    /*
     * Get Complete Product List
     * @param RestServer $server
     * @return RestResponse 
     */
    public function listProduct(RestServer $server)
    {
        // If an ID is specified
        $code = $server->getRequest()->getURI(2); // Second part of the URI
        $this->load->model('catalog/product');
        if(!$code){
            $rProduct = $this->model_catalog_product->getProducts();
            if($rProduct)
                foreach($rProduct as &$aProduct){
                   $aProduct['inventory']=$this->model_catalog_product->getProductDetails($aProduct['product_id']);
                }
        }else{
            $rProduct = $this->model_catalog_product->getProductByCode($code);
            if($rProduct)
                $rProduct['inventory']=$this->model_catalog_product->getProductDetails($rProduct['product_id']);
        }
        // Lets say no user was found
        if(!count($rProduct)) {
             $server->getResponse()->setError("Product not found");
        } else {
            $sXML = ArrayToXML::toXML($rProduct,"products");
            
            $server->getResponse()->setResponse($sXML);
        }
        return $server;
        
    }
    public function updateQuantity(RestServer $server){
        $action = $server->getRequest()->getURI(2); // Second part of the URI
        $code = $server->getRequest()->getURI(3); // Third part of the URI
        $this->load->model('catalog/product');
        $rProduct = $this->model_catalog_product->getProductByCode($code);
        
        // Lets say no user was found
        if(count($rProduct) < 1) {
            $server->getResponse()->setError("Product not found");
        }
        else{
            $aData = $server->getRequest()->getData();
            if(!isset($aData['quantity'])){
                $server->getResponse()->setError('In-valid parameters');
                return $server;
            }
            $rDetail = $this->model_catalog_product->getDetailByCode($code);
            if(!$rDetail || !count($rDetail)){
                $server->getResponse()->setError('In-valid parameters');
                return $server;
            }
            $detail_quantity = $aData['quantity'];
            switch($action){
                case 'update':
                    $aData['quantity'] = ($rProduct['quantity']-$rDetail['quantity']) + $aData['quantity'];
                    break;
                case 'increase':
                    $aData['quantity'] = $rProduct['quantity'] + $aData['quantity'];
                    $detail_quantity += $rDetail['quantity'];
                    break;
                case 'decrease':
                    $aData['quantity'] = $rProduct['quantity'] - $aData['quantity'];
                    $detail_quantity = $rDetail['quantity']-$detail_quantity;
                    break;
                default:
                    $server->getResponse()->setError('In-valid action');
                    return $server;
            }
            $this->model_catalog_product->updateDetail(array('quantity'=>$detail_quantity),$rDetail['product_detail_id']);
            unset($aData['code']);
            $this->model_catalog_product->update($aData,$rProduct['product_id']);
        }
        return $server;

    }

    public function updateStatus(RestServer $server){
        $action = $server->getRequest()->getURI(2); // Second part of the URI
        $code = $server->getRequest()->getURI(3); // Third part of the URI
        $this->load->model('catalog/product');
        $rProduct = $this->model_catalog_product->getProductByCode($code,false);

        // Lets say no user was found
        if(count($rProduct) < 1) {
            $server->getResponse()->setError("Product not found");
        }
        else{
            switch($action){
                case 'enable':
                    $aData['status'] =1;
                    break;
                case 'disable':
                    $aData['status'] =0;
                    break;
                default:
                    $server->getResponse()->setError('In-valid action');
                    return $server;
            }
            $this->model_catalog_product->update($aData,$rProduct['product_id']);

        }
        return $server;

    }
}
?>
