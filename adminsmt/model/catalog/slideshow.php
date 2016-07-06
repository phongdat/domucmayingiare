<?php
class ModelCatalogslideshow extends Model {
	public function addslideshow($data) {
      	$this->db->query("INSERT INTO " . DB_PREFIX . "slideshow SET sort_order = '" . (int)$data['sort_order'] . "', link = '" . $this->db->escape($data['link']) . "'");
		
		$slideshow_id = $this->db->getLastId();
		
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "slideshow SET image = '" . $this->db->escape($data['image']) . "' WHERE slideshow_id = '" . (int)$slideshow_id . "'");
		}
		
		foreach ($data['slideshow_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "slideshow_description SET slideshow_id = '" . (int)$slideshow_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}
		
		$this->cache->delete('slideshow');
	}
	
	public function editslideshow($slideshow_id, $data) {
      	$this->db->query("UPDATE " . DB_PREFIX . "slideshow SET sort_order = '" . (int)$data['sort_order'] . "', link = '" . $this->db->escape($data['link']) . "' WHERE slideshow_id = '" . (int)$slideshow_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "slideshow SET image = '" . $this->db->escape($data['image']) . "' WHERE slideshow_id = '" . (int)$slideshow_id . "'");
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "slideshow_description WHERE slideshow_id = '" . (int)$slideshow_id . "'");
		
		foreach ($data['slideshow_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "slideshow_description SET slideshow_id = '" . (int)$slideshow_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}
		
		$this->cache->delete('slideshow');
	}
	
	public function deleteslideshow($slideshow_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "slideshow WHERE slideshow_id = '" . (int)$slideshow_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "slideshow_description WHERE slideshow_id = '" . (int)$slideshow_id . "'");
			
		$this->cache->delete('slideshow');
	}	
	
	public function getslideshow($slideshow_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "slideshow WHERE slideshow_id = '" . (int)$slideshow_id . "'");
		
		return $query->row;
	}
	
	public function getslideshows($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "slideshow c LEFT JOIN " . DB_PREFIX . "slideshow_description cd ON (c.slideshow_id = cd.slideshow_id) WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
			
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
			$slideshow_data = $this->cache->get('slideshow');
		
			if (!$slideshow_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "slideshow c LEFT JOIN " . DB_PREFIX . "slideshow_description cd ON (c.slideshow_id = cd.slideshow_id) WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY cd.name");
	
				$slideshow_data = $query->rows;
			
				$this->cache->set('slideshow', $slideshow_data);
			}
		 
			return $slideshow_data;
		}
	}
	
	public function getslideshowDescriptions($slideshow_id) {
		$slideshow_description_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "slideshow_description WHERE slideshow_id = '" . (int)$slideshow_id . "'");

		foreach ($query->rows as $result) {
			$slideshow_description_data[$result['language_id']] = array(
				'name'            => $result['name']
			);
		}
		
		return $slideshow_description_data;
	}

	public function getTotalslideshows() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "slideshow");
		
		return $query->row['total'];
	}	
}
?>