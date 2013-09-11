<?php
class Modelcontentcomment extends Model {		
	public function addcomment($news_id, $data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "comment SET author = '" . $this->db->escape($data['name']) . "', customer_id = '" . (int)$this->customer->getId() . "', news_id = '" . (int)$news_id . "', text = '" . $this->db->escape($data['text']) . "', rating = '" . (int)$data['rating'] . "', date_added = NOW()");
	}
		
	public function getcommentsBynewsId($news_id, $start = 0, $limit = 20) {
		$query = $this->db->query("SELECT r.comment_id, r.author, r.rating, r.text, p.news_id, pd.name, p.image, r.date_added FROM " . DB_PREFIX . "comment r LEFT JOIN " . DB_PREFIX . "news p ON (r.news_id = p.news_id) LEFT JOIN " . DB_PREFIX . "news_description pd ON (p.news_id = pd.news_id) WHERE p.news_id = '" . (int)$news_id . "' AND p.date_available <= NOW() AND p.status = '1' AND r.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY r.date_added DESC LIMIT " . (int)$start . "," . (int)$limit);
		
		return $query->rows;
	}
	
	public function getAverageRating($news_id) {
		$query = $this->db->query("SELECT AVG(rating) AS total FROM " . DB_PREFIX . "comment WHERE status = '1' AND news_id = '" . (int)$news_id . "' GROUP BY news_id");
		
		if (isset($query->row['total'])) {
			return (int)$query->row['total'];
		} else {
			return 0;
		}
	}	
	
	public function getTotalcomments() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "comment r LEFT JOIN " . DB_PREFIX . "news p ON (r.news_id = p.news_id) WHERE p.date_available <= NOW() AND p.status = '1' AND r.status = '1'");
		
		return $query->row['total'];
	}

	public function getTotalcommentsBynewsId($news_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "comment r LEFT JOIN " . DB_PREFIX . "news p ON (r.news_id = p.news_id) LEFT JOIN " . DB_PREFIX . "news_description pd ON (p.news_id = pd.news_id) WHERE p.news_id = '" . (int)$news_id . "' AND p.date_available <= NOW() AND p.status = '1' AND r.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		return $query->row['total'];
	}
}
?>