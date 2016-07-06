<?php
class ControllerCommonHeader extends Controller {
	protected function index() {
		$this->load->model('tool/seo_url');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && isset($this->request->post['currency_code'])) {
      		$this->currency->set($this->request->post['currency_code']);

			if (isset($this->request->post['redirect'])) {
				$this->redirect($this->request->post['redirect']);
			} else {
				$this->redirect($this->model_tool_seo_url->rewrite($this->url->http('common/home')));
			}
   		}
		if ($this->config->get('header_status')) {
			$this->data['header'] = html_entity_decode($this->config->get('header_code'), ENT_QUOTES, 'UTF-8');
		} else {
			$this->data['header'] = '';
		}
		$this->language->load('common/header');
		$this->data['title'] = $this->document->title;
		$this->data['description'] = $this->document->description;
		$this->data['keywords'] = $this->document->keywords;

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['base'] = HTTPS_SERVER;
		} else {
			$this->data['base'] = HTTP_SERVER;
		}
		
		if ($this->config->get('google_analytics_status')) {
			$this->data['google_analytics'] = html_entity_decode($this->config->get('google_analytics_code'), ENT_QUOTES, 'UTF-8');
		} else {
			$this->data['google_analytics'] = '';
		}
		
		if(isset($this->request->get['route'])){
			$link = explode('/', $this->request->get['route']);
			$this->data['home_select'] = 0;
			if ($link[0] == "news"){ $this->data['news_select'] = 1; } else { $this->data['news_select'] = 0; }
			if ($link[0] == "information" && $link[1] == "contact"){ $this->data['contact_select'] = 1; } else { $this->data['contact_select'] = 0; }
		} else {
			$this->data['home_select'] = 1;
			$this->data['news_select'] = 0;
			$this->data['contact_select'] = 0;
		}
// Menu
		if (isset($this->request->get['category_id'])) {
			$this->data['category_id'] = $this->request->get['category_id'];
		} elseif (isset($this->document->path)) {
			$path = explode('_', $this->document->path);
		
			$this->data['category_id'] = end($path);
		} else {
			$this->data['category_id'] = '';
		}
		$this->load->model('catalog/category');
		$this->data['categories'] = $this->getCategories(0);
//end menu

//cnews		
		$this->load->model('catalog/cnews');
		$cnews_info = $this->model_catalog_cnews->getCnewss(0);
		$this->data['cnews'] = array();
		foreach ($cnews_info as $result) {
			$this->data['cnews'][] = array(
				'name'  => $result['name'],
				'href'  => $this->model_tool_seo_url->rewrite($this->url->http('news/cnews&cnews_id=' . $result['cnews_id']))
			);
		}
//end news

// dang nhap
		$this->load->model('account/customer');
		if ($this->customer->isLogged()) {
				$customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
				if (isset($customer_info)) {
					$this->data['customername'] = $customer_info['customername'];
				}
		} else {
		$this->data['customername'] = '';
		}
		$this->data['text_create'] = $this->language->get('text_create');
    	$this->data['text_login'] = $this->language->get('text_login');
    	$this->data['text_logout'] = $this->language->get('text_logout');
    	$this->data['text_account'] = $this->language->get('text_account');
		$this->data['text_helu'] = $this->language->get('text_helu');
		$this->data['create_account'] = $this->model_tool_seo_url->rewrite($this->url->https('account/create'));
    	$this->data['account'] = $this->model_tool_seo_url->rewrite($this->url->https('account/account'));
		$this->data['logged'] = $this->customer->isLogged();
		$this->data['loginpopup'] = $this->model_tool_seo_url->rewrite($this->url->https('account/loginpopup'));
		$this->data['logout'] = $this->model_tool_seo_url->rewrite($this->url->http('account/logout'));
		$this->data['logoutpopup'] = $this->model_tool_seo_url->rewrite($this->url->https('account/logout/logoutpopup'));
