<?php 
class ControllerProductCategory extends Controller {  
	public function index() { 
		$this->language->load('product/category');
		$this->load->model('tool/seo_url'); 
	
		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
      		'href'      => $this->model_tool_seo_url->rewrite($this->url->http('common/home')),
       		'text'      => $this->language->get('text_home'),
       		'separator' => FALSE
   		);

		$this->load->model('catalog/category'); 
		
		if (isset($this->request->get['category_id'])) {
			$category = $this->model_catalog_category->getCategory($this->request->get['category_id']);
			if($category) {
			$path = $this->request->get['category_id'];
			while ($category['parent_id'] != 0) {
				$path = $category['parent_id'] . '_' . $path;
				$category = $this->model_catalog_category->getCategory($category['parent_id']);
			}
			$this->document->path =  $path;
			$parts = explode('_', $path);
			foreach ($parts as $part) {
			
				$category = $this->model_catalog_category->getCategory($part);
				
				$this->document->breadcrumbs[] = array(
					'href'      => $this->model_tool_seo_url->rewrite($this->url->http('product/category&category_id=' . $part)),
					'text'      => $category['name'],
					'separator' => $this->language->get('text_separator')
				);
			}
			}
			$category_id = $this->request->get['category_id'];
		} else {
			$category_id = 0;
		}

		$category_info = $this->model_catalog_category->getCategory($category_id);
	
