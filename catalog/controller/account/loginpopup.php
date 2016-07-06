<?php 
class ControllerAccountLoginpopup extends Controller {
	private $error = array();
	
	public function index() {
		$this->load->model('tool/seo_url');
	
    	$this->language->load('account/login');

    	$this->document->title = $this->language->get('heading_title');

    	$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_forgotten_password'] = $this->language->get('text_forgotten_password');
		$this->data['text_account'] = $this->language->get('text_account');
    	$this->data['text_create_account'] = $this->language->get('text_create_account');
		$this->data['text_confirm_notice'] = $this->language->get('text_confirm_notice');
		$this->data['text_wait'] = $this->language->get('text_wait');
    	$this->data['entry_email'] = $this->language->get('entry_email_address');
    	$this->data['entry_password'] = $this->language->get('entry_password');
		$this->data['logged'] = $this->customer->isLogged();
		$this->data['button_login'] = $this->language->get('button_login');
		$this->data['button_yes'] = $this->language->get('button_yes');
		
		$this->data['action'] = $this->model_tool_seo_url->rewrite($this->url->https('account/login'));
		$this->data['create'] = $this->model_tool_seo_url->rewrite($this->url->https('account/create'));
    	$this->data['forgotten'] = $this->model_tool_seo_url->rewrite($this->url->https('account/forgotten'));

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/loginpopup.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/loginpopup.tpl';
		} else {
			$this->template = 'default/template/account/loginpopup.tpl';
		}
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
  	}
	public function account() {

    	$this->language->load('account/login');
		$json = array();
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (isset($this->request->post['email']) && isset($this->request->post['password'])) {
				unset($this->session->data['guest']);
			}
			$json['success'] = $this->language->get('text_success');
		} else {
			$json['error'] = $this->error['message'];
		}	
		
		$this->load->library('json');
		
		$this->response->setOutput(Json::encode($json));
	}
  	private function validate() {
    	if (!$this->customer->login($this->request->post['email'], $this->request->post['password'])) {
      		$this->error['message'] = $this->language->get('error_login');
    	}
	
    	if (!$this->error) {
      		return TRUE;
    	} else {
      		return FALSE;
    	}  	
  	}
}
?>