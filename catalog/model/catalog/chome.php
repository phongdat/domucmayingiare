<?php
class ModelCatalogchome extends Model {
	public function getchome($chome_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "chome c LEFT JOIN " . DB_PREFIX . "chome_description cd ON (c.chome_id = cd.chome_id) WHERE c.chome_id = '" . (int)$chome_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		return $query->row;
	}
	
	public function getchomes() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "chome c LEFT JOIN " . DB_PREFIX . "chome_description cd ON (c.chome_id = cd.chome_id) WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY c.sort_order");

		return $query->rows;
	} 
}
?>