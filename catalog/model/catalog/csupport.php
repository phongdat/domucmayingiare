<?php
class ModelCatalogcsupport extends Model {
	public function getcsupport($csupport_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "csupport c LEFT JOIN " . DB_PREFIX . "csupport_description cd ON (c.csupport_id = cd.csupport_id) WHERE c.csupport_id = '" . (int)$csupport_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		return $query->row;
	}
	
	public function getcsupports() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "csupport c LEFT JOIN " . DB_PREFIX . "csupport_description cd ON (c.csupport_id = cd.csupport_id) WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY c.sort_order");

		return $query->rows;
	} 
}
?>