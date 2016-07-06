<?php 
class ControllerProductSpecial extends Controller { 	
	public function index() { 
    	$this->language->load('product/special');
		$this->load->model('tool/seo_url');
	  	  
    	$this->document->title = $this->language->get('heading_title');

		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->model_tool_seo_url->rewrite($this->url->http('common/home')),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);
			
   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->model_tool_seo_url->rewrite($this->url->http('product/special')),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => $this->language->get('text_separator')
   		);
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
   
		$this->data['text_sort'] = $this->language->get('text_sort');
			 
  		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.viewed';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}
	
		$this->load->model('catalog/product');
			
		$product_total = $this->model_catalog_product->getTotalProductSpecials();
						
		if ($product_total && (!$this->config->get('config_customer_price') || ($this->customer->isLogged() && $this->customer->getCustomerGroupVip()))) {
			$url = '';
				
			$this->load->model('catalog/review');
			
			$this->load->helper('image');
				
       		$this->data['products'] = array();
				
			$results = $this->model_catalog_product->getProductSpecials($sort, $order, ($page - 1) * $this->config->get('config_special'), $this->config->get('config_special'));
        		
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $result['image'];
				} else {
					$image = 'no_image.jpg';
				}						
					
				$rating = $this->model_catalog_review->getAverageRating($result['product_id']);	
									
					if ($result['quantity'] <= 0) {
						$stock = $result['stock'];
					} else {
						if ($this->config->get('config_stock_display')) {
							$stock = $result['quantity'];
						} else {
							$stock = $this->language->get('text_instock');
						}
					}
					$this->data['text_price'] = $this->language->get('text_price');
					$this->data['text_model'] = $this->language->get('text_model');
					$this->data['products'][] = array(
						'name'    			=> $result['name'],
						'model'   			=> $result['model'],
						'warranty' 			=> $result['warranty'],
						'stock'   			=> $stock,
						'brief_description' => html_entity_decode($result['brief_description'], ENT_QUOTES, 'UTF-8'),
						'rating'  			=> $rating,
						'stars'   			=> sprintf($this->language->get('text_stars'), $rating),
						'thumb'   			=> image_resize($image, $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')),
						'price'   => $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'))),
						'special' => $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax'))),
						'href'    			=> $this->model_tool_seo_url->rewrite($this->url->http('product/product&product_id=' . $result['product_id']))
					);
        	}

			if (!$this->config->get('config_customer_price')) {
				$this->data['display_price'] = TRUE;
			} elseif ($this->customer->isLogged() && $this->customer->getCustomerGroupVip()) {
				$this->data['display_price'] = TRUE;
			} else {
				$this->data['display_price'] = FALSE;
			}

			$this->data['sorts'] = array();
			
			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_select'),
				'value' => '',
				'href'  => $this->model_tool_seo_url->rewrite($this->url->http('product/special'))
			); 
			
			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_name_asc'),
				'value' => 'pd.name-ASC',
				'href'  => $this->model_tool_seo_url->rewrite($this->url->http('product/special')) . '&sort=pd.name&order=ASC'
			); 

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_name_desc'),
				'value' => 'pd.name-DESC',
				'href'  => $this->model_tool_seo_url->rewrite($this->url->http('product/special')) . '&sort=pd.name&order=DESC'
			);  

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_price_asc'),
				'value' => 'special-ASC',
				'href'  => $this->model_tool_seo_url->rewrite($this->url->http('product/special')) . '&sort=special&order=ASC'
			); 

			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_price_desc'),
				'value' => 'special-DESC',
				'href'  => $this->model_tool_seo_url->rewrite($this->url->http('product/special')) . '&sort=special&order=DESC'
			); 
				
			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_rating_desc'),
				'value' => 'rating-DESC',
				'href'  => $this->model_tool_seo_url->rewrite($this->url->http('product/special')) . '&sort=rating&order=DESC'
			); 
				
			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_rating_asc'),
				'value' => 'rating-ASC',
				'href'  => $this->model_tool_seo_url->rewrite($this->url->http('product/special')) . '&sort=rating&order=ASC'
			); 
				
			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			$title = '';
			$href = $this->model_tool_seo_url->rewrite($this->url->http('product/special'));
			if (isset($this->request->get['order']) && isset($this->request->get['sort'])) {
				$this->document->title .= ' - ' . $this->language->get('text_sort') . $this->language->get('text_' .str_replace(array('pd.','p.'),'',$this->request->get['sort']) . '_' . strtolower($this->request->get['order']));
				$this->document->description = $this->document->description;
				$href .= '&sort=' . $this->request->get['sort'] . '&order=' . $this->request->get['order'];
				$title = $this->language->get('text_sort') . $this->language->get('text_' .str_replace(array('pd.','p.'),'',$this->request->get['sort']) . '_' . strtolower($this->request->get['order']));
				
				$this->document->breadcrumbs[] = array(
					'href'      => $href,
					'text'      => $title,
					'separator' => $this->language->get('text_separator')
				);
				$title .= ' - ';
			}
			if (isset($this->request->get['page'])) {
				$this->document->title .= ' - ' . $this->language->get('text_page') . $this->request->get['page'];
				$this->document->description = $this->language->get('text_page') . $this->request->get['page'] . ' - ' . $this->document->description;
				
				$this->document->breadcrumbs[] = array(
					'href'      => $href . '&page=' . $this->request->get['page'],
					'text'      => $title . $this->language->get('text_page') . $this->request->get['page'],
					'separator' => $this->language->get('text_separator')
				);
			}

			$pagination = new Pagination();
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->limit = $this->config->get('config_special'); 
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->model_tool_seo_url->rewrite($this->url->http('product/special')) .$url. '&page=%s';
				
			$this->data['pagination'] = $pagination->render();
				
			$this->data['sort'] = $sort;
			$this->data['order'] = $order;

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/special.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/product/special.tpl';
			} else {
				$this->template = 'default/template/product/special.tpl';
			}
			
			$this->children = array(
				'common/header',
				'common/footer',
				'common/column_left',
				'common/column_right'
			);
		
			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));			
		} else {
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
  	}
}
?>