<?php  
class ControllerModulelienket4 extends Controller {
	protected function index() {
		$this->language->load('module/lienket4');
		if($this->config->get('lienket4_title')) {
			$this->data['heading_title'] = html_entity_decode($this->config->get('lienket4_title'), ENT_QUOTES, 'UTF-8');
		} else {
			$this->data['heading_title'] = $this->language->get('heading_title');
		}
		$this->data['code'] = str_replace('height="','rel="',html_entity_decode($this->config->get('lienket4_code'), ENT_QUOTES, 'UTF-8'));
		$this->data['lienket_heading_title'] = $this->config->get('lienket4_heading_title');
	
		$this->id = 'lienket4';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/lienket4.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/lienket4.tpl';
		} else {
			$this->template = 'default/template/module/lienket4.tpl';
		}
		
		$this->render();
	}
}
?>