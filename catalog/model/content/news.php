<?php
class Modelcontentnews extends Model {
	public function updateViewed($news_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "news SET viewed = (viewed + 1) WHERE news_id = '" . (int)$news_id . "'");
	}
	
	public function getnews($news_id) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	
				
		$query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image,  (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "comment r2 WHERE r2.news_id = p.news_id AND r2.status = '1' GROUP BY r2.news_id) AS comments, p.sort_order FROM " . DB_PREFIX . "news p LEFT JOIN " . DB_PREFIX . "news_description pd ON (p.news_id = pd.news_id) LEFT JOIN " . DB_PREFIX . "news_to_store p2s ON (p.news_id = p2s.news_id) WHERE p.news_id = '" . (int)$news_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
		
		return $query->row;
	}

	public function getnewss($data = array()) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	
		
		$cache = md5(http_build_query($data));
		
		$news_data = $this->cache->get('news.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . (int)$customer_group_id . '.' . $cache);
		
		if (!$news_data) {
			$sql = "SELECT p.news_id, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "comment r1 WHERE r1.news_id = p.news_id AND r1.status = '1' GROUP BY r1.news_id) AS rating FROM " . DB_PREFIX . "news p LEFT JOIN " . DB_PREFIX . "news_description pd ON (p.news_id = pd.news_id) LEFT JOIN " . DB_PREFIX . "news_to_store p2s ON (p.news_id = p2s.news_id)"; 
			
			if (!empty($data['filter_tag'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "news_tag pt ON (p.news_id = pt.news_id)";			
			}
						
			if (!empty($data['filter_cat_id'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "news_to_cat p2c ON (p.news_id = p2c.news_id)";			
			}
			
			$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'"; 
			
			if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
				$sql .= " AND (";
											
				if (!empty($data['filter_name'])) {
					$implode = array();
					
					$words = explode(' ', $data['filter_name']);
					
					foreach ($words as $word) {
						if (!empty($data['filter_description'])) {
							$implode[] = "LCASE(pd.name) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%' OR LCASE(pd.description) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%'";
						} else {
							$implode[] = "LCASE(pd.name) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%'";
						}				
					}
					
					if ($implode) {
						$sql .= " " . implode(" OR ", $implode) . "";
					}
				}
				
				if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
					$sql .= " OR ";
				}
				
				if (!empty($data['filter_tag'])) {
					$implode = array();
					
					$words = explode(' ', $data['filter_tag']);
					
					foreach ($words as $word) {
						$implode[] = "LCASE(pt.tag) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%' AND pt.language_id = '" . (int)$this->config->get('config_language_id') . "'";
					}
					
					if ($implode) {
						$sql .= " " . implode(" OR ", $implode) . "";
					}
				}
			
				$sql .= ")";
			}
			
			if (!empty($data['filter_cat_id'])) {
				if (!empty($data['filter_sub_cat'])) {
					$implode_data = array();
					
					$implode_data[] = "p2c.cat_id = '" . (int)$data['filter_cat_id'] . "'";
					
					$this->load->model('content/cat');
					
					$categories = $this->model_content_cat->getCategoriesByParentId($data['filter_cat_id']);
										
					foreach ($categories as $cat_id) {
						$implode_data[] = "p2c.cat_id = '" . (int)$cat_id . "'";
					}
								
					$sql .= " AND (" . implode(' OR ', $implode_data) . ")";			
				} else {
					$sql .= " AND p2c.cat_id = '" . (int)$data['filter_cat_id'] . "'";
				}
			}		
					

			
			$sql .= " GROUP BY p.news_id";
			
			$sort_data = array(
				'pd.name',
				'p.news_id',
				'p.sort_order',
				'p.date_added'
			);	
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
					$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
				} else {
					$sql .= " ORDER BY " . $data['sort'];
				}
			} else {
				$sql .= " ORDER BY p.sort_order";	
			}
			
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC, LCASE(pd.name) DESC";
			} else {
				$sql .= " ASC, LCASE(pd.name) ASC";
			}
		
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}				
	
				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}	
			
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
			
			$news_data = array();
		
			$query = $this->db->query($sql);
		
			foreach ($query->rows as $result) {
				$news_data[$result['news_id']] = $this->getnews($result['news_id']);
			}
			
			$this->cache->set('news.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . (int)$customer_group_id . '.' . $cache, $news_data);
		}
		return $news_data;
	}
	
	
	public function getLatestnewss($limit) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	
				
		$news_data = $this->cache->get('news.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $customer_group_id . '.' . (int)$limit);

		if (!$news_data) { 
			$query = $this->db->query("SELECT p.news_id FROM " . DB_PREFIX . "news p LEFT JOIN " . DB_PREFIX . "news_to_store p2s ON (p.news_id = p2s.news_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.date_added DESC LIMIT " . (int)$limit);
		 	 
			foreach ($query->rows as $result) {
				$news_data[$result['news_id']] = $this->getnews($result['news_id']);
			}
			
			$this->cache->set('news.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'). '.' . $customer_group_id . '.' . (int)$limit, $news_data);
		}
		
		return $news_data;
	}
	
	public function getPopularnewss($limit) {
		$news_data = array();
		
		$query = $this->db->query("SELECT p.news_id FROM " . DB_PREFIX . "news p LEFT JOIN " . DB_PREFIX . "news_to_store p2s ON (p.news_id = p2s.news_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.viewed, p.date_added DESC LIMIT " . (int)$limit);
		
		foreach ($query->rows as $result) { 		
			$news_data[$result['news_id']] = $this->getnews($result['news_id']);
		}
					 	 		
		return $news_data;
	}

	public function getBestSellernewss($limit) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	
				
		$news_data = $this->cache->get('news.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'). '.' . $customer_group_id . '.' . (int)$limit);

		if (!$news_data) { 
			$news_data = array();
			
			$query = $this->db->query("SELECT op.news_id, COUNT(*) AS total FROM " . DB_PREFIX . "order_news op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id) LEFT JOIN `" . DB_PREFIX . "news` p ON (op.news_id = p.news_id) LEFT JOIN " . DB_PREFIX . "news_to_store p2s ON (p.news_id = p2s.news_id) WHERE o.order_status_id > '0' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' GROUP BY op.news_id ORDER BY total DESC LIMIT " . (int)$limit);
			
			foreach ($query->rows as $result) { 		
				$news_data[$result['news_id']] = $this->getnews($result['news_id']);
			}
			
			$this->cache->set('news.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'). '.' . $customer_group_id . '.' . (int)$limit, $news_data);
		}
		
		return $news_data;
	}
	
		
	public function getnewsImages($news_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news_image WHERE news_id = '" . (int)$news_id . "' ORDER BY sort_order ASC");

		return $query->rows;
	}
	
	public function getnewsRelated($news_id) {
		$news_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news_related pr LEFT JOIN " . DB_PREFIX . "news p ON (pr.related_id = p.news_id) LEFT JOIN " . DB_PREFIX . "news_to_store p2s ON (p.news_id = p2s.news_id) WHERE pr.news_id = '" . (int)$news_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
		
		foreach ($query->rows as $result) { 
			$news_data[$result['related_id']] = $this->getnews($result['related_id']);
		}
		
		return $news_data;
	}
		
	public function getnewsTags($news_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news_tag WHERE news_id = '" . (int)$news_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->rows;
	}
		
	public function getnewsLayoutId($news_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news_to_layout WHERE news_id = '" . (int)$news_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");
		
		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return  $this->config->get('config_layout_news');
		}
	}
	
	public function getCategories($news_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "news_to_cat WHERE news_id = '" . (int)$news_id . "'");
		
		return $query->rows;
	}	
		
	public function getTotalnewss($data = array()) {
		$sql = "SELECT COUNT(DISTINCT p.news_id) AS total FROM " . DB_PREFIX . "news p LEFT JOIN " . DB_PREFIX . "news_description pd ON (p.news_id = pd.news_id) LEFT JOIN " . DB_PREFIX . "news_to_store p2s ON (p.news_id = p2s.news_id)";

		if (!empty($data['filter_cat_id'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "news_to_cat p2c ON (p.news_id = p2c.news_id)";			
		}
		
		if (!empty($data['filter_tag'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "news_tag pt ON (p.news_id = pt.news_id)";			
		}
					
		$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
		
		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";
								
			if (!empty($data['filter_name'])) {
				$implode = array();
				
				$words = explode(' ', $data['filter_name']);
				
				foreach ($words as $word) {
					if (!empty($data['filter_description'])) {
						$implode[] = "LCASE(pd.name) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%' OR LCASE(pd.description) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%'";
					} else {
						$implode[] = "LCASE(pd.name) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%'";
					}				
				}
				
				if ($implode) {
					$sql .= " " . implode(" OR ", $implode) . "";
				}
			}
			
			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}
			
			if (!empty($data['filter_tag'])) {
				$implode = array();
				
				$words = explode(' ', $data['filter_tag']);
				
				foreach ($words as $word) {
					$implode[] = "LCASE(pt.tag) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%' AND pt.language_id = '" . (int)$this->config->get('config_language_id') . "'";
				}
				
				if ($implode) {
					$sql .= " " . implode(" OR ", $implode) . "";
				}
			}
		
			$sql .= ")";
		}
		
		if (!empty($data['filter_cat_id'])) {
			if (!empty($data['filter_sub_cat'])) {
				$implode_data = array();
				
				$implode_data[] = "p2c.cat_id = '" . (int)$data['filter_cat_id'] . "'";
				
				$this->load->model('content/cat');
				
				$categories = $this->model_content_cat->getCategoriesByParentId($data['filter_cat_id']);
					
				foreach ($categories as $cat_id) {
					$implode_data[] = "p2c.cat_id = '" . (int)$cat_id . "'";
				}
							
				$sql .= " AND (" . implode(' OR ', $implode_data) . ")";			
			} else {
				$sql .= " AND p2c.cat_id = '" . (int)$data['filter_cat_id'] . "'";
			}
		}		
		

	}
			
	
}
?>