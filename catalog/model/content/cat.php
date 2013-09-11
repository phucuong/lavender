<?php
class Modelcontentcat extends Model {
	public function getcat($cat_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "cat c LEFT JOIN " . DB_PREFIX . "cat_description cd ON (c.cat_id = cd.cat_id) LEFT JOIN " . DB_PREFIX . "cat_to_store c2s ON (c.cat_id = c2s.cat_id) WHERE c.cat_id = '" . (int)$cat_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND c.status = '1'");
		
		return $query->row;
	}
	
	public function getCategories($parent_id = 0) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cat c LEFT JOIN " . DB_PREFIX . "cat_description cd ON (c.cat_id = cd.cat_id) LEFT JOIN " . DB_PREFIX . "cat_to_store c2s ON (c.cat_id = c2s.cat_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name)");
		
		return $query->rows;
	}

	public function getCategoriesByParentId($cat_id) {
		$cat_data = array();
		
		$cat_query = $this->db->query("SELECT cat_id FROM " . DB_PREFIX . "cat WHERE parent_id = '" . (int)$cat_id . "'");
		
		foreach ($cat_query->rows as $cat) {
			$cat_data[] = $cat['cat_id'];
			
			$children = $this->getCategoriesByParentId($cat['cat_id']);
			
			if ($children) {
				$cat_data = array_merge($children, $cat_data);
			}			
		}
		
		return $cat_data;
	}
		
	public function getcatLayoutId($cat_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cat_to_layout WHERE cat_id = '" . (int)$cat_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");
		
		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return $this->config->get('config_layout_cat');
		}
	}
					
	public function getTotalCategoriesBycatId($parent_id = 0) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "cat c LEFT JOIN " . DB_PREFIX . "cat_to_store c2s ON (c.cat_id = c2s.cat_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND c.status = '1'");
		
		return $query->row['total'];
	}
}
?>