<?php  
class ControllerModuletntweather extends Controller {
	protected function index() {
		$this->language->load('module/tnt_weather');

      	$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['code'] = $this->config->get('tnt_weather_code');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/tnt_weather.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/tnt_weather.tpl';
		} else {
			$this->template = 'default/template/module/tnt_weather.tpl';
		}
		
		$this->render();
	}
}
?>