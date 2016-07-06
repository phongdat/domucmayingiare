<?php
class ModelCatalogcinformation extends Model {
	public function addcinformation($data) {
      	$this->db->query("INSERT INTO " . DB_PREFIX . "cinformation SET sort_order = '" . (int)$data['sort_order'] . "', cshow = '" . $this->db->escape($data['cshow']) . "'");
		
		$cinformation_id = $this->db->getLastId();
		
		foreach ($data['cinformation_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "cinformation_description SET cinformation_id = '" . (int)$cinformation_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}
		
		$this->cache->delete('cinformation');
	}
	
	public function editcinformation($cinformation_id, $data) {
      	$this->db->query("UPDATE " . DB_PREFIX . "cinformation SET sort_order = '" . (int)$data['sort_order'] . "', cshow = '" . $this->db->escape($data['cshow']) . "' WHERE cinformation_id = '" . (int)$cinformation_id . "'");
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "cinformation_description WHERE cinformation_id = '" . (int)$cinformation_id . "'");
		
		foreach ($data['cinformation_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "cinformation_description SET cinformation_id = '" . (int)$cinformation_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}
		
		$this->cache->delete('cinformation');
	}
	
	public function deletecinformation($cinformation_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "cinformation WHERE cinformation_id = '" . (int)$cinformation_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "cinformation_description WHERE cinformation_id = '" . (int)$cinformation_id . "'");
			
		$this->cache->delete('cinformation');
	}	
	
	public function getcinformation($cinformation_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "cinformation WHERE cinformation_id = '" . (int)$cinformation_id . "'");
		
		return $query->row;
	}
	
	public function getcinformations($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "cinformation c LEFT JOIN " . DB_PREFIX . "cinformation_description cd ON (c.cinformation_id = cd.cinformation_id) WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
			
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
			$cinformation_data = $this->cache->get('cinformation');
		
			if (!$cinformation_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cinformation c LEFT JOIN " . DB_PREFIX . "cinformation_description cd ON (c.cinformation_id = cd.cinformation_id) WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY cd.name");
	
				$cinformation_data = $query->rows;
			
				$this->cache->set('cinformation', $cinformation_data);
			}
		 
			return $cinformation_data;
		}
	}
	
	public function getcinformationDescriptions($cinformation_id) {
		$cinformation_description_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cinformation_description WHERE cinformation_id = '" . (int)$cinformation_id . "'");

		foreach ($query->rows as $result) {
			$cinformation_description_data[$result['language_id']] = array(
				'name'            => $result['name']
			);
		}
		
		return $cinformation_description_data;
	}

	public function getTotalcinformations() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "cinformation");
		
		return $query->row['total'];
	}	
}
?>