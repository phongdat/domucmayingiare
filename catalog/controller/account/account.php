<?php 
class ControllerAccountAccount extends Controller { 
	public function index() {
		$this->load->model('tool/seo_url');
		
		if (!$this->customer->isLogged()) {
	  		$this->session->data['redirect'] = $this->model_tool_seo_url->rewrite($this->url->https('account/account'));
	  
	  		$this->redirect($this->model_tool_seo_url->rewrite($this->url->https('account/login')));
    	} 
	
		$this->language->load('account/account');

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

		$this->document->title = $this->language->get('heading_title');

    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_my_account'] = $this->language->get('text_my_account');
		$this->data['text_my_orders'] = $this->language->get('text_my_orders');
		$this->data['text_my_newsletter'] = $this->language->get('text_my_newsletter');
    	$this->data['text_information'] = $this->language->get('text_information');
    	$this->data['text_password'] = $this->language->get('text_password');
    	$this->data['text_address'] = $this->language->get('text_address');
    	$this->data['text_history'] = $this->language->get('text_history');
		$this->data['text_newsletter'] = $this->language->get('text_newsletter');

		if (isset($this->session->data['success'])) {
    		$this->data['success'] = $this->session->data['success'];
			
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

    	$this->data['information'] = $this->model_tool_seo_url->rewrite($this->url->https('account/edit'));
    	$this->data['password'] = $this->model_tool_seo_url->rewrite($this->url->https('account/password'));
		$this->data['address'] = $this->model_tool_seo_url->rewrite($this->url->https('account/address'));
    	$this->data['history'] = $this->model_tool_seo_url->rewrite($this->url->https('account/history'));
		$this->data['newsletter'] = $this->model_tool_seo_url->rewrite($this->url->https('account/newsletter'));
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/account.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/account.tpl';
		} else {
			$this->template = 'default/template/account/account.tpl';
		}
		
		$this->children = array(
			'common/header',
			'common/footer',
			'common/column_left',
			'common/column_right'
		);

		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));		
  	}
}
?>
