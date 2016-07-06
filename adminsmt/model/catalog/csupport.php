<?php
class ModelCatalogcsupport extends Model {
	public function addcsupport($data) {
      	$this->db->query("INSERT INTO " . DB_PREFIX . "csupport SET sort_order = '" . (int)$data['sort_order'] . "'");
		
		$csupport_id = $this->db->getLastId();
		
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "csupport SET image = '" . $this->db->escape($data['image']) . "' WHERE csupport_id = '" . (int)$csupport_id . "'");
		}
		
		foreach ($data['csupport_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "csupport_description SET csupport_id = '" . (int)$csupport_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}
		
		$this->cache->delete('csupport');
	}
	
	public function editcsupport($csupport_id, $data) {
      	$this->db->query("UPDATE " . DB_PREFIX . "csupport SET sort_order = '" . (int)$data['sort_order'] . "' WHERE csupport_id = '" . (int)$csupport_id . "'");
		
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "csupport SET image = '" . $this->db->escape($data['image']) . "' WHERE csupport_id = '" . (int)$csupport_id . "'");
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "csupport_description WHERE csupport_id = '" . (int)$csupport_id . "'");
		
		foreach ($data['csupport_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "csupport_description SET csupport_id = '" . (int)$csupport_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}
		
		$this->cache->delete('csupport');
	}
	
	public function deletecsupport($csupport_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "csupport WHERE csupport_id = '" . (int)$csupport_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "csupport_description WHERE csupport_id = '" . (int)$csupport_id . "'");
			
		$this->cache->delete('csupport');
	}	
	
	public function getcsupport($csupport_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "csupport WHERE csupport_id = '" . (int)$csupport_id . "'");
		
		return $query->row;
	}
	
	public function getcsupports($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "csupport c LEFT JOIN " . DB_PREFIX . "csupport_description cd ON (c.csupport_id = cd.csupport_id) WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
			
			$sort_data = array(
				'cd.name',
				'c.sort_order'
			);	
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY cd.name";	
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
			$csupport_data = $this->cache->get('csupport');
		
			if (!$csupport_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "csupport c LEFT JOIN " . DB_PREFIX . "csupport_description cd ON (c.csupport_id = cd.csupport_id) WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY cd.name");
	
				$csupport_data = $query->rows;
			
				$this->cache->set('csupport', $csupport_data);
			}
		 
			return $csupport_data;
		}
	}
	
	public function getcsupportDescriptions($csupport_id) {
		$csupport_description_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "csupport_description WHERE csupport_id = '" . (int)$csupport_id . "'");

		foreach ($query->rows as $result) {
			$csupport_description_data[$result['language_id']] = array(
				'name'            => $result['name']
			);
		}
		
		return $csupport_description_data;
	}

	public function getTotalcsupports() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "csupport");
		
		return $query->row['total'];
	}	
}
?>