<?php
class ModelCatalogslideshow extends Model {
	public function getslideshow($slideshow_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "slideshow s LEFT JOIN " . DB_PREFIX . "slideshow_description sd ON (s.slideshow_id = sd.slideshow_id) WHERE s.slideshow_id = '" . (int)$slideshow_id . "' AND sd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
	
		return $query->row;
	}
	
	public function getslideshows() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "slideshow s LEFT JOIN " . DB_PREFIX . "slideshow_description sd ON (s.slideshow_id = sd.slideshow_id) WHERE sd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY s.sort_order ASC");
	
		return $query->rows;
	}
}
?>