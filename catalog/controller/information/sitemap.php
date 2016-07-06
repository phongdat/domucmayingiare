<?php  
class ControllerInformationSitemap extends Controller {
	public function index() {
		$this->load->model('tool/seo_url');
		
    	$this->language->load('information/sitemap');
 
		$this->document->title = $this->language->get('heading_title'); 

      	$this->document->breadcrumbs = array();

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->model_tool_seo_url->rewrite($this->url->http('common/home')),
        	'text'      => $this->language->get('text_home'),
        	'separator' => FALSE
      	);

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->model_tool_seo_url->rewrite($this->url->http('information/sitemap')),
        	'text'      => $this->language->get('heading_title'),
        	'separator' => $this->language->get('text_separator')
      	);	
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_special'] = $this->language->get('text_special');
		$this->data['text_account'] = $this->language->get('text_account');
    	$this->data['text_edit'] = $this->language->get('text_edit');
    	$this->data['text_password'] = $this->language->get('text_password');
    	$this->data['text_address'] = $this->language->get('text_address');
    	$this->data['text_history'] = $this->language->get('text_history');
    	$this->data['text_download'] = $this->language->get('text_download');
    	$this->data['text_cart'] = $this->language->get('text_cart');
    	$this->data['text_checkout'] = $this->language->get('text_checkout');
    	$this->data['text_search'] = $this->language->get('text_search');
    	$this->data['text_information'] = $this->language->get('text_information');
    	$this->data['text_contact'] = $this->language->get('text_contact');
		$this->data['text_category'] = $this->language->get('text_category');
		$this->data['text_category_news'] = $this->language->get('text_category_news');
		$this->data['text_thongtin'] = $this->language->get('text_thongtin');
		
		$this->load->model('catalog/category');
		
		$this->load->model('tool/seo_url');
		
		$this->data['category'] = $this->getCategories(0);
		
		$this->load->model('catalog/cnews');
		
		$this->data['category_news'] = $this->getCnewss(0);
		
		$this->data['special'] = $this->model_tool_seo_url->rewrite($this->url->https('product/special'));
		$this->data['account'] = $this->model_tool_seo_url->rewrite($this->url->https('account/account'));
    	$this->data['edit'] = $this->model_tool_seo_url->rewrite($this->url->https('account/edit'));
    	$this->data['password'] = $this->model_tool_seo_url->rewrite($this->url->https('account/password'));
    	$this->data['address'] = $this->model_tool_seo_url->rewrite($this->url->https('account/address'));
    	$this->data['history'] = $this->model_tool_seo_url->rewrite($this->url->https('account/history'));
    	$this->data['download'] = $this->model_tool_seo_url->rewrite($this->url->https('account/download'));
    	$this->data['cart'] = $this->model_tool_seo_url->rewrite($this->url->http('checkout/cart'));
    	$this->data['checkout'] = $this->model_tool_seo_url->rewrite($this->url->https('checkout/shipping'));
    	$this->data['search'] = $this->model_tool_seo_url->rewrite($this->url->http('product/search'));
    	$this->data['contact'] = $this->model_tool_seo_url->rewrite($this->url->http('information/contact'));
		
		$this->load->model('catalog/information');
		
		$this->data['informations'] = array();
    	
		foreach ($this->model_catalog_information->getInformations() as $result) {
      		$this->data['informations'][] = array(
        		'name' => $result['name'],
        		'href'  => $this->model_tool_seo_url->rewrite($this->url->http('information/information&information_id=' . $result['information_id']))
      		);
    	}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/sitemap.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/information/sitemap.tpl';
		} else {
			$this->template = 'default/template/information/sitemap.tpl';
		}
		
		$this->children = array(
			'common/header',
			'common/footer',
			'common/column_left',
			'common/column_right'
		);
		
 		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));		
	}
	
	protected function getCategories($parent_id, $current_path = '') {
		$output = '';
		
		$results = $this->model_catalog_category->getCategories($parent_id);
		
		if ($results) {
			$output .= '<ul>';
    	}
		
		foreach ($results as $result) {	
			if (!$current_path) {
				$new_path = $result['category_id'];
			} else {
				$new_path = $current_path . '_' . $result['category_id'];
			}
			
			$output .= '<li>';
			
			$output .= '<a href="' . $this->model_tool_seo_url->rewrite($this->url->http('product/category&category_id=' . $result['category_id']))  . '">' . $result['name'] . '</a>';
			
        	$output .= $this->getCategories($result['category_id'], $new_path);
        
        	$output .= '</li>'; 
		}
 
		if ($results) {
			$output .= '</ul>';
		}
		
		return $output;
	}
	protected function getCnewss($parent_id, $current_path = '') {
		$output = '';
		
		$results = $this->model_catalog_cnews->getCnewss($parent_id);
		
		if ($results) {
			$output .= '<ul>';
    	}
		
		foreach ($results as $result) {	
			if (!$current_path) {
				$new_path = $result['cnews_id'];
			} else {
				$new_path = $current_path . '_' . $result['cnews_id'];
			}
			
			$output .= '<li>';
			
			$output .= '<a href="' . $this->model_tool_seo_url->rewrite($this->url->http('product/cnews&cnews_id=' . $result['cnews_id']))  . '">' . $result['name'] . '</a>';
			
        	$output .= $this->getCnewss($result['cnews_id'], $new_path);
        
        	$output .= '</li>'; 
		}
 
		if ($results) {
			$output .= '</ul>';
		}
		
		return $output;
	}
}
?>