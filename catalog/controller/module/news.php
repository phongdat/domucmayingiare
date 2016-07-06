<?php  
class ControllerModulenews extends Controller {
	protected function index() {
		$this->language->load('module/news');
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
		
    	$this->data['news_href'] = $this->model_tool_seo_url->rewrite($this->url->http('news/cnews'));
		
		$this->load->model('catalog/news'); 
		$results = $this->model_catalog_news->tinmoinhat(10);
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
			
			$this->data['newss'][] = array(
				'name' 		 => $result['name'],
				'image'      => $first_img,
				'date_added' => $date_added,
				'href'  => $this->model_tool_seo_url->rewrite($this->url->http('news/news&news_id=' . $result['news_id']))
			);
		}
		
		$this->id = 'news';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/news.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/news.tpl';
		} else {
			$this->template = 'default/template/module/news.tpl';
		}
		
		$this->render();
	}
}
?>