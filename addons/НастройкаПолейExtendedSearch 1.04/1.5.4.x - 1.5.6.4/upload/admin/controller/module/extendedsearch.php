<?php
################################################################
#     ExtendedSearch 1.04 for Opencart 154x-156x by AlexDW 	   #
################################################################
class ControllerModuleExtendedsearch extends Controller {

	private $error = array(); 

	public function index() {   
		$this->load->language('module/extendedsearch');

		$this->document->setTitle(strip_tags($this->language->get('heading_title')));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('extendedsearch', $this->request->post);		

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$text_strings = array(
				'heading_title',
				'text_extsearch',
				'text_reserved',
				'text_enabled',
				'text_disabled',

				'entry_status',
				'entry_tag',
				'entry_model',
				'entry_sku',
				'entry_upc',
				'entry_ean',
				'entry_jan',
				'entry_isbn',
				'entry_mpn',
				'entry_location',
				'entry_attr',

				'button_save',
				'button_cancel'
		);

		foreach ($text_strings as $text) {
			$this->data[$text] = $this->language->get($text);
		}

		$config_data = array(
				'extendedsearch_status',
				'extendedsearch_tag',
				'extendedsearch_model',
				'extendedsearch_sku',
				'extendedsearch_upc',
				'extendedsearch_ean',
				'extendedsearch_jan',
				'extendedsearch_isbn',
				'extendedsearch_mpn',
				'extendedsearch_location',
				'extendedsearch_attr'
		);
		
		foreach ($config_data as $conf) {
			if (isset($this->request->post[$conf])) {
				$this->data[$conf] = $this->request->post[$conf];
			} else {
				$this->data[$conf] = $this->config->get($conf);
			}
		}

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/extendedsearch', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

		$this->data['action'] = $this->url->link('module/extendedsearch', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		$this->template = 'module/extendedsearch.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);

		$this->response->setOutput($this->render());
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/extendedsearch')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
}
?>