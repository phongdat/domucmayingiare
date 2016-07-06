<?php
class ModelToolSeoUrl extends Model {
	public function rewrite($link) {
		if ($this->config->get('config_seo_url')) {
			$url_data = parse_url(str_replace('&amp;', '&', $link));

			$url = '/'; 
			
			$data = array();
		
			parse_str($url_data['query'], $data);
			$url_rewrite = explode('=', str_replace('&amp;', '&', $link));
			
			foreach ($data as $key => $value) {
				if (($key == 'product_id')) {
										
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $this->db->escape($key . '=' . (int)$value) . "'");
					
					if ($query->num_rows) {
						$url .= $query->row['keyword'];
						
						unset($data[$key]);
					} else {
						$query = $this->db->query("SELECT * FROM product_description WHERE `".$key."` = '" . (int)$value . "'");
						if ($query->num_rows) {
							$url .= (int)$value .'/'. $this->model_tool_seo_url->khongdau($query->row['name']) .'.html';
							
							unset($data[$key]);
						}
					}
				} elseif (($key == 'information_id')) {
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $this->db->escape($key . '=' . (int)$value) . "'");
					
					if ($query->num_rows) {
						$url .= $query->row['keyword'];
						
						unset($data[$key]);
					} else {
						$query = $this->db->query("SELECT * FROM information_description WHERE `".$key."` = '" . (int)$value . "'");
					
						if ($query->num_rows) {
							$url .='info/' . (int)$value .'/'. $this->model_tool_seo_url->khongdau($query->row['name']) .'.html';
							
							unset($data[$key]);
						}
					}
				} elseif (($key == 'manufacturer_id')) {
					$query = $this->db->query("SELECT * FROM manufacturer_description WHERE `".$key."` = '" . (int)$value . "'");
				
					if ($query->num_rows) {
						$url .='manu/' . (int)$value .'/'. $this->model_tool_seo_url->khongdau($query->row['name']) .'.html';
						
						unset($data[$key]);
					}
				} elseif (($key == 'news_id')) {
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $this->db->escape($key . '=' . (int)$value) . "'");
					
					if ($query->num_rows) {
						$url .= $query->row['keyword'];
						
						unset($data[$key]);
					} else {
						$query = $this->db->query("SELECT * FROM news_description WHERE `".$key."` = '" . (int)$value . "'");
					
						if ($query->num_rows) {
							$url .= 'news/' . (int)$value .'/'. $this->model_tool_seo_url->khongdau($query->row['name']) .'.html';
							
							unset($data[$key]);
						}
					}
				} elseif ($key == 'category_id') {
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $this->db->escape($key . '=' . (int)$value) . "'");
					
