<?php  
class ControllerCommonFooter extends Controller {
	protected function index() {
		$this->language->load('common/footer');
		$this->load->model('tool/seo_url');
		$this->load->helper('image');

// information
		$this->load->model('catalog/information');
		$this->load->model('catalog/cinformation');
		$this->data['cinformations'] = array();
		$informations = array();
		foreach ($this->model_catalog_cinformation->getcinformations('footer',2) as $cinformation_info) {
			$informations[$cinformation_info['cinformation_id']] = array();
			foreach ($this->model_catalog_information->getinformationbycinformation($cinformation_info['cinformation_id'],5) as $result) {
				$informations[$cinformation_info['cinformation_id']][] = array(
					'name' 	   => $result['name'],
					'link' => $result['link'],
					'href' 	 	   => $this->model_tool_seo_url->rewrite($this->url->http('information/information&information_id=' . $result['information_id']))
				);
			}
			$this->data['cinformations'][] = array(
				'name' 			 => $cinformation_info['name'],
				'informations' 	 => $informations[$cinformation_info['cinformation_id']],
				'href'  => $this->model_tool_seo_url->rewrite($this->url->http('information/cinformation&cinformation_id=' . $cinformation_info['cinformation_id']))
			);
		}
//end information

		$this->data['text_powered_by'] = sprintf($this->language->get('text_powered_by'), date('Y', time()),HTTP_SERVER,$this->config->get('config_store'),$this->language->get('text_arr'));
		$this->data['text_powered'] = $this->language->get('text_powered');
		$this->data['owner'] = $this->config->get('config_owner');
		$this->data['address'] = $this->config->get('config_address');
		$this->data['telephone'] = $this->config->get('config_telephone');
		$this->data['fax'] = $this->config->get('config_fax');
		$this->data['hotline'] = $this->config->get('config_hotline');
		
    	$this->data['text_address'] = $this->language->get('text_address');
		$this->data['text_emails'] = $this->language->get('text_emails');
		$this->data['text_hotline'] = $this->language->get('text_hotline');
    	$this->data['text_telephone'] = $this->language->get('text_telephone');
    	$this->data['text_fax'] = $this->language->get('text_fax');
		
		$this->data['text_home'] = $this->language->get('text_home');
		$this->data['text_sitemap'] = $this->language->get('text_sitemap');
		$this->data['text_special'] = $this->language->get('text_special');
		$this->data['text_contact'] = $this->language->get('text_contact');
		$this->data['text_gioithieu'] = $this->language->get('text_gioithieu');
		$this->data['text_news'] = $this->language->get('text_news');
		$this->data['text_dangky'] = $this->language->get('text_dangky');
		$this->data['text_dangnhap'] = $this->language->get('text_dangnhap');
		
		$this->data['special'] = $this->model_tool_seo_url->rewrite($this->url->http('product/special'));
		$this->data['home'] = $this->model_tool_seo_url->rewrite($this->url->http('common/home'));
		$this->data['contact'] = $this->model_tool_seo_url->rewrite($this->url->http('information/contact'));
    	$this->data['sitemap'] = $this->model_tool_seo_url->rewrite($this->url->http('information/sitemap'));
		$this->data['gioithieu'] = $this->model_tool_seo_url->rewrite($this->url->http('information/about'));
		$this->data['tintuc'] = $this->model_tool_seo_url->rewrite($this->url->http('news/cnews'));
		$this->data['dangnhap'] = $this->model_tool_seo_url->rewrite($this->url->https('account/login'));
		$this->data['dangky'] = $this->model_tool_seo_url->rewrite($this->url->https('account/create'));
		
		$this->id = 'footer';
		if ($this->config->get('footer_status')) {
			$this->data['footer'] = html_entity_decode($this->config->get('footer_code'), ENT_QUOTES, 'UTF-8');
		} else {
			$this->data['footer'] = '';
		}
		if ($this->config->get('hotkeyword_status')) {
			$this->data['hotkeyword'] = html_entity_decode($this->config->get('hotkeyword_code'), ENT_QUOTES, 'UTF-8');
		} else {
			$this->data['hotkeyword'] = '';
		}
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/footer.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/footer.tpl';
		} else {
			$this->template = 'default/template/common/footer.tpl';
		}
		
		$this->render();
	}
}
?>