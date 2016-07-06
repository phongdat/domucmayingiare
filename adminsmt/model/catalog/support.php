<?php
class ModelCatalogsupport extends Model {
	public function addsupport($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "support SET sort_order = '" . (int)$this->request->post['sort_order'] . "', csupport_id = '" . (int)$data['csupport_id'] . "'");

		$support_id = $this->db->getLastId(); 
			
		foreach ($data['support_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "support_description SET support_id = '" . (int)$support_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', yahoo_id = '" . $this->db->escape($value['yahoo_id']) . "', skype_id = '" . $this->db->escape($value['skype_id']) . "',telephone= '" . $this->db->escape($value['telephone']) . "'");
		}
		
		$this->cache->delete('support');
	}
	
	public function editsupport($support_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "support SET sort_order = '" . (int)$data['sort_order'] . "', csupport_id = '" . (int)$data['csupport_id'] . "' WHERE support_id = '" . (int)$support_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "support_description WHERE support_id = '" . (int)$support_id . "'");
					
		foreach ($data['support_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "support_description SET support_id = '" . (int)$support_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', yahoo_id = '" . $this->db->escape($value['yahoo_id']) . "', skype_id = '" . $this->db->escape($value['skype_id']) . "',telephone= '" . $this->db->escape($value['telephone']) . "'");
		}
		
		$this->cache->delete('support');
	}
	
	public function deletesupport($support_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "support WHERE support_id = '" . (int)$support_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "support_description WHERE support_id = '" . (int)$support_id . "'");

		$this->cache->delete('support');
	}	

	public function getsupport($support_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "support WHERE support_id = '" . (int)$support_id . "'");
		
		return $query->row;
	}
		
	public function getsupports($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "support s LEFT JOIN " . DB_PREFIX . "support_description sd ON (s.support_id = sd.support_id) WHERE sd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
			$sort_data = array(
				'sd.name',
				's.sort_order'
			);		
		
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY sd.name";	
			}
			
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}
		
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}		

				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}	
			
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}	
			
			$query = $this->db->query($sql);
			
			return $query->rows;
		} else {
			$support_data = $this->cache->get('support.' . $this->config->get('config_language_id'));
		
			if (!$support_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "support s LEFT JOIN " . DB_PREFIX . "support_description sd ON (s.support_id = sd.support_id) WHERE sd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY sd.name");
	
				$support_data = $query->rows;
			
				$this->cache->set('support.' . $this->config->get('config_language_id'), $support_data);
			}	
	
			return $support_data;			
		}
	}
	
	public function getsupportDescriptions($support_id) {
		$support_description_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "support_description WHERE support_id = '" . (int)$support_id . "'");

		foreach ($query->rows as $result) {
			$support_description_data[$result['language_id']] = array(
				'name'       	 => $result['name'],
				'yahoo_id'       => $result['yahoo_id'],
				'skype_id'       => $result['skype_id'],
				'telephone'		 => $result['telephone']
			);
		}
		
		return $support_description_data;
	}
	
	public function getTotalProductsBycsupportId($csupport_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "support WHERE csupport_id = '" . (int)$csupport_id . "'");

		return $query->row['total'];
	}
	
	public function getTotalsupports() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "support");
		
		return $query->row['total'];
	}	
}
?>