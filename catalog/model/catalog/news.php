<?php
class ModelCatalogNews extends Model {
	public function getnews($news_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "news n LEFT JOIN " . DB_PREFIX . "news_description nd ON (n.news_id = nd.news_id) WHERE n.news_id = '" . (int)$news_id . "' AND nd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
	
		return $query->row;
	}

	public function getnewss($start = 0, $limit = 20) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news n LEFT JOIN " . DB_PREFIX . "news_description nd ON (n.news_id = nd.news_id) WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY n.news_id DESC " . " LIMIT " . (int)$start . "," . (int)$limit);
	
		return $query->rows;
	}
	public function gettotalnewss() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "news");
		
		return $query->row['total'];
	}
	public function tinmoinhat($limit) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news n LEFT JOIN " . DB_PREFIX . "news_description nd ON (n.news_id = nd.news_id) WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY n.news_id DESC LIMIT " . (int)$limit);
	
		return $query->rows;
	}
	public function getnewssbyCnewss($cnews_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news n LEFT JOIN " . DB_PREFIX . "news_description nd ON (n.news_id = nd.news_id) LEFT JOIN " . DB_PREFIX . "news_to_cnews n2c ON (n.news_id = n2c.news_id) WHERE n.cnews_id =".(int)$cnews_id." ORDER BY n.news_id DESC");
		
		return $query->rows;
	}
	public function getnewssbycnews($cnews_id, $start = 0, $limit = 20) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news n LEFT JOIN " . DB_PREFIX . "news_description nd ON (n.news_id = nd.news_id) LEFT JOIN " . DB_PREFIX . "news_to_cnews n2c ON (n.news_id = n2c.news_id) WHERE n2c.cnews_id =" . (int)$cnews_id . " ORDER BY n.news_id DESC" . " LIMIT " . (int)$start . "," . (int)$limit);
		
		return $query->rows;
	}
	public function getNewssLienQuanByCnews($cnews_id, $start, $limit) {
		if (isset($this->request->get['news_id'])) {
			$news_id = $this->request->get['news_id'];
		} else {
			$news_id = 0;
		}
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news n LEFT JOIN " . DB_PREFIX . "news_description nd ON (n.news_id = nd.news_id) LEFT JOIN " . DB_PREFIX . "news_to_cnews n2c ON (n.news_id = n2c.news_id) WHERE n2c.cnews_id =" . (int)$cnews_id . " AND n.news_id != " .(int)$news_id. " ORDER BY n.news_id DESC" . " LIMIT " . (int)$start . "," . (int)$limit);
		
		return $query->rows;
	}
	public function getTotalnewsByCnewsId($cnews_id = 0) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "news n LEFT JOIN " . DB_PREFIX . "news_description nd ON (n.news_id = nd.news_id) LEFT JOIN " . DB_PREFIX . "news_to_cnews m ON (n.news_id = m.news_id) WHERE m.cnews_id =" . (int)$cnews_id . " ORDER BY n.news_id ASC");

		return $query->row['total'];
	}
	public function updateViewed($news_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "news SET viewed = viewed + 1 WHERE news_id = '" . (int)$news_id . "'");
	}
	public function getCnewsIdByNewsId($news_id = 0) {
		$result = $this->db->query("SELECT * FROM " . DB_PREFIX . "news_to_cnews WHERE news_id = '" . (int)$news_id . "'");
		$cnews_id = array();
		if (isset($result->rows)) {
			foreach($result->rows  as $row ) {
				$cnews = $this->model_catalog_cnews->getcnews($row['cnews_id']);
				if($cnews) {
					$path = $row['cnews_id'];
					while ($cnews['parent_id'] != 0) {
						$path = $cnews['parent_id'] . '_' . $path;
						$cnews = $this->model_catalog_cnews->getcnews($cnews['parent_id']);
					}
				}
				$cnews_id[] = array(
					'strlen_path'      => strlen($path),
					'cnews_id'         => $row['cnews_id']
				);
			}
			if ($cnews_id) {
				$cnewsid =  max($cnews_id);
				return $cnewsid['cnews_id'];
			} else {
				return 0;
			}
		} else {
			return 0;
		}
	}
}
?>