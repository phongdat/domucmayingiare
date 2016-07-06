<?php 
class ControllerCheckoutCart extends Controller {
	private $error = array();
	
	public function index() {
		$this->load->model('tool/seo_url'); 
		$this->language->load('checkout/cart');
		
    	if ($this->request->server['REQUEST_METHOD'] == 'POST') {
      		if (isset($this->request->post['quantity'])) {
				if (!is_array($this->request->post['quantity'])) {
					if (isset($this->request->post['option'])) {
						$option = $this->request->post['option'];
					} else {
						$option = array();	
					}
			
      				$this->cart->add($this->request->post['product_id'], $this->request->post['quantity'], $option);
				} else {
					foreach ($this->request->post['quantity'] as $key => $value) {
	      				$this->cart->update($key, $value);
					}
				}
				unset($this->session->data['payment_methods']);
      		}

      		if (isset($this->request->post['remove'])) {
	    		foreach (array_keys($this->request->post['remove']) as $key) {
          			$this->cart->remove($key);
				}
      		}
			
			if (isset($this->request->post['redirect'])) {
				$this->session->data['redirect'] = $this->request->post['redirect'];
			}	
			
			if (isset($this->request->post['quantity']) || isset($this->request->post['remove'])) {
				unset($this->session->data['payment_methods']);
				
				$this->redirect($this->model_tool_seo_url->rewrite($this->url->https('checkout/cart')));
			}
    	}

    	$this->document->title = $this->language->get('heading_title');

      	$this->document->breadcrumbs = array();

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->model_tool_seo_url->rewrite($this->url->http('common/home')),
        	'text'      => $this->language->get('text_home'),
        	'separator' => FALSE
      	); 

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->model_tool_seo_url->rewrite($this->url->http('checkout/cart')),
        	'text'      => $this->language->get('text_basket'),
        	'separator' => $this->language->get('text_separator')
      	);
			
    	if ($this->cart->hasProducts()) {
      		$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->data['text_select'] = $this->language->get('text_select');
      		$this->data['text_sub_total'] = $this->language->get('text_sub_total');
			$this->data['text_discount'] = $this->language->get('text_discount');
		
     		$this->data['column_remove'] = $this->language->get('column_remove');
      		$this->data['column_image'] = $this->language->get('column_image');
      		$this->data['column_name'] = $this->language->get('column_name');
      		$this->data['column_model'] = $this->language->get('column_model');
      		$this->data['column_quantity'] = $this->language->get('column_quantity');
			$this->data['column_price'] = $this->language->get('column_price');
      		$this->data['column_total'] = $this->language->get('column_total');

      		$this->data['button_update'] = $this->language->get('button_update');
      		$this->data['button_shopping'] = $this->language->get('button_shopping');
      		$this->data['button_order'] = $this->language->get('button_order');
			
			if (isset($this->error['warning'])) {
				$this->data['error_warning'] = $this->error['warning'];			
			} elseif (!$this->cart->hasStock() && $this->config->get('config_stock_check')) {
      			$this->data['error_warning'] = $this->language->get('error_stock');
			} else {
				$this->data['error_warning'] = '';
			}
		
			$this->data['action'] = $this->model_tool_seo_url->rewrite($this->url->http('checkout/cart'));
			
			$this->load->helper('image');
			
      		$this->data['products'] = array();

      		foreach ($this->cart->getProducts() as $result) {
        		$option_data = array();

        		foreach ($result['option'] as $option) {
          			$option_data[] = array(
            			'name'  => $option['name'],
            			'value' => $option['value']
          			);
        		}

				if ($result['image']) {
					$image = $result['image'];
				} else {
					$image = 'no_image.jpg';
				}

        		$this->data['products'][] = array(
          			'key'      => $result['key'],
          			'name'     => $result['name'],
          			'model'    => $result['model'],
          			'thumb'    => image_resize($image, $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height')),
          			'option'   => $option_data,
          			'quantity' => $result['quantity'],
          			'stock'    => $result['stock'],
					'price'    => $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'))),
					'total'    => $this->currency->format($this->tax->calculate($result['total'], $result['tax_class_id'], $this->config->get('config_tax'))),
					'href'     => $this->model_tool_seo_url->rewrite($this->url->http('product/product&product_id=' . $result['product_id']))
        		);
      		}
			
			if (!$this->config->get('config_customer_price')) {
				$this->data['display_price'] = TRUE;
			} elseif ($this->customer->isLogged() && $this->customer->getCustomerGroupVip()) {
				$this->data['display_price'] = TRUE;
			} else {
				$this->data['display_price'] = FALSE;
			}
			
      		$this->data['sub_total'] = $this->currency->format($this->cart->getTotal());
				$total_data = array();
				$total = 0;
				$taxes = $this->cart->getTaxes();
				 
				$this->load->model('checkout/extension');
				
				$sort_order = array(); 
				
				$results = $this->model_checkout_extension->getExtensions('total');
				
				foreach ($results as $key => $value) {
					$sort_order[$key] = $this->config->get($value['key'] . '_sort_order');
				}
				
				array_multisort($sort_order, SORT_ASC, $results);
				
				foreach ($results as $result) {
					$this->load->model('total/' . $result['key']);

					$this->{'model_total_' . $result['key']}->getTotal($total_data, $total, $taxes);
				}
				
				$sort_order = array(); 
			  
				foreach ($total_data as $key => $value) {
					$sort_order[$key] = $value['sort_order'];
				}

				array_multisort($sort_order, SORT_ASC, $total_data);

				$this->data['totals'] = $total_data;
				
			if (isset($this->session->data['redirect'])) {
      			$this->data['continue'] = $this->session->data['redirect'];
				
				unset($this->session->data['redirect']);
			} else {
				$this->data['continue'] = $this->model_tool_seo_url->rewrite($this->url->http('common/home'));
			}
			
			$this->data['checkout'] = $this->model_tool_seo_url->rewrite($this->url->http('checkout/cart/order'));
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/cart.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/checkout/cart.tpl';
			} else {
				$this->template = 'default/template/checkout/cart.tpl';
			}
			
			$this->children = array(
				'common/header',
				'common/footer',
				'common/column_left',
				'common/column_right'
			);		
			
			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));					
    	} else {
      		$this->data['heading_title'] = $this->language->get('heading_title');

      		$this->data['text_error'] = $this->language->get('text_error');

      		$this->data['button_continue'] = $this->language->get('button_continue');

      		$this->data['continue'] = $this->model_tool_seo_url->rewrite($this->url->http('common/home'));

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

	public function order() {
		$this->language->load('checkout/confirm');
		$this->data['logged'] = $this->customer->isLogged();
		if ($this->customer->isLogged()) {
			$this->data['customername'] = $this->customer->getcustomername();
			$this->data['email'] = $this->customer->getEmail();
			$this->data['telephone'] = $this->customer->getTelephone();
			$this->load->model('account/address');
			$address = $this->model_account_address->getAddress($this->customer->getAddressId());

			$this->data['address'] = $address['address'];
			$this->data['city'] = $address['city'];
			if($address['zone_id']){
				$this->data['zone_id'] = $address['zone_id'];
			} else {
				$this->data['zone_id'] = 'FALSE';
			}
		} else {
			$this->data['customername'] = '';
			$this->data['email'] = '';
			$this->data['telephone'] = '';
			$this->data['address'] = '';
			$this->data['city'] = '';
			$this->data['zone_id'] = 'FALSE';
		}

			$this->data['country_id'] = $this->config->get('config_country_id');
			$this->data['getProducts'] = $this->cart->getProducts();
			$this->load->model('localisation/country');	

			$this->data['countries'] = $this->model_localisation_country->getCountries();
			
			$this->data['heading_title'] = $this->language->get('heading_title');
			$this->data['text_select'] = $this->language->get('text_select');
			$this->data['text_your_details'] = $this->language->get('text_your_details');
			$this->data['text_your_address'] = $this->language->get('text_your_address');
			$this->data['text_wait'] = $this->language->get('text_wait');		
			$this->data['entry_customername'] = $this->language->get('entry_customername');
			$this->data['entry_email'] = $this->language->get('entry_email');
			$this->data['entry_telephone'] = $this->language->get('entry_telephone');
			$this->data['entry_address'] = $this->language->get('entry_address');
			$this->data['entry_city'] = $this->language->get('entry_city');
			$this->data['entry_country'] = $this->language->get('entry_country');
			$this->data['entry_zone'] = $this->language->get('entry_zone');
			$this->data['entry_newsletter'] = $this->language->get('entry_newsletter');
			$this->data['button_confirm'] = $this->language->get('button_confirm');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/order.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/checkout/order.tpl';
		} else {
			$this->template = 'default/template/checkout/order.tpl';
		}
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	public function confirm() {
		$this->language->load('checkout/confirm');
		$json = array();
		
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
				$total_data = array();
				$total = 0;
				$taxes = $this->cart->getTaxes();
				 
				$this->load->model('checkout/extension');
				
				$sort_order = array(); 
				
				$results = $this->model_checkout_extension->getExtensions('total');
				
				foreach ($results as $key => $value) {
					$sort_order[$key] = $this->config->get($value['key'] . '_sort_order');
				}
				
				array_multisort($sort_order, SORT_ASC, $results);
				
				foreach ($results as $result) {
					$this->load->model('total/' . $result['key']);

					$this->{'model_total_' . $result['key']}->getTotal($total_data, $total, $taxes);
				}
				
				$sort_order = array(); 
			  
				foreach ($total_data as $key => $value) {
					$sort_order[$key] = $value['sort_order'];
				}

				array_multisort($sort_order, SORT_ASC, $total_data);
				
				$data = array();
				if (!$this->customer->isLogged()) {
					$data['customer_id'] = 0;
				} else {
					$data['customer_id'] = $this->customer->getId();
				}
					$data['customername'] = $this->request->post['customername'];
					$data['telephone'] = $this->request->post['telephone'];
					$data['email'] = $this->request->post['email'];
					if (isset($this->request->post['address'])) {
						$data['address'] = $this->request->post['address'];
					} else {
						$data['address'] = '';
					}
					if (isset($this->request->post['city'])) {
						$data['city'] = $this->request->post['city'];
					} else {
						$data['city'] = '';
					}
					if ($this->request->post['zone_id'] != 'FALSE') {
						$data['zone_id'] = $this->request->post['zone_id'];
						$this->load->model('localisation/zone');
						$zone_info = $this->model_localisation_zone->getZone($this->request->post['zone_id']);
						if ($zone_info) {
							$data['zone'] = $zone_info['name'];
						}
					} else {
						$data['zone'] = '';
						$data['zone_id'] = $this->request->post['zone_id'];
					}
					
					if (isset($this->request->post['country_id'])) {
						$data['country_id'] = $this->request->post['country_id'];
						$this->load->model('localisation/country');
						$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);
						if ($country_info) {
							$data['country'] = $country_info['name'];
						}
					} else {
						$data['country'] = '';
						$data['country_id'] = '';
					}
					
				$product_data = array();
			
				foreach ($this->cart->getProducts() as $product) {
					$option_data = array();

					foreach ($product['option'] as $option) {
						$option_data[] = array(
							'product_option_value_id' => $option['product_option_value_id'],			   
							'name'                    => $option['name'],
							'value'                   => $option['value'],
							'prefix'                  => $option['prefix']
						);
					}
		 
					$product_data[] = array(
						'product_id' => $product['product_id'],
						'name'       => $product['name'],
						'model'      => $product['model'],
						'option'     => $option_data,
						'quantity'   => $product['quantity'], 
						'price'      => $product['price'],
						'total'      => $product['total'],
						'tax'        => $this->tax->getRate($product['tax_class_id'])
					); 
				}
				
				$data['products'] = $product_data;

				$data['total'] = $total;
				$data['totals'] = $total_data;
				$data['comment'] = '';
				$data['language_id'] = $this->config->get('config_language_id');
				$data['currency_id'] = $this->currency->getId();
				$data['currency'] = $this->currency->getCode();
				$data['value'] = $this->currency->getValue($this->currency->getCode());
				
				$data['coupon_id'] = 0;
				
				$data['ip'] = $this->request->server['REMOTE_ADDR'];
				$data['shipping_method'] = '';
				$data['payment_method'] = '';
				$this->load->model('checkout/order');
				
				$this->session->data['order_id'] = $this->model_checkout_order->create($data);
				$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('config_order_status_id'));
				$this->cart->clear();
				$json['success'] = $this->language->get('text_success');
			} else {
				$json['error'] = $this->error['message'];
			}

		$this->load->library('json');
		
		$this->response->setOutput(Json::encode($json));
	}
	
	private function validate() {
		$this->language->load('checkout/confirm');
		
    	if ((strlen(utf8_decode($this->request->post['telephone'])) < 3) || (strlen(utf8_decode($this->request->post['telephone'])) > 32)) {
      		$this->error['message'] = $this->language->get('error_telephone');
    	}
		
		$pattern = '/^[A-Z0-9._%-]+@[A-Z0-9][A-Z0-9.-]{0,61}[A-Z0-9]\.[A-Z]{2,6}$/i';

    	if (!preg_match($pattern, $this->request->post['email'])) {
      		$this->error['message'] = $this->language->get('error_email');
    	}

    	if ((strlen(utf8_decode($this->request->post['customername'])) < 3) || (strlen(utf8_decode($this->request->post['customername'])) > 64)) {
      		$this->error['message'] = $this->language->get('error_customername');
    	}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
  	public function zone() {
		$output = '<option value="FALSE">' . $this->language->get('text_select') . '</option>';
		
		$this->load->model('localisation/zone');

    	$results = $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']);
        
      	foreach ($results as $result) {
        	$output .= '<option value="' . $result['zone_id'] . '"';
	
	    	if (isset($this->request->get['zone_id']) && ($this->request->get['zone_id'] == $result['zone_id'])) {
	      		$output .= ' selected="selected"';
	    	}
	
	    	$output .= '>' . $result['name'] . '</option>';
    	} 
		
		if (!$results) {
			if (!$this->request->get['zone_id']) {
		  		$output .= '<option value="0" selected="selected">' . $this->language->get('text_none') . '</option>';
			} else {
				$output .= '<option value="0">' . $this->language->get('text_none') . '</option>';
			}
		}
	
		$this->response->setOutput($output, $this->config->get('config_compression'));
  	} 	
}
?>