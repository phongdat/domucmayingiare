<?php 
class Controllernewscnews extends Controller {  
	public function index() {
		$this->load->model('tool/seo_url');  
		$this->load->model('tool/substr');
		$this->load->model('catalog/cnews');
		$this->load->model('catalog/news');
		$this->language->load('news/cnews');
	
		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
      		'href'      => $this->model_tool_seo_url->rewrite($this->url->http('common/home')),
       		'text'      => $this->language->get('text_home'),
       		'separator' => FALSE
   		);	
   		$this->document->breadcrumbs[] = array(
      		'href'      => $this->model_tool_seo_url->rewrite($this->url->http('news/cnews')),
       		'text'      => $this->language->get('text_news'),
       		'separator' => $this->language->get('text_separator')
   		);
		
		if (isset($this->request->get['cnews_id'])) {
			$cnews = $this->model_catalog_cnews->getCnews($this->request->get['cnews_id']);
			if($cnews) {
			$path = $this->request->get['cnews_id'];
			while ($cnews['parent_id'] != 0) {
				$path = $cnews['parent_id'] . '_' . $path;
				$cnews = $this->model_catalog_cnews->getCnews($cnews['parent_id']);
			}
			$parts = explode('_', $path);
			foreach ($parts as $part) {
			
				$cnews = $this->model_catalog_cnews->getCnews($part);
				
				$this->document->breadcrumbs[] = array(
					'href'      => $this->model_tool_seo_url->rewrite($this->url->http('product/cnews&cnews_id=' . $part)),
					'text'      => $cnews['name'],
					'separator' => $this->language->get('text_separator')
				);
			}
			}
			$cnews_id = $this->request->get['cnews_id'];
		} else {
			$cnews_id = 0;
		}

		$cnews_info = $this->model_catalog_cnews->getCnews($cnews_id);
	
		if ($cnews_info) {
			$url_seo = $this->model_tool_seo_url->rewrite($this->url->http('news/cnews&cnews_id=' . $cnews_id));
			if($this->config->get('config_seo_url')) {
			if(isset($this->request->get['_route_'])){
			if ($url_seo != (HTTP_SERVER . $this->request->get['_route_'])){
			$this->redirect($url_seo);
			}
			} else {
				$this->redirect($url_seo);
			}
			}
	  		$this->document->title = $cnews_info['name'];
			
			$this->document->description = $cnews_info['meta_description'];
			
			$this->data['heading_title'] = $cnews_info['name'];
			
			$this->data['description'] = html_entity_decode($cnews_info['description'], ENT_QUOTES, 'UTF-8');
			
			$this->data['text_sort'] = $this->language->get('text_sort');

			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
				
				$this->document->breadcrumbs[] = array(
					'href'      => $this->model_tool_seo_url->rewrite($this->url->http('news/cnews&path=' . $path))  . '?page=' . $page,
					'text'      => $cnews_info['name'] . ' - ' . sprintf($this->language->get('text_page'),$page),
					'separator' => $this->language->get('text_separator')
				);
				
				$this->document->title = $cnews_info['name'] . ' - ' . sprintf($this->language->get('text_page'),$page);
				
				$this->document->description = sprintf($this->language->get('text_page'),$page) . ' - ' . $cnews_info['meta_description'];
				
			} else { 
				$page = 1;
			}

			$this->load->model('catalog/cnews');  
			$this->load->model('catalog/news'); 
			 
			$category_total = $this->model_catalog_cnews->getTotalCnewssByCnewsId($cnews_id);
			$news_total = $this->model_catalog_news->getTotalnewsByCnewsId($cnews_id);
			
