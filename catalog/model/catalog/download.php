<?php
class ModelCatalogdownload extends Model {
	public function getdownload($download_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "download i LEFT JOIN " . DB_PREFIX . "download_description id ON (i.download_id = id.download_id) WHERE i.download_id = '" . (int)$download_id . "' AND id.language_id = '" . (int)$this->config->get('config_language_id') . "'");
	
		return $query->row;
	}
	
	public function getdownloads() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "download i LEFT JOIN " . DB_PREFIX . "download_description id ON (i.download_id = id.download_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY i.date_added DESC");
	
		return $query->rows;
	}
	public function gettotaldownloads() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "download");

		return $query->row['total'];
	}
}
?>