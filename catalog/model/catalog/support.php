<?php
class ModelCatalogsupport extends Model {
	public function getsupport($support_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "support s LEFT JOIN " . DB_PREFIX . "support_description sd ON (s.support_id = sd.support_id) WHERE s.support_id = '" . (int)$support_id . "' AND sd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
	
		return $query->row;
	}
	
	public function getsupports() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "support s LEFT JOIN " . DB_PREFIX . "support_description sd ON (s.support_id = sd.support_id) WHERE sd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY s.sort_order ASC");
	
		return $query->rows;
	}
	public function getsupportbycsupport($csupport_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "support s LEFT JOIN " . DB_PREFIX . "support_description sd ON (s.support_id = sd.support_id) WHERE sd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND s.csupport_id ='" . (int)$csupport_id . "' ORDER BY s.sort_order ASC");
	
		return $query->rows;
	}
}
?>