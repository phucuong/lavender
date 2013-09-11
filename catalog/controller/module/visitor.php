<?php
/*
Visitor Module code by T2vn
07 04 2012
http://www.opencart.vn
*/

class ControllerModuleVisitor extends Controller
{
	private $_name;
	private $_type		= 'module';
	private $_version	= '1.0';

	protected function index() {
		$this->getName();
		$this->data['version']	= $this->_version;
		$this->id				= $this->_name;

		$this->getLanguage();
		$this->getParams();
		$this->getTemplate();
		$this->getFooter();
		$this->getData();

		$this->render();
	}
	private function getName() {
        $this->_fName	= str_replace( 'ControllerModule', '', get_class( $this ) );
		$this->_name	= strtolower( $this->_fName );
    }

	private function getLanguage() {
		$this->language->load( $this->_type .'/'. $this->_name );

		// standard params
		$txtParams = array(
			'heading_title', 'text_today', 'text_week', 'text_month',
			'text_year', 'text_all', 'text_online'
		);

		foreach( $txtParams as $txtParam ) {
			$this->data[$txtParam]	= $this->language->get( $txtParam );
		}

		unset( $txtParams );
	}
	private function getParams() {
		// module specific
		$this->data['theme']	= $this->config->get( $this->_name . '_theme' );
		$this->data['imgPath']	= 'catalog/view/theme/default/image/counter/' . $this->data['theme'] .'/';
	}
	private function getTemplate() {
		$tmpl = '/template/' . $this->_type .'/'. $this->_name . '.tpl';

		if( file_exists( DIR_TEMPLATE . $this->config->get( 'config_template' ) . $tmpl ) ) {
			$this->template = $this->config->get( 'config_template' ) . $tmpl;
		}else{
			$this->template = 'default' . $tmpl;
		}
	}
	private function getData() {
		$this->load->model( 'module/visitor' );

		$this->data['data'] = $this->model_module_visitor->getFileData( $this->_name );
	}
	private function getFooter() {
		$this->data['t2vn']	= "\n" . '<!-- Module ' . $this->_fName .' v.'. $this->_version . ' by http://www.opencart.vn -->' . "\n";
	}
}