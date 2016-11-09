<?php

class ModelShippingUpsQty extends Model {

    public function getQuote($address) {
        $this->load->language('shipping/ups_qty');
        $shipment_methods[] = array(
            'code'  =>  'ups_01',
            'name'  =>  'Next day Air'
        );
        $shipment_methods[] = array(
            'code'  =>  'ups_02',
            'name'  =>  '2nd day Air'
        );
        $shipment_methods[] = array(
            'code'  =>  'ups_03',
            'name'  =>  'Ground'
        );
        $shipment_methods[] = array(
            'code'  =>  'ups_07',
            'name'  =>  'Worldwide Express'
        );
        $shipment_methods[] = array(
            'code'  =>  'ups_08',
            'name'  =>  'Worldwide Expedited'
        );
        $shipment_methods[] = array(
            'code'  =>  'ups_11',
            'name'  =>  'Standard'
        );
        $shipment_methods[] = array(
            'code'  =>  'ups_12',
            'name'  =>  '3 Day Select'
        );
        $shipment_methods[] = array(
            'code'  =>  'ups_13',
            'name'  =>  'Next Day Air Saver'
        );
        $shipment_methods[] = array(
            'code'  =>  'ups_14',
            'name'  =>  'Next Day Air Early A.M.'
        );
        $shipment_methods[] = array(
            'code'  =>  'ups_54',
            'name'  =>  'Worldwide Express Plus'
        );
        $shipment_methods[] = array(
            'code'  =>  'ups_59',
            'name'  =>  '2nd Day Air A.M'
        );
        $shipment_methods[] = array(
            'code'  =>  'ups_65',
            'name'  =>  'Saver'
        );

        $quote_data = array();

        if ($this->config->get('ups_qty_status')) {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "geo_zone ORDER BY name");

            foreach ($query->rows as $result) {
                if ($this->config->get('ups_qty_' . $result['geo_zone_id'] . '_status')) {
                    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int) $result['geo_zone_id'] . "' AND country_id = '" . (int) $address['country_id'] . "' AND (zone_id = '" . (int) $address['zone_id'] . "' OR zone_id = '0')");

                    if ($query->num_rows) {
                        $status = TRUE;
                    } else {
                        $status = FALSE;
                    }
                } else {
                    $status = FALSE;
                }

                if ($status) {
                    $ups_qty = $this->cart->getQty();
                    $rates = unserialize($this->config->get('ups_qty_' . $result['geo_zone_id'] . '_rate'));
                    $datas = array();
                    foreach($rates as $rate) {
                        if($ups_qty > $rate['qty'] && $rate['rate'] > 0) {
                            foreach($shipment_methods as $shipment_method) {
                                if($shipment_method['code'] == $rate['method']) {
                                    $title = $shipment_method['name'];
                                }
                                $cost = $rate['rate'];
                            }
                            $quote_data['ups_qty_' . $result['geo_zone_id']. '_' . $rate['method']] = array(
                                'id' => 'ups_qty.ups_qty_' . $result['geo_zone_id'] . '_' . $rate['method'],
                                'title' => $result['name'] . '  (' . $title . ')',
                                'cost' => $cost,
                                'tax_class_id' => $this->config->get('ups_qty_tax_class_id'),
                                'text' => $this->currency->format($this->tax->calculate($cost, $this->config->get('ups_qty_tax_class_id'), $this->config->get('config_tax')))
                            );
                        }
                    }
                }
            }
        }

        $method_data = array();

        if ($quote_data) {
            $method_data = array(
                'id' => 'ups_qty',
                'title' => $this->language->get('text_title'),
                'quote' => $quote_data,
                'sort_order' => $this->config->get('ups_qty_sort_order'),
                'error' => FALSE
            );
        }

        return $method_data;
    }

}

?>