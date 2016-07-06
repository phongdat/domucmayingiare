<?php 
class ControllerAccountAddress extends Controller {
	private $error = array();
	  
  	public function index() {
		$this->load->model('tool/seo_url');
		
    	if (!$this->customer->isLogged()) {
	  		$this->session->data['redirect'] = $this->model_tool_seo_url->rewrite($this->url->https('account/address'));

	  		$this->redirect($this->model_tool_seo_url->rewrite($this->url->https('account/login'))); 
    	}
	
    	$this->language->load('account/address');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('account/address');
		
		$this->getList();
  	}

  	public function insert() {
		$this->load->model('tool/seo_url');
		
    	if (!$this->customer->isLogged()) {
	  		$this->session->data['redirect'] = $this->model_tool_seo_url->rewrite($this->url->https('account/address'));

	  		$this->redirect($this->model_tool_seo_url->rewrite($this->url->https('account/login'))); 
    	} 

    	$this->language->load('account/address');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('account/address');
			
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_account_address->addAddress($this->request->post);
			
      		$this->session->data['success'] = $this->language->get('text_insert');

	  		$this->redirect($this->model_tool_seo_url->rewrite($this->url->https('account/address')));
    	} 
	  	
		$this->getForm();
  	}

  	public function update() {
		$this->load->model('tool/seo_url');
		
    	if (!$this->customer->isLogged()) {
	  		$this->session->data['redirect'] = $this->model_tool_seo_url->rewrite($this->url->https('account/address'));

	  		$this->redirect($this->model_tool_seo_url->rewrite($this->url->https('account/login'))); 
    	} 
		
    	$this->language->load('account/address');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('account/address');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
       		$this->model_account_address->editAddress($this->request->get['address_id'], $this->request->post);
	  		
			if (isset($this->session->data['shipping_address_id']) && ($this->request->get['address_id'] == $this->session->data['shipping_address_id'])) {
	  			unset($this->session->data['shipping_methods']);
				unset($this->session->data['shipping_method']);	

				$this->tax->setZone($this->request->post['country_id'], $this->request->post['zone_id']);
			}

			if (isset($this->session->data['payment_address_id']) && ($this->request->get['address_id'] == $this->session->data['payment_address_id'])) {
	  			unset($this->session->data['payment_methods']);
				unset($this->session->data['payment_method']);				
			}
			
			$this->session->data['success'] = $this->language->get('text_update');
	  
	  		$this->redirect($this->model_tool_seo_url->rewrite($this->url->https('account/address')));
    	} 
	  	
		$this->getForm();
  	}

  	public function delete() {
		$this->load->model('tool/seo_url');
		
    	if (!$this->customer->isLogged()) {
	  		$this->session->data['redirect'] = $this->model_tool_seo_url->rewrite($this->url->https('account/address'));

	  		$this->redirect($this->model_tool_seo_url->rewrite($this->url->https('account/login'))); 
    	} 
			
    	$this->language->load('account/address');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('account/address');
		
    	if (isset($this->request->get['address_id']) && $this->validateDelete()) {
			$this->model_account_address->deleteAddress($this->request->get['address_id']);	

			if ($this->request->get['address_id'] == $this->session->data['shipping_address_id']) {
	  			unset($this->session->data['shipping_address_id']);
				unset($this->session->data['shipping_methods']);
				unset($this->session->data['shipping_method']);	
			}

			if ($this->request->get['address_id'] == $this->session->data['payment_address_id']) {
	  			unset($this->session->data['payment_address_id']);
				unset($this->session->data['payment_methods']);
				unset($this->session->data['payment_method']);				
			}
			
			$this->session->data['success'] = $this->language->get('text_delete');
	  
	  		$this->redirect($this->model_tool_seo_url->rewrite($this->url->https('account/address')));
    	}
	
		$this->getList();	
  	}

  	private function getList() {
		$this->load->model('tool/seo_url');
		
      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->model_tool_seo_url->rewrite($this->url->http('common/home')),
        	'text'      => $this->language->get('text_home'),
        	'separator' => FALSE
      	); 

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->model_tool_seo_url->rewrite($this->url->http('account/account')),
        	'text'      => $this->language->get('text_account'),
        	'separator' => $this->language->get('text_separator')
      	);

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->model_tool_seo_url->rewrite($this->url->http('account/address')),
        	'text'      => $this->language->get('heading_title'),
        	'separator' => $this->language->get('text_separator')
      	);
			
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_address_book'] = $this->language->get('text_address_book');
   
    	$this->data['button_new_address'] = $this->language->get('button_new_address');
    	$this->data['button_edit'] = $this->language->get('button_edit');
    	$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_back'] = $this->language->get('button_back');

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
		
    	$this->data['addresses'] = array();
		
		$results = $this->model_account_address->getAddresses();

    	foreach ($results as $result) {
			if ($result['address_format']) {
      			$format = $result['address_format'];
    		} else {
				$format = '{customername}' . "\n" . '{address}' . "\n" . '{city}' . "\n" . '{zone}' . "\n" . '{country}';
			}
		
    		$find = array(
	  			'{customername}',
      			'{address}',
     			'{city}',
      			'{zone}',
      			'{country}'
			);
	
			$replace = array(
	  			'customername' 	=> $result['customername'],
      			'address'   	=> $result['address'],
      			'city'      	=> $result['city'],
      			'zone'      	=> $result['zone'],
      			'country'   	=> $result['country']  
			);

      		$this->data['addresses'][] = array(
        		'address_id' => $result['address_id'],
        		'address'    => str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format)))),
        		'update'     => $this->model_tool_seo_url->rewrite($this->url->https('account/address/update')) . '&address_id=' . $result['address_id'],
				'delete'     => $this->model_tool_seo_url->rewrite($this->url->https('account/address/delete')) . '&address_id=' . $result['address_id']
      		);
    	}

    	$this->data['insert'] = $this->model_tool_seo_url->rewrite($this->url->https('account/address/insert'));
		$this->data['back'] = $this->model_tool_seo_url->rewrite($this->url->https('account/account'));
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/addresses.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/addresses.tpl';
		} else {
			$this->template = 'default/template/account/addresses.tpl';
		}
		
		$this->children = array(
			'common/header',
			'common/footer',
			'common/column_left',
			'common/column_right'
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));		
  	}

  	private function getForm() {
		$this->load->model('tool/seo_url');
		
      	$this->document->breadcrumbs = array();

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->model_tool_seo_url->rewrite($this->url->http('common/home')),
        	'text'      => $this->language->get('text_home'),
        	'separator' => FALSE
      	); 

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->model_tool_seo_url->rewrite($this->url->http('account/account')),
        	'text'      => $this->language->get('text_account'),
        	'separator' => $this->language->get('text_separator')
      	);

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->model_tool_seo_url->rewrite($this->url->http('account/address')),
        	'text'      => $this->language->get('heading_title'),
        	'separator' => $this->language->get('text_separator')
      	);
		
		if (!isset($this->request->get['address_id'])) {
      		$this->document->breadcrumbs[] = array(
        		'href'      => $this->model_tool_seo_url->rewrite($this->url->http('account/address/insert')),
        		'text'      => $this->language->get('text_edit_address'),
        		'separator' => $this->language->get('text_separator')
      		);
		} else {
      		$this->document->breadcrumbs[] = array(
        		'href'      => $this->model_tool_seo_url->rewrite($this->url->http('account/address/update')) . '&address_id=' . $this->request->get['address_id'],
        		'text'      => $this->language->get('text_edit_address'),
        		'separator' => $this->language->get('text_separator')
      		);
		}
						
    	$this->data['heading_title'] = $this->language->get('heading_title');
    	
		$this->data['text_edit_address'] = $this->language->get('text_edit_address');
    	$this->data['text_yes'] = $this->language->get('text_yes');
    	$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_select'] = $this->language->get('text_select');
		
    	$this->data['entry_customername'] = $this->language->get('entry_customername');
    	$this->data['entry_address'] = $this->language->get('entry_address');
    	$this->data['entry_city'] = $this->language->get('entry_city');
    	$this->data['entry_country'] = $this->language->get('entry_country');
    	$this->data['entry_zone'] = $this->language->get('entry_zone');
    	$this->data['entry_default'] = $this->language->get('entry_default');

    	$this->data['button_continue'] = $this->language->get('button_continue');
    	$this->data['button_back'] = $this->language->get('button_back');

		if (isset($this->error['customername'])) {
    		$this->data['error_customername'] = $this->error['customername'];
		} else {
			$this->data['error_customername'] = '';
		}
		
		if (isset($this->error['address'])) {
    		$this->data['error_address'] = $this->error['address'];
		} else {
			$this->data['error_address'] = '';
		}
		
		if (isset($this->error['city'])) {
    		$this->data['error_city'] = $this->error['city'];
		} else {
			$this->data['error_city'] = '';
		}		

		if (isset($this->error['country'])) {
			$this->data['error_country'] = $this->error['country'];
		} else {
			$this->data['error_country'] = '';
		}

		if (isset($this->error['zone'])) {
			$this->data['error_zone'] = $this->error['zone'];
		} else {
			$this->data['error_zone'] = '';
		}
		
		if (!isset($this->request->get['address_id'])) {
    		$this->data['action'] = $this->model_tool_seo_url->rewrite($this->url->https('account/address/insert'));
		} else {
    		$this->data['action'] = $this->model_tool_seo_url->rewrite($this->url->https('account/address/update')) . '&address_id=' . $this->request->get['address_id'];
		}
		
    	if (isset($this->request->get['address_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$address_info = $this->model_account_address->getAddress($this->request->get['address_id']);
		}
	
    	if (isset($this->request->post['customername'])) {
      		$this->data['customername'] = $this->request->post['customername'];
    	} elseif (isset($address_info)) {
      		$this->data['customername'] = $address_info['customername'];
    	} else {
			$this->data['customername'] = '';
		}

    	if (isset($this->request->post['address'])) {
      		$this->data['address'] = $this->request->post['address'];
    	} elseif (isset($address_info)) {
			$this->data['address'] = $address_info['address'];
		} else {
      		$this->data['address'] = '';
    	}

    	if (isset($this->request->post['city'])) {
      		$this->data['city'] = $this->request->post['city'];
    	} elseif (isset($address_info)) {
			$this->data['city'] = $address_info['city'];
		} else {
      		$this->data['city'] = '';
    	}

    	if (isset($this->request->post['country_id'])) {
      		$this->data['country_id'] = $this->request->post['country_id'];
    	}  elseif (isset($address_info)) {
      		$this->data['country_id'] = $address_info['country_id'];			
    	} else {
      		$this->data['country_id'] = $this->config->get('config_country_id');
    	}

    	if (isset($this->request->post['zone_id'])) {
      		$this->data['zone_id'] = $this->request->post['zone_id'];
    	}  elseif (isset($address_info)) {
      		$this->data['zone_id'] = $address_info['zone_id'];			
    	} else {
      		$this->data['zone_id'] = 'FALSE';
    	}
		
		$this->load->model('localisation/country');
		
    	$this->data['countries'] = $this->model_localisation_country->getCountries();

    	if (isset($this->request->post['default'])) {
      		$this->data['default'] = $this->request->post['default'];
    	} elseif (isset($this->request->get['address_id'])) {
      		$this->data['default'] = $this->customer->getAddressId() == $this->request->get['address_id'];
    	} else {
			$this->data['default'] = FALSE;
		}

    	$this->data['back'] = $this->model_tool_seo_url->rewrite($this->url->https('account/address'));
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/address.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/address.tpl';
		} else {
			$this->template = 'default/template/account/address.tpl';
		}
		
		$this->children = array(
			'common/header',
			'common/footer',
			'common/column_left',
			'common/column_right'
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));		
  	}
	
  	private function validateForm() {
    	if ((strlen(utf8_decode($this->request->post['customername'])) < 3) || (strlen(utf8_decode($this->request->post['customername'])) > 32)) {
      		$this->error['customername'] = $this->language->get('error_customername');
    	}

    	if ((strlen(utf8_decode($this->request->post['address'])) < 3) || (strlen(utf8_decode($this->request->post['address'])) > 128)) {
      		$this->error['address'] = $this->language->get('error_address');
    	}

    	if ((strlen(utf8_decode($this->request->post['city'])) < 3) || (strlen(utf8_decode($this->request->post['city'])) > 128)) {
      		$this->error['city'] = $this->language->get('error_city');
    	}
		
    	if ($this->request->post['country_id'] == 'FALSE') {
      		$this->error['country'] = $this->language->get('error_country');
    	}
		
    	if ($this->request->post['zone_id'] == 'FALSE') {
      		$this->error['zone'] = $this->language->get('error_zone');
    	}
		
    	if (!$this->error) {
      		return TRUE;
		} else {
      		return FALSE;
    	}
  	}

  	private function validateDelete() {
    	if ($this->model_account_address->getTotalAddresses() == 1) {
      		$this->error['warning'] = $this->language->get('error_delete');
    	}

    	if ($this->customer->getAddressId() == $this->request->get['address_id']) {
      		$this->error['warning'] = $this->language->get('error_default');
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