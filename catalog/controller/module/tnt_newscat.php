<?php
class ControllerModuleTntnewsCat extends Controller {
	protected function index($setting) {
		$this->language->load('module/tnt_newscat');
		
      	$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['button_cart'] = $this->language->get('button_cart');
		$this->data['text_model'] = $this->language->get('text_model');
		$this->data['text_price'] = $this->language->get('text_price');

		$this->load->model('content/cat');
				
		$this->load->model('content/news');
		
		$this->load->model('tool/image');
		
		$this->load->model('tool/t2vn');
		
		$this->data['position'] = $setting['position'];
		$this->data['newss'] = array();
		
		$cat_info = $this->model_content_cat->getcat($setting['cat']);
		if(isset($cat_info['name'])){
			$this->data['cat_name'] = $cat_info['name'];
			$data = array(
				'filter_cat_id' => $setting['cat'], 
				'sort'  => 'name',
				'order' => '',
				'start' => 0,
				'limit' => $setting['limit']
			);
			$results = $this->model_content_news->getnewss($data);

			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $setting['image_width'], $setting['image_height']);
				} else {
					$image = false;
				}
						
				
			
				if ($this->config->get('config_comment_status')) {
					$rating = $result['rating'];
				} else {
					$rating = false;
				}

				if ($result['description'] && $setting['description'] == 1) {
					$description = $this->model_tool_t2vn->cut_string(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'), $setting['limitdescription']);
				} else {
					$description = false;
				}
				$this->data['newss'][] = array(
					'news_id' 		=> $result['news_id'],
					'thumb'   	 	=> $image,
					'description'	=> $description,
					'image' 		=> HTTP_IMAGE . $result['image'],
					'name'    	 	=> $result['name'],
					'rating'     	=> $rating,
					'comments'    	=> sprintf($this->language->get('text_comments'), (int)$result['comments']),
					'href'    	 	=> $this->url->link('news/news', 'news_id=' . $result['news_id']),
				);
			}
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/tnt_newscat.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/tnt_newscat.tpl';
		} else {
			$this->template = 'default/template/module/tnt_newscat.tpl';
		}

		$this->render();
	}
}
?>