<?php   
class ControllerCommonHeader extends Controller {
	protected function index() {
		$this->data['title'] = $this->document->getTitle();
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['base'] = $this->config->get('config_ssl');
		} else {
			$this->data['base'] = $this->config->get('config_url');
		}
		
		if(isset($this->request->get['route'])){
			$this->data['route'] = $this->request->get['route'];
		}else{
			$this->data['route'] = '';
		}
		
		$this->data['description'] = $this->document->getDescription();
		$this->data['keywords'] = $this->document->getKeywords();
		$this->data['links'] = $this->document->getLinks();	 
		$this->data['styles'] = $this->document->getStyles();
		$this->data['scripts'] = $this->document->getScripts();
		$this->data['lang'] = $this->language->get('code');
		$this->data['direction'] = $this->language->get('direction');
		$this->data['google_analytics'] = html_entity_decode($this->config->get('config_google_analytics'), ENT_QUOTES, 'UTF-8');
		
		$this->language->load('common/header');
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$server = HTTPS_IMAGE;
		} else {
			$server = HTTP_IMAGE;
		}	
				
		if ($this->config->get('config_icon') && file_exists(DIR_IMAGE . $this->config->get('config_icon'))) {
			$this->data['icon'] = $server . $this->config->get('config_icon');
		} else {
			$this->data['icon'] = '';
		}
		
		$this->data['name'] = $this->config->get('config_name');
				
		if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo'))) {
			$this->data['logo'] = $server . $this->config->get('config_logo');
		} else {
			$this->data['logo'] = '';
		}
		
		$this->data['text_home'] = $this->language->get('text_home');
		$this->data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
		$this->data['text_shopping_cart'] = $this->language->get('text_shopping_cart');
    	$this->data['text_search'] = $this->language->get('text_search');
		$this->data['text_welcome'] = sprintf($this->language->get('text_welcome'), $this->url->link('account/login', '', 'SSL'), $this->url->link('account/register', '', 'SSL'));
		$this->data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', 'SSL'), $this->customer->getFirstName(), $this->url->link('account/logout', '', 'SSL'));
		$this->data['text_account'] = $this->language->get('text_account');
    	$this->data['text_checkout'] = $this->language->get('text_checkout');
		$this->data['text_contact'] = $this->language->get('text_contact');
		$this->data['text_forum'] = $this->language->get('text_forum');
		$this->data['text_product'] = $this->language->get('text_product');
		$this->data['home'] = $this->url->link('common/home');
		$this->data['wishlist'] = $this->url->link('account/wishlist');
		$this->data['logged'] = $this->customer->isLogged();
		$this->data['account'] = $this->url->link('account/account', '', 'SSL');
		$this->data['shopping_cart'] = $this->url->link('checkout/cart');
		$this->data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');
		
		if (isset($this->request->get['filter_name'])) {
			$this->data['filter_name'] = $this->request->get['filter_name'];
		} else {
			$this->data['filter_name'] = '';
		}
		
		if (!isset($this->request->get['route'])) {
			$this->data['home_active'] = 1;
		}elseif($this->request->get['route'] == 'common/home'){
			$this->data['home_active'] = 1;
		}else {
			$this->data['home_active'] = 0;
		}
		
		// Menu Product
		
		if (isset($this->request->get['path'])) {
			$part = explode('_', (string)$this->request->get['path']);
		} else {
			$part = array();
		}
		
		if (isset($part[0])) {
			$this->data['category_id'] = $part[0];
		} else {
			$this->data['category_id'] = 0;
		}
		
		$this->load->model('catalog/category');
		$this->load->model('catalog/product');
		
		$this->data['categories'] = array();
					
		$categories = $this->model_catalog_category->getCategories(0);
		
		foreach ($categories as $category) {
			if ($category['top']) {
				$children_data = array();
				
				$children = $this->model_catalog_category->getCategories($category['category_id']);
				
				foreach ($children as $child) {
					$data = array(
						'filter_category_id'  => $child['category_id'],
						'filter_sub_category' => true	
					);		
						
					$product_total = $this->model_catalog_product->getTotalProducts($data);
					if($child['image']!='') {
						$image = $child['image'];			
					}else{
						$image = $category['image'];			
					}
					
					$children_data[] = array(
						'name'  	=> $child['name'] . ' (' . $product_total . ')',
						'image'    	=> $image,
						'href'  	=> $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])	
					);					
				}
				
				// Level 1
				$this->data['categories'][] = array(
					'name'     		=> $category['name'],
					'category_id'   => $category['category_id'],
					'children'	 	=> $children_data,
					'column'   		=> $category['column'] ? $category['column'] : 1,
					'href'     		=> $this->url->link('product/category', 'path=' . $category['category_id'])
				);
			}
		}
		
		// Menu News
		
		if (isset($this->request->get['catid'])) {
			$partnews = explode('_', (string)$this->request->get['catid']);
		} else {
			$partnews = array();
		}
		
		if (isset($partnews[0])) {
			$this->data['cat_id'] = $partnews[0];
		} else {
			$this->data['cat_id'] = 0;
		}

		$this->load->model('content/cat');
		$this->load->model('content/news');
		
		$this->data['categoriesnews'] = array();
					
		$categoriesnews = $this->model_content_cat->getCategories(0);
		
		foreach ($categoriesnews as $cat) {
			if ($cat['top']) {
				$childrennews_data = array();
				
				$childrennews = $this->model_content_cat->getCategories($cat['cat_id']);
				
				foreach ($childrennews as $childnews) {
					$data = array(
						'filter_cat_id'  => $childnews['cat_id'],
						'filter_sub_cat' => true	
					);		
						
					$news_total = $this->model_content_news->getTotalnewss($data);
					if($childnews['image']!='') {
						$image = $childnews['image'];			
					}else{
						$image = $cat['image'];			
					}
					
					$childrennews_data[] = array(
						'name'  	=> $childnews['name'] . ' (' . $news_total . ')',
						'image'    	=> $image,
						'href'  	=> $this->url->link('news/cat', 'catid=' . $cat['cat_id'] . '_' . $childnews['cat_id'])	
					);					
				}
				
				// Level 1
				$this->data['categoriesnews'][] = array(
					'name'     => $cat['name'],
					'cat_id'   => $cat['cat_id'],
					'children' => $childrennews_data,
					'column'   => $cat['column'] ? $cat['column'] : 1,
					'href'     => $this->url->link('news/cat', 'catid=' . $cat['cat_id'])
				);
			}
		}
		
		// Menu Information
		if (isset($this->request->get['information_id'])) {
			$this->data['information_id'] = $this->request->get['information_id'];	
		}else{
			$this->data['information_id'] = 0;
		}

		$this->load->model('catalog/information');
		
		$this->data['informations'] = array();

		foreach ($this->model_catalog_information->getInformations() as $result) {
			if($result['top']== 1){
      			$this->data['informations'][] = array(
        			'title' => $result['title'],
      				'id'	=> $result['information_id'],
	    			'href'  => $this->url->link('information/information', 'information_id=' . $result['information_id'])
      			);
			}
    	}
		
		$this->children = array(
			'module/language',
			'module/currency',
			'module/cart'
		);
				
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/header.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/header.tpl';
		} else {
			$this->template = 'default/template/common/header.tpl';
		}
		
    	$this->render();
	} 	
}
?>