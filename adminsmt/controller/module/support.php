<?php
class ControllerModulesupport extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('module/support');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('support', $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->https('extension/module'));
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_left'] = $this->language->get('text_left');
		$this->data['text_right'] = $this->language->get('text_right');
		
		$this->data['entry_code'] = $this->language->get('entry_code');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
 		if (isset($this->error['code'])) {
			$this->data['error_code'] = $this->error['code'];
		} else {
			$this->data['error_code'] = '';
		}
		
  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('extension/module'),
       		'text'      => $this->language->get('text_module'),
      		'separator' => ' :: '
   		);
		
   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('module/support'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->https('module/support');
		
		$this->data['cancel'] = $this->url->https('extension/module');

		if (isset($this->request->post['support_code'])) {
			$this->data['support_code'] = $this->request->post['support_code'];
		} else {
			$this->data['support_code'] = $this->config->get('support_code');
		}	
		
		if (isset($this->request->post['support_position'])) {
			$this->data['support_position'] = $this->request->post['support_position'];
		} else {
			$this->data['support_position'] = $this->config->get('support_position');
		}
		
		if (isset($this->request->post['support_status'])) {
			$this->data['support_status'] = $this->request->post['support_status'];
		} else {
			$this->data['support_status'] = $this->config->get('support_status');
		}
		
		if (isset($this->request->post['support_sort_order'])) {
			$this->data['support_sort_order'] = $this->request->post['support_sort_order'];
		} else {
			$this->data['support_sort_order'] = $this->config->get('support_sort_order');
		}				
		
		$this->template = 'module/support.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/support')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
}
?>