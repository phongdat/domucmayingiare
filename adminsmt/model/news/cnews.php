<?php
class ModelNewscnews extends Model {
	public function addcnews($data) {

		$this->db->query("INSERT INTO " . DB_PREFIX . "cnews SET parent_id = '" . (int)$data['parent_id'] . "', sort_order = '" . (int)$data['sort_order'] . "', date_modified = NOW(), date_added = NOW()");
	
		$cnews_id = $this->db->getLastId();
		
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "cnews SET image = '" . $this->db->escape($data['image']) . "' WHERE cnews_id = '" . (int)$cnews_id . "'");
		}

		foreach ($data['cnews_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "cnews_description SET cnews_id = '" . (int)$cnews_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}

		$this->cache->delete('cnews');
	}
	
	public function editcnews($cnews_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "cnews SET parent_id = '" . (int)$data['parent_id'] . "', sort_order = '" . (int)$data['sort_order'] . "', date_modified = NOW() WHERE cnews_id = '" . (int)$cnews_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "cnews SET image = '" . $this->db->escape($data['image']) . "' WHERE cnews_id = '" . (int)$cnews_id . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "cnews_description WHERE cnews_id = '" . (int)$cnews_id . "'");

		foreach ($data['cnews_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "cnews_description SET cnews_id = '" . (int)$cnews_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}
			
		$this->cache->delete('cnews');
	}
	
	public function deletecnews($cnews_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "cnews WHERE cnews_id = '" . (int)$cnews_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "cnews_description WHERE cnews_id = '" . (int)$cnews_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "news_to_cnews WHERE cnews_id = '" . (int)$cnews_id . "'");
		$query = $this->db->query("SELECT cnews_id FROM " . DB_PREFIX . "cnews WHERE parent_id = '" . (int)$cnews_id . "'");

		foreach ($query->rows as $result) {
			$this->deletecnews($result['cnews_id']);
		}
		
		$this->cache->delete('cnews');
	} 

	public function getcnews($cnews_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "cnews WHERE cnews_id = '" . (int)$cnews_id . "'");
		
		return $query->row;
	} 
	
	public function getcnewss($parent_id) {
		$cnews_data = $this->cache->get('cnews.' . $this->config->get('config_language_id') . '.' . $parent_id);
	
		if (!$cnews_data) {
			$cnews_data = array();
		
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cnews c LEFT JOIN " . DB_PREFIX . "cnews_description cd ON (c.cnews_id = cd.cnews_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY c.sort_order, cd.name ASC");
		
			foreach ($query->rows as $result) {
				$cnews_data[] = array(
					'cnews_id' => $result['cnews_id'],
					'name'        => $this->getpath($result['cnews_id'], $this->config->get('config_language_id')),
					'sort_order'  => $result['sort_order']
				);
			
				$cnews_data = array_merge($cnews_data, $this->getcnewss($result['cnews_id']));
			}	
	
			$this->cache->set('cnews.' . $this->config->get('config_language_id') . '.' . $parent_id, $cnews_data);
		}
		
		return $cnews_data;
	}
	
	public function getpath($cnews_id) {
		$query = $this->db->query("SELECT name, parent_id FROM " . DB_PREFIX . "cnews c LEFT JOIN " . DB_PREFIX . "cnews_description cd ON (c.cnews_id = cd.cnews_id) WHERE c.cnews_id = '" . (int)$cnews_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY c.sort_order, cd.name ASC");
		
		$cnews_info = $query->row;
		
		if ($cnews_info['parent_id']) {
			return $this->getpath($cnews_info['parent_id'], $this->config->get('config_language_id')) . $this->language->get('text_separator') . $cnews_info['name'];
		} else {
			return $cnews_info['name'];
		}
	}
	
	public function getcnewsDescriptions($cnews_id) {
		$cnews_description_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cnews_description WHERE cnews_id = '" . (int)$cnews_id . "'");
		
		foreach ($query->rows as $result) {
			$cnews_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'meta_description' => $result['meta_description'],
				'description'      => $result['description']
			);
		}
		
		return $cnews_description_data;
	}	
		
	public function getTotalcnewss() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "cnews");
		
		return $query->row['total'];
	}	
		
	public function getTotalcnewssByImageId($image_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "cnews WHERE image_id = '" . (int)$image_id . "'");
		
		return $query->row['total'];
	}
}
?>