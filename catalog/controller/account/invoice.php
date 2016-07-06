<?php 
class ControllerAccountInvoice extends Controller {
	public function index() {
		$this->load->model('tool/seo_url');
		
    	if (!$this->customer->isLogged()) {
			if (isset($this->request->get['order_id'])) {
				$order_id = $this->request->get['order_id'];
			} else {
				$order_id = 0;
			}	
			
			$this->session->data['redirect'] = $this->model_tool_seo_url->rewrite($this->url->https('account/invoice')) . '&order_id=' . $order_id;
			
			$this->redirect($this->model_tool_seo_url->rewrite($this->url->https('account/login')));
    	}
	  
    	$this->language->load('account/invoice');

    	$this->document->title = $this->language->get('heading_title');
      	
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
        	'href'      => $this->model_tool_seo_url->rewrite($this->url->http('account/history')),
        	'text'      => $this->language->get('text_history'),
        	'separator' => $this->language->get('text_separator')
      	);
      	
		$this->document->breadcrumbs[] = array(
        	'href'      => $this->model_tool_seo_url->rewrite($this->url->http('account/invoice')) . '&order_id=' . $this->request->get['order_id'],
        	'text'      => $this->language->get('text_invoice'),
        	'separator' => $this->language->get('text_separator')
      	);
		
		$this->load->model('account/order');

		if (isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = 0;
		}
			
		$order_info = $this->model_account_order->getOrder($order_id);
		
		if ($order_info) {
      		$this->data['heading_title'] = $this->language->get('heading_title');

    		$this->data['text_order'] = $this->language->get('text_order');
			$this->data['text_email'] = $this->language->get('text_email');
			$this->data['text_telephone'] = $this->language->get('text_telephone');
      		$this->data['text_shipping_address'] = $this->language->get('text_shipping_address');
      		$this->data['text_shipping_method'] = $this->language->get('text_shipping_method');
      		$this->data['text_payment_address'] = $this->language->get('text_payment_address');
      		$this->data['text_payment_method'] = $this->language->get('text_payment_method');
      		$this->data['text_order_history'] = $this->language->get('text_order_history');
      		$this->data['text_product'] = $this->language->get('text_product');
      		$this->data['text_model'] = $this->language->get('text_model');
      		$this->data['text_quantity'] = $this->language->get('text_quantity');
      		$this->data['text_price'] = $this->language->get('text_price');
      		$this->data['text_total'] = $this->language->get('text_total');
			$this->data['text_comment'] = $this->language->get('text_comment');

      		$this->data['column_date_added'] = $this->language->get('column_date_added');
      		$this->data['column_status'] = $this->language->get('column_status');
      		$this->data['column_comment'] = $this->language->get('column_comment');
			
      		$this->data['button_continue'] = $this->language->get('button_continue');

			$this->data['order_id'] = $order_info['order_id'];
			$this->data['email'] = $order_info['email'];
			$this->data['telephone'] = $order_info['telephone'];

			if ($order_info['address']) {
      			$format = $order_info['address'];
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
	  			'customername'  => $order_info['customername'],
      			'address'   	=> $order_info['address'],
      			'city'      	=> $order_info['city'],
      			'zone'      	=> $order_info['zone'],
      			'country'   	=> $order_info['country']  
			);

			$this->data['address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

			$this->data['shipping_method'] = $order_info['shipping_method'];

      		$this->data['payment_method'] = $order_info['payment_method'];
			
			$this->data['products'] = array();
			
			$products = $this->model_account_order->getOrderProducts($this->request->get['order_id']);

      		foreach ($products as $product) {
				$options = $this->model_account_order->getOrderOptions($this->request->get['order_id'], $product['order_product_id']);

        		$option_data = array();

        		foreach ($options as $option) {
          			$option_data[] = array(
            			'name'  => $option['name'],
            			'value' => $option['value'],
          			);
        		}

        		$this->data['products'][] = array(
          			'name'     => $product['name'],
          			'model'    => $product['model'],
          			'option'   => $option_data,
          			'quantity' => $product['quantity'],
          			'price'    => $this->currency->format($product['price'], $order_info['currency'], $order_info['value']),
					'total'    => $this->currency->format($product['total'], $order_info['currency'], $order_info['value'])
        		);
      		}
			
			if (!$this->config->get('config_customer_price')) {
				$this->data['display_price'] = TRUE;
			} elseif ($this->customer->isLogged() && $this->customer->getCustomerGroupVip()) {
				$this->data['display_price'] = TRUE;
			} else {
				$this->data['display_price'] = FALSE;
			}

      		$this->data['totals'] = $this->model_account_order->getOrderTotals($this->request->get['order_id']);
			
			$this->data['comment'] = $order_info['comment'];
      		
			$this->data['historys'] = array();

			$results = $this->model_account_order->getOrderHistorys($this->request->get['order_id']);

      		foreach ($results as $result) {
        		$this->data['historys'][] = array(
          			'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
          			'status'     => $result['status'],
          			'comment'    => nl2br($result['comment'])
        		);
      		}

      		$this->data['continue'] = $this->model_tool_seo_url->rewrite($this->url->https('account/history'));

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/invoice.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/account/invoice.tpl';
			} else {
				$this->template = 'default/template/account/invoice.tpl';
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

      		$this->data['continue'] = $this->model_tool_seo_url->rewrite($this->url->http('account/history'));
      			
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