// end dang nhap

		$this->data['charset'] = $this->language->get('charset');
		$this->data['lang'] = $this->language->get('code');
		$this->data['direction'] = $this->language->get('direction');
		$this->data['links'] = $this->document->links;	
		$this->data['styles'] = $this->document->styles;
		$this->data['scripts'] = $this->document->scripts;		
		$this->data['breadcrumbs'] = $this->document->breadcrumbs;
		$this->data['icon'] = $this->config->get('config_icon');
		
		if (isset($this->request->server['HTTPS']) && ($this->request->server['HTTPS'] == 'on')) {
			$this->data['logo'] = HTTPS_IMAGE . $this->config->get('config_logo');
		} else {
			$this->data['logo'] = HTTP_IMAGE . $this->config->get('config_logo');
		}
		
		$this->data['text_home'] = $this->language->get('text_home');
		$this->data['text_special'] = $this->language->get('text_special');
		$this->data['text_contact'] = $this->language->get('text_contact');
		$this->data['text_sitemap'] = $this->language->get('text_sitemap');
    	$this->data['text_cart'] = $this->language->get('text_cart'); 
    	$this->data['text_checkout'] = $this->language->get('text_checkout');
		$this->data['text_keyword'] = $this->language->get('text_keyword');
		$this->data['text_category'] = $this->language->get('text_category');
		$this->data['text_advanced'] = $this->language->get('text_advanced');
		$this->data['text_cart_chitiet'] = $this->language->get('text_cart_chitiet');
		$this->data['entry_search'] = $this->language->get('entry_search');
		$this->data['text_product'] = $this->language->get('text_product');
		$this->data['button_go'] = $this->language->get('button_go');
		$this->data['text_news'] = $this->language->get('text_news');
		$this->data['text_gioithieu'] = $this->language->get('text_gioithieu');
		$this->data['text_thongbao'] = $this->language->get('text_thongbao');
		$this->data['text_currency'] = $this->language->get('text_currency');

		$this->data['home'] = $this->model_tool_seo_url->rewrite($this->url->http('common/home'));
		$this->data['special'] = $this->model_tool_seo_url->rewrite($this->url->http('product/special'));
		$this->data['contact'] = $this->model_tool_seo_url->rewrite($this->url->http('information/contact'));
    	$this->data['sitemap'] = $this->model_tool_seo_url->rewrite($this->url->http('information/sitemap'));
    	$this->data['cart'] = $this->model_tool_seo_url->rewrite($this->url->http('checkout/cart'));
		$this->data['checkout'] = $this->model_tool_seo_url->rewrite($this->url->https('checkout/shipping'));
		$this->data['news_href'] = $this->model_tool_seo_url->rewrite($this->url->http('news/cnews'));
		$this->data['gioithieu'] = $this->model_tool_seo_url->rewrite($this->url->http('information/gioithieu'));
		
		if (isset($this->request->get['keyword'])) {
			$this->data['keyword'] = $this->request->get['keyword'];
		} else {
			$this->data['keyword'] = '';
		}
		
		$this->data['action'] = $this->model_tool_seo_url->rewrite($this->url->http('common/home'));

		if (!isset($this->request->get['route'])) {
			$this->data['redirect'] = $this->model_tool_seo_url->rewrite($this->url->http('common/home'));
		} elseif (isset($this->request->get['_route_'])) {
			$this->data['redirect'] = HTTP_SERVER . $this->request->get['_route_'];
		} else {
			
			$data = $this->request->get;
			
			unset($data['_route_']);
			
			$route = $data['route'];
			
			unset($data['route']);
			
			$url = '';
			
			if ($data) {
				$url = '&' . urldecode(http_build_query($data));
			}			
			
			$this->data['redirect'] = $this->url->http($route . $url);
		}
		
		$this->data['language_code'] = $this->session->data['language'];	
		
		$this->load->model('localisation/language');
		
		$this->data['languages'] = array();
		
		$results = $this->model_localisation_language->getLanguages();
		
		foreach ($results as $result) {
			if ($result['status']) {
				$this->data['languages'][] = array(
					'name'  => $result['name'],
					'code'  => $result['code'],
					'image' => $result['image']
				);	
			}
		}
		
		$this->data['currency_code'] = $this->currency->getCode(); 
		
		$this->load->model('localisation/currency');
		 
		 $this->data['currencies'] = array();
		 
		$results = $this->model_localisation_currency->getCurrencies();	
		
		foreach ($results as $result) {
			if ($result['status']) {
   				$this->data['currencies'][] = array(
					'title' => $result['title'],
					'code'  => $result['code']
				);
			}
		}

