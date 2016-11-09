<?php
class ModelShippingUps extends Model {
	function getQuote($address) {
		$this->load->language('shipping/ups');
		if ($this->config->get('ups_status')) {
			//$address = $this->customer->getAddress($this->session->data['shipping_address_id']);

      		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('ups_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

      		if (!$this->config->get('ups_geo_zone_id')) {
        		$status = TRUE;
      		} elseif ($query->num_rows) {
        		$status = TRUE;
      		} else {
        		$status = FALSE;
      		}
		} else {
			$status = FALSE;
		}
		//print $status;
		$method_data = array();

		if ($status) {
			$quote_data = array();
			$this->load->library('ups');

			$access = $this->config->get('ups_accesskey');
			$user = $this->config->get('ups_userId');
			$pass = $this->config->get('ups_password');
			$shipper = $this->config->get('ups_shipper');
			$ups = new ups();
			$ups->setCredentials($access,$user,$pass,$shipper);
			//$address = $this->customer->getAddress($this->session->data['shipping_address_id']);
			$aCustomer['postcode'] = $address['postcode'];
			$this->load->model('localisation/country');
			$country = $this->model_localisation_country->getCountry($address['country_id']);
			$aCustomer['country'] = $country['iso_code_2'];
			$this->load->model('localisation/zone');
			$zone = $this->model_localisation_zone->getZone($address['zone_id']);
			$aCustomer['state'] = $zone['code'];

			$aPickup = array(
                'RDP'    => array("label"=>'Regular Daily Pickup',"code"=>"01"),
                'OCA'    => array("label"=>'On Call Air',"code"=>"07"),
                'OTP'    => array("label"=>'One Time Pickup',"code"=>"06"),
                'LC'     => array("label"=>'Letter Center',"code"=>"19"),
                'CC'     => array("label"=>'Customer Counter',"code"=>"03"),
            );

            $container = array(
                'CP'     => '00', // Customer Packaging
                'ULE'    => '01', // UPS Letter Envelope
                'UT'     => '03', // UPS Tube
                'UEB'    => '21', // UPS Express Box
                'UW25'   => '24', // UPS Worldwide 25 kilo
                'UW10'   => '25', // UPS Worldwide 10 kilo
            );

            $dest = array(
                'RES'    => array("code"=>'01',"label"=>'GNDRES'), // Residential
                'COM'    => array("code"=>'02',"label"=>'GNDCOM'), // Commercial
            );

			$this->load->model('catalog/product');
			$product_data = $this->session->data['cart'];
			$product_ids = array_keys($product_data);
			$iWeight = 0.00;
			foreach ($product_ids as $productId)
			{
				$product = ($this->model_catalog_product->getProduct($productId));
				$iWeight = (float)$iWeight + (float)$product['weight'];
				#print "Weight ".$iWeight;
				$iWeight = (float)$product_data[$productId] * (float)$iWeight;
				#print "Weight * by quantity ".$iWeight;
			}

			//print $post_code;
			if ($this->config->get('ups_1dm')) {

				$cost = $this->ups('1DM');
				$cost = $ups->getRate("14",$iWeight,$aCustomer,$aPickup[$this->config->get('ups_rate')],$container[$this->config->get('ups_packaging')],$dest[$this->config->get('ups_type')]);

				if ($cost) {
      				$quote_data['ups.1dm'] = array(
        				'id'           => 'ups.1dm',
        				'title'        => $this->language->get('text_1dm'),
        				'cost'         => $cost,
        				'tax_class_id' => $this->config->get('ups_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('ups_tax_class_id'), $this->config->get('config_tax')))
      				);
				}
			}
			if ($this->config->get('ups_1da')) {
				$cost = $ups->getRate("01",$iWeight,$aCustomer,$aPickup[$this->config->get('ups_rate')],$container[$this->config->get('ups_packaging')],$dest[$this->config->get('ups_type')]);
				//$cost = $this->ups('1DA');

				if ($cost) {
      				$quote_data['ups.1da'] = array(
        				'id'           => 'ups.1da',
        				'title'        => $this->language->get('text_1da'),
        				'cost'         => $cost,
        				'tax_class_id' => $this->config->get('ups_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('ups_tax_class_id'), $this->config->get('config_tax')))
      				);
				}
			}

			if ($this->config->get('ups_1dp')) {
				$cost = $ups->getRate("13",$iWeight,$aCustomer,$aPickup[$this->config->get('ups_rate')],$container[$this->config->get('ups_packaging')],$dest[$this->config->get('ups_type')]);
				//$cost = $this->ups('1DP');

				if ($cost) {
      				$quote_data['ups.1dp'] = array(
        				'id'           => 'ups.1dp',
        				'title'        => $this->language->get('text_1dp'),
        				'cost'         => $cost,
        				'tax_class_id' => $this->config->get('ups_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('ups_tax_class_id'), $this->config->get('config_tax')))
      				);
				}
			}
			if ($this->config->get('ups_2dm')) {
				$cost = $ups->getRate("59",$iWeight,$aCustomer,$aPickup[$this->config->get('ups_rate')],$container[$this->config->get('ups_packaging')],$dest[$this->config->get('ups_type')]);
				//$cost = $this->ups('2DM');

				if ($cost) {
      				$quote_data['ups.2dm'] = array(
        				'id'           => 'ups.2dm',
        				'title'        => $this->language->get('text_2dm'),
        				'cost'         => $cost,
        				'tax_class_id' => $this->config->get('ups_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('ups_tax_class_id'), $this->config->get('config_tax')))
      				);
				}
			}
			if ($this->config->get('ups_2da')) {
				$cost = $ups->getRate("02",$iWeight,$aCustomer,$aPickup[$this->config->get('ups_rate')],$container[$this->config->get('ups_packaging')],$dest[$this->config->get('ups_type')]);
				//$cost = $this->ups('2DA');

				if ($cost) {
      				$quote_data['ups.2da'] = array(
        				'id'           => 'ups.2da',
        				'title'        => $this->language->get('text_2da'),
        				'cost'         => $cost,
        				'tax_class_id' => $this->config->get('ups_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('ups_tax_class_id'), $this->config->get('config_tax')))
      				);
				}
			}
			if ($this->config->get('ups_3ds')) {
				$cost = $ups->getRate("12",$iWeight,$aCustomer,$aPickup[$this->config->get('ups_rate')],$container[$this->config->get('ups_packaging')],$dest[$this->config->get('ups_type')]);
				//$cost = $this->ups('3DS');

				if ($cost) {
      				$quote_data['ups.3ds'] = array(
        				'id'           => 'ups.3ds',
        				'title'        => $this->language->get('text_3ds'),
        				'cost'         => $cost,
        				'tax_class_id' => $this->config->get('ups_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('ups_tax_class_id'), $this->config->get('config_tax')))
      				);
				}
			}
			if ($this->config->get('ups_gnd')) {
				$cost = $ups->getRate("03",$iWeight,$aCustomer,$aPickup[$this->config->get('ups_rate')],$container[$this->config->get('ups_packaging')],$dest[$this->config->get('ups_type')]);
				//$cost = $this->ups('GND');

				if ($cost) {
      				$quote_data['ups.gnd'] = array(
        				'id'           => 'ups.gnd',
        				'title'        => $this->language->get('text_gnd'),
        				'cost'         => $cost,
        				'tax_class_id' => $this->config->get('ups_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('ups_tax_class_id'), $this->config->get('config_tax')))
      				);
				}
			}
			if ($this->config->get('ups_std')) {
				$cost = $ups->getRate("11",$iWeight,$aCustomer,$aPickup[$this->config->get('ups_rate')],$container[$this->config->get('ups_packaging')],$dest[$this->config->get('ups_type')]);
				//$cost = $this->ups('STD');

				if ($cost) {
      				$quote_data['ups.std'] = array(
        				'id'           => 'ups.std',
        				'title'        => $this->language->get('text_std'),
        				'cost'         => $cost,
        				'tax_class_id' => $this->config->get('ups_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('ups_tax_class_id'), $this->config->get('config_tax')))
      				);
				}
			}
			if ($this->config->get('ups_xpr')) {
				$cost = $ups->getRate("07",$iWeight,$aCustomer,$aPickup[$this->config->get('ups_rate')],$container[$this->config->get('ups_packaging')],$dest[$this->config->get('ups_type')]);
				//$cost = $this->ups('XPR');

				if ($cost) {
      				$quote_data['ups.xpr'] = array(
        				'id'           => 'ups.xpr',
        				'title'        => $this->language->get('text_xpr'),
        				'cost'         => $cost,
        				'tax_class_id' => $this->config->get('ups_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('ups_tax_class_id'), $this->config->get('config_tax')))
      				);
				}
			}
			if ($this->config->get('ups_xdm')) {
				$cost = $ups->getRate("54",$iWeight,$aCustomer,$aPickup[$this->config->get('ups_rate')],$container[$this->config->get('ups_packaging')],$dest[$this->config->get('ups_type')]);
				//$cost = $this->ups('XDM');

				if ($cost) {
      				$quote_data['ups.xdm'] = array(
        				'id'           => 'ups.xdm',
        				'title'        => $this->language->get('text_xdm'),
        				'cost'         => $cost,
        				'tax_class_id' => $this->config->get('ups_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('ups_tax_class_id'), $this->config->get('config_tax')))
      				);
				}
			}
			if ($this->config->get('ups_xpdd')) {
				$cost = $ups->getRate("08",$iWeight,$aCustomer,$aPickup[$this->config->get('ups_rate')],$container[$this->config->get('ups_packaging')],$dest[$this->config->get('ups_type')]);
				//$cost = $this->ups('XPDD');

				if ($cost) {
      				$quote_data['ups.xpdd'] = array(
        				'id'           => 'ups.xpdd',
        				'title'        => $this->language->get('text_xpdd'),
        				'cost'         => $cost,
        				'tax_class_id' => $this->config->get('ups_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('ups_tax_class_id'), $this->config->get('config_tax')))
      				);
				}
			}

			if ($quote_data) {
                            $method_data = array(
                                    'id'         => 'ups',
                                    'title'      => $this->language->get('text_title'),
                                    'quote'      => $quote_data,
                                            'sort_order' => $this->config->get('ups_sort_order'),
                                    'error'      => FALSE
                            );
			}
			else
			{
                            $method_data = array(
        			'id'         => 'ups',
        			'title'      => 'Shipping address not found',
        			'quote'      => '',
					'sort_order' => '',
        			'error'      => TRUE
                            );
			}
		}

		return $method_data;
	}

	function ups($product) {
		switch ($this->config->get('ups_rate')) {
			case 'RDP':
				$rate = 'Regular+Daily+Pickup';
				break;
			case 'OCA':
				$rate = 'On+Call+Air';
				break;
			case 'OTP':
				$rate = 'One+Time+Pickup';
				break;
			case 'LC':
				$rate = 'Letter+Center';
				break;
			case 'CC':
				$rate = 'Customer+Counter';
				break;
		}

		switch ($this->config->get('ups_packaging')) {
			case 'CP':            // Customer Packaging
				$container = '00';
				break;
			case 'ULE':        // UPS Letter Envelope
				$container = '01';
				break;
			case 'UT':            // UPS Tube
				$container = '03';
				break;
			case 'UEB':            // UPS Express Box
				$container = '21';
				break;
			case 'UW25':        // UPS Worldwide 25 kilo
				$container = '24';
				break;
			case 'UW10':        // UPS Worldwide 10 kilo
				$container = '25';
				break;
		}

		switch ($this->config->get('ups_type')) {
			case 'RES':
				$type = '1';
				break;
			case 'COM':
				$type = '0';
				break;
		}

		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry($this->config->get('config_country_id'));

		$url  = 'http://www.ups.com/using/services/rave/qcostcgi.cgi?accept_UPS_license_agreement=yes';
		$url .= '&10_action=4';
		$url .= '&13_product=' . $product;
		$url .= '&14_origCountry=US';
		$url .= '&15_origPostal=08053';
		$url .= '&19_destPostal=08055';
		$url .= '&22_destCountry=US';
		$url .= '&23_weight=2';
		$url .= '&47_rateChart=Regular+Daily+Pickup';
		$url .= '&48_container=00';
		$url .= '&49_residential=1';
/*
$upsAction = "3"; //3 Price a Single Product OR 4 Shop entire UPS product range
$upsProduct = "GND"; //set UPS Product Code See Chart Above
$OriginPostalCode = "08053"; //zip code from where the client will ship from
$DestZipCode = "08055"; //set where product is to be sent
$PackageWeight = "5"; //weight of product
$OrigCountry = "US"; //country where client will ship from
$DestCountry = "US"; //set to country whaere product is to be sent
$RateChart = "Regular+Daily+Pickup"; //set to how customer wants UPS to collect the product
$Container = "00"; //Set to Client Shipping package type
$ResCom = "1"; //See ResCom Table
*/
		$fp = fopen($url, 'r');

		while (!feof($fp)) {
			$result = fgets($fp, 500);

			$result = explode('%', $result);

			$errcode = substr($result[0], -1);

			switch($errcode){
        		case 3:
           			$returnval = $result[8];
               		break;
        		case 4:
           			$returnval = $result[8];
           			break;
        		case 5:
           			$returnval = $result[1];
           			break;
        		case 6:
          			$returnval = $result[1];
           			break;
			}
		}

		fclose($fp);

		if (!$returnval) {
			$returnval = 'error';
		}

		return $returnval;
	}
}
?>