		if ($category_info) {
			$this->load->helper('image'); 
			
			$url_seo = $this->model_tool_seo_url->rewrite($this->url->http('product/category&category_id=' . $category_id));
			if($this->config->get('config_seo_url')) {
				if(isset($this->request->get['_route_'])){
					if ($url_seo != (HTTP_SERVER . $this->request->get['_route_'])){
					$this->redirect($url_seo);
					}
				} else {
					$this->redirect($url_seo);
				}
			}
			
			if($category_info['name_seo']) {
				$this->document->title = $category_info['name_seo'];
			} else {
				$this->document->title = $category_info['name'];
			}
			
			$this->document->description = $category_info['meta_description'];
			
			$this->document->keywords = $category_info['keywords'];
			
			$this->data['heading_title'] = $category_info['name'];
			
			$this->data['description'] = html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8');
			
			if ($category_info['image']) {
				$image = $category_info['image'];
			} else {
				$image = 'no_image.jpg';
			}
			
			$this->data['image'] = image_resize($image, 717, 310);
			
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

			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->load->model('catalog/product');
			 
			$category_total = $this->model_catalog_category->getTotalCategoriesByCategoryId($category_id);
			$product_total = $this->model_catalog_product->getTotalProductsByCategoryId($category_id);
			
			if ($category_total || $product_total) {
        		$this->data['categories'] = array();
        		
				$results = $this->model_catalog_category->getCategories($category_id);
				
        		foreach ($results as $result) {
					if ($result['image']) {
						$image = $result['image'];
					} else {
						$image = 'no_image.jpg';
					}
					
					$this->data['categories'][] = array(
            			'name'  => $result['name'],
            			'href'  => $this->model_tool_seo_url->rewrite($this->url->http('product/category&category_id=' . $result['category_id'] . $url)),
            			'thumb' => image_resize($image, $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'))
          			);
        		}

				$this->data['products'] = array();
        		
				$results = $this->model_catalog_product->getProductsByCategoryId($category_id, $sort, $order, ($page - 1) * $this->config->get('config_category'), $this->config->get('config_category'));
				
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
		
				$url = '';
		
				$this->data['sorts'] = array();

				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_select'),
					'value' => '',
					'href'  => $this->model_tool_seo_url->rewrite($this->url->http('product/category&category_id=' . $this->request->get['category_id']))
				);
				
				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_name_asc'),
					'value' => 'pd.name-ASC',
					'href'  => $this->model_tool_seo_url->rewrite($this->url->http('product/category&category_id=' . $this->request->get['category_id'] . '&sort=pd.name&order=ASC'))
				);  
 
				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_name_desc'),
					'value' => 'pd.name-DESC',
					'href'  => $this->model_tool_seo_url->rewrite($this->url->http('product/category&category_id=' . $this->request->get['category_id'] . '&sort=pd.name&order=DESC'))
				);  

				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_price_asc'),
					'value' => 'p.price-ASC',
					'href'  => $this->model_tool_seo_url->rewrite($this->url->http('product/category&category_id=' . $this->request->get['category_id'] . '&sort=p.price&order=ASC'))
				); 

				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_price_desc'),
					'value' => 'p.price-DESC',
					'href'  => $this->model_tool_seo_url->rewrite($this->url->http('product/category&category_id=' . $this->request->get['category_id'] . '&sort=p.price&order=DESC'))
				); 
				
				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_desc'),
					'value' => 'rating-DESC',
					'href'  => $this->model_tool_seo_url->rewrite($this->url->http('product/category&category_id=' . $this->request->get['category_id'] . '&sort=rating&order=DESC'))
				); 
				
				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_asc'),
					'value' => 'rating-ASC',
					'href'  => $this->model_tool_seo_url->rewrite($this->url->http('product/category&category_id=' . $this->request->get['category_id'] . '&sort=rating&order=ASC'))
				);		
				
				$url = '';

				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}
				
				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}
				$href = $this->url->http('product/category&category_id=' . $this->request->get['category_id']);
				$title = '';
				if (isset($this->request->get['order']) && isset($this->request->get['sort'])) {
					$this->document->title .= ' - ' . $this->language->get('text_sort') . $this->language->get('text_' .str_replace(array('pd.','p.'),'',$this->request->get['sort']) . '_' . strtolower($this->request->get['order']));
					$this->document->description = $this->document->description;
					$href .= '&sort=' . $this->request->get['sort'] . '&order=' . $this->request->get['order'];
					$title = $this->language->get('text_sort') . $this->language->get('text_' .str_replace(array('pd.','p.'),'',$this->request->get['sort']) . '_' . strtolower($this->request->get['order']));
					
					$this->document->breadcrumbs[] = array(
						'href'      => $this->model_tool_seo_url->rewrite($href),
						'text'      => $title,
						'separator' => $this->language->get('text_separator')
					);
					$title .= ' - ';
				}
				if (isset($this->request->get['page'])) {
					$this->document->title .= ' - ' . $this->language->get('text_page') . $this->request->get['page'];
					$this->document->description = $this->language->get('text_page') . $this->request->get['page'] . ' - ' . $this->document->description;
					$title .= $this->language->get('text_page') . $this->request->get['page'];
					$href .= '&page=' . $this->request->get['page'];
					
					$this->document->breadcrumbs[] = array(
						'href'      => $this->model_tool_seo_url->rewrite($href),
						'text'      => $title,
						'separator' => $this->language->get('text_separator')
					);
				}
				
				$pagination = new Pagination();
				$pagination->total = $product_total;
				$pagination->page = $page;
				$pagination->limit = $this->config->get('config_category');
				$pagination->text = $this->language->get('text_pagination');
				$pagination->url = $this->model_tool_seo_url->rewrite($this->url->http('product/category&category_id=' . $this->request->get['category_id'] . $url . '&page=%s'));
			
				$this->data['pagination'] = $pagination->render();
			
				$this->data['sort'] = $sort;
				$this->data['order'] = $order;
			
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/category.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/product/category.tpl';
				} else {
					$this->template = 'default/template/product/category.tpl';
				}	
				
				$this->children = array(
					'common/header',
					'common/footer',
					'common/column_left',
					'common/column_right'
				);
		
				$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));										
      		} else {
        		$this->document->title = $category_info['name'];
				
				$this->document->description = $category_info['meta_description'];
				
        		$this->data['heading_title'] = $category_info['name'];

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
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
				
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}	
			
			if (isset($this->request->get['category_id'])) {	
	       		$this->document->breadcrumbs[] = array(
   	    			'href'      => $this->model_tool_seo_url->rewrite($this->url->http('product/category&category_id=' . $this->request->get['category_id'] . $url)),
    	   			'text'      => $this->language->get('text_error'),
        			'separator' => $this->language->get('text_separator')
        		);
			}
				
			$this->document->title = $this->language->get('text_error');

      		$this->data['heading_title'] = $this->language->get('text_error');

      		$this->data['text_error'] = $this->language->get('text_error');

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