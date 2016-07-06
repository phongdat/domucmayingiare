<?php  
class ControllerModulelienket1 extends Controller {
	protected function index() {
		$this->language->load('module/lienket1');
		if($this->config->get('lienket1_title')) {
			$this->data['heading_title'] = html_entity_decode($this->config->get('lienket1_title'), ENT_QUOTES, 'UTF-8');
		} else {
			$this->data['heading_title'] = $this->language->get('heading_title');
		}
		$this->data['code'] = str_replace('height="','rel="',html_entity_decode($this->config->get('lienket1_code'), ENT_QUOTES, 'UTF-8'));
		$this->data['lienket_heading_title'] = $this->config->get('lienket1_heading_title');
	
		$this->id = 'lienket1';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/lienket1.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/lienket1.tpl';
		} else {
			$this->template = 'default/template/module/lienket1.tpl';
		}
		
		$this->render();
	}
}
?>