//cart
		$this->data['text_subtotal'] = $this->language->get('text_subtotal');
		$this->data['text_empty'] = $this->language->get('text_empty');
		$this->data['text_confirm'] = $this->language->get('text_confirm');
		$this->load->model('catalog/product');
    	$this->load->helper('image');
		$this->data['products'] = array();
		$sanpham = 0;
    	foreach ($this->cart->getProducts() as $result) {
        	$option_data = array();

        	foreach ($result['option'] as $option) {
          		$option_data[] = array(
            		'name'  => $option['name'],
            		'value' => $option['value']
          		);
        	}
			if ($result['image']) {
				$image = $result['image'];
			} else {
				$image = 'no_image.jpg';
			}
			
      		$this->data['products'][] = array(
				'key' 		 => $result['key'],
        		'name'     => $result['name'],
				'option'   => $option_data,
        		'quantity' => $result['quantity'],
				'stock'    => $result['stock'],
				'price'    => $this->currency->format($this->tax->calculate($result['price']*$result['quantity'], $result['tax_class_id'], $this->config->get('config_tax'))),
				'thumb'   => image_resize($image, 40, 40),
				'href'     => $this->model_tool_seo_url->rewrite($this->url->http('product/product&product_id=' . $result['product_id'])),
      		);
			$sanpham = $sanpham + $result['quantity'];
		}
		$this->data['sanpham'] = $sanpham;

    	$this->data['subtotal'] = $this->currency->format($this->cart->getTotal());
		if (!$this->config->get('config_customer_price')) {
			$this->data['display_price'] = TRUE;
		} elseif ($this->customer->isLogged()) {
			$this->data['display_price'] = TRUE;
		} else {
			$this->data['display_price'] = FALSE;
		}
		
