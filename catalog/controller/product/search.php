<?php 
class ControllerProductSearch extends Controller { 	
	public function index() { 
    	$this->language->load('product/search');
		$this->load->model('tool/seo_url');
		
		if (isset($this->request->get['keyword'])) {
			$this->document->title = sprintf($this->language->get('text_timthay'),$this->request->get['keyword'],$this->request->get['keyword'],$this->request->get['keyword']);
			$this->document->description = sprintf($this->language->get('text_meta_description'),$this->request->get['keyword'],$this->request->get['keyword'],$this->request->get['keyword']);
			$title = sprintf($this->language->get('text_ketqua'),$this->request->get['keyword']);
		}else{
			$this->document->title = $this->language->get('heading_title');
			$title = $this->language->get('heading_title');
			$this->data['text_search'] = sprintf($this->language->get('text_search'),0);
		}

		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->model_tool_seo_url->rewrite($this->url->http('common/home')),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

		$url_search='';
		if (isset($this->request->get['keyword'])) {
			$url_search .= '&keyword=' . html_entity_decode($this->request->get['keyword'], ENT_QUOTES, 'UTF-8');
			$url_seo = $this->model_tool_seo_url->rewrite($this->url->http('product/search' . $url_search));
			if($this->config->get('config_seo_url')) {
			if (!isset($this->request->get['_route_'])){
			$this->redirect($url_seo);
			}
			}
		}else{
			if($this->config->get('config_seo_url')) {
			if (!isset($this->request->get['_route_'])){
			$this->redirect($this->model_tool_seo_url->rewrite($this->url->http('product/search')));
			}
			}		
		}
	
   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->model_tool_seo_url->rewrite($this->url->http('product/search' . $url_search)),
       		'text'      => $title,
      		'separator' => $this->language->get('text_separator')
   		);

    	$this->data['heading_title'] = $title;
   
    	$this->data['text_critea'] = $this->language->get('text_critea');
		$this->data['text_keyword'] = $this->language->get('text_keyword');
		$this->data['text_category'] = $this->language->get('text_category');
		$this->data['text_empty'] = $this->language->get('text_empty');
		$this->data['text_sort'] = $this->language->get('text_sort');
			 
		$this->data['entry_search'] = $this->language->get('entry_search');
    	$this->data['entry_description'] = $this->language->get('entry_description');
		  
    	$this->data['button_search'] = $this->language->get('button_search');
   
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
		
		if (isset($this->request->get['keyword'])) {
			$this->load->model('catalog/product');
			
			$product_total = $this->model_catalog_product->getTotalProductsByKeyword($this->request->get['keyword']);
			$this->data['text_search'] = sprintf($this->language->get('text_search'),$product_total);
			
			if ($product_total) {
				$url = '';

				if (isset($this->request->get['category_id'])) {
					$url .= '&category_id=' . $this->request->get['category_id'];
				}
		
				if (isset($this->request->get['description'])) {
					$url .= '&description=' . $this->request->get['description'];
				}    
				
				$this->load->helper('image');
				
        		$this->data['products'] = array();
				
				$results = $this->model_catalog_product->getProductsByKeyword($this->request->get['keyword'], $sort, $order, ($page - 1) * $this->config->get('config_search'), $this->config->get('config_search'));
        		
				foreach ($results as $result) {
					if ($result['image']) {
						$image = $result['image'];
					} else {
						$image = 'no_image.jpg';
					}						
					
					$special = FALSE;
					
					$discount = $this->model_catalog_product->getProductDiscount($result['product_id']);
 					
					if ($discount) {
						$price = $this->currency->format($this->tax->calculate($discount, $result['tax_class_id'], $this->config->get('config_tax')));
					} else {
						$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
					
						$special = $this->model_catalog_product->getProductSpecial($result['product_id']);
					
						if ($special) {
							$special = $this->currency->format($this->tax->calculate($special, $result['tax_class_id'], $this->config->get('config_tax')));
						}					
					}
					if ($result['quantity'] <= 0) {
						$stock = $result['stock'];
					} else {
						if ($this->config->get('config_stock_display')) {
							$stock = $result['quantity'];
						} else {
							$stock = $this->language->get('text_instock');
						}
					}
					
					if($result['price'] == 0) {$price = $this->language->get('price_contact');}
					$this->data['text_price'] = $this->language->get('text_price');
					$this->data['text_model'] = $this->language->get('text_model');
					$this->data['products'][] = array(
						'name'    			=> $result['name'],
						'model'   			=> $result['model'],
						'warranty' 			=> $result['warranty'],
						'stock'   			=> $stock,
						'brief_description' => html_entity_decode($result['brief_description'], ENT_QUOTES, 'UTF-8'),
						'thumb'   			=> image_resize($image, $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')),
						'price'   			=> $price,
						'special' 			=> $special,
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
					'href'  => $this->model_tool_seo_url->rewrite($this->url->http('product/search' . $url_search))
				);  
				
				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_name_asc'),
					'value' => 'pd.name-ASC',
					'href'  => $this->model_tool_seo_url->rewrite($this->url->http('product/search' . $url_search)) . $url . '&sort=pd.name&order=ASC'
				); 

				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_name_desc'),
					'value' => 'pd.name-DESC',
					'href'  => $this->model_tool_seo_url->rewrite($this->url->http('product/search' . $url_search)) . $url . '&sort=pd.name&order=DESC'
				);  

				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_price_asc'),
					'value' => 'p.price-ASC',
					'href'  => $this->model_tool_seo_url->rewrite($this->url->http('product/search' . $url_search)) . $url . '&sort=p.price&order=ASC'
				); 

				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_price_desc'),
					'value' => 'p.price-DESC',
					'href'  => $this->model_tool_seo_url->rewrite($this->url->http('product/search' . $url_search)) . $url . '&sort=p.price&order=DESC'
				); 
				
				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_desc'),
					'value' => 'rating-DESC',
					'href'  => $this->model_tool_seo_url->rewrite($this->url->http('product/search' . $url_search)) . $url . '&sort=rating&order=DESC'
				); 
				
				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_asc'),
					'value' => 'rating-ASC',
					'href'  => $this->model_tool_seo_url->rewrite($this->url->http('product/search' . $url_search)) . $url . '&sort=rating&order=ASC'
				); 
				
				$url = '';

				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}	

				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}
				$href = $this->model_tool_seo_url->rewrite($this->url->http('product/search' . $url_search));
				$title = '';
				if (isset($this->request->get['order']) && isset($this->request->get['sort'])) {
					$this->document->title .= ' - ' . $this->language->get('text_sort') . $this->language->get('text_' .str_replace(array('pd.','p.'),'',$this->request->get['sort']) . '_' . strtolower($this->request->get['order']));
					$this->document->description = $this->document->description;
					$href = $href . '&sort=' . $this->request->get['sort'] . '&order=' . $this->request->get['order'];
					$title .= $this->language->get('text_sort') . $this->language->get('text_' .str_replace(array('pd.','p.'),'',$this->request->get['sort']) . '_' . strtolower($this->request->get['order']));
					
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
				$pagination->limit = $this->config->get('config_search'); 
				$pagination->text = $this->language->get('text_pagination');
				$pagination->url = $this->model_tool_seo_url->rewrite($this->url->http('product/search' . $url_search)) . $url . '&page=%s';
				
				$this->data['pagination'] = $pagination->render();
				
				$this->data['sort'] = $sort;
				$this->data['order'] = $order;
			}
		}
  
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/search.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/product/search.tpl';
		} else {
			$this->template = 'default/template/product/search.tpl';
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