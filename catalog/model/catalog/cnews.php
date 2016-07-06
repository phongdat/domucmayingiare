<?php
class ModelCatalogCnews extends Model {
	public function getCnews($cnews_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "cnews c LEFT JOIN " . DB_PREFIX . "cnews_description cd ON (c.cnews_id = cd.cnews_id) WHERE c.cnews_id = '" . (int)$cnews_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		return $query->row;
	}
	
	public function getCnewss($parent_id = 0) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cnews c LEFT JOIN " . DB_PREFIX . "cnews_description cd ON (c.cnews_id = cd.cnews_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY c.sort_order");

		return $query->rows;
	}
				
	public function getTotalCnewssByCnewsId($parent_id = 0) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "cnews WHERE parent_id = '" . (int)$parent_id . "'");

		return $query->row['total'];
	}
	
}
?>