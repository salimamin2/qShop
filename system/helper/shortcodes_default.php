<?php
class ShortcodesDefault extends Controller {

   /**
    * Generate product link with it variant of category and manufacture link.
    *
    * @since 1.0
    * 
    * @param array $atts Shortcode attributes
    * @param string $content Shortcode content
    * @return string Link to product page
    * 
    * @example [link_product id="x" path="x_x" brand="x" ssl="0" title="xyz" /]
    * @example [link_product id="x" path="x_x" brand="x" ssl="0" title="xyz"]custom text[/link_product]
    */


   function link_product($atts, $content = '') {
      extract($this->shortcodes->shortcode_atts(array(
         'id'     => 0,
         'path'   => 0,
         'brand'  => 0,
         'ssl'    => 0,
         'title'  => ''
      ), $atts));
      $link = $content;
      if ($id) {
         $ssl = $ssl ? true : false;
         $title   = ($title) ? 'Title="' . $title . '"' : "";

         $product = Make::a('catalog/product')->create()->getProduct($id);
         if ($product) {
            $params = array();
            $params[] = 'product_id=' . $id;
            if($path)
              $params[] = 'path=' . $path;
            elseif($brand)
              $params[] = 'manufacturer_id=' . $brand;
            $link = '<a href="' . makeUrl('product/product',$params,true,$ssl) . '" ' . $title . '>' . html_entity_decode(($content != '' ? $content : $product->name)) . '</a>';
         }
      }
      return $link;
   }
   
   /**
    * Generate category link.
    *
    * @since 1.0
    * 
    * @param array $atts Shortcode attributes
    * @param string $content Shortcode content
    * @return string Link to category page
    * 
    * @example [link_category path="x_y" ssl="0" title="xyz" /]
    * @example [link_category path="x_y" ssl="1" title="xyz"]custom text[/link_category]
    */
   function link_category($atts, $content = '') {
      extract($this->shortcodes->shortcode_atts(array(
         'path'   => 0,
         'ssl'    => 0,
         'title'  => ''
      ), $atts));
      $link = $content;
      if ($path) {
         $ssl     = ($ssl ? true : false) ;
         $title   = ($title ? 'title="' . $title . '"' : "");
         $parts = explode('_',$path);
         $category = Make::a('catalog/category')->create()->getCategory(end($parts));
         if ($category) {
            $category = $category->toArray();
            $link = '<a href="' . makeUrl('product/category',array('path=' . $path),true,$ssl) . '" ' . $title . ' >' . html_entity_decode(($content != '' ? $content : $category['name'])) . '</a>';
         }
      }
      return $link;
   }
   
   /**
    * Generate brand/ manufacturer link.
    * 
    * @since 1.0
    * 
    * @param array $atts Shortcode attributes
    * @param string $content Shortcode content
    * @return string Link to manufacturer list or manufacture page
    * 
    * @example [link_brand ssl="0" title="xyz" /]
    * @example [link_brand ssl="1" title="xyz"]custom text[/link_brand]
    * @example [link_brand brand="x" ssl="0" title="xyz" /]
    * @example [link_brand brand="x" ssl="1" title="xyz"]custom text[/link_brand]
    */
   function link_brand($atts, $content = '') {
      extract($this->shortcodes->shortcode_atts(array(
         'brand'  => 0,
         'ssl'    => 0,
         'title'  => ''
      ), $atts));
      
      $ssl     = ($ssl) ? true : false;
      $title   = html_entity_decode(($title) ? 'Title="' . $title . '"' : "");
      $link = $content;
      if ($brand) {
         //$this->load->model('catalog/manufacturer');
         $manufacturer = Make::a('catalog/manufacturer')->create()->getManufacturer($brand);         
         if ($manufacturer) {
            $link = '<a href="' . makeUrl('product/manufacturer',array('manufacturer_id=' . $brand),true,$ssl) . '" ' . $title . ' >' . html_entity_decode(($content != '' ? $content : $manufacturer['name'])) . '</a>';
         }
      }
      return $link;
   }
    function more_designers($atts, $content = '') {
        extract($this->shortcodes->shortcode_atts(array(
            'brand'  => 0,
            'ssl'    => 0,
            'title'  => ''
        ), $atts));
        
        $link = '<a href="' . makeUrl('designers', array(), true, true) . '">More Designers > </a>';

        return $link;
    }
   
   /**
    * Generate information link.
    * 
    * @since 1.0
    * 
    * @param array $atts Shortcode attributes
    * @param string $content Shortcode content
    * @return string Link to information page
    * 
    * @example [link_info id="x" ssl="0" title="xyz" /]
    * @example [link_info id="x" ssl="0" title="xyz"]custom text[/link_info]
    */
   function link_info($atts, $content = '') {
      extract($this->shortcodes->shortcode_atts(array(
         'id'     => 0,
         'ssl'    => 0,
         'title'  => ''
      ), $atts));
      
      $link = $content;
      if ($id) {
         $ssl     = $ssl ? true : false;
         $title   = html_entity_decode(($title) ? 'Title="' . $title . '"' : "");
         $this->load->model('catalog/information');
         $information = $this->model_catalog_information->getInformation($id);
         if ($information) {
            $link = '<a href="' . makeUrl('information/information',array('information_id=' . $id),true,$ssl) . '" ' . $title . ' >' . html_entity_decode(($content != '' ? $content : $information['title'])) . '</a>';
         }
      }
      return $link;
   }
   