// end cart

		$this->data['store'] = $this->config->get('config_store');
		$this->data['address'] = $this->config->get('config_address');
		$this->data['telephone'] = $this->config->get('config_telephone');
		$this->data['fax'] = $this->config->get('config_fax');
		$this->data['hotline'] = $this->config->get('config_hotline');
		
    	$this->data['text_address'] = $this->language->get('text_address');
		$this->data['text_emails'] = $this->language->get('text_emails');
		$this->data['text_hotline'] = $this->language->get('text_hotline');
    	$this->data['text_telephone'] = $this->language->get('text_telephone');
    	$this->data['text_fax'] = $this->language->get('text_fax');
		
		$this->id = 'header';
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/header.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/header.tpl';
		} else {
			$this->template = 'default/template/common/header.tpl';
		}
		
    	$this->render();
	}
	private function getCategories($parent_id, $current_path = '', $level = 0) {
		$level++;
		$data = array();
		
		$results = $this->model_catalog_category->getCategories($parent_id);
		
		foreach ($results as $result) {
			$children = $this->getCategories($result['category_id'], $level);
			$data[] = array(
				'category_id' => $result['category_id'],
				'href'        => $this->model_tool_seo_url->rewrite($this->url->http('product/category&category_id=' . $result['category_id'])),
				'children'		  => $children,
				'name'        => $result['name']
			);
		}
		
		return $data;
	}
	public function cart() {
		$this->language->load('common/header');
		$this->load->model('catalog/product');
		$this->load->model('tool/seo_url');
		$this->load->helper('image');
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (isset($this->request->post['remove'])) {
	    		$result = explode('_', $this->request->post['remove']);
          		$this->cart->remove(trim($result[1]));
      		} else {
				if (isset($this->request->post['option'])) {
					$option = $this->request->post['option'];
				} else {
					$option = array();	
				}
				
      			$this->cart->add($this->request->post['product_id'], $this->request->post['quantity'], $option);
			}		
		}
		$this->data['text_subtotal'] = $this->language->get('text_subtotal');
		$this->data['text_empty'] = $this->language->get('text_empty');
		$this->data['text_confirm'] = $this->language->get('text_confirm');
		$this->data['text_cart'] = $this->language->get('text_cart');
		$this->data['text_product'] = $this->language->get('text_product');
		$this->data['text_cart_chitiet'] = $this->language->get('text_cart_chitiet');
		$this->data['cart'] = $this->model_tool_seo_url->rewrite($this->url->http('checkout/cart'));
		$this->data['text_thongbao'] = $this->language->get('text_thongbao');

		$this->data['products'] = array();
		$sanpham = 0;
    	foreach ($this->cart->getProducts() as $result) {
        	$option_data = array();

        	foreach ($result['option'] as $option) {
          		$option_data[] = array(
            		'name'  => $option['name'],
            		'value' => $option['value']
          		);
        	}
			if ($result['image']) {
				$image = $result['image'];
			} else {
				$image = 'no_image.jpg';
			}
			
      		$this->data['products'][] = array(
				'key' 		 => $result['key'],
        		'name'     => $result['name'],
				'option'   => $option_data,
        		'quantity' => $result['quantity'],
				'stock'    => $result['stock'],
				'price'    => $this->currency->format($this->tax->calculate($result['price']*$result['quantity'], $result['tax_class_id'], $this->config->get('config_tax'))),
				'thumb'   => image_resize($image, 40, 40),
				'href'     => $this->model_tool_seo_url->rewrite($this->url->http('product/product&product_id=' . $result['product_id'])),
      		);
			$sanpham = $sanpham + $result['quantity'];
    	}
		$this->data['sanpham'] = $sanpham;
    	$this->data['subtotal'] = $this->currency->format($this->cart->getTotal());
		
		if (!$this->config->get('config_customer_price')) {
			$this->data['display_price'] = TRUE;
		} elseif ($this->customer->isLogged() && $this->customer->getCustomerGroupVip()) {
			$this->data['display_price'] = TRUE;
		} else {
			$this->data['display_price'] = FALSE;
		}
		if (isset($this->request->post['remove'])) {
			$this->data['remove'] = 1;
		} else {
			$this->data['remove'] = 0;
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/cart.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/cart.tpl';
		} else {
			$this->template = 'default/template/common/cart.tpl';
		}
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	public function account() {
// dang nhap

		$this->language->load('common/header');
		$this->load->model('tool/seo_url');
		$this->load->model('account/customer');
		$this->data['logged'] = $this->customer->isLogged();
		$this->data['text_confirm'] = $this->language->get('text_confirm');
		if ($this->customer->isLogged()) {
				$customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
				if (isset($customer_info)) {
					$this->data['customername'] = $customer_info['customername'];
				}
		} else {
		$this->data['customername'] = '';
		}
		$this->data['text_create'] = $this->language->get('text_create');
    	$this->data['text_login'] = $this->language->get('text_login');
    	$this->data['text_logout'] = $this->language->get('text_logout');
    	$this->data['text_account'] = $this->language->get('text_account');
		$this->data['text_helu'] = $this->language->get('text_helu');
		$this->data['create_account'] = $this->model_tool_seo_url->rewrite($this->url->https('account/create'));
    	$this->data['account'] = $this->model_tool_seo_url->rewrite($this->url->https('account/account'));
		$this->data['logged'] = $this->customer->isLogged();
		$this->data['loginpopup'] = $this->model_tool_seo_url->rewrite($this->url->https('account/loginpopup'));
		$this->data['logoutpopup'] = $this->model_tool_seo_url->rewrite($this->url->https('account/logout/logoutpopup'));
		$this->data['logout'] = $this->model_tool_seo_url->rewrite($this->url->http('account/logout'));
// end dang nhap
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/account.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/account.tpl';
		} else {
			$this->template = 'default/template/common/account.tpl';
		}
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	public function search() {

		$this->language->load('common/header');
		if (isset($this->request->get['keyword'])) {
			$this->data['keyword'] = $this->request->get['keyword'];
		} else {
			$this->data['keyword'] = '';
		}
		$this->data['text_keyword'] = $this->language->get('text_keyword');
		$this->data['entry_search'] = $this->language->get('entry_search');


		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/search.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/search.tpl';
		} else {
			$this->template = 'default/template/common/search.tpl';
		}
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
}
?>