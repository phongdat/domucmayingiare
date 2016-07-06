<?php  
class ControllerModuleLienKet extends Controller {

	protected function index() {
		$this->language->load('module/lienket');
		$this->load->model('tool/seo_url');
      	$this->data['heading_title'] = $this->language->get('heading_title');

		
		$this->data['code'] = html_entity_decode($this->config->get('lienket_code'));
		$this->id = 'lienket';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/lienket.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/lienket.tpl';
		} else {
			$this->template = 'default/template/module/lienket.tpl';
		}
		
		$this->render();
	}

}
?>