<?php  
class ControllerModulecnews extends Controller {
	protected $cnews_id = 0;
	protected $path = array();
	
	protected function index() {
		$this->language->load('module/cnews');
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->load->model('catalog/cnews');
		$this->load->model('tool/seo_url');
		
		if (isset($this->request->get['cnews_id'])) {
			$this->path = explode('_', $this->request->get['cnews_id']);
			
			$this->cnews_id = end($this->path);
		}
		
		$this->data['cnews'] = $this->getCnewss(0);
												
		$this->id = 'cnews';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/cnews.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/cnews.tpl';
		} else {
			$this->template = 'default/template/module/cnews.tpl';
		}
		
		$this->render();
  	}
	
	protected function getCnewss($parent_id, $current_path = '') {
		$cnews_id = array_shift($this->path);
		
		$output = '';
		
		$results = $this->model_catalog_cnews->getCnewss($parent_id);
		
		if ($results) { 
			$output .= '<ul>';
    	}
		
		foreach ($results as $result) {	
			if (!$current_path) {
				$new_path = $result['cnews_id'];
			} else {
				$new_path = $current_path . '_' . $result['cnews_id'];
			}
			
			$output .= '<li>';
			
			$children = '';
			
			if ($cnews_id == $result['cnews_id']) {
				$children = $this->getCnewss($result['cnews_id'], $new_path);
			}
			
			if ($this->cnews_id == $result['cnews_id']) {
				$output .= '<a href="' . $this->model_tool_seo_url->rewrite($this->url->http('product/cnews&cnews_id=' . $result['cnews_id']))  . '"><b>' . $result['name'] . '</b></a>';
			} else {
				$output .= '<a href="' . $this->model_tool_seo_url->rewrite($this->url->http('product/cnews&cnews_id=' . $result['cnews_id']))  . '">' . $result['name'] . '</a>';
			}
			
        	$output .= $children;
        
        	$output .= '</li>'; 
		}
 
		if ($results) {
			$output .= '</ul>';
		}
		
		return $output;
	}		
}
?>