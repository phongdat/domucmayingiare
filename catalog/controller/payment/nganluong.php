<?php
class ControllerPaymentNganLuong extends Controller {
	protected function index() {
		$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['button_back'] = $this->language->get('button_back');

		$this->load->model('checkout/order');
		$this->load->model('payment/nganluong');
		
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
		$this->load->library('encryption');
		//$this->data['action'] = 'https://www.nganluong.vn/checkout.php';
		//Tạo link checkout cho nganluong
		$secure_pass= $this->config->get('nganluong_security');
		$merchant_site_code=$this->config->get('nganluong_merchant');
		$return_url=HTTPS_SERVER . 'index.php?route=checkout/success';
		$receiver=$this->config->get('nganluong_receiver');
		$transaction_info="không có gì";
		$order_code=$this->session->data['order_id'];
		$price=$this->currency->format($order_info['total'], $order_info['currency'], $order_info['value'], FALSE);
		$this->data['action'] = $this->model_payment_nganluong->buildCheckoutUrl($return_url, $receiver, $transaction_info, $order_code, $price,$merchant_site_code,$secure_pass);
	    // echo  $this->data['action'];die();
		//==============================

		$this->data['ap_merchant'] = $this->config->get('nganluong_merchant');
		$this->data['ap_amount'] = $this->currency->format($order_info['total'], $order_info['currency'], $order_info['value'], FALSE);
		$this->data['ap_currency'] = $order_info['currency'];
		$this->data['ap_purchasetype'] = 'Item';
		$this->data['ap_itemname'] = $this->config->get('config_name') . ' - #' . $this->session->data['order_id'];
		$this->data['ap_itemcode'] = $this->session->data['order_id'];
		$this->data['ap_returnurl'] = HTTPS_SERVER . 'index.php?route=checkout/success';
		
		if ($this->request->get['route'] != 'checkout/guest_step_3') {
			$this->data['ap_cancelurl'] = HTTPS_SERVER . 'index.php?route=checkout/payment';
		} else {
			$this->data['ap_cancelurl'] = HTTPS_SERVER . 'index.php?route=checkout/guest_step_2';
		}
		
		if ($this->request->get['route'] != 'checkout/guest_step_3') {
			$this->data['back'] = HTTPS_SERVER . 'index.php?route=checkout/payment';
		} else {
			$this->data['back'] = HTTPS_SERVER . 'index.php?route=checkout/guest_step_2';
		}
		
		$this->id = 'payment';
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/nganluong.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/nganluong.tpl';
		} else {
			$this->template = 'default/template/payment/nganluong.tpl';
		}		
		
		$this->render();
	}
	
	public function callback() {
		if (isset($this->request->post['ap_securitycode']) && ($this->request->post['ap_securitycode'] == $this->config->get('nganluong_security'))) {
			$this->load->model('checkout/order');
			$this->model_checkout_order->confirm($this->request->post['ap_itemcode'], $this->config->get('nganluong_order_status_id'));
		}
	}
}
?>