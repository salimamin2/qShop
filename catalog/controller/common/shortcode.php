<?php

class ControllerCommonShortcode extends Controller {

	public function index() {
         
         //=== Default shortcodes
         $this->load->helper('shortcodes_default');
         
         $class         = new ShortcodesDefault($this->registry);
         $scDefaults    = get_class_methods($class);
         foreach ($scDefaults as $shortcode) {
            $this->shortcodes->add_shortcode($shortcode, $shortcode, $class);
         }

         //=== Extensions shortcodes : for extensions developer
         $files = glob(DIR_APPLICATION . '/view/shortcodes/*.php');
         if ($files) {
            foreach ($files as $file) {
               require_once($file);
               
               $file       = basename($file, ".php");
               $extClass   = 'Shortcodes' . preg_replace('/[^a-zA-Z0-9]/', '', $file);
               
               $class      = new $extClass($this->registry);
               $scExtensions = get_class_methods($class);
               foreach ($scExtensions as $shortcode) {
                  $this->shortcodes->add_shortcode($shortcode, $shortcode, $class);
               }
            }
         }
         
         //=== Themes shortcodes : for theme developer
         $file = DIR_TEMPLATE . $this->config->get('config_template') . '/shortcodes_theme.php';
         if (file_exists($file)) {
            require_once(VQMod::modCheck($file));
            
            $class         = new ShortcodesTheme($this->registry);
            $scThemes      = get_class_methods($class);
            foreach ($scThemes as $shortcode) {
               $this->shortcodes->add_shortcode($shortcode, $shortcode, $class);
            }
         }
         
         //=== Custom shortcodes : user power!
         $file = DIR_TEMPLATE . $this->config->get('config_template') . '/shortcodes_custom.php';
         if (file_exists($file)) {
            require_once(VQMod::modCheck($file));
            
            $class         = new ShortcodesCustom($this->registry);
            $scCustom      = get_class_methods($class);
            foreach ($scCustom as $shortcode) {
               $this->shortcodes->add_shortcode($shortcode, $shortcode, $class);
            }
         }
	}
}