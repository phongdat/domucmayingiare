<?php 
class ControllerAccountCreate extends Controller {
	private $error = array();
	      
  	public function index() {
		$this->load->model('tool/seo_url');
		
		if ($this->customer->isLogged()) {
	  		$this->redirect($this->model_tool_seo_url->rewrite($this->url->https('account/account')));
    	}

    	$this->language->load('account/create');
		
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('account/customer');

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
        	'href'      => $this->model_tool_seo_url->rewrite($this->url->http('account/create')),
        	'text'      => $this->language->get('text_create'),
        	'separator' => $this->language->get('text_separator')
      	);
		
    	$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_select'] = $this->language->get('text_select');
    	$this->data['text_account_already'] = sprintf($this->language->get('text_account_already'), $this->model_tool_seo_url->rewrite($this->url->https('account/login')));
    	$this->data['text_your_details'] = $this->language->get('text_your_details');
    	$this->data['text_your_address'] = $this->language->get('text_your_address');
    	$this->data['text_your_password'] = $this->language->get('text_your_password');
		$this->data['text_newsletter'] = $this->language->get('text_newsletter');
		$this->data['text_wait'] = $this->language->get('text_wait');		
    	$this->data['entry_customername'] = $this->language->get('entry_customername');
    	$this->data['entry_email'] = $this->language->get('entry_email');
    	$this->data['entry_telephone'] = $this->language->get('entry_telephone');
    	$this->data['entry_company'] = $this->language->get('entry_company');
    	$this->data['entry_address'] = $this->language->get('entry_address');
    	$this->data['entry_city'] = $this->language->get('entry_city');
    	$this->data['entry_country'] = $this->language->get('entry_country');
    	$this->data['entry_zone'] = $this->language->get('entry_zone');
		$this->data['entry_newsletter'] = $this->language->get('entry_newsletter');
    	$this->data['entry_password'] = $this->language->get('entry_password');
    	$this->data['entry_confirm'] = $this->language->get('entry_confirm');

		$this->data['button_create'] = $this->language->get('button_create');

    	if (isset($this->request->post['country_id'])) {
      		$this->data['country_id'] = $this->request->post['country_id'];
		} else {	
      		$this->data['country_id'] = $this->config->get('config_country_id');
    	}

    	if (isset($this->request->post['zone_id'])) {
      		$this->data['zone_id'] = $this->request->post['zone_id']; 	
		} else {
      		$this->data['zone_id'] = 'FALSE';
    	}
		
		$this->load->model('localisation/country');
		
    	$this->data['countries'] = $this->model_localisation_country->getCountries();

		if ($this->config->get('config_account')) {
			$this->load->model('catalog/information');
			
			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_account'));
			
			if ($information_info) {
				$this->data['text_agree'] = sprintf($this->language->get('text_agree'), $this->model_tool_seo_url->rewrite($this->url->http('information/information&information_id=' . $this->config->get('config_account'))), $information_info['name']);
			} else {
				$this->data['text_agree'] = '';
			}
		} else {
			$this->data['text_agree'] = '';
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/create.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/create.tpl';
		} else {
			$this->template = 'default/template/account/create.tpl';
		}
		
		$this->children = array(
			'common/header',
			'common/footer',
			'common/column_left',
			'common/column_right'
		);

		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));	
  	}
	public function createajax() {
		$json = array();
		
		$this->load->model('tool/seo_url');
		
		if ($this->customer->isLogged()) {
	  		$this->redirect($this->model_tool_seo_url->rewrite($this->url->https('account/account')));
    	}

    	$this->language->load('account/create');

		$this->load->model('account/customer');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_account_customer->addCustomer($this->request->post);

			unset($this->session->data['guest']);

			$this->customer->login($this->request->post['email'], $this->request->post['password']);
			
			$json['success'] = $this->language->get('text_success');
			
			$this->language->load('mail/account_create');
			
			$subject = sprintf($this->language->get('text_subject'), $this->config->get('config_store'));
			
			$message = sprintf($this->language->get('text_welcome'), $this->config->get('config_store')) . "\n\n";
			
			if (!$this->config->get('config_customer_approval')) {
				$message .= $this->language->get('text_login') . "\n";
			} else {
				$message .= $this->language->get('text_approval') . "\n";
			}
			
			$message .= $this->model_tool_seo_url->rewrite($this->url->https('account/login')) . "\n\n";
			$message .= $this->language->get('text_services') . "\n\n";
			$message .= $this->language->get('text_thanks') . "\n";
			$message .= $this->config->get('config_store');
			
			$mail = new Mail($this->config->get('config_mail_protocol'), $this->config->get('config_smtp_host'), $this->config->get('config_smtp_username'), html_entity_decode($this->config->get('config_smtp_password')), $this->config->get('config_smtp_port'), $this->config->get('config_smtp_timeout'));
			$mail->setTo($this->request->post['email']);
	  		$mail->setFrom($this->config->get('config_email'));
	  		$mail->setSender($this->config->get('config_store'));
	  		$mail->setSubject($subject);
			$mail->setText($message);
      		$mail->send();
		} else {
			$json['error'] = $this->error['message'];
		}
		
		$this->load->library('json');
		
		$this->response->setOutput(Json::encode($json));
	}
  	private function validate() {
		if ($this->config->get('config_account')) {
			$this->load->model('catalog/information');
			
			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_account'));
			
			if ($information_info) {
    			if (!$this->request->post['agree']) {
      				$this->error['message'] = sprintf($this->language->get('error_agree'), $information_info['name']);
    			}
			}
		}
		
    	if ((strlen(utf8_decode($this->request->post['telephone'])) < 3) || (strlen(utf8_decode($this->request->post['telephone'])) > 32)) {
      		$this->error['message'] = $this->language->get('error_telephone');
    	}

    	if ((strlen(utf8_decode($this->request->post['password'])) < 4) || (strlen(utf8_decode($this->request->post['password'])) > 20)) {
      		$this->error['message'] = $this->language->get('error_password');
    	}

    	if ($this->request->post['confirm'] != $this->request->post['password']) {
      		$this->error['message'] = $this->language->get('error_confirm');
    	}

    	if ($this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
      		$this->error['message'] = $this->language->get('error_exists');
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