   /**
    * Generate custom link based on OpenCart API Url format
    * 
    * @since 1.0
    * 
    * @param array $atts Shortcode attributes
    * @param string $content Shortcode content
    * @return string Return link based on user input
    * 
    * @example [link_custom route="foo" args="bar" ssl="0" title="xyz"]custom text[/link_custom]
    */
   function link_custom($atts, $content = '') {
      extract($this->shortcodes->shortcode_atts(array(
         'route'  => '',
         'args'   => '',
         'ssl'    => 0,
         'title'  => ''
      ), $atts));
      $link = $content;
      if($route) {
         $link = '<a href="' . makeUrl($route,$args,true,($ssl ? true : false)) . '" ' . $title . '>' . $content . '</a>';
      }
      return $link;
   }
   
   /**
    * Generate custom link for multi-store site
    * 
    * @since 1.0
    * 
    * @param array $atts Shortcode attributes
    * @param string $content Shortcode content
    * @return string Link to manufacturer list or manufacture page
    * 
    * @example [link_store store="x" route="foo" args="bar" ssl="0" title="xyz"]custom text[/link_custom]
    */
   function link_store($atts, $content = '') {
      extract($this->shortcodes->shortcode_atts(array(
         'store'  => 0,
         'route'  => '',
         'args'   => '',
         'ssl'    => 0,
         'title'  => ''
      ), $atts));
      
      if ($route && $content) {
         $current_store    = $this->config->get('config_url');
         
         if ($store) {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store . "'" );
            
            if ($query->num_rows) {
               foreach ($query->rows as $setting) {
                  if ($setting['key'] == 'config_url') {
                     $store_url  = $setting['value'];
                  }
               }

               $url = str_replace($current_store, $store_url, $this->url->link($route, $args, $ssl));
               
               return '<a href="' . $url . '" ' . $title . '>' . $content . '</a>';
            } else {
               return $content;
            }
         } else {
            $store_url  = HTTP_SERVER;
            
            $url = str_replace($current_store, $store_url, $this->url->link($route, $args, $ssl));
               
            return '<a href="' . $url . '" ' . $title . '>' . $content . '</a>';
         }
      }
   }
   
   /**
    * Load module type product (featured, latest, bestseller, special) anywhere!
    * 
    * @since 1.0
    * 
    * @param array $atts Shortcode attributes
    * @return string Module based on user choose
    * 
    * @example [module_product type="featured" limit="5" img_w="100" img_h="100" /]
    */
   function module_product($atts) {
      extract($this->shortcodes->shortcode_atts(array(
         'type'   => '',
         'limit'  => 5,
         'img_w'  => $this->config->get('config_image_product_width'),
         'img_h'  => $this->config->get('config_image_product_height')
      ), $atts));

      if ($type) {
         $module = $this->load('module/' . $type, array(
                     'limit'        => $limit,
                     'image_width'  => $img_w,
                     'image_height' => $img_h
                  ));
         
         $html = '<div class="shortcode-module sc-' . $type . '">' . $module . '</div>';
         
         return $html;
      }
   }

    /**
    * Load module anywhere!
    * 
    * @since 1.0
    * 
    * @param array $atts Shortcode attributes
    * @return string Module based on user choose
    * 
    * @example [module name="latest_blogs" class="col-md-6" limit="4" category_id="1" /]
    */
   function module($atts) {
      extract($this->shortcodes->shortcode_atts(array(
        'name' => '',
        'class' => '',
        'limit' => '',
        'category_id' => false
      ), $atts));
//d($atts);
      $html = '';
      if ($name) {
          $params['class'] = $class;
          $params['category_id'] = $category_id;
          if($limit != '') {
            $params['limit'] = $limit;
          }
          $module = $this->load('module/' . $name,$params);
          $html = $module;
      }
      return $html;
   }

    /**
    * Prodoct Details !
    * 
    * @since 1.0
    * 
    * @param array $atts Shortcode attributes
    * @return string Product Detail based on user choose
    * 
    * @example [order_product id="1" field="name" /]
    */
   function order_product($atts) {
      $this->language->load('common/shortcodes_default');
      $content = '';
      extract($this->shortcodes->shortcode_atts(array(
        'id' => 0,
        'field' => 'name',
        'bkg_img' => ''
      ), $atts));
      $this->load->model('tool/image');

      if($id) {
         $oProduct = Make::a('catalog/product')->create();
         $oOrm = $oProduct->getProduct($id);
         if($oOrm) {
              switch($field) {
                  case 'name':
                  case 'manufacturer':
                  case 'description':
                  case 'meta_description':
                      $content = $oOrm->{$field};
                      break;
                  case 'price':
                      $special = $oOrm->getProductSpecial($id);
                      if($special) {
                          $content = '<span class="special-price">' .  
                                        $this->currency->format($this->tax->calculate($special['price'], $oOrm->tax_class_id,$this->config->get('config_tax'))) . 
                                      '</span>'; 
                      }
                      $content .= '<span class="price">' . 
                                    $this->currency->format($this->tax->calculate($oOrm->price, $oOrm->tax_class_id,$this->config->get('config_tax'))) . 
                                  '</span>';
                      break;
                  case 'stock_status':
                      $status = $oProduct->getProductStockStatus($id);
                      if(!empty($status[0])) {
                        $content = sprintf($this->language->get('order_product_stock_status'),$status[0]['quantity'],$status[0]['total_qty']);
                      }
                      break;
                  case 'purchased_today':
                      $status = $oProduct->getProductStockStatus($id,date('Y-m-d'));
                      if(!empty($status[0])) {
                          $content = sprintf($this->language->get('order_product_purchased_today'),$status[0]['quantity']);
                      }
                      break;
                  case 'form':
                      $content = '<form method="post" action="' . makeUrl('checkout/cart',array(),true) . '">
                                    <input type="hidden" name="product_id" value="' . $id . '" />
                                    <input type="hidden" name="quantity" value="1" />
                                    <button type="submit">' . $this->language->get('order_product_button_text') . '</button>
                                  </form>';
                      break;
                  case 'image':
                    if(!$bkg_img){
                      $Pimage = $this->model_tool_image->resize($oOrm->image, $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
                    }
                    $content = '<img src="' . ($bkg_img ? $bkg_img : $Pimage ) . '" alt="' . $oProduct->name . '" />';
                  break;
                  default: 
                    break;
              }
          }
      } 
      return $content;
   }

    /**
    * Load Blog post anywhere!
    * 
    * @since 1.0
    * 
    * @param array $atts Shortcode attributes
    * @return string Blog detail based on user choice
    * 
    * @example [blog_post id="1" title="1" category="1"]content[\blog_post]
    */
    public function blog_post($atts,$text = '') {
        $this->language->load('common/shortcodes_default');
        extract($this->shortcodes->shortcode_atts(array(
          'id' => 0,
          'title' => 0,
          'category' => 0
        ), $atts));
        $content = '<div class="blog-post">';
        if($title)
          $content .= '<h5 class="blog-header">' . ($text != '' ? $text : $this->language->get('blog_post_title')) . '</h5>';
        $oModel = Make::a('catalog/blog_post')->create();
        
        if($id)
          $aOrm = $oModel->getBlog($id);
        elseif($category)
          $aOrm = $oModel->getBlogByCategory($category);
        if(isset($aOrm) && $aOrm) {
            $blog_url = makeUrl('blog/post',array('blog_category_id=' . $aOrm['blog_category_id'],'blog_post_id=' . $aOrm['blog_post_id']),true);
            if(file_exists(DIR_IMAGE . $aOrm['image']))
                $content .= '<a href="' . $blog_url . '" ><img class="blog-image" src="' . (HTTP_IMAGE . $aOrm['image']) . '" alt="' . $aOrm['title'] . '" /></a>';
            $content .= '<h3 class="blog-title"><a href="' . $blog_url . '" >' . $aOrm['title'] . '</a></h3>';
            $content .= '<a  href="' . $blog_url . '" class="read-more">'. $this->language->get('blog_post_read_more') .'</a>';
          
        }   

        return ($content . '</div>');
    }
    function manufacturer_info($atts) {
        $this->language->load('common/shortcodes_default');
        extract($this->shortcodes->shortcode_atts(array(
            'category_id'  => 0,
            'manufacturer_id' => 0,
            'product_id' => 0,
            'limit' => 4,
            'heading'  => ''
        ), $atts));
        $this->load->model('catalog/information');
        $this->load->model('tool/image');
        // $category_id=$atts['category_id'];
        // $manufacturer_id = $atts['manufacturer_id'];
        // $limit=$atts['limit'];
        if($category_id && $manufacturer_id){
            $this->data['description']= $this->language->get('text_manufacturer_product');
            $manufacturer = $this->model_catalog_information->getProductsByManufacturerCategory($category_id,$manufacturer_id,$limit,$product_id);
        }
        else if($category_id){
            $this->data['description']= $this->language->get('text_manufacturer_product');
            $manufacturer = $this->model_catalog_information->getProductsByCategory($category_id,$limit);
        }
        else if($manufacturer_id){
            $manufacturer = $this->model_catalog_information->getProductsByManufacturer($manufacturer_id,$limit,$product_id);
            $this->data['description']= $this->language->get('text_manufacture_latest');
        }

        $this->data['header_text'] = '';
        if($heading != '') {
          $this->data['header_text'] = $heading;
        }
        //d($manufacturer);
        foreach($manufacturer as $manufacturer_info) {

            $image = '';
            if ($manufacturer_info['image'] && file_exists(DIR_IMAGE . $manufacturer_info['image'])) {
                $image = $manufacturer_info['image'];
            } else {
                $image = 'no_image.jpg';
            }
            $aProductUrl['product'] = 'product_id=' . $manufacturer_info['product_id'];
            $this->data['manufacturer_info'][] = array(
                'manufacturer_id'=>$manufacturer_info['manufacturer_id'],
                'name'=>html_entity_decode($manufacturer_info['name']),
                'description'=>$manufacturer_info['description'],
                'image'=>$this->model_tool_image->resize($image, $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height')),
                'model'=>html_entity_decode($manufacturer_info['model']),
                'href' => makeUrl('product/product', $aProductUrl, true),
                'price'=>$this->currency->format($this->tax->calculate($manufacturer_info['price'], $manufacturer_info['tax_class_id'], $this->config->get('config_tax'))),
                'meta_title'=>html_entity_decode($manufacturer_info['meta_title']),
                'facebook'=>$manufacturer_info['facebook'],
                'twitter'=>$manufacturer_info['twitter'],

            );
        }

        return $this->loadFetch('information/manufacturer_info');
    }

    function manufacturerDetails($atts) {
        $this->language->load('common/shortcodes_default');
        $this->load->model('catalog/information');
        extract($this->shortcodes->shortcode_atts(array(
            'brand'  => 0,
            'ssl'    => 0,
            'title'  => '',
            'bkg_img' => ''
        ), $atts));
        $manufacturer_id = $atts['manufacturer_id'];

        if($manufacturer_id){
            $manufacturer_info = $this->model_catalog_information->getManufacturerDetails($manufacturer_id);
            $this->data['description']= $this->language->get('text_manufacture_latest');

        }
            $image = '';
            $headers = @get_headers($bkg_img);  
            if($bkg_img != '' && $headers && $headers[0] != 'HTTP/1.1 404 Not Found') {
                $image = $bkg_img;
            }
            else if ($manufacturer_info['image'] && file_exists(DIR_IMAGE . $manufacturer_info['image'])) {
                $image = HTTPS_IMAGE . $manufacturer_info['image'];
            }
            $aProductUrl['product'] = 'manufacturer_id=' . $manufacturer_info['manufacturer_id'];
            $this->data['manufacturer_info'] = array(
                'manufacturer_id'=>$manufacturer_info['manufacturer_id'],
                'name'=>html_entity_decode($manufacturer_info['name']),
                'description'=>$manufacturer_info['description'],
                'image'=>$image,
                'href' => makeUrl('product/manufacturer', $aProductUrl, true),
                'price'=>$this->currency->format($this->tax->calculate($manufacturer_info['price'], $manufacturer_info['tax_class_id'], $this->config->get('config_tax'))),
                'meta_title'=>html_entity_decode($manufacturer_info['meta_title']),
                'facebook'=>$manufacturer_info['facebook'],
                'twitter'=>$manufacturer_info['twitter'],

            );

        return $this->loadFetch('information/manufacturer_details');
    }

    /**
    * Load Information Page!
    * 
    * @since 1.0
    * 
    * @param array $atts Shortcode attributes
    * @return string Blog detail based on user choice
    * 
    * @example [information id="1" title="1" /]
    */
    public function information($atts,$text = '') {
        $this->language->load('common/shortcodes_default');
        extract($this->shortcodes->shortcode_atts(array(
          'id' => 0,
          'title' => 0
        ), $atts));
        $content = '';
        if($id) {
            $this->load->model('catalog/information');
            $information = $this->model_catalog_information->getInformation($id);
            if($information) {
                if($title) {
                    $content .= '<div class="page-title"><h1>' . html_entity_decode($information['title']) . '</h1></div>';
                }
                $content .= html_entity_decode($information['description']);
            }
        }  
        return $content;
    }

    /**
    * Load Product Information!
    * 
    * @since 1.0
    * 
    * @param array $atts Shortcode attributes
    * @return string Blog detail based on user choice
    * 
    * @example [product id="1" title="1" img_w='' img_h='' /]
    */
    public function product($atts,$text = '') {
        $this->language->load('common/shortcodes_default');
        extract($this->shortcodes->shortcode_atts(array(
          'id' => 0,
          'img_w' => $this->config->get('config_image_product_width'),
          'img_h' => $this->config->get('config_image_product_height')
        ), $atts));
        $content = '<div class="product">';
        if($id) {
            $this->load->model('tool/image');
            $oModel = Make::a('catalog/product')->create();
            $oProduct =  $oModel->getProduct($id);
            if($oProduct) {
                $oProduct = $oProduct->as_array();
                $content .= '<a href="' . makeUrl('product/product',array('product_id='.$oProduct['product_id']),true) . '">';
                $content .= '<img src="' . $this->model_tool_image->resize($oProduct['image'],$img_w,$img_h) . '" />';
                $content .= '<span class="name">' . $oProduct['name'] . '</span>';
                $special = $oModel->getProductSpecial($id);
                if($special) {
                    $content = '<span class="special-price">' .  
                                  $this->currency->format($this->tax->calculate($special['price'], $oOrm->tax_class_id,$this->config->get('config_tax'))) . 
                                '</span>'; 
                }
                $content .= '<span class="price">' . 
                              $this->currency->format($this->tax->calculate($oProduct['price'], $oOrm->tax_class_id,$this->config->get('config_tax'))) . 
                            '</span></a>';
            }
        }  
        return $content . '</div>';
    }

    /**
    * Load Product Manufacturer!
    * 
    * @since 1.0
    * 
    * @param array $atts Shortcode attributes
    * @return string Product Manufacturer
    * 
    * @example [product_manufacturer product_id="1" link_text="Check Products"][/product_manufacturer]
    */
    public function product_manufacturer($atts,$text = '') {
        $this->language->load('common/shortcodes_default');
        extract($this->shortcodes->shortcode_atts(array(
          'product_id' => 0,
          'link_text' => 'Check Products',
          'img_w' => $this->config->get('config_image_product_width'),
          'img_h' => $this->config->get('config_image_product_height')
        ), $atts));

        $content = '<div class="product-manufacturer">' . ($text != '' ? '<h4>' . $text . '</h4>' : '');
        if($product_id) {
            $this->load->model('tool/image');
            $oModel = Make::a('catalog/manufacturer')->create();
            $aManufacturer =  $oModel->getManufacturerByProductId($product_id);
            // d($aManufacturer);
            if($aManufacturer) {
                $content .= '<div class="manufacturer-image"><img src="' . $this->model_tool_image->resize($aManufacturer['image'],$img_w,$img_h) . '" alt="' . html_entity_decode($aManufacturer['name']) . '" /></div>';
                $content .= '<div class="manufacturer-content"><a href="' . 
                makeUrl('product/manufacturer',array('manufacturer_id=' . $aManufacturer['manufacturer_id']),true) . '"><span class="name">' . html_entity_decode($aManufacturer['name']) . '</span></a><span class="country">' . $aManufacturer['country'] . '</span>';
                $content .= '<div class="description">' . substr($aManufacturer['description'],0,50) . ' <a href="' . 
                makeUrl('product/manufacturer',array('manufacturer_id=' . $aManufacturer['manufacturer_id']),true) . '">' . $link_text . '</a></div></div>';
            }
        }  
        return $content . '</div>';
    }


   /**
    * Load module slideshow
    * 
    * @since 1.0
    * 
    * @param array $atts Shortcode attributes
    * @return string Show module slideshow
    * 
    * @example [module_slideshow id="x" limit="5" img_w="100" img_h="100" /]
    */
   function module_slideshow($atts) {
      extract($this->shortcodes->shortcode_atts(array(
         'id'     => 0,
         'img_w'  => 80,
         'img_h'  => 80
      ), $atts));

      if ($id) {
         $script  = '<script type="text/javascript" src="catalog/view/javascript/jquery/nivo-slider/jquery.nivo.slider.pack.js"></script>';
         $style   = '<link href="catalog/view/theme/default/stylesheet/slideshow.css" type="text/css" rel="stylesheet" />';

         $module = $this->getChild('module/slideshow', array(
                     'banner_id' => $id,
                     'width'     => $img_w,
                     'height'    => $img_h
                  ));
         
         $html = '<div class="shortcode-module sc-slideshow">' . $script . $style . $module . '</div>';
         
         return $html;
      }
   }
   
   function category($atts) {
      extract($this->shortcodes->shortcode_atts(array(
         'id'     => 0,
         'img_w'  => $this->config->get('config_image_product_width'),
         'img_h'  => $this->config->get('config_image_product_height')
      ), $atts));
      $content = '<div class="category">';
      if ($id) {
        $aOrm = Make::a('catalog/category')->create()->getCategory($id);
        if($aOrm) {
            $this->load->model('tool/image');
            $aOrm = $aOrm->toArray();
            $content .= '<img src="' . $this->model_tool_image->resize($aOrm['image'],$img_w,$img_h) . '" alt="' . html_entity_decode($aOrm['name']) . '" />';
            $content .= '<div class="category-title">' . html_entity_decode($aOrm['name']) . '</div>';
        }
      }
      return $content . '</div>';
   }

   function home_category($atts,$text) {
      extract($this->shortcodes->shortcode_atts(array(
         'img_w'  => $this->config->get('config_image_product_width'),
         'img_h'  => $this->config->get('config_image_product_height'),
         'description' => 0,
         'path'   => 0,
         'ssl'    => 0,
         'title'  => ''
      ), $atts));
      $content = '';
      // $content = '<div class="category row">';
      if ($path) {
        $parts = explode('_',$path);
        $category = Make::a('catalog/category')->create()->getCategory(end($parts));
        $ssl     = ($ssl ? true : false) ;
        // $title   = ($title ? 'title="' . $title . '"' : "");
        if ($category) {
            $this->language->load('common/shortcodes_default');
            $this->load->model('tool/image');
            $this->data['category'] = $category->toArray();
            $this->data['category']['image'] = $this->model_tool_image->resize($category->image,$img_w,$img_h);
            $this->data['category']['href'] = makeUrl('product/category',array('path=' . $path),true,$ssl);
            $this->data['title'] = $title;
            $this->data['text'] = ($text != '' ? $text : $this->language->get('home_category_text'));
            $content = $this->loadFetch('module/featured_category');
            // $content .= '<div class="col-sm-6"><img src="' .  . '" alt="' . $category['name'] . '" /></div>';
            // $content .= '<div class="col-sm-6"><div class="category-title">' . $category['name'] . '</div><div class="category-desc">' . $category['meta_description'] . '</div>';
            // $content .= '<div class="category-link"><a href="' .  . '" ' . $title . ' >Browse through other<br />great accessories</a></div></div>';
        }
      }
      return $content;
      // return $content . '</div>';
   }
   
   /**
    * User required to login to read the rest of the content.
    * Able to restrict user to read based on their group.
    * 
    * @since 1.0
    * 
    * @param array $atts Shortcode attributes
    * @param string $content Shortcode content
    * @return string Message if not permitted to read, show rest of article if permitted.
    * 
    * @example [login msg_login='Silahkan <a href="%s">login</a> untuk melihat halaman ini.']content[/login]
    */
   function login($atts, $content = '') {
      $this->language->load('common/shortcodes_default');
      
      extract($this->shortcodes->shortcode_atts(array(
         'msg_login'    => $this->language->get('login_message'),
         'msg_group'    => $this->language->get('login_group'),
         'suffix'       => 'attention',
         'group'        => ''
      ), $atts));
      
      
      
      if ($content && $this->customer->isLogged()) {
         if($group) {
            if ($group == $this->customer->getCustomerGroupId()) {
               return $this->shortcodes->do_shortcode($content);
            } else {
               return '<div class="' . $suffix . '">' . sprintf($msg_group, $this->url->link('information/contact')) . '</div>';
            }
         } else {
            return $this->shortcodes->do_shortcode($content);
         }
      } else {
         return '<div class="' . $suffix . '">' . sprintf($msg_login, $this->url->link('account/login')) . '</div>';
      }
   }
   
   /**
    * Embed video: youtube and vimeo
    * 
    * @since 1.0
    * 
    * @param array $atts Shortcode attributes
    * @return string Video embed code
    * 
    * @example [video type="vimeo" id="xxx" vid_w="450" vid_h="280" /]
    */
   function video($atts) {
      extract($this->shortcodes->shortcode_atts(array(
         'type'      => 'youtube',
         'id'        => 0,
         'vid_w'     => 450,
         'vid_h'     => 280,
         'autoplay'  => 0
      ), $atts));

      if ($id) {
         if ($type == 'youtube') {
            $video   = '<iframe width="' . $vid_w . '" height="' . $vid_h . '" src="http://youtube.com/embed/' . $id . '?rel=0&autoplay=' . $autoplay . '" frameborder="0" allowfullscreen></iframe>';
            
            $html    = '<div class="shortcode-video sc-' . $type . '">' . $video . '</div>';
            
            return $html;
            
         } elseif ($type == 'vimeo') {
            $video   = '<iframe src="//player.vimeo.com/video/' . $id . '?autoplay=' . $autoplay . '" width="' . $vid_w . '" height="' . $vid_h . '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
            
            $html    = '<div class="shortcode-video sc-' . $type . '">' . $video . '</div>';
            
            return $html;
         }
      }
   }
   
   /**
    * Embed image directly or with cache
    * 
    * @since 1.0
    * 
    * @param array $atts Shortcode attributes
    * @param string $content Shortcode content
    * @return string Image html code
    * 
    * @example [image src="" img_w="450" img_h="280" title="" alt="" align="" /]
    */
   function image($atts, $content = '') {
      extract($this->shortcodes->shortcode_atts(array(
         'src'       => '',
         'img_w'     => 200,
         'img_h'     => 200,
         'title'     => '',
         'alt'       => '',
         'align'     => '',    // left, right, center
         'cache'     => 1
      ), $atts));
      
      if (!$src && $content) { $src = $content; }
      if (!$alt & $title) { $alt = $title; }
      if ($align == 'right') {
         $align_style = 'float:right;margin:0 0 10px 10px;';
      } elseif ($align == 'center') {
         $align_style = 'display:block;margin:0 auto 15px;';
      } else {
         $align_style = 'float:left;margin:0 10px 0 10px;';
      }

      $src_resize = str_replace('image/', '', $src);
      
      if (is_file(DIR_IMAGE . $src_resize)) {
         if ($cache) {
            $this->load->model('tool/image');
            $src = $this->model_tool_image->resize($src_resize, $img_w, $img_h);
         }
         
         return '<img class="shortcode-image" src="' . $src . '" width="' . $img_w.'px' . '" height="' . $img_h.'px' . '" alt="' . $alt . '" title="' . $title . '" style="' . $align_style . '">';
      }
   }
   
   /**
    * Embed image with modalbox feature
    * 
    * @since 1.1
    * 
    * @param array $atts Shortcode attributes
    * @return string Thumbnail with link to open modal box
    * 
    * @example [image_modal src="image/data/your_image.jpg" /]
    * @example [image_modal src="image/data/your_image.jpg" img_w="450" img_h="280" title="" alt="" align="" caption="" load_script="1" /]
    */
   function image_modal($atts, $content = '') {
      $this->language->load('common/shortcodes_default');
      
      extract($this->shortcodes->shortcode_atts(array(
         'src'       => '',
         'img_w'     => 200,
         'img_h'     => 200,
         'title'     => '',
         'alt'       => '',
         'align'     => 'left', // left, right, center
         'caption'   => $this->language->get('imgModal_caption'),
         'load_script'  => 0,
         'cache'     => 1
      ), $atts));
      
      if (!$src && $content) { $src = $content; }
      if (!$alt & $title) { $alt = $title; }
      if ($align == 'right') {
         $align_style = 'float:right;margin:0 0 10px 10px;';
      } elseif ($align == 'center') {
         $align_style = 'display:block;margin:0 auto 15px;';
      } else {
         $align_style = 'float:left;margin:0 10px 10px 0;';
      }
      
      $src_resize    = str_replace('image/', '', $src);
      $script_load   = '';
      
      if ($load_script) {
         $script_load = '<script type="text/javascript"><!--
               $(document).ready(function() {
                  $(".modalbox").colorbox({
                     overlayClose: true,
                     opacity: 0.5
                  });
               });
               //--></script> ';
         $script_load .= '<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/colorbox/colorbox.css" media="screen" />';
         $script_load .= '<script type="text/javascript" src="catalog/view/javascript/jquery/colorbox/jquery.colorbox-min.js"></script>';
      }
      
      if (is_file(DIR_IMAGE . $src_resize)) {
         if ($cache) {
            $this->load->model('tool/image');
            $src_thumb  = $this->model_tool_image->resize($src_resize, $img_w, $img_h);
         } else {
            $src_thumb  = $src;
         }

         $html  = '<div style="' . $align_style . '">';
         $html .= '<a href="' . $src . '" title="' . $title . '" class="colorbox modalbox" style="text-decoration:none; outline:0;">';
         $html .= '<img class="shortcode-image-modal" src="' . $src_thumb . '" width="' . $img_w.'px' . '" height="' . $img_h.'px' . '" alt="' . $alt . '" title="' . $title . '">';
         $html .= '<div class="help" style="font-style:italic;">' . $caption . '</div>';
         $html .= '</a>';
         $html .= $script_load;
         $html .= '</div>';
         
         return $html;
      }
   }
   
   /**
    * Show lite System Information 
    * (full: http://www.echothemes.com/extensions/system-information.html)
    * 
    * @since 1.0
    * 
    * @return string List of system information
    * 
    * @example [debug /]
    */
   function debug() {
      $this->language->load('common/shortcodes_default');
      
      $data    = '<h3>' . $this->language->get('debug_title') . ' - ' . date('d M, Y') . '</h3>';
      $data   .= '<table class="sc-debug">';
      $data   .= '<tr><td>' . $this->language->get('debug_opencart') . '</td><td>: v' . VERSION . '</td></tr>';
      if (isset(VQMod::$_vqversion)) {
         $data   .=  '<tr><td>' . $this->language->get('debug_vqmod') . '</td><td>: v' . VQMod::$_vqversion . '</td></tr>';
      }
      $data   .= '<tr><td>' . $this->language->get('debug_shortcodes') . '</td><td>: v' . SHORTCODES_VERSION . '</td></tr>';
      $data   .= '</table>';
      $data   .= '<table class="sc-debug">';
      $data   .= '<tr><td>' . $this->language->get('debug_php') . '</td><td>: v.' . phpversion() . '</td></tr>';
      $data   .= '<tr><td>' . $this->language->get('debug_safemode') . '</td><td>: ' . ((ini_get('safe_mode')) ? $this->language->get('text_on') . ' <span class="sc-alert">- ' . $this->language->get('text_req_off') . '</span>' :  $this->language->get('text_off') . ' <span class="sc-good">- ' . $this->language->get('text_good') . '</span>') . '</td></tr>';
      $data   .= '<tr><td>' . $this->language->get('debug_reg_global') . '</td><td>: ' . ((ini_get('register_globals')) ? $this->language->get('text_on') . ' <span class="sc-alert">- ' . $this->language->get('text_req_off') . '</span>' :  $this->language->get('text_off') . ' <span class="sc-good">- ' . $this->language->get('text_good') . '</span>') . '</td></tr>';
      $data   .= '<tr><td>' . $this->language->get('debug_gpc') . '</td><td>: ' . ((ini_get('magic_quotes_gpc') || get_magic_quotes_gpc()) ? $this->language->get('text_on') . ' <span class="sc-alert">- ' . $this->language->get('text_req_off') . '</span>' :  $this->language->get('text_off') . ' <span class="sc-good">- ' . $this->language->get('text_good') . '</span>') . '</td></tr>';
      $data   .= '<tr><td>' . $this->language->get('debug_session') . '</td><td>: ' . ((ini_get('session_auto_start')) ? $this->language->get('text_on') . ' <span class="sc-alert">- ' . $this->language->get('text_req_off') . '</span>' :  $this->language->get('text_off') . ' <span class="sc-good">- ' . $this->language->get('text_good') . '</span>') . '</td></tr>';
      $data   .= '<tr><td>' . $this->language->get('debug_fopen') . '</td><td>: ' . ((ini_get('allow_url_fopen')) ? $this->language->get('text_on') . ' <span class="sc-good">- ' . $this->language->get('text_good') . '</span>' :  $this->language->get('text_off') . ' <span class="sc-alert">- ' . $this->language->get('text_req_on') . '</span>') . '</td></tr>';
      if(VERSION >= '1.5.4') {
         $data   .= '<tr><td>' . $this->language->get('debug_mcrypt') . '</td><td>: ' . ((extension_loaded('mcrypt')) ? $this->language->get('text_on') . ' <span class="sc-good">- ' . $this->language->get('text_good') . '</span>' :  $this->language->get('text_off') . ' <span class="sc-alert">- ' . $this->language->get('text_req_on') . '</span>') . '</td></tr>';
      }
      $data   .= '<tr><td>' . $this->language->get('debug_upload') . '</td><td>: ' . ((ini_get('file_uploads')) ? $this->language->get('text_on') . ' <span class="sc-good">- ' . $this->language->get('text_good') . '</span>' :  $this->language->get('text_off') . ' <span class="sc-alert">- ' . $this->language->get('text_req_on') . '</span>') . '</td></tr>';
      $data   .= '<tr><td>' . $this->language->get('debug_cookies') . '</td><td>: ' . ((ini_get('session.use_cookies')) ? $this->language->get('text_on') . ' <span class="sc-good">- ' . $this->language->get('text_good') . '</span>' :  $this->language->get('text_off') . ' <span class="sc-alert">- ' . $this->language->get('text_req_on') . '</span>') . '</td></tr>';
      $data   .= '<tr><td>' . $this->language->get('debug_gd') . '</td><td>: ' . ((extension_loaded('gd')) ? $this->language->get('text_on') . ' <span class="sc-good">- ' . $this->language->get('text_good') . '</span>' :  $this->language->get('text_off') . ' <span class="sc-alert">- ' . $this->language->get('text_req_on') . '</span>') . '</td></tr>';
      $data   .= '<tr><td>' . $this->language->get('debug_curl') . '</td><td>: ' . ((extension_loaded('curl')) ? $this->language->get('text_on') . ' <span class="sc-good">- ' . $this->language->get('text_good') . '</span>' :  $this->language->get('text_off') . ' <span class="sc-alert">- ' . $this->language->get('text_req_on') . '</span>') . '</td></tr>';
      $data   .= '<tr><td>' . $this->language->get('debug_fsock') . '</td><td>: ' . ((extension_loaded('sockets')) ? $this->language->get('text_on') . ' <span class="sc-good">- ' . $this->language->get('text_good') . '</span>' :  $this->language->get('text_off') . ' <span class="sc-alert">- ' . $this->language->get('text_req_on') . '</span>') . '</td></tr>';
      $data   .= '<tr><td>' . $this->language->get('debug_zip') . '</td><td>: ' . ((extension_loaded('zlib')) ? $this->language->get('text_on') . ' <span class="sc-good">- ' . $this->language->get('text_good') . '</span>' :  $this->language->get('text_off') . ' <span class="sc-alert">- ' . $this->language->get('text_req_on') . '</span>') . '</td></tr>';
      $data   .= '<tr><td>' . $this->language->get('debug_xml') . '</td><td>: ' . ((extension_loaded('xml')) ? $this->language->get('text_on') . ' <span class="sc-good">- ' . $this->language->get('text_good') . '</span>' :  $this->language->get('text_off') . ' <span class="sc-alert">- ' . $this->language->get('text_req_on') . '</span>') . '</td></tr>';
      $data   .= '</table>';

      $style   = '<style>';
      $style  .= '.sc-debug { width:400px; border-collapse:separate; border-spacing:0; margin-bottom:20px; line-height:16px; }';
      $style  .= '.sc-debug > tbody > tr:nth-child(odd) > td { background-color:#f2f2f2; }';
      $style  .= '.sc-debug td { padding:6px 10px; vertical-align:top; }';
      $style  .= '.sc-debug td:first-child { width:175px; }';
      $style  .= '.sc-alert { color:#d00; }';
      $style  .= '.sc-good { color:#1da00c; font-weight:bold; }';
      $style  .= '</style>';
      
      // Show debug only for admin user
      $this->load->library('user');
      $this->user = new User($this->registry);

      if ($this->user->isLogged()) {
         $html = '<div class="shortcode-debug">' . $style . $data . '</div>';
         
         return $html;
      }
   }

    /**
    * Load module slideshow
    * 
    * @since 1.0
    * 
    * @param array $atts Shortcode attributes
    * @return string Count of Designer
    * 
    * @example [designer_count num="3" /]
    */
   function designer_count($atts) {
      extract($this->shortcodes->shortcode_atts(array(
         'num'     => 3,
      ), $atts));
      $count = Make::a('catalog/manufacturer')->create()->getCount();
      return $count > 0 ? ($count > $num ? ($count - $num) : $count) : '';
   }

    /**
    * Load Featured Product
    * 
    * @since 1.0
    * 
    * @param array $atts Shortcode attributes
    * @return string Featured Product HTML
    * 
    * @example [featured_product id="1" bkg_img="" /]
    */
   function featured_product($atts) {
      extract($this->shortcodes->shortcode_atts(array(
         'id' => 0,
         'bkg_img' => ''
      ), $atts));
      $content = '';
      if($id) {
          $this->language->load('common/shortcodes_default');
          $oModel = Make::a('catalog/product')->create();
          $oProd = $oModel->getProduct($id);
          if($oProd) {
              $this->data = $oProd->toArray();
              $this->data['id'] = $id;
              $this->data['bkg_img'] = $bkg_img;
              $this->data['price'] = $this->currency->format($this->tax->calculate($oProd->price, $oProd->tax_class_id,$this->config->get('config_tax')));
              $special = $oModel->getProductSpecial($id);
              if($special) {
                  $this->data['special'] = $this->currency->format($this->tax->calculate($special['price'], $oProd->tax_class_id,$this->config->get('config_tax')));
              }
              $status = $oModel->getProductStockStatus($id);
              $this->data['stock_status'] = '';
              if(!empty($status[0])) {
                $this->data['stock_status'] = sprintf($this->language->get('order_product_stock_status'),$status[0]['quantity'],$status[0]['total_qty']);
              }
              $this->data['sUrl'] = makeUrl('product/product',array('product_id=' . $id),true);
              $this->data['cart_url'] = makeUrl('checkout/cart',array(),true,true);
              $this->data['order_product_button_text'] = $this->language->get('order_product_button_text');
              if(!$bkg_img) {
                  $this->data['image'] = HTTP_IMAGE . $oProd->image;
              }
              $content = $this->loadFetch('module/featured_product');
          }
      }
      return $content;
   }

    /**
    * Load Trending Product
    * 
    * @since 1.0
    * 
    * @param array $atts Shortcode attributes
    * @return string Trending Product HTML
    * 
    * @example [trending_product id="1" bkg_img="" /]
    */
   function trending_product($atts) {
      extract($this->shortcodes->shortcode_atts(array(
         'id' => 0,
         'bkg_img' => ''
      ), $atts));
      $content = '';
      if($id) {
          $this->language->load('common/shortcodes_default');
          $oModel = Make::a('catalog/product')->create();
          $oProd = $oModel->getProduct($id);
          if($oProd) {
              $this->data = $oProd->toArray();
              $this->data['id'] = $id;
              $this->data['price'] = $this->currency->format($this->tax->calculate($oProd->price, $oProd->tax_class_id,$this->config->get('config_tax')));
              $status = $oModel->getProductStockStatus($id,date('Y-m-d'));
              $this->data['purchased_today'] = '';
              if(!empty($status[0]) && $status[0]['quantity'] > 0) {
                  $content = sprintf($this->language->get('order_product_purchased_today'),$status[0]['quantity']);
              }
              $this->data['sUrl'] = makeUrl('product/product',array('product_id=' . $id),true);
              $this->data['image'] = HTTP_IMAGE . $oProd->image;
              $content = $this->loadFetch('module/trending_product');
          }
      } 
      return $content;
   }
}