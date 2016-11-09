<?php

class ControllerModuleCoupon extends Controller {

    protected function index() {
        //d($this->config);
        $this->data['coupon_status'] = $this->config->get('coupon_status');
        $this->data['coupon_box_title'] = $this->config->get('coupon_box_title');
        $this->data['coupon_box_desc'] = $this->config->get('coupon_box_desc');
        $this->data['coupon_desc'] = $this->config->get('coupon_description');
        $this->data['coupon_tt_link'] = $this->config->get('coupon_tt_link');
        $this->data['coupon_fb_link'] = $this->config->get('coupon_fb_link');
        $this->data['coupon_gp_link'] = $this->config->get('coupon_gp_link');
        $this->data['coupon_title'] = HTTP_SERVER . 'module/coupon/titleImage';
        $this->data['coupon_code'] = HTTP_SERVER . 'module/coupon/getPromoCode';
        $this->data['coupon_box'] = HTTP_SERVER . 'module/coupon/getPromoBox';

        $this->id = 'coupon';

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/coupon.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/module/coupon.tpl';
        } else {
            $this->template = 'default/template/module/coupon.tpl';
        }

        $this->render();
    }

    public function getPromoCode() {
        if (!isset($this->request->get['cp']) || !isset($this->request->get['type'])) {
            echo false;
        } else {
            $bPermit = true;
            if ($this->request->get['type'] == '1000' && !isset($this->session->data['social_promo'])) {
                $bPermit = false;
            }
            if ($bPermit) {
                echo 'jsonpCallback({"cpids":"' . $this->request->get['cp'] . '","codes":"' . $this->config->get('coupon_promo') . '","titles":"' . $this->config->get('coupon_box_desc') . '"});';
                $this->session->data['social_promo'] = true;
            } else {
                echo false;
            }
        }
    }

    public function getPromoBox() {
        $this->data['coupon_box_title'] = $this->config->get('coupon_box_title');
        $this->data['coupon_box_desc'] = $this->config->get('coupon_box_desc');
        $this->data['coupon_desc'] = html_entity_decode($this->config->get('coupon_description'));
        $this->data['coupon_tt_link'] = $this->config->get('coupon_tt_link');
        $this->data['coupon_fb_link'] = $this->config->get('coupon_fb_link');
        $this->data['coupon_gp_link'] = $this->config->get('coupon_gp_link');
        $this->data['coupon_code'] = HTTP_SERVER . 'module/coupon/getPromoCode';
        $this->data['baseUrl'] = HTTP_SERVER;

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/coupon_box.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/module/coupon_box.tpl';
        } else {
            $this->template = 'default/template/module/coupon_box.tpl';
        }

        echo $this->render(true);
    }

    public function titleImage() {
        // Set the content-type
        //
        try {
            // Create the image
            $im = imagecreatetruecolor(24, 140);

            // Create some colors
            $white = imagecolorallocate($im, 255, 255, 255);
            $grey = imagecolorallocate($im, 128, 128, 128);
            $red = imagecolorallocate($im, 0, 0, 255);
            $black = imagecolorallocate($im, 0, 0, 0);
            //imagefilledrectangle($im, 0, 0, 24, 140, $white);
            imagecolortransparent($im, $black);
            // The text to draw
            $text = $this->config->get('coupon_title');
            // Replace path by your own font path
            $font = 'arial.ttf';
            //d($text);
            // Add some shadow to the text
            imagestringup($im, 3, 5, 134, $text, $grey);
            // Add the text
            imagestringup($im, 3, 4, 135, $text, $white);
            // Using imagepng() results in clearer text compared with imagejpeg()
            header('Content-Type: image/png');
            imagepng($im);
            imagedestroy($im);
        } catch (Exception $e) {
            d($e);
        }
    }

}

?>