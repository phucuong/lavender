<?php
class Modelcontentcat extends Model {
	public function addcat($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "cat SET parent_id = '" . (int)$data['parent_id'] . "', `top` = '" . (isset($data['top']) ? (int)$data['top'] : 0) . "', `column` = '" . (int)$data['column'] . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW(), date_added = NOW()");
	
		$cat_id = $this->db->getLastId();
		
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "cat SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "' WHERE cat_id = '" . (int)$cat_id . "'");
		}
		
		foreach ($data['cat_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "cat_description SET cat_id = '" . (int)$cat_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}
		
		if (isset($data['cat_store'])) {
			foreach ($data['cat_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "cat_to_store SET cat_id = '" . (int)$cat_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		if (isset($data['cat_layout'])) {
			foreach ($data['cat_layout'] as $store_id => $layout) {
				if ($layout['layout_id']) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "cat_to_layout SET cat_id = '" . (int)$cat_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
				}
			}
		}
						
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'cat_id=" . (int)$cat_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
		
		$this->cache->delete('cat');
	}
	
	public function editcat($cat_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "cat SET parent_id = '" . (int)$data['parent_id'] . "', `top` = '" . (isset($data['top']) ? (int)$data['top'] : 0) . "', `column` = '" . (int)$data['column'] . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW() WHERE cat_id = '" . (int)$cat_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "cat SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "' WHERE cat_id = '" . (int)$cat_id . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "cat_description WHERE cat_id = '" . (int)$cat_id . "'");

		foreach ($data['cat_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "cat_description SET cat_id = '" . (int)$cat_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "cat_to_store WHERE cat_id = '" . (int)$cat_id . "'");
		
		if (isset($data['cat_store'])) {		
			foreach ($data['cat_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "cat_to_store SET cat_id = '" . (int)$cat_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "cat_to_layout WHERE cat_id = '" . (int)$cat_id . "'");

		if (isset($data['cat_layout'])) {
			foreach ($data['cat_layout'] as $store_id => $layout) {
				if ($layout['layout_id']) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "cat_to_layout SET cat_id = '" . (int)$cat_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
				}
			}
		}
						
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'cat_id=" . (int)$cat_id. "'");
		
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'cat_id=" . (int)$cat_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
		
		$this->cache->delete('cat');
	}
	
	public function deletecat($cat_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "cat WHERE cat_id = '" . (int)$cat_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "cat_description WHERE cat_id = '" . (int)$cat_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "cat_to_store WHERE cat_id = '" . (int)$cat_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "cat_to_layout WHERE cat_id = '" . (int)$cat_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'cat_id=" . (int)$cat_id . "'");
		
		$query = $this->db->query("SELECT cat_id FROM " . DB_PREFIX . "cat WHERE parent_id = '" . (int)$cat_id . "'");

		foreach ($query->rows as $result) {
			$this->deletecat($result['cat_id']);
		}
		
		$this->cache->delete('cat');
	} 

	public function getcat($cat_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'cat_id=" . (int)$cat_id . "') AS keyword FROM " . DB_PREFIX . "cat WHERE cat_id = '" . (int)$cat_id . "'");
		
		return $query->row;
	} 
	
	public function getCategories($parent_id = 0) {
		$cat_data = $this->cache->get('cat.' . (int)$this->config->get('config_language_id') . '.' . (int)$parent_id);
	
		if (!$cat_data) {
			$cat_data = array();
		
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cat c LEFT JOIN " . DB_PREFIX . "cat_description cd ON (c.cat_id = cd.cat_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY c.sort_order, cd.name ASC");
		
			foreach ($query->rows as $result) {
				$cat_data[] = array(
					'cat_id' => $result['cat_id'],
					'name'        => $this->getPath($result['cat_id'], $this->config->get('config_language_id')),
					'status'  	  => $result['status'],
					'sort_order'  => $result['sort_order']
				);
			
				$cat_data = array_merge($cat_data, $this->getCategories($result['cat_id']));
			}	
	
			$this->cache->set('cat.' . (int)$this->config->get('config_language_id') . '.' . (int)$parent_id, $cat_data);
		}
		
		return $cat_data;
	}
	
	public function getPath($cat_id) {
		$query = $this->db->query("SELECT name, parent_id FROM " . DB_PREFIX . "cat c LEFT JOIN " . DB_PREFIX . "cat_description cd ON (c.cat_id = cd.cat_id) WHERE c.cat_id = '" . (int)$cat_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY c.sort_order, cd.name ASC");
		
		if ($query->row['parent_id']) {
			return $this->getPath($query->row['parent_id'], $this->config->get('config_language_id')) . $this->language->get('text_separator') . $query->row['name'];
		} else {
			return $query->row['name'];
		}
	}
	
	public function getcatDescriptions($cat_id) {
		$cat_description_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cat_description WHERE cat_id = '" . (int)$cat_id . "'");
		
		foreach ($query->rows as $result) {
			$cat_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'meta_keyword'     => $result['meta_keyword'],
				'meta_description' => $result['meta_description'],
				'description'      => $result['description']
			);
		}
		
		return $cat_description_data;
	}	
	
	public function getcatStores($cat_id) {
		$cat_store_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cat_to_store WHERE cat_id = '" . (int)$cat_id . "'");

		foreach ($query->rows as $result) {
			$cat_store_data[] = $result['store_id'];
		}
		
		return $cat_store_data;
	}

	public function getcatLayouts($cat_id) {
		$cat_layout_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cat_to_layout WHERE cat_id = '" . (int)$cat_id . "'");
		
		foreach ($query->rows as $result) {
			$cat_layout_data[$result['store_id']] = $result['layout_id'];
		}
		
		return $cat_layout_data;
	}
		
	public function getTotalCategories() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "cat");
		
		return $query->row['total'];
	}	
		
	public function getTotalCategoriesByImageId($image_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "cat WHERE image_id = '" . (int)$image_id . "'");
		
		return $query->row['total'];
	}

	public function getTotalCategoriesByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "cat_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}		
}
?>