			if ($category_total || $news_total ) {
				$results = $this->model_catalog_news->getnewssbycnews($cnews_id, ($page - 1) * 20, 20);

				$this->data['newss'] = array();

				foreach ($results as $result) {
					$first_img = '';
					$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'), $matches);
					if($matches [1]){
					$first_img = $matches [1] [0];
					} else {
					$first_img = "image/no_image.jpg";
					}
					
					if($result['date_added'] != '0000-00-00 00:00:00') {
						$date_added = date('h:iA d/m/Y',strtotime($result['date_added']));
					} else {
						$date_added = '';
					}
					
					$description = strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'));
					
					$this->data['newss'][] = array(
						'name' => $result['name'],
						'date_added' => $date_added,
						'description' => $this->model_tool_substr->substr($description,490,3),
						'description_no_image' => $this->model_tool_substr->substr($description,1000,3),
						'image'     => $first_img,
						'href'  => $this->model_tool_seo_url->rewrite($this->url->http('news/news&news_id=' . $result['news_id']))
					);
				}
				
			
				$pagination = new Pagination();
				$pagination->total = $news_total;
				$pagination->page = $page;
				$pagination->limit = 20; 
				$pagination->text = $this->language->get('text_pagination');
				$pagination->url = $this->model_tool_seo_url->rewrite($this->url->http('news/cnews&cnews_id=' . $this->request->get['cnews_id'] . '&page=%s'));
			
				$this->data['pagination'] = $pagination->render();				

			
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/news/cnews.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/news/cnews.tpl';
				} else {
					$this->template = 'default/template/news/cnews.tpl';
				}	
				
				$this->children = array(
					'common/header',
					'common/footer',
					'common/column_left',
					'common/column_right'
				);
		
				$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));			
				
      		} else {
        		$this->document->title = $cnews_info['name'];
				
				$this->document->description = $cnews_info['meta_description'];
				
        		$this->data['heading_title'] = $cnews_info['name'];

        		$this->data['text_error'] = $this->language->get('text_empty');

        		$this->data['button_continue'] = $this->language->get('button_continue');

        		$this->data['continue'] = $this->url->http('common/home');
		
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
				} else {
					$this->template = 'default/template/error/not_found.tpl';
				}	
				
				$this->children = array(
					'common/header',
					'common/footer',
					'common/column_left',
					'common/column_right'
				);
		
				$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));					
      		}
    	} else {
			$this->data['description'] = '';
			
	  		$this->document->title = $this->language->get('text_news');
			
			$this->data['heading_title'] = $this->language->get('text_news');

			$this->data['text_sort'] = $this->language->get('text_sort');

			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			} else {
				$page = 1;
			}
			$results = $this->model_catalog_news->getnewss(($page - 1) * 20, 20);
			$this->data['newss'] = array();

			foreach ($results as $result) {
				$first_img = '';
				$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'), $matches);
				if($matches [1]){
				$first_img = $matches [1] [0];
				} else {
				$first_img = "image/no_image.jpg";
				}
				
				if($result['date_added'] != '0000-00-00 00:00:00') {
					$date_added = date('h:iA d/m/Y',strtotime($result['date_added']));
				} else {
					$date_added = '';
				}
				
				$description = strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'));
				
				$this->data['newss'][] = array(
					'name' => $result['name'],
					'date_added' => $date_added,
					'description' => $this->model_tool_substr->substr($description,490,3),
					'description_no_image' => $this->model_tool_substr->substr($description,1000,3),
					'image'     => $first_img,
					'href'  => $this->model_tool_seo_url->rewrite($this->url->http('news/news&news_id=' . $result['news_id']))
				);
			}
		
				$pagination = new Pagination();
				$pagination->total = $this->model_catalog_news->gettotalnewss();
				$pagination->page = $page;
				$pagination->limit = 20;
				$pagination->text = $this->language->get('text_pagination');
				$pagination->url = $this->model_tool_seo_url->rewrite($this->url->http('news/cnews')) . '?page=%s';
			
				$this->data['pagination'] = $pagination->render();				

			
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/news/cnews.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/news/cnews.tpl';
				} else {
					$this->template = 'default/template/news/cnews.tpl';
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
}
?>