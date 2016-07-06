<?php 
class ControllerInformationabout extends Controller {
	public function index() {
		$this->load->model('tool/seo_url');
		
    	$this->language->load('information/about');
		
		$this->document->breadcrumbs = array();
		
      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->model_tool_seo_url->rewrite($this->url->http('common/home')),
        	'text'      => $this->language->get('text_home'),
        	'separator' => FALSE
      	);				


	  		$this->document->title = $this->language->get('text_about'); 
      		$this->document->breadcrumbs[] = array(
        		'href'      => $this->model_tool_seo_url->rewrite($this->url->http('information/about')),
        		'text'      => $this->language->get('text_about'),
        		'separator' => $this->language->get('text_separator')
      		);		
						
      		$this->data['heading_title'] = $this->language->get('text_about');
      		
      		$this->data['button_continue'] = $this->language->get('button_continue');
			
			$this->data['description'] = html_entity_decode($this->config->get('config_welcome_' . $this->config->get('config_language_id')));

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/about.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/information/about.tpl';
			} else {
				$this->template = 'default/template/information/about.tpl';
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