					if ($query->num_rows) {
						$url .= $query->row['keyword'];
						
						unset($data[$key]);
					} else {
						$query = $this->db->query("SELECT * FROM category_description WHERE `".$key."` = '" . (int)$value . "'");
						
						if ($query->num_rows) {
							$url .= (int)$value .'/'. $this->model_tool_seo_url->khongdau($query->row['name']) .'/';
							
							unset($data[$key]);
						}
					}
				} elseif ($key == 'cnews_id') {
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $this->db->escape($key . '=' . (int)$value) . "'");
					
					if ($query->num_rows) {
						$url .= $query->row['keyword'];
						
						unset($data[$key]);
					} else {
						$query = $this->db->query("SELECT * FROM cnews_description WHERE `".$key."` = '" . (int)$value . "'");
						
						if ($query->num_rows) {
							$url .= 'news/' . (int)$value .'/'. $this->model_tool_seo_url->khongdau($query->row['name']) .'/';
							
							unset($data[$key]);
						}
					}
				} elseif ($key == 'hoidap_id') {
					$query = $this->db->query("SELECT * FROM hoidap WHERE `".$key."` = '" . (int)$value . "'");
					
					if ($query->num_rows) {
						$url .= 'hoidap/' . (int)$value .'/';
						
						unset($data[$key]);
					}
				} elseif ($url_rewrite[1] == 'product/search') {
					$url .= 'search/';
					unset($data[$key]);
				} elseif ($key == 'keyword' && $url_rewrite[2]) {
					$url .= 'search/keyword/' . mb_strtolower(str_replace(" ","-",trim($url_rewrite[2])),'UTF-8') . "/";
					unset($data[$key]);
				} elseif ($url_rewrite[1] == 'news/cnews') {
					$url .= 'news/';
					unset($data[$key]);
				} elseif ($url_rewrite[1] == 'product/product') {
					$url .= 'product/';
					unset($data[$key]);
				} elseif ($url_rewrite[1] == 'hoidap/hoidap') {
					$url .= 'hoidap/';
					unset($data[$key]);
				} elseif (($url_seo = explode('/', $url_rewrite[1])) && $url_seo[0] =="account"  && sizeof($url_rewrite) == 2) {
					$url .= $url_rewrite[1] .'/';
					unset($data[$key]);
				} elseif (($url_seo = explode('/', $url_rewrite[1])) && $url_seo[0] =="information" && sizeof($url_rewrite) == 2) {
					$url .= str_replace('information/','info/',$url_rewrite[1]) .'/';
					unset($data[$key]);
				} elseif (($url_seo = explode('/', $url_rewrite[1])) && $url_seo[0] =="product"  && sizeof($url_rewrite) == 2) {
					$url .= $url_rewrite[1] .'/';
					unset($data[$key]);
				} elseif (($url_seo = explode('/', $url_rewrite[1])) && $url_seo[0] =="checkout"  && sizeof($url_rewrite) == 2) {
					$url .= $url_rewrite[1] .'/';
					unset($data[$key]);
				}
			}

			if ($url) {
				unset($data['route']);
			
				$query = '';
			
				if ($data) {
					foreach ($data as $key => $value) {
						$query .= '&' . $key . '=' . $value;
					}
					
					if ($query) {
						$query = '&' . str_replace('&amp;', '&', trim($query, '&'));
					}
				}

				return $url_data['scheme'] . '://' . $url_data['host'] . (isset($url_data['port']) ? ':' . $url_data['port'] : '') . str_replace('/index.php', '', $url_data['path']) . $url . $query;
			} else {
				return $link;
			}
		} else {
			return $link;
		}		
	}
	public function khongdau($text){
	//global $ibforums;
	//Charachters must be in ASCII and certain ones aint allowed
	$text = html_entity_decode ($text, ENT_QUOTES, 'UTF-8');
	$text = preg_replace("/(ä|à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $text);
	$text = str_replace("ç","c",$text);
	$text = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $text);
	$text = preg_replace("/(ì|í|î|ị|ỉ|ĩ)/", 'i', $text);
	$text = preg_replace("/(ö|ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $text);
	$text = preg_replace("/(ü|ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $text);
	$text = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $text);
	$text = preg_replace("/(đ)/", 'd', $text);
	//CHU HOA
	$text = preg_replace("/(Ä|À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $text);
	$text = str_replace("Ç","C",$text);
	$text = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $text);
	$text = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $text);
	$text = preg_replace("/(Ö|Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $text);
	$text = preg_replace("/(Ü|Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $text);
	$text = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $text);
	$text = preg_replace("/(Đ)/", 'D', $text);
	$text = preg_replace("/[^a-zA-Z0-9\-\_]/", ' ', $text);
	$text = str_replace("     ", ' ', $text);
	$text = str_replace("    ", ' ', $text);
	$text = str_replace("   ", ' ', $text);
	$text = str_replace("  ", ' ', $text);
	$text = str_replace(" - ", ' ', trim($text));
	$text = str_replace(" -", ' ', trim($text));
	$text = str_replace("- ", ' ', trim($text));
	$text = str_replace(" ", '-', trim($text));
	return strtolower($text);
	}
}
?>