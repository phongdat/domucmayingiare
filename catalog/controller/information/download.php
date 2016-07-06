<?php 
class ControllerInformationdownload extends Controller {  
	public function index() {
		$this->language->load('information/download');
		$this->load->model('catalog/download'); 
		$this->load->model('tool/seo_url');  
		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
      		'href'      => $this->model_tool_seo_url->rewrite($this->url->http('common/home')),
       		'text'      => $this->language->get('text_home'),
       		'separator' => FALSE
   		);
   		$this->document->breadcrumbs[] = array(
      		'href'      => $this->model_tool_seo_url->rewrite($this->url->http('information/download')),
       		'text'      => $this->language->get('text_download'),
       		'separator' => $this->language->get('text_separator')
   		);	
		
	  		$this->document->title = $this->language->get('text_download');
			
			$this->data['heading_title'] = $this->language->get('text_download');

			$this->data['text_sort'] = $this->language->get('text_sort');

			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			} else {
				$page = 1;
			}
			$results = $this->model_catalog_download->getdownloads(($page - 1) * 20, 20);
			$this->data['downloads'] = array();

			foreach ($results as $result) {
				
				$this->data['downloads'][] = array(
					'name' => $result['name'],
					'date_added' => date('h:iA d/m/Y',strtotime($result['date_added'])),
					'filename' => HTTP_SERVER . 'download/' . $result['filename']
				);
			}
		
				$pagination = new Pagination();
				$pagination->total = $this->model_catalog_download->gettotaldownloads();
				$pagination->page = $page;
				$pagination->limit = 20;
				$pagination->text = $this->language->get('text_pagination');
				$pagination->url = $this->model_tool_seo_url->rewrite($this->url->http('information/download')) . '?page=%s';
			
				$this->data['pagination'] = $pagination->render();				

			
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/download.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/information/download.tpl';
				} else {
					$this->template = 'default/template/information/download.tpl';
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