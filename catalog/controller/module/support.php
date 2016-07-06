<?php  
class ControllerModulesupport extends Controller {
	protected function index() {
		$this->language->load('module/support');

      	$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['code'] = html_entity_decode($this->config->get('support_code'), ENT_QUOTES, 'UTF-8');
		$this->load->model('catalog/support');
		$this->load->model('catalog/csupport');
		$this->load->helper('image');
		$this->data['csupports'] = array();
		$supports = array();
		foreach ($this->model_catalog_csupport->getcsupports() as $csupport_info) {
			if ($csupport_info['image']) {
				$image = $csupport_info['image'];
			} else {
				$image = 'no_image.jpg';
			}
			$supports[$csupport_info['csupport_id']] = array();
			foreach ($this->model_catalog_support->getsupportbycsupport($csupport_info['csupport_id']) as $result) {
				$supports[$csupport_info['csupport_id']][] = array(
					'name' => $result['name'],
					'yahoo_id' => $result['yahoo_id'],
					'skype_id' => $result['skype_id'],
					'telephone' => $result['telephone']
				);
			}
			$this->data['csupports'][] = array(
				'name' => $csupport_info['name'],
				'image' => image_resize($image, 33, 33),
				'supports' => $supports[$csupport_info['csupport_id']]
			);
		}
		$this->id = 'support';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/support.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/support.tpl';
		} else {
			$this->template = 'default/template/module/support.tpl';
		}
		
		$this->render();
	}
}
?>