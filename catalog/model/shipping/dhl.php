<?php

class ModelShippingDHL extends Model {

    function getQuote($address) {
        $this->load->language('shipping/dhl');

        if ($this->config->get('dhl_status') && $this->config->get('dhl_country') != $address['iso_code_2']) {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int) $this->config->get('dhl_geo_zone_id') . "' AND country_id = '" . (int) $address['country_id'] . "' AND (zone_id = '" . (int) $address['zone_id'] . "' OR zone_id = '0')");

            if (!$this->config->get('dhl_geo_zone_id')) {
                $status = TRUE;
            } elseif ($query->num_rows) {
                $status = TRUE;
            } else {
                $status = FALSE;
            }
        } else {
            $status = FALSE;
        }

        $method_data = array();

        if ($status) {
            $weight = $this->weight->convert($this->cart->getWeight(), $this->config->get('config_weight_class'), $this->config->get('dhl_weight_class'));
            $weight = ($weight < 0.1 ? 0.1 : $weight);
            $weight = round($weight);
            
            $qty = $this->cart->getQty();
            $qty = ($qty < 1 ? 1 : $qty);

            $xml = '<?xml version="1.0" encoding="UTF-8"?>';
            $xml .= '<p:DCTRequest xmlns:p="http://www.dhl.com" xmlns:p1="http://www.dhl.com/datatypes" xmlns:p2="http://www.dhl.com/DCTRequestdatatypes" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.dhl.com DCT-req.xsd ">';
            $xml .= '<GetQuote>';
            $xml .= '   <Request>';
            $xml .= '       <ServiceHeader>';
            //$xml .= '           <MessageTime>2002-08-20T11:28:56.000-08:00</MessageTime>';
            //$xml .= '           <MessageReference>1234567890123456789012345678901</MessageReference>';
            $xml .= '           <SiteID>'.$this->config->get('dhl_key').'</SiteID>';
            $xml .= '           <Password>'.$this->config->get('dhl_password').'</Password>';
            $xml .= '       </ServiceHeader>';
            $xml .= '   </Request>';
            $xml .= '   <From>';
            $xml .= '       <CountryCode>'.$this->config->get('dhl_country').'</CountryCode>';
            $xml .= '       <Postalcode>'.$this->config->get('dhl_postcode').'</Postalcode>';
            $xml .= '       <City>'.$this->config->get('dhl_city').'</City>';
            $xml .= '   </From>';
            $xml .= '   <BkgDetails>';
            $xml .= '       <PaymentCountryCode>PK</PaymentCountryCode>';
            $xml .= '       <Date>'.date('Y-m-d').'</Date>';
            $xml .= '       <ReadyTime>PT01H21M</ReadyTime>';
            $xml .= '       <ReadyTimeGMTOffset>+05:00</ReadyTimeGMTOffset>';
            $xml .= '       <DimensionUnit>IN</DimensionUnit>';
            $xml .= '       <WeightUnit>LB</WeightUnit>';
            $xml .= '       <NumberOfPieces>'.$qty.'</NumberOfPieces>';
            $xml .= '       <ShipmentWeight>'.$weight.'</ShipmentWeight>';
            //$xml .= '       <Pieces>';
            //$xml .= '           <Piece>';
            //$xml .= '               <PieceID>1</PieceID>';
            //$xml .= '               <Height>10</Height>';
            //$xml .= '               <Depth>20</Depth>';
            //$xml .= '               <Width>30</Width>';
            //$xml .= '               <Weight>10</Weight>';
            //$xml .= '           </Piece>';
            //$xml .= '       </Pieces>';
            $xml .= '       <PaymentAccountNumber>'.$this->config->get('dhl_shipper_account_number').'</PaymentAccountNumber>';
            $xml .= '       <IsDutiable>Y</IsDutiable>';
            $xml .= '   </BkgDetails>';
            $xml .= '   <To>';
//            $xml .= '       <CountryCode>MY</CountryCode>';
//            $xml .= '       <Postalcode>50000</Postalcode>';
//            $xml .= '       <City>Kuala Lumpur</City>';
            $xml .= '       <CountryCode>' . $address['iso_code_2'] . '</CountryCode>';
            $xml .= '       <Postalcode>' . $address['postcode'] . '</Postalcode>';
            $xml .= '       <City>' . $address['city'] . '</City>';
            $xml .= '   </To>';
            $xml .= '   <Dutiable>';
            $xml .= '       <DeclaredCurrency>USD</DeclaredCurrency> ';
            $xml .= '       <DeclaredValue>1</DeclaredValue> ';
            $xml .= '   </Dutiable>';
            $xml .= '</GetQuote>';
            $xml .= '</p:DCTRequest>';

//            $log = "'" . $xml . "'";
//            $handle = fopen('Request-' . date('YmdHis').'.xml', 'a+');
//            fwrite($handle, $log . "\n\n");
//            fclose($handle);
//
            if (!$this->config->get('dhl_test')) {
                $url = 'https://xmlpi-ea.dhl.com/XMLShippingServlet';
            } else {
                $url = 'https://xmlpitest-ea.dhl.com/XMLShippingServlet';
            }

            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);

            $result = curl_exec($ch);
            curl_close($ch);

            $title = $this->language->get('text_title');
            $description = $this->language->get('text_description');

            $error = '';
            $error_msg = '';
            $quote_data = array();
            if ($result) {
                $response = $this->xml2array($result);
                if (isset($response['res:DCTResponse']['GetQuoteResponse']['BkgDetails'])) {
                    $aResponse = $response['res:DCTResponse']['GetQuoteResponse']['BkgDetails']['QtdShp'];
                    if(isset($aResponse['CurrencyCode']) ){
                        $currency_code = $aResponse['CurrencyCode'];
                        $cost = $aResponse['ShippingCharge'];
                        $cCost = $this->currency->convert($cost, $currency_code, $this->config->get('config_currency'));
                        $tax = $this->tax->calculate($cCost, $this->config->get('dhl_tax_class_id'), $this->config->get('config_tax'));

                        if($aResponse['GlobalProductCode'] == 'P'){
                            $quote_data[$aResponse['GlobalProductCode']] = array(
                                'id' => 'dhl.' . $aResponse['GlobalProductCode'],
                                'title' => $title,//$aResponse['LocalProductName'],
                                'cost' => $cCost,
                                'tax_class_id' => $this->config->get('dhl_tax_class_id'),
                                'text' => $this->currency->format($this->currency->convert($tax, $this->config->get('config_currency'), $this->currency->getCode()), $this->currency->getCode(),1)
                            );
                        }
                    }else {
                        foreach($aResponse as $rate) {
                            if($rate['GlobalProductCode'] == 'P'){
                                $currency_code = $rate['CurrencyCode'];
                                $cost = $rate['ShippingCharge'];
                                $cCost = $this->currency->convert($cost, $currency_code, $this->config->get('config_currency'));
                                $tax = $this->tax->calculate($cCost, $this->config->get('dhl_tax_class_id'), $this->config->get('config_tax'));

                                $quote_data[$rate['GlobalProductCode']] = array(
                                    'id' => 'dhl.' . $rate['GlobalProductCode'],
                                    'title' => $title,//$rate['LocalProductName'],
                                    'cost' => $cCost,
                                    'tax_class_id' => $this->config->get('dhl_tax_class_id'),
                                    'text' => $this->currency->format($this->currency->convert($tax, $this->config->get('config_currency'), $this->currency->getCode()), $this->currency->getCode(),1)
                                );
                            }

    //                        d(array($cCost, $this->config->get('dhl_tax_class_id'), $this->config->get('config_tax'),$tax,$this->currency->format($tax),$quote_data[$rate['GlobalProductCode']]));
    //                        d(array($cost,$currency_code, $this->currency->getCode(),$cCost,$quote_data[$rate['GlobalProductCode']]));
                        }
                    }
                }
            }

