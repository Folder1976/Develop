<?php

class ControllerExtensionModuleMarkup extends Controller {

    public function index() {

        // Загружаем "модель" модуля
        //$this->load->model('extension/module/markup');

        // Сохранение настроек модуля, когда пользователь нажал "Записать"
        /*
        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            // Вызываем метод "модели" для сохранения настроек
            $this->model_extension_module_markup->SaveSettings();
            // Выходим из настроек с выводом сообщения
            $this->session->data['success'] = 'Настройки сохранены';
            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
        }
        */
        
        $sql = "SELECT *  FROM information_schema.columns 
                    WHERE table_schema = '".DB_DATABASE."'
                      AND table_name   = '" . DB_PREFIX . "product'
                      AND column_name  = 'price1'";
                      
        $r = $this->db->query($sql);
        
        if($r->num_rows == 0){
            $sql = "ALTER TABLE " . DB_PREFIX . "product ADD COLUMN price1 INT(10) NOT NULL AFTER product_id;";
            $this->db->query($sql);
            $sql = "ALTER TABLE " . DB_PREFIX . "product ADD COLUMN price2 INT(10) NOT NULL AFTER product_id;";
            $this->db->query($sql);
            $sql = "ALTER TABLE " . DB_PREFIX . "product ADD COLUMN price3 INT(10) NOT NULL AFTER product_id;";
            $this->db->query($sql);
            $sql = "ALTER TABLE " . DB_PREFIX . "product ADD COLUMN is_price INT(2) NOT NULL AFTER product_id;";
            $this->db->query($sql);
            $sql = "ALTER TABLE " . DB_PREFIX . "product ADD COLUMN minimum_pak INT(10) NOT NULL AFTER product_id;";
            $this->db->query($sql);
            $sql = "ALTER TABLE " . DB_PREFIX . "product ADD COLUMN minimum_sum INT(10) NOT NULL AFTER product_id;";
            $this->db->query($sql);
            $sql = "ALTER TABLE " . DB_PREFIX . "product ADD COLUMN customer_group_id INT(10) NOT NULL AFTER product_id;";
            $this->db->query($sql);
            $sql = "ALTER TABLE " . DB_PREFIX . "product ADD COLUMN currency_id INT(10) NOT NULL AFTER product_id;";
            $this->db->query($sql);
        }

        
        $this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
            
            //$json = json_encode($this->request->post);
            $this->model_setting_setting->editSetting('markup', $this->request->post);
            
    		$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}
        
        // Загружаем настройки через метод "модели"
        $data = array();
        $data = $this->model_setting_setting->getSetting('markup');
   
        
       	$this->load->model('localisation/currency');

		$data['currencies'] = array(0 => array(
									'title'        => 'По умолчанию',
									'code'         => '0',
									'currency_id'         => '0'	   
											   ));

		$results = $this->model_localisation_currency->getCurrencies();
		
		foreach ($results as $result) {
			if ($result['status']) {
				$data['currencies'][$result['currency_id']] = array(
					'title'        => $result['title'],
					'code'         => $result['code'],
					'currency_id'         => $result['currency_id'],
					'symbol_left'  => $result['symbol_left'],
					'symbol_right' => $result['symbol_right']
				);
			}
		}

        
      	$this->load->model('customer/customer_group');

		$data['customer_groups'] = array(0 => array(
									'name'        => 'Всем',
									'customer_group_id'         => '0'	   
											   ));

		$results = $this->model_customer_customer_group->getCustomerGroups();
		
		foreach ($results as $result) {
            $data['customer_groups'][$result['customer_group_id']] = array(
                'name'        => $result['name'],
                'customer_group_id'         => $result['customer_group_id']
            );
		}



		if($data['markup_currency_id'] > 0){
			$data['selected_currency'] = $data['currencies'][$data['markup_currency_id']]['code'];
		}else{
			$data['selected_currency'] = $this->config->get('config_currency');
		}
       
       // Загружаем языковой файл
        $data += $this->load->language('extension/module/markup');
        // Загружаем "хлебные крошки"
        $data += $this->GetBreadCrumbs();

        // Кнопки действий
        $data['action'] = $this->url->link('extension/module/markup', 'user_token=' . $this->session->data['user_token'], true);
        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
        // Загрузка шаблонов для шапки, колонки слева и футера
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        // Выводим в браузер шаблон
        $this->response->setOutput($this->load->view('extension/module/markup', $data));

    }

    // Хлебные крошки
    private function GetBreadCrumbs() {
        $data = array(); $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/markup', 'user_token=' . $this->session->data['user_token'], true)
        );
        return $data;
    }

}

