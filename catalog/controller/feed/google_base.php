<?php
class ControllerFeedGoogleBase extends Controller {
	public function index() {
		if ($this->config->get('google_base_status')) {
   			header('Content-Type: application/rss+xml');
            print '<?xml version="1.0" encoding="UTF-8" ?>';
			print'<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">';
            print '<channel>';
			print '<title><![CDATA[' . $this->config->get('config_name') . ']]></title>';
			print '<description><![CDATA[' . $this->config->get('config_meta_description') . ']]></description>';
			print '<link><![CDATA[' . HTTP_SERVER . ']]></link>';

			$this->load->model('tool/image');

			$products = Make::a('catalog/product')->create()->getProducts();

			foreach ($products as $product) {
				if ($product['description']) {
					print '<item>';
					print '<title><![CDATA[' . html_entity_decode($product['name'], ENT_QUOTES, 'UTF-8') . ']]></title>';
					print '<link><![CDATA[' . HTTP_SERVER . 'product/product&amp;product_id=' . $product['product_id'] . ']]></link>';
					print '<description><![CDATA[' . $product['description'] . ']]></description>';
					print '<g:brand><![CDATA[' . html_entity_decode($product['manufacturer'], ENT_QUOTES, 'UTF-8') . ']]></g:brand>';
					print '<g:condition>new</g:condition>';
					print '<g:id><![CDATA[' . $product['product_id'] . ']]></g:id>';

					if ($product['image']) {
						print '<g:image_link><![CDATA[' . $this->model_tool_image->resize($product['image'], 500, 500) . ']]></g:image_link>';
					} else {
						print '<g:image_link><![CDATA[' . $this->model_tool_image->resize('no_image.jpg', 500, 500) . ']]></g:image_link>';
					}

					print '<g:mpn><![CDATA[' . $product['model'] . ']]></g:mpn>';

					$special = Make::a('catalog/product')->create()->getProductSpecial($product['product_id']);

					if ($special) {
						print '<g:price><![CDATA[' . $this->tax->calculate($special['price'], $product['tax_class_id']) . ']]></g:price>';
					} else {
						print '<g:price><![CDATA[' . $this->tax->calculate($product['price'], $product['tax_class_id']) . ']]></g:price>';
					}

					$categories = Make::a('catalog/product')->create()->getCategories($product['product_id']);

					foreach ($categories as $category) {
						$path = $this->getPath($category['category_id']);

						if ($path) {
							$string = '';

							foreach (explode('_', $path) as $path_id) {
								$category_info = Make::a('catalog/category')->create()->getCategory($path_id);

								if ($category_info) {
									if (!$string) {
										$string = $category_info['name'];
									} else {
										$string .= ' &gt; ' . $category_info['name'];
									}
								}
							}

							print '<g:product_type><![CDATA[' . $string . ']]></g:product_type>';
						}
					}

					print '<g:quantity><![CDATA[' . $product['quantity'] . ']]></g:quantity>';
					print '<g:upc><![CDATA[' . $product['model'] . ']]></g:upc>';
					print '<g:weight><![CDATA[' . $this->weight->format($product['weight'], $product['weight_class']) . ']]></g:weight>';
					print '</item>';
				}
			}

			print '</channel>';
			print '</rss>';


			exit();
		}
	}

	protected function getPath($parent_id, $current_path = '') {
		$category_info = Make::a('catalog/category')->create()->getCategory($parent_id);

		if ($category_info) {
			if (!$current_path) {
				$new_path = $category_info['category_id'];
			} else {
				$new_path = $category_info['category_id'] . '_' . $current_path;
			}

			$path = $this->getPath($category_info['parent_id'], $new_path);

			if ($path) {
				return $path;
			} else {
				return $new_path;
			}
		}
	}
}
?>