<?php
class ControllerFeedGoogleSitemap extends Controller {
	public function index() {
		if ($this->config->get('google_sitemap_status')) { 
			$output  = '<?xml version="1.0" encoding="UTF-8"?>';
			$output .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
			
			$this->load->model('tool/seo_url');
			
			$this->load->model('catalog/product');
			
			$products = $this->model_catalog_product->getProducts();
			
			foreach ($products as $product) {
				$output .= '<url>';
				$output .= '<loc>' . $this->model_tool_seo_url->rewrite($this->url->http('product/product&product_id=' . $product['product_id'])) . '</loc>';
				$output .= '<changefreq>weekly</changefreq>';
				$output .= '<priority>1.0</priority>';
				$output .= '</url>';	
			}
			
			$this->load->model('catalog/category');
			
			$categories = $this->model_catalog_category->getCategories();
			
			$output .= $this->getCategories(0);
			
			$this->load->model('catalog/manufacturer');
			
			$manufacturers = $this->model_catalog_manufacturer->getManufacturers();
			
			foreach ($manufacturers as $manufacturer) {
				$output .= '<url>';
				$output .= '<loc>' . $this->model_tool_seo_url->rewrite($this->url->http('product/manufacturer&manufacturer_id=' . $manufacturer['manufacturer_id'])) . '</loc>';
				$output .= '<changefreq>weekly</changefreq>';
				$output .= '<priority>0.7</priority>';
				$output .= '</url>';			
			}
			
			$this->load->model('catalog/information');
			
			$informations = $this->model_catalog_information->getInformations();
			
			foreach ($informations as $information) {
				$output .= '<url>';
				$output .= '<loc>' . $this->model_tool_seo_url->rewrite($this->url->http('product/information&information_id=' . $information['information_id'])) . '</loc>';
				$output .= '<changefreq>weekly</changefreq>';
				$output .= '<priority>0.5</priority>';
				$output .= '</url>';	
			}
			
			$this->load->model('catalog/news');
			
			$newss = $this->model_catalog_news->getnewss();
			
			foreach ($newss as $news) {
				$output .= '<url>';
				$output .= '<loc>' . $this->model_tool_seo_url->rewrite($this->url->http('news/news&news_id=' . $news['news_id'])) . '</loc>';
				$output .= '<changefreq>weekly</changefreq>';
				$output .= '<priority>0.5</priority>';
				$output .= '</url>';	
			}
			$this->load->model('catalog/cnews');
			
			$categories = $this->model_catalog_cnews->getCnewss();
			
			$output .= $this->getCnewss(0);
			
			$output .= '</urlset>';
			
			$this->response->addHeader('Content-Type', 'application/xml');
			$this->response->setOutput($output);
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
			$output .= '<loc>' . $this->model_tool_seo_url->rewrite($this->url->http('product/category&category_id=' . $result['category_id'])) . '</loc>';
			$output .= '<changefreq>weekly</changefreq>';
			$output .= '<priority>0.7</priority>';
			$output .= '</url>';			
			
        	$output .= $this->getCategories($result['category_id'], $new_path);
		}
 
		return $output;
	}
	protected function getCnewss($parent_id, $current_path = '') {
		$output = '';
		
		$results = $this->model_catalog_cnews->getCnewss($parent_id);
		
		foreach ($results as $result) {
			if (!$current_path) {
				$new_path = $result['cnews_id'];
			} else {
				$new_path = $current_path . '_' . $result['cnews_id'];
			}

			$output .= '<url>';
			$output .= '<loc>' . $this->model_tool_seo_url->rewrite($this->url->http('news/cnews&cnews_id=' . $result['cnews_id'])) . '</loc>';
			$output .= '<changefreq>weekly</changefreq>';
			$output .= '<priority>0.7</priority>';
			$output .= '</url>';			
			
        	$output .= $this->getCnewss($result['cnews_id'], $new_path);
		}
 
		return $output;
	}	
}
?>