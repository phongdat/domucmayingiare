<?php
final class Customer {
	private $customer_id;
	private $customername;
	private $email;
	private $telephone;
	private $newsletter;
	private $customer_group_id;
	private $customer_group_status;
	private $address_id;
	
  	public function __construct() {
		$this->db = Registry::get('db');
		$this->request = Registry::get('request');
		$this->session = Registry::get('session');
				
		if (isset($this->session->data['customer_id'])) { 
			$customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . " customer_group cg ON (c.customer_group_id = cg.customer_group_id) WHERE c.customer_id = '" . (int)$this->session->data['customer_id'] . "' AND c.status = '1'");
			
			if ($customer_query->num_rows) {
				$this->customer_id = $customer_query->row['customer_id'];
				$this->customername = $customer_query->row['customername'];
				$this->email = $customer_query->row['email'];
				$this->telephone = $customer_query->row['telephone'];
				$this->newsletter = $customer_query->row['newsletter'];
				$this->customer_group_id = $customer_query->row['customer_group_id'];
				$this->customer_group_status = $customer_query->row['cg_status'];
				$this->address_id = $customer_query->row['address_id'];
							
      			$this->db->query("UPDATE " . DB_PREFIX . "customer SET cart = '" . $this->db->escape(serialize($this->session->data['cart'])) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE customer_id = '" . (int)$this->session->data['customer_id'] . "'");
			} else {
				$this->logout();
			}
  		}
	}
		
  	public function login($email, $password) {
		$customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE email = '" . $this->db->escape($email) . "' AND password = '" . $this->db->escape(md5($password)) . "' AND status = '1'");
		
		if ($customer_query->num_rows) {
			$this->session->data['customer_id'] = $customer_query->row['customer_id'];	
		    
			if (($customer_query->row['cart']) && (is_string($customer_query->row['cart']))) {
				$cart = unserialize($customer_query->row['cart']);
				
				foreach ($cart as $key => $value) {
					if (!array_key_exists($key, $this->session->data['cart'])) {
						$this->session->data['cart'][$key] = $value;
					} else {
						$this->session->data['cart'][$key] += $value;
					}
				}			
			}
			
			$this->customer_id = $customer_query->row['customer_id'];
			$this->customername = $customer_query->row['customername'];
			$this->email = $customer_query->row['email'];
			$this->telephone = $customer_query->row['telephone'];
			$this->newsletter = $customer_query->row['newsletter'];
			$this->customer_group_id = $customer_query->row['customer_group_id'];
			$this->address_id = $customer_query->row['address_id'];
      
	  		return TRUE;
    	} else {
      		return FALSE;
    	}
  	}
  
  	public function logout() {
		unset($this->session->data['customer_id']);

		$this->customer_id = '';
		$this->customername = '';
		$this->email = '';
		$this->telephone = '';
		$this->newsletter = '';
		$this->customer_group_id = '';
		$this->address_id = '';
  	}
  
  	public function isLogged() {
    	return $this->customer_id;
  	}

  	public function getId() {
    	return $this->customer_id;
  	}
      
  	public function getcustomername() {
		return $this->customername;
  	}
  
  	public function getEmail() {
		return $this->email;
  	}
  
  	public function getTelephone() {
		return $this->telephone;
  	}

  	public function getNewsletter() {
		return $this->newsletter;	
  	}

  	public function getCustomerGroupId() {
		return $this->customer_group_id;	
  	}
	
  	public function getCustomerGroupVip() {
		if($this->customer_group_status == "vip") {
			return true;
		} else {
			return false;
		}
  	}
	
  	public function getCustomerGroupAdmin() {
		if($this->customer_group_status == "admin") {
			return true;
		} else {
			return false;
		}
  	}
	
  	public function getAddressId() {
		return $this->address_id;	
  	}
}
?>