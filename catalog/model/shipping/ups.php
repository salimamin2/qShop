<?php

class ModelShippingUps extends Model {

    function getQuote($address) {
        $this->load->language('shipping/ups');

        if ($this->config->get('ups_status')) {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int) $this->config->get('ups_geo_zone_id') . "' AND country_id = '" . (int) $address['country_id'] . "' AND (zone_id = '" . (int) $address['zone_id'] . "' OR zone_id = '0')");

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

        $method_data = array();

        if ($status) {
            $weight = $this->weight->convert($this->cart->getWeight(), $this->config->get('config_weight_class'), $this->config->get('ups_weight_class'));

            $weight = ($weight < 0.1 ? 0.1 : $weight);

            $service_code = array(
                // US Origin
                'US' => array(
                    '01' => $this->language->get('text_us_origin_01'),
                    '02' => $this->language->get('text_us_origin_02'),
                    '03' => $this->language->get('text_us_origin_03'),
                    '07' => $this->language->get('text_us_origin_07'),
                    '08' => $this->language->get('text_us_origin_08'),
                    '11' => $this->language->get('text_us_origin_11'),
                    '12' => $this->language->get('text_us_origin_12'),
                    '13' => $this->language->get('text_us_origin_13'),
                    '14' => $this->language->get('text_us_origin_14'),
                    '54' => $this->language->get('text_us_origin_54'),
                    '59' => $this->language->get('text_us_origin_59'),
                    '65' => $this->language->get('text_us_origin_65')
                ),
                // Canada Origin
                'CA' => array(
                    '01' => $this->language->get('text_ca_origin_01'),
                    '02' => $this->language->get('text_ca_origin_02'),
                    '07' => $this->language->get('text_ca_origin_07'),
                    '08' => $this->language->get('text_ca_origin_08'),
                    '11' => $this->language->get('text_ca_origin_11'),
                    '12' => $this->language->get('text_ca_origin_12'),
                    '13' => $this->language->get('text_ca_origin_13'),
                    '14' => $this->language->get('text_ca_origin_14'),
                    '54' => $this->language->get('text_ca_origin_54'),
                    '65' => $this->language->get('text_ca_origin_65')
                ),
                // European Union Origin
                'EU' => array(
                    '07' => $this->language->get('text_eu_origin_07'),
                    '08' => $this->language->get('text_eu_origin_08'),
                    '11' => $this->language->get('text_eu_origin_11'),
                    '54' => $this->language->get('text_eu_origin_54'),
                    '65' => $this->language->get('text_eu_origin_65'),
                    // next five services Poland domestic only
                    '82' => $this->language->get('text_eu_origin_82'),
                    '83' => $this->language->get('text_eu_origin_83'),
                    '84' => $this->language->get('text_eu_origin_84'),
                    '85' => $this->language->get('text_eu_origin_85'),
                    '86' => $this->language->get('text_eu_origin_86')
                ),
                // Puerto Rico Origin
                'PR' => array(
                    '01' => $this->language->get('text_pr_origin_01'),
                    '02' => $this->language->get('text_pr_origin_02'),
                    '03' => $this->language->get('text_pr_origin_03'),
                    '07' => $this->language->get('text_pr_origin_07'),
                    '08' => $this->language->get('text_pr_origin_08'),
                    '14' => $this->language->get('text_pr_origin_14'),
                    '54' => $this->language->get('text_pr_origin_54'),
                    '65' => $this->language->get('text_pr_origin_65')
                ),
                // Mexico Origin
                'MX' => array(
                    '07' => $this->language->get('text_mx_origin_07'),
                    '08' => $this->language->get('text_mx_origin_08'),
                    '54' => $this->language->get('text_mx_origin_54'),
                    '65' => $this->language->get('text_mx_origin_65')
                ),
                // All other origins
                'other' => array(
                    // service code 7 seems to be gone after January 2, 2007
                    '07' => $this->language->get('text_other_origin_07'),
                    '08' => $this->language->get('text_other_origin_08'),
                    '11' => $this->language->get('text_other_origin_11'),
                    '54' => $this->language->get('text_other_origin_54'),
                    '65' => $this->language->get('text_other_origin_65')
                )
            );

            $xml = '<?xml version="1.0"?>';
            $xml .= '<AccessRequest xml:lang="en-US">';
            $xml .= '	<AccessLicenseNumber>' . $this->config->get('ups_key') . '</AccessLicenseNumber>';
            $xml .= '	<UserId>' . $this->config->get('ups_username') . '</UserId>';
            $xml .= '	<Password>' . $this->config->get('ups_password') . '</Password>';
            $xml .= '</AccessRequest>';
            $xml .= '<?xml version="1.0"?>';
            $xml .= '<RatingServiceSelectionRequest xml:lang="en-US">';
            $xml .= '	<Request>';
            $xml .= '		<TransactionReference>';
            $xml .= '			<CustomerContext>Bare Bones Rate Request</CustomerContext>';
            $xml .= '			<XpciVersion>1.0001</XpciVersion>';
            $xml .= '		</TransactionReference>';
            $xml .= '		<RequestAction>Rate</RequestAction>';
            $xml .= '		<RequestOption>shop</RequestOption>';
            $xml .= '	</Request>';
            $xml .= '   <PickupType>';
            $xml .= '       <Code>' . $this->config->get('ups_pickup') . '</Code>';
            $xml .= '   </PickupType>';

            if ($this->config->get('ups_country') == 'US' && $this->config->get('ups_pickup') == '11') {
                $xml .= '   <CustomerClassification>';
                $xml .= '       <Code>' . $this->config->get('ups_classification') . '</Code>';
                $xml .= '   </CustomerClassification>';
            }

            $xml .= '	<Shipment>';

            $xml .= '		<Shipper>';
            $xml .= '			<Address>';
            $xml .= '				<City>' . $this->config->get('ups_city') . '</City>';
            $xml .= '				<StateProvinceCode>' . $this->config->get('ups_state') . '</StateProvinceCode>';
            $xml .= '				<CountryCode>' . $this->config->get('ups_country') . '</CountryCode>';
            $xml .= '				<PostalCode>' . $this->config->get('ups_postcode') . '</PostalCode>';
            $xml .= '			</Address>';
            $xml .= '		</Shipper>';
            $xml .= '		<ShipTo>';
            $xml .= '			<Address>';
            $xml .= ' 				<City>' . $address['city'] . '</City>';
            $xml .= '				<StateProvinceCode>' . $address['zone_code'] . '</StateProvinceCode>';
            $xml .= '				<CountryCode>' . $address['iso_code_2'] . '</CountryCode>';
            $xml .= '				<PostalCode>' . $address['postcode'] . '</PostalCode>';

            if ($this->config->get('ups_quote_type') == 'residential') {
                $xml .= '				<ResidentialAddressIndicator />';
            }

            $xml .= '			</Address>';
            $xml .= '		</ShipTo>';
            $xml .= '		<ShipFrom>';
            $xml .= '			<Address>';
            $xml .= '				<City>' . $this->config->get('ups_city') . '</City>';
            $xml .= '				<StateProvinceCode>' . $this->config->get('ups_state') . '</StateProvinceCode>';
            $xml .= '				<CountryCode>' . $this->config->get('ups_country') . '</CountryCode>';
            $xml .= '				<PostalCode>' . $this->config->get('ups_postcode') . '</PostalCode>';
            $xml .= '			</Address>';
            $xml .= '		</ShipFrom>';

            $xml .= '		<Package>';
            $xml .= '			<PackagingType>';
            $xml .= '				<Code>' . $this->config->get('ups_packaging') . '</Code>';
            $xml .= '			</PackagingType>';

            $xml .= '			<PackageWeight>';
            $xml .= '				<UnitOfMeasurement>';
            $xml .= '					<Code>' . $this->config->get('ups_weight_code') . '</Code>';
            $xml .= '				</UnitOfMeasurement>';
            $xml .= '				<Weight>' . $weight . '</Weight>';
            $xml .= '			</PackageWeight>';

            if ($this->config->get('ups_insurance')) {
                $xml .= '           <PackageServiceOptions>';
                $xml .= '               <InsuredValue>';
                $xml .= '                   <CurrencyCode>' . $this->currency->getCode() . '</CurrencyCode>';
                $xml .= '                   <MonetaryValue>' . $this->currency->format($this->cart->getTotal(), FALSE, FALSE, FALSE) . '</MonetaryValue>';
                $xml .= '               </InsuredValue>';
                $xml .= '           </PackageServiceOptions>';
            }

            $xml .= '		</Package>';

            $xml .= '	</Shipment>';
            $xml .= '</RatingServiceSelectionRequest>';

//            $log = "'" . $xml . "'";
//            $handle = fopen('Request-' . date('YmdHis').'.xml', 'a+');
//            fwrite($handle, $log . "\n\n");
//            fclose($handle);
//
            if (!$this->config->get('ups_test')) {
                $url = 'https://www.ups.com/ups.app/xml/Rate';
            } else {
                $url = 'https://wwwcie.ups.com/ups.app/xml/Rate';
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
            
//            $log = "'" . $result . "'";
//            $handle = fopen('Response-' . date('YmdHis').'.xml', 'a+');
//            fwrite($handle, $log . "\n\n");
//            fclose($handle);
//
            $error = '';
            $error_msg = '';
            $quote_data = array();

            if ($result) {
                $response = $this->xml2array($result);
                if(isset($response['RatingServiceSelectionResponse']['RatedShipment'])) {
                    if(isset($response['RatingServiceSelectionResponse']['RatedShipment']['Service'])) {
                        $code = $response['RatingServiceSelectionResponse']['RatedShipment']['Service']['Code'];
                        $cost = $response['RatingServiceSelectionResponse']['RatedShipment']['TotalCharges']['MonetaryValue'];
                        $currency = $response['RatingServiceSelectionResponse']['RatedShipment']['TotalCharges']['CurrencyCode'];
//                        d(array($code,$cost,$currency,$this->config->get('ups_origin'),$this->config->get('ups_' . strtolower($this->config->get('ups_origin')) . '_' . $code)));
                        if ($this->config->get('ups_' . strtolower($this->config->get('ups_origin')) . '_' . $code)) {
                            $quote_data[$code] = array(
                                'id' => 'ups.' . $code,
                                'title' => $service_code[$this->config->get('ups_origin')][$code],
                                'cost' => $this->currency->convert($cost, 'USD', $this->currency->getCode()),
                                'tax_class_id' => $this->config->get('ups_tax_class_id'),
                                'text' => $this->currency->format($this->tax->calculate($this->currency->convert($cost, $currency, $this->currency->getCode()), $this->config->get('ups_tax_class_id'), $this->config->get('config_tax')))
                            );
                        }
                        
                    } else {
                        foreach($response['RatingServiceSelectionResponse']['RatedShipment'] as $rate) {
                        $code = $rate['Service']['Code'];
                        $cost = $rate['TotalCharges']['MonetaryValue'];
                        $currency = $rate['TotalCharges']['CurrencyCode'];
//                        d(array($rate,$code,$cost,$currency,$this->config->get('ups_origin'),$this->config->get('ups_' . strtolower($this->config->get('ups_origin')) . '_' . $code)));
                        if ($this->config->get('ups_' . strtolower($this->config->get('ups_origin')) . '_' . $code)) {
                            $quote_data[$code] = array(
                                'id' => 'ups.' . $code,
                                'title' => $service_code[$this->config->get('ups_origin')][$code],
                                'cost' => $this->currency->convert($cost, 'USD', $this->currency->getCode()),
                                'tax_class_id' => $this->config->get('ups_tax_class_id'),
                                'text' => $this->currency->format($this->tax->calculate($this->currency->convert($cost, $currency, $this->currency->getCode()), $this->config->get('ups_tax_class_id'), $this->config->get('config_tax')))
                            );
                        }
                        }
                    }
                    
                }
            }

            $title = $this->language->get('text_title');

            if ($this->config->get('ups_display_weight')) {
                $title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class')) . ')';
            }

            if(!$quote_data) {
                if(isset($response['RatingServiceSelectionResponse']['Response']['Error']['ErrorDescription'])) {
                    $error_msg = $response['RatingServiceSelectionResponse']['Response']['Error']['ErrorDescription'];
                } else {
                    $error_msg = "No shipment found for the current address.";
                }
            }
            $method_data = array(
                'id' => 'ups',
                'title' => $title,
                'quote' => $quote_data,
                'sort_order' => $this->config->get('ups_sort_order'),
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