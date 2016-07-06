<?php  
class ControllerCommonHome extends Controller {
	public function index() {
		$this->language->load('common/home');
		$this->load->model('catalog/product');
		$this->load->model('catalog/review');
		$this->load->model('tool/seo_url');
		$this->load->helper('image');
		if (isset($this->session->data['popup'])) {
			$this->session->data['popup'] = "damoroi";
		}
		if (isset($this->session->data['popup'])) {
			$this->data['popup'] = $this->session->data['popup'];
		} else {
			$this->data['popup'] ='';
		}
		$this->data['link_popup'] = $this->url->http('common/home/popup');
		
		$this->document->title = $this->config->get('config_title');
		$this->document->description = $this->config->get('config_meta_description');
		if($this->config->get('popup_status')) {
		$this->data['popup_status'] = $this->config->get('popup_status');
		} else {
		$this->data['popup_status'] = '';
		}
		$this->data['heading_title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_store'));
		$this->data['welcome'] = html_entity_decode($this->config->get('config_welcome_' . $this->config->get('config_language_id')));

//slide
		$this->load->model('catalog/slideshow');	
		
		$this->data['slideshows'] = array();

		foreach ($this->model_catalog_slideshow->getslideshows() as $result) {
      		$this->data['slideshows'][] = array(
        		'name' => $result['name'],
				'link' => $result['link'],
	    		'image' => image_resize_fix($result['image'], 770, 310)
      		);
    	}
//end slide


// danh muc home
		$this->load->model('catalog/chome');
		 
		$this->data['chomes'] = array();
		
		$chomes = $this->model_catalog_chome->getchomes();
		
	foreach ($chomes as $chome) {
		
		$chome['products'] = array();

		foreach ($this->model_catalog_product->getProductsBychomeId($chome['chome_id'],0,20) as $result) {	
			if ($result['image']) {
				$image = $result['image'];
			} else {
				$image = 'no_image.jpg';
			}
			
			$rating = $this->model_catalog_review->getAverageRating($result['product_id']);	
			
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
          	$chome['products'][] = array(
            	'name'     			=> $result['name'],
				'model'    			=> $result['model'],
				'warranty' 			=> $result['warranty'],
				'stock'    			=> $result['stock'],
				'brief_description' => html_entity_decode($result['brief_description'], ENT_QUOTES, 'UTF-8'),
				'promotion'			=> html_entity_decode($result['promotion'], ENT_QUOTES, 'UTF-8'),
				'thumb'    			=> image_resize($image, $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')),
            	'price'    			=> $price,
				'special'  			=> $special,
				'href'     			=> $this->model_tool_seo_url->rewrite($this->url->http('product/product&product_id=' . $result['product_id']))
          	);
		}
		$this->data['chomes'][] = array(
			'name'            => $chome['name'],
			'image'           => $chome['image'],
			'link'        	  => $chome['link'],
			'products'        => $chome['products']
		);
	}
// end danh muc home

//san pham moi
		$this->data['products'] = array();

		foreach ($this->model_catalog_product->getLatestProducts(20) as $result) {			
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
// end san pham moi

		if (!$this->config->get('config_customer_price')) {
			$this->data['display_price'] = TRUE;
		} elseif ($this->customer->isLogged() && $this->customer->getCustomerGroupVip()) {
			$this->data['display_price'] = TRUE;
		} else {
			$this->data['display_price'] = FALSE;
		}
				
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/home.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/home.tpl';
		} else {
			$this->template = 'default/template/common/home.tpl';
		}
		
		$this->children = array(
			'common/header',
			'common/footer',
			'common/column_left',
			'common/column_right'
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	public function popup() {
		$this->session->data['popup'] = 1;
		
		if ($this->config->get('popup_status')) {
			$this->data['popup'] = html_entity_decode($this->config->get('popup_code'), ENT_QUOTES, 'UTF-8');
		} else {
			$this->data['popup'] = '';
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/popup.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/popup.tpl';
		} else {
			$this->template = 'default/template/common/popup.tpl';
		}
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
}
?>