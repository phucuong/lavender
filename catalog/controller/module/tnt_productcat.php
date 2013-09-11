<?php
class ControllerModuleTntProductCat extends Controller {
	protected function index($setting) {
		$this->language->load('module/tnt_productcat');
		
      	$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['button_cart'] = $this->language->get('button_cart');
		$this->data['text_model'] = $this->language->get('text_model');
		$this->data['text_price'] = $this->language->get('text_price');

		$this->load->model('catalog/category');
				
		$this->load->model('catalog/product');
		
		$this->load->model('tool/image');
		$this->data['position'] = $setting['position'];
		$this->data['products'] = array();
		
		$category_info = $this->model_catalog_category->getCategory($setting['cat']);
		if(isset($category_info['name'])){
			$this->data['category_name'] = $category_info['name'];
			$data = array(
				'filter_category_id' => $setting['cat'], 
				'sort'  => 'name',
				'order' => '',
				'start' => 0,
				'limit' => $setting['limit']
			);
			$results = $this->model_catalog_product->getProducts($data);

			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $setting['image_width'], $setting['image_height']);
				} else {
					$image = false;
				}
						
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}
					
				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
				}
			
				if ($this->config->get('config_review_status')) {
					$rating = $result['rating'];
				} else {
					$rating = false;
				}
				if ($result['quantity'] <= 0) {
					$stock  = $result['stock_status'];
				} elseif ($this->config->get('config_stock_display')) {
					$stock  = $result['quantity'];
				} else {
					$stock = $this->language->get('text_instock');
				}	
				$product_info = $this->model_catalog_product->getProduct($result['product_id']);
				$product_descript = str_replace("<p>","",trim(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')));
				$product_descript = str_replace("</p>","",$product_descript);
				$this->data['products'][] = array(
					'description'=> $product_descript,
					'product_id' => $result['product_id'],
					'thumb'   	 => $image,
					'name'    	 => $result['name'],
					'price'   	 => $price,
					'special' 	 => $special,
					'model'  	  => $result['model'],
					'stock'       => $stock,
					'rating'     => $rating,
					'reviews'    => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
					'href'    	 => $this->url->link('product/product', 'product_id=' . $result['product_id']),
				);
			}
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/tnt_productcat.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/tnt_productcat.tpl';
		} else {
			$this->template = 'default/template/module/tnt_productcat.tpl';
		}

		$this->render();
	}
}
?>