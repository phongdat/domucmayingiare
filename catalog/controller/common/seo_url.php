<?php
class ControllerCommonSeoUrl extends Controller {
	public function index() {
		if (isset($this->request->get['_route_'])) {
			$parts = str_replace(HTTP_SERVER,'', $this->request->get['_route_']);

				if ($parts) {
					$url = explode('/', $parts);
					if(sizeof($url) == 1) {
					$url[1] = "";
					}
					
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($url[0]) . "'");
					if ($query->num_rows) {
						$url_query = explode('=', $query->row['query']);
						
						if ($url_query[0] == 'product_id') {
							$this->request->get['product_id'] = $url_query[1];
						}
						
						if ($url_query[0] == 'category_id') {
							$this->request->get['category_id'] = $url_query[1];
						}
						
						if ($url_query[0] == 'information_id') {
							$this->request->get['information_id'] = $url_query[1];
						}
						
						if ($url_query[0] == 'news_id') {
							$this->request->get['news_id'] = $url_query[1];
						}
						
						if ($url_query[0] == 'cnews_id') {
							$this->request->get['cnews_id'] = $url_query[1];
						}
					} else {
						if (is_numeric($url[0]) && strpos($url[1],".html")) {
							$this->request->get['product_id'] = $url[0];
						}
						
						if (is_numeric($url[0]) && !strpos($url[1],".html")) {
							$this->request->get['category_id'] = $url[0];
						}
						
						if ($url[0] == 'news' && !strpos($url[sizeof($url)-1],".html") && $url[1]) {
							$this->request->get['cnews_id'] = $url[1];
						}
						
						if ($url[0] == 'news' && strpos($url[sizeof($url)-1],".html") && $url[1]) {
							$this->request->get['news_id'] = $url[1];
						}
						
						if ($url[0] == 'info' && is_numeric($url[1])) {
							$this->request->get['information_id'] = $url[1];
						}
					}
					
					if ($url[0] == 'manu') {
						$this->request->get['manufacturer_id'] = $url[1];
					}
					
					if ($url[0] == 'search' && !$url[1]) {
						$this->request->get['search'] = 1;
					}
					
					if ($url[0] == 'search' && $url[1] == 'keyword' && sizeof($url)>=3) {
						$this->request->get['keyword'] = str_replace("-"," ",$url[2]);
					}
					
					$information_files = glob(DIR_APPLICATION . 'controller/information/*.php');
					$information = array();
					foreach ($information_files as $file) {
					$information[] = basename($file, '.php');
					}
					if ($url[0] == 'info' && (in_array($url[1], $information))) {
						$this->request->get['information'] = str_replace('info/','information/',$parts);
					}
					
					$account_files = glob(DIR_APPLICATION . 'controller/account/*.php');
					$account = array();
					foreach ($account_files as $file) {
					$account[] = basename($file, '.php');
					}
					if ($url[0] == 'account' && (in_array($url[1], $account))) {
						$this->request->get['account'] = $parts;
					}
					
					$checkout_files = glob(DIR_APPLICATION . 'controller/checkout/*.php');
					$checkout = array();
					foreach ($checkout_files as $file) {
					$checkout[] = basename($file, '.php');
					}
					if ($url[0] == 'checkout' && (in_array($url[1], $checkout))) {
						$this->request->get['checkout'] = $parts;
					}
					
					$product_files = glob(DIR_APPLICATION . 'controller/product/*.php');
					$product = array();
					foreach ($product_files as $file) {
					$product[] = basename($file, '.php');
					}
					if ($url[0] == 'product' && (in_array($url[1], $product))) {
						$this->request->get['product'] = $url[0] . '/' . $url[1];
					}
					
					if ($url[0] == 'news' && !$url[1]) {
						$this->request->get['news'] = 1;
					}
				}
				
			if (isset($this->request->get['product_id'])) {
				$this->request->get['route'] = 'product/product';
			} elseif (isset($this->request->get['category_id'])) {
				$this->request->get['route'] = 'product/category';
			} elseif (isset($this->request->get['information_id'])) {
				$this->request->get['route'] = 'information/information';
			} elseif (isset($this->request->get['manufacturer_id'])) {
				$this->request->get['route'] = 'product/manufacturer';
			} elseif (isset($this->request->get['search'])) {
				$this->request->get['route'] = 'product/search';
			} elseif (isset($this->request->get['keyword'])) {
				$this->request->get['route'] = 'product/search';
			} elseif (isset($this->request->get['product'])) {
				$this->request->get['route'] = $this->request->get['product'];
			} elseif (isset($this->request->get['account'])) {
				$this->request->get['route'] = $this->request->get['account'];
			} elseif (isset($this->request->get['information'])) {
				$this->request->get['route'] = $this->request->get['information'];
			} elseif (isset($this->request->get['checkout'])) {
				$this->request->get['route'] = $this->request->get['checkout'];
			} elseif (isset($this->request->get['news_id'])) {
				$this->request->get['route'] = 'news/news';
			} elseif (isset($this->request->get['news'])) {
				$this->request->get['route'] = 'news/cnews';
			} elseif (isset($this->request->get['cnews_id'])) {
				$this->request->get['route'] = 'news/cnews';
			}
			
			if (isset($this->request->get['route'])) {
				return $this->forward($this->request->get['route']);
			}
		}
	}
}
?>