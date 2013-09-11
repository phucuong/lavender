<?php
class Modelcontentcomment extends Model {
	public function addcomment($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "comment SET author = '" . $this->db->escape($data['author']) . "', news_id = '" . $this->db->escape($data['news_id']) . "', text = '" . $this->db->escape(strip_tags($data['text'])) . "', rating = '" . (int)$data['rating'] . "', status = '" . (int)$data['status'] . "', date_added = NOW()");
	
		$this->cache->delete('news');
	}
	
	public function editcomment($comment_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "comment SET author = '" . $this->db->escape($data['author']) . "', news_id = '" . $this->db->escape($data['news_id']) . "', text = '" . $this->db->escape(strip_tags($data['text'])) . "', rating = '" . (int)$data['rating'] . "', status = '" . (int)$data['status'] . "', date_added = NOW() WHERE comment_id = '" . (int)$comment_id . "'");
	
		$this->cache->delete('news');
	}
	
	public function deletecomment($comment_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "comment WHERE comment_id = '" . (int)$comment_id . "'");
		
		$this->cache->delete('news');
	}
	
	public function getcomment($comment_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT pd.name FROM " . DB_PREFIX . "news_description pd WHERE pd.news_id = r.news_id AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS news FROM " . DB_PREFIX . "comment r WHERE r.comment_id = '" . (int)$comment_id . "'");
		
		return $query->row;
	}

	public function getcomments($data = array()) {
		$sql = "SELECT r.comment_id, pd.name, r.author, r.rating, r.status, r.date_added FROM " . DB_PREFIX . "comment r LEFT JOIN " . DB_PREFIX . "news_description pd ON (r.news_id = pd.news_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";																																					  
		
		$sort_data = array(
			'pd.name',
			'r.author',
			'r.rating',
			'r.status',
			'r.date_added'
		);	
			
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY r.date_added";	
		}
			
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
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
																																							  
		$query = $this->db->query($sql);																																				
		
		return $query->rows;	
	}
	
	public function getTotalcomments() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "comment");
		
		return $query->row['total'];
	}
	
	public function getTotalcommentsAwaitingApproval() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "comment WHERE status = '0'");
		
		return $query->row['total'];
	}	
}
?>