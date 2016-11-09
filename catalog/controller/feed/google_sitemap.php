<?php
class ControllerFeedGoogleSitemap extends Controller {
	public function index() {
		if ($this->config->get('google_sitemap_status')) {
            header('Content-Type: application/xml');
            print '<?xml version="1.0" encoding="UTF-8"?>';
			print  '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
			
			$this->load->model('tool/seo_url');
			
			$products = Make::a('catalog/product')->create()->getProducts();
			
			foreach ($products as $product) {
				print  '<url>';
				print  '<loc><![CDATA[' . str_replace('&', '&amp;', $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'product/product&product_id=' . $product['product_id'])) . ']]></loc>';
				print  '<changefreq>weekly</changefreq>';
				print  '<priority>1.0</priority>';
				print  '</url>';	
			}
			
			$categories = Make::a('catalog/category')->create()->getCategories();
			
			print  $this->getCategories(0);
			
			$this->load->model('catalog/manufacturer');
			
			$manufacturers = $this->model_catalog_manufacturer->getManufacturers();
			
			foreach ($manufacturers as $manufacturer) {
				print  '<url>';
				print  '<loc><![CDATA[' . str_replace('&', '&amp;', $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'product/manufacturer&manufacturer_id=' . $manufacturer['manufacturer_id'])) . ']]></loc>';
				print  '<changefreq>weekly</changefreq>';
				print  '<priority>0.7</priority>';
				print  '</url>';	
				
				$products = Make::a('catalog/product')->create()->getProductsByManufacturerId($manufacturer['manufacturer_id']);
				
				foreach ($products as $product) {
					print  '<url>';
					print  '<loc><![CDATA[' . str_replace('&', '&amp;', $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'product/product&manufacturer_id=' . $manufacturer['manufacturer_id'] . '&product_id=' . $product['product_id'])) . ']]></loc>';
					print  '<changefreq>weekly</changefreq>';
					print  '<priority>1.0</priority>';
					print  '</url>';	
				}			
			}
			
			$this->load->model('catalog/information');
			
			$informations = $this->model_catalog_information->getInformations();
			
			foreach ($informations as $information) {
				print  '<url>';
				print  '<loc><![CDATA[' . str_replace('&', '&amp;', $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'product/information&information_id=' . $information['information_id'])) . ']]></loc>';
				print  '<changefreq>weekly</changefreq>';
				print  '<priority>0.5</priority>';
				print  '</url>';	
			}
			
			print  '</urlset>';
			exit();
		}
	}
	
	protected function getCategories($parent_id, $current_path = '') {
		$output = '';
		
		$results = Make::a('catalog/category')->create()->getCategories($parent_id);
		
		foreach ($results as $result) {
			if (!$current_path) {
				$new_path = $result['category_id'];
			} else {
				$new_path = $current_path . '_' . $result['category_id'];
			}

			print  '<url>';
			print  '<loc><![CDATA[' . $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'product/category&path=' . $new_path) . '>>]</loc>';
			print  '<changefreq>weekly</changefreq>';
			print  '<priority>0.7</priority>';
			print  '</url>';			

			$products = Make::a('catalog/product')->create()->getProductsByCategoryId($result['category_id']);
			
			foreach ($products as $product) {
				print  '<url>';
				print  '<loc><![CDATA[' . $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'product/product&path=' . $new_path . '&product_id=' . $product['product_id']) . ']]></loc>';
				print  '<changefreq>weekly</changefreq>';
				print  '<priority>1.0</priority>';
				print  '</url>';	
			}	
			
        	print  $this->getCategories($result['category_id'], $new_path);
		}
 
		return $output;
	}		
}
?>