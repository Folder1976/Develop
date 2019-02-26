<?php
class ControllerExtensionFeedGoogleSitemap extends Controller {
	public function index() {
		
		
		
		ini_set('error_reporting', E_ALL);
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		set_time_limit(0);
		$count = 1;
		$file = 'sitemap.xml';

		if ($this->config->get('google_sitemap_status')) {
			$output  = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
			$output .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">'."\n";

			$this->load->model('catalog/product');
			$this->load->model('tool/image');

			$products = $this->model_catalog_product->getProducts();

			
			
			foreach ($products as $product) {
				
				if(date('Y', strtotime($product['date_modified']) < 2018)){
					$product['date_modified'] = $product['date_added'];
				}

				if ($product['image']) {
					$output .= '<url>'."\n";
					$output .= '<loc>' . $this->url->link('product/product', 'product_id=' . $product['product_id']) . '</loc>'."\n";
					$output .= '<changefreq>weekly</changefreq>'."\n";
					$output .= '<lastmod>' . date('Y-m-d\TH:i:sP', strtotime($product['date_modified'])) . '</lastmod>'."\n";
					$output .= '<priority>1.0</priority>'."\n";
					$output .= '<image:image>'."\n";
					$output .= '<image:loc>' . $this->model_tool_image->resize($product['image'], $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_height')) . '</image:loc>'."\n";
					$output .= '<image:caption>' . $product['name'] . '</image:caption>'."\n";
					$output .= '<image:title>' . $product['name'] . '</image:title>'."\n";
					$output .= '</image:image>'."\n";
					$output .= '</url>'."\n";
				}
				
				if($count++ > 500){
					$count = 1;
					sleep(2);
				}
				
				
			}

			$this->load->model('catalog/category');

			$output .= $this->getCategories(0);

			$this->load->model('catalog/manufacturer');

			$manufacturers = $this->model_catalog_manufacturer->getManufacturers();

			foreach ($manufacturers as $manufacturer) {
				$output .= '<url>'."\n";
				$output .= '<loc>' . $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer['manufacturer_id']) . '</loc>'."\n";
				$output .= '<changefreq>weekly</changefreq>'."\n";
				$output .= '<priority>0.7</priority>'."\n";
				$output .= '</url>'."\n";

				$products = $this->model_catalog_product->getProducts(array('filter_manufacturer_id' => $manufacturer['manufacturer_id']));

				foreach ($products as $product) {
					$output .= '<url>'."\n";
					$output .= '<loc>' . $this->url->link('product/product', 'manufacturer_id=' . $manufacturer['manufacturer_id'] . '&product_id=' . $product['product_id']) . '</loc>'."\n";
					$output .= '<changefreq>weekly</changefreq>'."\n";
					$output .= '<priority>1.0</priority>'."\n";
					$output .= '</url>'."\n";
					
					if($count++ > 500){
						$count = 1;
						sleep(2);
					}
				}
			}

			$this->load->model('catalog/information');

			$informations = $this->model_catalog_information->getInformations();

			foreach ($informations as $information) {
				$output .= '<url>'."\n";
				$output .= '<loc>' . $this->url->link('information/information', 'information_id=' . $information['information_id']) . '</loc>'."\n";
				$output .= '<changefreq>weekly</changefreq>'."\n";
				$output .= '<priority>0.5</priority>'."\n";
				$output .= '</url>'."\n";
				
				if($count++ > 500){
					$count = 1;
					sleep(2);
				}
				
			}

			$output .= '</urlset>';

			//$this->response->addHeader('Content-Type: application/xml');
			//$this->response->setOutput($output);
			//$current = file_get_contents($file);
			file_put_contents($file, $output);
		}
	}

	protected function getCategories($parent_id, $current_path = '') {
		$output = '';

		$results = $this->model_catalog_category->getCategories($parent_id);

		foreach ($results as $result) {
			if (!$current_path) {
				$new_path = $result['category_id'];
			} else {
				$new_path = $current_path . '_' . $result['category_id'];
			}

			$output .= '<url>';
			$output .= '<loc>' . $this->url->link('product/category', 'path=' . $new_path) . '</loc>';
			$output .= '<changefreq>weekly</changefreq>';
			$output .= '<priority>0.7</priority>';
			$output .= '</url>';

			$products = $this->model_catalog_product->getProducts(array('filter_category_id' => $result['category_id']));

			foreach ($products as $product) {
				$output .= '<url>';
				$output .= '<loc>' . $this->url->link('product/product', 'path=' . $new_path . '&product_id=' . $product['product_id']) . '</loc>';
				$output .= '<changefreq>weekly</changefreq>';
				$output .= '<priority>1.0</priority>';
				$output .= '</url>';
			}

			$output .= $this->getCategories($result['category_id'], $new_path);
		}

		return $output;
	}
}