//            d(array($address,$xml,$result,$response,$quote_data),true);

            if ($this->config->get('dhl_display_weight')) {
                $title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class')) . ')';
            }

            if (!$quote_data) {
                if (isset($response['res:DCTResponse']['GetQuoteResponse']['Note']['Condition'])) {
                    $error_msg = $response['res:DCTResponse']['GetQuoteResponse']['Note']['Condition']['ConditionData'];
                } else {
                    $error_msg = "No shipment found for the current address.";
                }
                    
//                d($response);
                
            }
            
            $strDate = date('YmdHis');
            $handle = fopen(DIR_LOGS . 'DHL-' . $strDate .'-Request.xml', 'a+');
            fwrite($handle, $xml . "\n\n");
            fclose($handle);

            $handle = fopen(DIR_LOGS . 'DHL-' . $strDate .'-Response.xml', 'a+');
            fwrite($handle, $result . "\n\n");
            fclose($handle);

            $method_data = array(
                'id' => 'dhl',
                'title' => $title,
                'description' => $description,
                'quote' => $quote_data,
                'sort_order' => $this->config->get('dhl_sort_order'),
                'error' => $error_msg
            );
        }

        return $method_data;
    }

    function xml2array($contents, $get_attributes=1, $priority = 'tag') {

        if (!$contents)
            return array();

        if (!function_exists('xml_parser_create')) {
            //print "'xml_parser_create()' function not found!";
            return array();
        }

        //Get the XML parser of PHP - PHP must have this module for the parser to work
        $parser = xml_parser_create('');
        xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8"); # http://minutillo.com/steve/weblog/2004/6/17/php-xml-and-character-encodings-a-tale-of-sadness-rage-and-data-loss
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($parser, trim($contents), $xml_values);
        xml_parser_free($parser);

        if (!$xml_values)
            return; //Hmm...
            
//Initializations
        $xml_array = array();
        $parents = array();
        $opened_tags = array();
        $arr = array();

        $current = &$xml_array; //Refference
        //Go through the tags.
        $repeated_tag_index = array(); //Multiple tags with same name will be turned into an array
        foreach ($xml_values as $data) {
            unset($attributes, $value); //Remove existing values, or there will be trouble
            //This command will extract these variables into the foreach scope
            // tag(string), type(string), level(int), attributes(array).
            extract($data); //We could use the array by itself, but this cooler.

            $result = array();
            $attributes_data = array();

            if (isset($value)) {
                if ($priority == 'tag')
                    $result = $value;
                else
                    $result['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode
            }

            //Set the attributes too.
            if (isset($attributes) and $get_attributes) {
                foreach ($attributes as $attr => $val) {
                    if ($priority == 'tag')
                        $attributes_data[$attr] = $val;
                    else
                        $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
                }
            }

            //See tag status and do the needed.
            if ($type == "open") {//The starting of the tag '<tag>'
                $parent[$level - 1] = &$current;
                if (!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag
                    $current[$tag] = $result;
                    if ($attributes_data)
                        $current[$tag . '_attr'] = $attributes_data;
                    $repeated_tag_index[$tag . '_' . $level] = 1;

                    $current = &$current[$tag];
                } else { //There was another element with the same tag name
                    if (isset($current[$tag][0])) {//If there is a 0th element it is already an array
                        $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
                        $repeated_tag_index[$tag . '_' . $level]++;
                    } else {//This section will make the value an array if multiple tags with the same name appear together
                        $current[$tag] = array($current[$tag], $result); //This will combine the existing item and the new item together to make an array
                        $repeated_tag_index[$tag . '_' . $level] = 2;

                        if (isset($current[$tag . '_attr'])) { //The attribute of the last(0th) tag must be moved as well
                            $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                            unset($current[$tag . '_attr']);
                        }
                    }
                    $last_item_index = $repeated_tag_index[$tag . '_' . $level] - 1;
                    $current = &$current[$tag][$last_item_index];
                }
            } elseif ($type == "complete") { //Tags that ends in 1 line '<tag />'
                //See if the key is already taken.
                if (!isset($current[$tag])) { //New Key
                    $current[$tag] = $result;
                    $repeated_tag_index[$tag . '_' . $level] = 1;
                    if ($priority == 'tag' and $attributes_data)
                        $current[$tag . '_attr'] = $attributes_data;
                } else { //If taken, put all things inside a list(array)
                    if (isset($current[$tag][0]) and is_array($current[$tag])) {//If it is already an array...
                        // ...push the new element into that array.
                        $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;

                        if ($priority == 'tag' and $get_attributes and $attributes_data) {
                            $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                        }
                        $repeated_tag_index[$tag . '_' . $level]++;
                    } else { //If it is not an array...
                        $current[$tag] = array($current[$tag], $result); //...Make it an array using using the existing value and the new value
                        $repeated_tag_index[$tag . '_' . $level] = 1;
                        if ($priority == 'tag' and $get_attributes) {
                            if (isset($current[$tag . '_attr'])) { //The attribute of the last(0th) tag must be moved as well
                                $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                                unset($current[$tag . '_attr']);
                            }

                            if ($attributes_data) {
                                $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                            }
                        }
                        $repeated_tag_index[$tag . '_' . $level]++; //0 and 1 index is already taken
                    }
                }
            } elseif ($type == 'close') { //End of tag '</tag>'
                $current = &$parent[$level - 1];
            }
        }

        return($xml_array);
    }

}

?>