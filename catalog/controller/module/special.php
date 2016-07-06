<?php  
class ControllerModulespecial extends Controller {
	protected function index() {
		$this->language->load('module/special');
		$this->load->model('catalog/product');
		$this->load->model('tool/seo_url');
		
		if(isset($this->request->get['route'])){
			$this->data['home_select'] = 1;
		} else {
			$this->data['home_select'] = 0;
		}
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_price'] = $this->language->get('text_price');
		
		$this->data['text_special'] = $this->language->get('text_special');
		
		$this->data['products'] = array();
			
		$results = $this->model_catalog_product->getProductSpecials('p.price', 'DESC', 0, 8);
		if(sizeof($results) > 8) {
			$this->data['special_href'] = $this->model_tool_seo_url->rewrite($this->url->http('product/special'));
		} else {
			$this->data['special_href'] = '';
		}
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
				
			if($result['price'] == 0) {$price = $this->language->get('price_contact');}
			
			$this->data['products'][] = array(
				'name'    			=> $result['name'],
				'product_id'     	=> $result['product_id'],
				'brief_description' => html_entity_decode($result['brief_description'], ENT_QUOTES, 'UTF-8'),
				'promotion'			=> html_entity_decode($result['promotion'], ENT_QUOTES, 'UTF-8'),
				'thumb'   			=> image_resize($image, 81, 109),
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
												
		$this->id = 'special';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/special.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/special.tpl';
		} else {
			$this->template = 'default/template/module/special.tpl';
		}
		
		$this->render();
  	}
}
?>