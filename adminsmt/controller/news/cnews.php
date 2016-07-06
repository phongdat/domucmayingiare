<?php 
class ControllerNewsCnews extends Controller { 
	private $error = array();
 
	public function index() {
		$this->load->language('news/cnews');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('news/cnews');
		 
		$this->getList();
	}

	public function insert() {
	
		$this->load->language('news/cnews');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('news/cnews');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
		
			$this->model_news_cnews->addcnews($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect($this->url->https('news/cnews')); 
		}

		$this->getForm();
	}

	public function update() {
		$this->load->language('news/cnews');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('news/cnews');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_news_cnews->editcnews($this->request->get['cnews_id'], $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect($this->url->https('news/cnews'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('news/cnews');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('news/cnews');
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $cnews_id) {
				$this->model_news_cnews->deletecnews($cnews_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->https('news/cnews'));
		}

		$this->getList();
	}

	private function getList() {
   		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('news/cnews'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
									
		$this->data['insert'] = $this->url->https('news/cnews/insert');
		$this->data['delete'] = $this->url->https('news/cnews/delete');
		
		$this->data['cnewss'] = array();
		
		$results = $this->model_news_cnews->getcnewss(0);

		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->https('news/cnews/update&cnews_id=' . $result['cnews_id'])
			);
					
			$this->data['cnewss'][] = array(
				'cnews_id' => $result['cnews_id'],
				'name'        => $result['name'],
				'sort_order'  => $result['sort_order'],
				'selected'    => isset($this->request->post['selected']) && in_array($result['cnews_id'], $this->request->post['selected']),
				'action'      => $action
			);
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
 
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
		$this->template = 'news/cnews_list.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function getForm() {
	
	
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_cnews'] = $this->language->get('entry_cnews');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_image'] = $this->language->get('entry_image');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
	
 		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = '';
		}

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('news/cnews'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		if (!isset($this->request->get['cnews_id'])) {
			$this->data['action'] = $this->url->https('news/cnews/insert');
			
		} else {
			$this->data['action'] = $this->url->https('news/cnews/update&cnews_id=' . $this->request->get['cnews_id']);
		}
		
		$this->data['cancel'] = $this->url->https('news/cnews');

		if (isset($this->request->get['cnews_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$cnews_info = $this->model_news_cnews->getcnews($this->request->get['cnews_id']);
    	}
		
		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['cnews_description'])) {
			$this->data['cnews_description'] = $this->request->post['cnews_description'];
		} elseif (isset($cnews_info)) {
			$this->data['cnews_description'] = $this->model_news_cnews->getcnewsDescriptions($this->request->get['cnews_id']);
		} else {
			$this->data['cnews_description'] = array();
		}

		$this->data['cnewss'] = $this->model_news_cnews->getcnewss(0);

		if (isset($this->request->post['parent_id'])) {
			$this->data['parent_id'] = $this->request->post['parent_id'];
		} elseif (isset($cnews_info)) {
			$this->data['parent_id'] = $cnews_info['parent_id'];
		} else {
			$this->data['parent_id'] = 0;
		}

		if (isset($this->request->post['image'])) {
			$this->data['image'] = $this->request->post['image'];
		} elseif (isset($cnews_info)) {
			$this->data['image'] = $cnews_info['image'];
		} else {
			$this->data['image'] = '';
		}
		
		$this->load->helper('image');

		if (isset($cnews_info) && $cnews_info['image'] && file_exists(DIR_IMAGE . $cnews_info['image'])) {
			$this->data['preview'] = image_resize($cnews_info['image'], 100, 100);
		} else {
			$this->data['preview'] = image_resize('no_image.jpg', 100, 100);
		}
		
		if (isset($this->request->post['sort_order'])) {
			$this->data['sort_order'] = $this->request->post['sort_order'];
		} elseif (isset($cnews_info)) {
			$this->data['sort_order'] = $cnews_info['sort_order'];
		} else {
			$this->data['sort_order'] = 0;
		}
		
		$this->template = 'news/cnews_form.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'news/cnews')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['cnews_description'] as $language_id => $value) {
			if ((strlen(utf8_decode($value['name'])) < 2) || (strlen(utf8_decode($value['name'])) > 64)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'news/cnews')) {
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