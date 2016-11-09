<?php

class ControllerModuleSocialPromotion extends Controller {

    protected function index() {
        //d($this->config);
        $this->data['social_promotion_status'] = $this->config->get('social_promotion_status');
        $this->data['social_promotion_box_title'] = $this->config->get('social_promotion_box_title');
        $this->data['social_promotion_box_desc'] = $this->config->get('social_promotion_box_desc');
        $this->data['social_promotion_desc'] = $this->config->get('social_promotion_description');
        $this->data['social_promotion_tt_link'] = $this->config->get('social_promotion_tt_link');
        $this->data['social_promotion_fb_link'] = $this->config->get('social_promotion_fb_link');
        $this->data['social_promotion_gp_link'] = $this->config->get('social_promotion_gp_link');
        $this->data['social_promotion_title'] = makeUrl('module/social_promotion/titleImage',array('no-layout=1'),true);
        $this->data['social_promotion_code'] = makeUrl('module/social_promotion/getPromoCode',array('no-layout=1'),true);
        $this->data['social_promotion_box'] = makeUrl('module/social_promotion/getPromoBox',array('no-layout=1'),true);

        $this->id = 'social_promotion';

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/social_promotion.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/module/social_promotion.tpl';
        } else {
            $this->template = 'default/template/module/social_promotion.tpl';
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
                echo 'jsonpCallback({"cpids":"' . $this->request->get['cp'] . '","codes":"' . $this->config->get('social_promotion_promo') . '","titles":"' . $this->config->get('social_promotion_box_desc') . '"});';
                $this->session->data['social_promo'] = true;
            } else {
                echo false;
            }
        }
    }

    public function getPromoBox() {
        $this->data['social_promotion_box_title'] = $this->config->get('social_promotion_box_title');
        $this->data['social_promotion_box_desc'] = $this->config->get('social_promotion_box_desc');
        $this->data['social_promotion_desc'] = html_entity_decode($this->config->get('social_promotion_description'));
        $this->data['social_promotion_tt_link'] = $this->config->get('social_promotion_tt_link');
        $this->data['social_promotion_fb_link'] = $this->config->get('social_promotion_fb_link');
        $this->data['social_promotion_gp_link'] = $this->config->get('social_promotion_gp_link');
        $this->data['social_promotion_code'] = HTTP_SERVER . 'module/social_promotion/getPromoCode';
        $this->data['baseUrl'] = HTTP_SERVER;
        $this->data['base'] = (HTTPS_SERVER) ? HTTPS_SERVER : HTTP_SERVER;

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/social_promotion_box.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/module/social_promotion_box.tpl';
        } else {
            $this->template = 'default/template/module/social_promotion_box.tpl';
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
            $text = $this->config->get('social_promotion_title');
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