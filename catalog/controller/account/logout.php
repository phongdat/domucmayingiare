<?php 
class ControllerAccountLogout extends Controller {
	public function index() {
		$this->load->model('tool/seo_url');
    	if ($this->customer->isLogged()) {
      		$this->customer->logout();
	  		$this->cart->clear();
			
			unset($this->session->data['address_id']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['comment']);
			unset($this->session->data['order_id']);
			unset($this->session->data['coupon']);
			
			$this->tax->setZone($this->config->get('config_country_id'), $this->config->get('config_zone_id'));
			
      		$this->redirect($this->model_tool_seo_url->rewrite($this->url->https('account/logout')));
    	}
		
    	$this->language->load('account/logout');
		
		$this->document->title = $this->language->get('heading_title');
      	
		$this->document->breadcrumbs = array();

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->model_tool_seo_url->rewrite($this->url->http('common/home')),
        	'text'      => $this->language->get('text_home'),
        	'separator' => FALSE
      	);
      	
		$this->document->breadcrumbs[] = array(
        	'href'      => $this->model_tool_seo_url->rewrite($this->url->http('account/account')),
        	'text'      => $this->language->get('text_account'),
        	'separator' => $this->language->get('text_separator')
      	);
		
      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->model_tool_seo_url->rewrite($this->url->http('account/logout')),
        	'text'      => $this->language->get('text_logout'),
        	'separator' => $this->language->get('text_separator')
      	);	
		
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_message'] = $this->language->get('text_message');
		
		$this->data['button_yes'] = $this->language->get('button_yes');
		
    	$this->data['button_continue'] = $this->language->get('button_continue');

    	$this->data['continue'] = $this->model_tool_seo_url->rewrite($this->url->http('common/home'));

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/logout.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/logout.tpl';
		} else {
			$this->template = 'default/template/account/logout.tpl';
		}
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));	
  	}
	public function logoutpopup() {
		$this->load->model('tool/seo_url');
		$this->language->load('account/logout');
		$this->data['logged'] = $this->customer->isLogged();
		$this->data['text_confirm_logout'] = $this->language->get('text_confirm_logout');
		$this->data['text_confirm_logouted'] = $this->language->get('text_confirm_logouted');
		$this->data['button_yes'] = $this->language->get('button_yes');
		$this->data['button_no'] = $this->language->get('button_no');
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/logoutpopup.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/logoutpopup.tpl';
		} else {
			$this->template = 'default/template/account/logoutpopup.tpl';
		}
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
}
?>
