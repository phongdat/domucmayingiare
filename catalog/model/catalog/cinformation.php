<?php
class ModelCatalogcinformation extends Model {
	public function getcinformation($cinformation_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "cinformation c LEFT JOIN " . DB_PREFIX . "cinformation_description cd ON (c.cinformation_id = cd.cinformation_id) WHERE c.cinformation_id = '" . (int)$cinformation_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		return $query->row;
	}
	
	public function getcinformations($cshow, $limit) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cinformation c LEFT JOIN " . DB_PREFIX . "cinformation_description cd ON (c.cinformation_id = cd.cinformation_id) WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c.cshow = '" . $cshow . "' ORDER BY c.sort_order LIMIT " . (int)$limit);

		return $query->rows;
	}
}
?>