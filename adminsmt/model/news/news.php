<?php
class ModelNewsNews extends Model {
	public function addnews($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "news SET sort_order = '" . (int)$this->request->post['sort_order'] . "', date_added = NOW()");

		$news_id = $this->db->getLastId(); 

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "news SET image = '" . $this->db->escape($data['image']) . "' WHERE news_id = '" . (int)$news_id . "'");
		}
		
		foreach ($data['news_description'] as $language_id => $value) {

			$this->db->query("INSERT INTO " . DB_PREFIX . "news_description SET news_id = '" . (int)$news_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', tags = '" . $this->db->escape($value['tags']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}

		if (isset($data['news_cnews'])) {
			$i = 0;
			foreach ($data['news_cnews'] as $cnews_id) {
				$i++;
				$this->db->query("INSERT INTO " . DB_PREFIX . "news_to_cnews SET news_id = '" . (int)$news_id . "', cnews_id = '" . (int)$cnews_id . "'");
				
				if ($i == sizeof($data['news_cnews'])) {
					$this->db->query("UPDATE " . DB_PREFIX . "news SET cnews_id = '" . (int)$cnews_id . "' WHERE news_id = '" . (int)$news_id . "'");
				}
			}
		}
		
		$this->cache->delete('news');
	}
	
	public function editnews($news_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "news SET sort_order = '" . (int)$data['sort_order'] . "' WHERE news_id = '" . (int)$news_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "news SET image = '" . $this->db->escape($data['image']) . "' WHERE news_id = '" . (int)$news_id . "'");
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "news_description WHERE news_id = '" . (int)$news_id . "'");
					
		foreach ($data['news_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "news_description SET news_id = '" . (int)$news_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', tags = '" . $this->db->escape($value['tags']) . "', description = '" . $this->db->escape($value['description']) . "'");	
		}

		
		$this->db->query("DELETE FROM " . DB_PREFIX . "news_to_cnews WHERE news_id = '" . (int)$news_id . "'");
		
		if (isset($data['news_cnews'])) {
			$i = 0;
			foreach ($data['news_cnews'] as $cnews_id) {
				$i++;
				$this->db->query("INSERT INTO " . DB_PREFIX . "news_to_cnews SET news_id = '" . (int)$news_id . "', cnews_id = '" . (int)$cnews_id . "'");
				
				if ($i == sizeof($data['news_cnews'])) {
					$this->db->query("UPDATE " . DB_PREFIX . "news SET cnews_id = '" . (int)$cnews_id . "' WHERE news_id = '" . (int)$news_id . "'");
				}
			}
		}
		
		
		$this->cache->delete('news');
	}
	public function getnewscnewss($news_id) {
		$news_cnews_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news_to_cnews WHERE news_id = '" . (int)$news_id . "'");
		
		foreach ($query->rows as $result) {
			$news_cnews_data[] = $result['cnews_id'];
		}

		return $news_cnews_data;
	}
	public function getnewssBycnewsId($cnews_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news n LEFT JOIN " . DB_PREFIX . "news_description nd ON (n.news_id = nd.news_id) LEFT JOIN " . DB_PREFIX . "news_to_cnews n2c ON (n.news_id = n2c.news_id) WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND n2c.cnews_id = '" . (int)$cnews_id . "' ORDER BY nd.name ASC");
								  
		return $query->rows;
	} 	
	public function deletenews($news_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "news WHERE news_id = '" . (int)$news_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "news_description WHERE news_id = '" . (int)$news_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "news_to_cnews WHERE news_id = '" . (int)$news_id . "'");

		$this->cache->delete('news');
	}	

	public function getnews($news_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "news WHERE news_id = '" . (int)$news_id . "'");
		
		return $query->row;
	}
		
	public function getnewss($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "news n LEFT JOIN " . DB_PREFIX . "news_description nd ON (n.news_id = nd.news_id) WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
			$sort_data = array(
				'n.sort_order',
				'nd.name'				
			);		
		
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY n.sort_order";	
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
			$news_data = $this->cache->get('news.' . $this->config->get('config_language_id'));
		
			if (!$news_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news n LEFT JOIN " . DB_PREFIX . "news_description nd ON (n.news_id = nd.news_id) WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY n.sort_order");
	
				$news_data = $query->rows;
			
				$this->cache->set('news.' . $this->config->get('config_language_id'), $news_data);
			}	
	
			return $news_data;			
		}
	}
	
	public function getnewsDescriptions($news_id) {
		$news_description_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news_description WHERE news_id = '" . (int)$news_id . "'");

		foreach ($query->rows as $result) {
			$news_description_data[$result['language_id']] = array(
				'name'        => $result['name'],
				'tags'        => $result['tags'],
				'description' => $result['description']
			);
		}
		
		return $news_description_data;
	}
	
	public function getTotalnewss() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "news");
		
		return $query->row['total'];
	}	
}
?>