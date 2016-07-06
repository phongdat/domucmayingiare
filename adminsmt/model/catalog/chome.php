<?php
class ModelCatalogchome extends Model {
	public function addchome($data) {
      	$this->db->query("INSERT INTO " . DB_PREFIX . "chome SET sort_order = '" . (int)$data['sort_order'] . "', link = '" . $this->db->escape($data['link']) . "'");
		
		$chome_id = $this->db->getLastId();
		
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "chome SET image = '" . $this->db->escape($data['image']) . "' WHERE chome_id = '" . (int)$chome_id . "'");
		}
		
		foreach ($data['chome_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "chome_description SET chome_id = '" . (int)$chome_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}
		
		$this->cache->delete('chome');
	}
	
	public function editchome($chome_id, $data) {
      	$this->db->query("UPDATE " . DB_PREFIX . "chome SET sort_order = '" . (int)$data['sort_order'] . "', link = '" . $this->db->escape($data['link']) . "' WHERE chome_id = '" . (int)$chome_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "chome SET image = '" . $this->db->escape($data['image']) . "' WHERE chome_id = '" . (int)$chome_id . "'");
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "chome_description WHERE chome_id = '" . (int)$chome_id . "'");
		
		foreach ($data['chome_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "chome_description SET chome_id = '" . (int)$chome_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}
		
		$this->cache->delete('chome');
	}
	
	public function deletechome($chome_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "chome WHERE chome_id = '" . (int)$chome_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "chome_description WHERE chome_id = '" . (int)$chome_id . "'");
			
		$this->cache->delete('chome');
	}	
	
	public function getchome($chome_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "chome WHERE chome_id = '" . (int)$chome_id . "'");
		
		return $query->row;
	}
	
	public function getchomes($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "chome c LEFT JOIN " . DB_PREFIX . "chome_description cd ON (c.chome_id = cd.chome_id) WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
			
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
			$chome_data = $this->cache->get('chome');
		
			if (!$chome_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "chome c LEFT JOIN " . DB_PREFIX . "chome_description cd ON (c.chome_id = cd.chome_id) WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY cd.name");
	
				$chome_data = $query->rows;
			
				$this->cache->set('chome', $chome_data);
			}
		 
			return $chome_data;
		}
	}
	
	public function getchomeDescriptions($chome_id) {
		$chome_description_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "chome_description WHERE chome_id = '" . (int)$chome_id . "'");

		foreach ($query->rows as $result) {
			$chome_description_data[$result['language_id']] = array(
				'name'            => $result['name']
			);
		}
		
		return $chome_description_data;
	}

	public function getTotalchomes() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "chome");
		
		return $query->row['total'];
	}	
}
?>