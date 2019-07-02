<?php
class ControllerExtensionModuleMultycurrgoods extends Controller {
	private $error = array();

	public function index() {
    $data['token'] = $this->session->data['token'];
		$this->load->language('extension/module/multycurrgoods');

		$this->document->setTitle('Мультивалютные товары v. 2.3');
		
		$this->load->model('extension/module');
        $this->load->model('extension/multycurrgoods');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL'));
		  }

		$data['heading_title'] = $this->language->get('heading_title');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module/multycurrgoods', 'token=' . $this->session->data['token'], 'SSL')
		);


        $info = $this->model_extension_multycurrgoods->get_extra_charge_info();
        $currency_info = $this->model_extension_multycurrgoods->get_setting_currency();

        $data['currency_info'] = $currency_info;
		$data['extra_charge'] = $info;
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$data['action_cron'] = $this->url->link('extension/module/multycurrgoods/save_cron_tab', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
        $data['action_add_prices'] = $this->url->link('extension/module/multycurrgoods/add_prices', 'token=' . $this->session->data['token'], 'SSL');
    $data['round_mode'] = $this->config->get('round_mode');
    if (($data['round_mode']<-2)||($data['round_mode']>3)) $data['round_mode'] = 2;

    $data['save_mode'] = $this->config->get('save_mode');
    if ($data['save_mode']!=0) $data['save_mode'] = 1;

    $data['task_save_mode']         = $this->config->get('task_save_mode');      
    $data['ro_support']             = $this->config->get('ro_support');      
    $data['cron_mc_access_key']     = $this->config->get('cron_mc_access_key');      

// - Закрузка списка валют --
		$data['currencies'] = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency" . " ORDER BY title" . " ASC");

		foreach ($query->rows as $result) {
      $r     = $this->db->query("SELECT COUNT(`product_id`) AS total FROM " . DB_PREFIX . "product_multycurr WHERE currency_id = '" . $result['currency_id'] . "'");
      $key  = "currency_id" . $result['currency_id'] . "LastValue";
  		$last = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `code` = 'multycurrgoods' AND `key` = '" . $key . "'");
      $lastvalue =  $result['value']?1/$result['value']:0;
      if ($last->num_rows) $lastvalue = $last->row['value'];
			$data['currencies'][$result['currency_id']] = array(
				'currency_id'   => $result['currency_id'],
				'title'         => $result['title'] . (($result['code'] == $this->config->get('config_currency')) ? $this->language->get('text_default') : null),
				'code'          => $result['code'],
				'value'         => round ( $result['value']?1/$result['value']:0, 4),
				'last'          => $lastvalue,
 				'total'         => $r->row['total']
         );
		}	
// - Закрузка истории --
    $data['log'] = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_multycurr_history" . " ORDER BY dateof DESC");
		foreach ($query->rows as $result1) {
			$data['log'][] = array(
				'id'            => $result1['id'],
				'date'          => $result1['dateof'],
				'user'          => $result1['user'],
				'code'          => $result1['currency_id'],
				'kurs'          => $result1['kurs'],
 				'total'         => $result1['total']
         );
        }
// - Загрузка списка поставщиков  --
		$data['suppliers'] = array();
    $query  = $this->db->query("SELECT DISTINCT supplier FROM " . DB_PREFIX . "product_to_supplier" . " ORDER BY supplier");
		foreach ($query->rows as $result) {
      $totals = $this->db->query("SELECT COUNT(`product_id`) AS total FROM " . DB_PREFIX . "product_to_supplier WHERE supplier = '" . $this->db->escape($result['supplier']) . "'");
			$data['suppliers'][] = array(
				'name'   => $result['supplier'],
        'total'  => $totals->row?$totals->row['total']:0
        );
      }
// Загружаем список производителей
    $data['manufacturer'] = array();
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer ORDER by name");
    foreach ($query->rows as $result) {
      $totals = array();
      foreach ($data['currencies'] as $curr) {   
        $currency_id = $curr['currency_id'];
        $c_query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_multycurr pm LEFT JOIN " . DB_PREFIX . "product p ON (pm.product_id = p.product_id) WHERE pm.currency_id = '" . $currency_id . "' AND p.manufacturer_id = '" . $result['manufacturer_id'] . "'");
        $totals [$curr['currency_id']] = array( 'total' => $c_query->row['total'] );
        }
			$data['manufacturer'][] = array(
				'manufacturer_id'    => $result['manufacturer_id'],
        'totals'             => $totals,
 				'name'               => $result['name']
         );
      }




        $data['mccron'] = array ();
		$data['mccron'] = $this->getCronData();		

		$this->response->setOutput($this->load->view('extension/module/multycurrgoods', $data));
	}

	public function calculate_ajax(){
        $this->load->model('extension/multycurrgoods');
        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {

            $this->model_extension_multycurrgoods->calculate_product_extra_charge_prices($this->request->post);


        }
    }
	public function add_prices() {
	    $this->load->model('extension/multycurrgoods');
        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {

            $this->model_extension_multycurrgoods->add_extra_charge($this->request->post);

            $this->response->redirect($this->url->link('extension/module/multycurrgoods', 'token=' . $this->session->data['token'], 'SSL'));
        }

    }

    public function start () {
  $total              = 0;
  if ($this->request->server['REQUEST_METHOD'] == 'POST') { // Для ДЕМО-режима
    $currency_id        = $this->request->post['currency_id'];
    $kurs               = round($this->request->post['kurs'],$this->request->post['curr_round_mode']);
  	if ($this->validate()) {
     // Обрабатываем запрос  из модуля на пересчет по новому курсу для ЗАДАЧИ
      $manufacturer_id    = $this->request->post['manufacturer_id'];
      $name               = $this->request->post['supplier'];
      $add_before         = $this->request->post['add_before'];
      $mul_after          = $this->request->post['mul_after'];
      $add_after          = $this->request->post['add_after'];
      $round_mode         = $this->request->post['round_mode'];
      
      if ($this->config->get('save_mode')) {
        $this->db->query("UPDATE " . DB_PREFIX . "currency SET date_modified = NOW(), value = '" . (float)(1/$kurs) . "' WHERE currency_id = '" . $currency_id . "'");
        }
  
      if ($this->config->get('task_save_mode')) {
        $mccron             = array ();
    		$mccron             = $this->getCronData();
        $task_id		        = (int)$this->request->post['task_id'];
        if (isset($mccron[$task_id])) {
          $mccron[$task_id]['last_value'] = $this->request->post['kurs'];
      		$this->db->query("DELETE FROM " . DB_PREFIX . "mc_cron WHERE `task_id` = '" . $task_id . "'");
  			  $this->db->query("INSERT INTO " . DB_PREFIX . "mc_cron SET `task_id` = '" . (int)$task_id . "', `value` = '" . $this->db->escape(json_encode($mccron[$task_id])) . "'");
          }
        }
  
      $sql = "SELECT pm.product_id,pm.price FROM "  . DB_PREFIX . "product_multycurr pm";
      if ($manufacturer_id>0) $sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (pm.product_id = p.product_id)"; 
      if ($name!='0')         $sql .= " LEFT JOIN " . DB_PREFIX . "product_to_supplier p2s ON (pm.product_id = p2s.product_id)"; 
                              $sql .= " WHERE pm.currency_id = '" . $currency_id . "'";
      if ($manufacturer_id>0) $sql .= " AND p.manufacturer_id = '" . $manufacturer_id . "'"; 
      if ($name!='0')         $sql .= " AND p2s.supplier = '" . $this->db->escape($name) . "'"; 
                              $sql .= " GROUP BY pm.product_id";
      $main_query = $this->db->query($sql);
  
  		$this->cache->delete('product');
      foreach ($main_query->rows as $product) {
        usleep (100); // Временная задержка, во избежание перегрузки сервера
        $product_id = $product['product_id'];
        $price = round (($product['price'] + $add_before) * $kurs * $mul_after + $add_after, $round_mode);
  		  $this->db->query("UPDATE " . DB_PREFIX . "product SET price = '" . (float)$price . "', date_modified = NOW() WHERE product_id = '" . (int)$product_id . "'");
        $total ++;
        // Таблица "Акции"
       $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_multycurr_special WHERE product_id = '" . (int)$product_id . "' AND currency_id = '" . $currency_id . "'");
  	   foreach ($query->rows as $result) {
            $price = round (($result['mc_price'] + $add_before) * $kurs * $mul_after + $add_after, $round_mode);
  		      $this->db->query("UPDATE " . DB_PREFIX . "product_special SET price = '" . (float)$price . "' WHERE product_special_id = '" . (int)$result['product_special_id'] . "'");
            }
      // Таблица "Скидки"
       $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_multycurr_discount WHERE product_id = '" . (int)$product_id . "' AND currency_id = '" . $currency_id . "'");
  	   foreach ($query->rows as $result) {
            $price = round (($result['mc_price'] + $add_before) * $kurs * $mul_after + $add_after, $round_mode);
  		      $this->db->query("UPDATE " . DB_PREFIX . "product_discount SET price = '" . (float)$price . "' WHERE product_discount_id = '" . (int)$result['product_discount_id'] . "'");
            }
      // Таблица "Опции"
       $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_multycurr_option WHERE product_id = '" . (int)$product_id . "' AND currency_id = '" . $currency_id . "'");
  	   foreach ($query->rows as $result) {
            $price = round (($result['mc_price'] + $add_before) * $kurs * $mul_after + $add_after, $round_mode);
  		      $this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET price = '" . (float)$price . "' WHERE product_option_value_id = '" . (int)$result['product_option_value_id'] . "'");
            }
      // Таблица "Связанные Опции"
        if ($this->config->get('ro_support')=='2_2') {
          $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "relatedoptions WHERE product_id = '" . (int)$product_id . "'");
  	       foreach ($query->rows as $result) {
            $price = round (($result['mc_price'] + $add_before) * $kurs * $mul_after + $add_after, $round_mode);
  		      $this->db->query("UPDATE " . DB_PREFIX . "relatedoptions SET price = '" . (float)$price . "' WHERE relatedoptions_id = '" . (int)$result['relatedoptions_id'] . "'");
            }
          }
  //		    $this->event->trigger('post.admin.product.edit', $product_id);
        }
      }
      // Записываем в ЛОГ
      $this->db->query("INSERT INTO " . DB_PREFIX . "product_multycurr_history SET kurs = '" . $kurs . "', total = '" . $total . "', currency_id = '" . $currency_id . "', user = '" . $this->user->getUserName() . "', dateof = NOW()");
      }
	$this->response->redirect($this->url->link('module/multycurrgoods', 'token=' . $this->session->data['token'], 'SSL'));
  }
  
public function save_cron_tab () {
	if (($this->request->server['REQUEST_METHOD'] == 'POST')&&$this->validate()) {
    $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "mc_cron");
    if (isset($this->request->post['mccron'])) {
      foreach ($this->request->post['mccron'] as $task_id => $value) {
  			$this->db->query("INSERT INTO " . DB_PREFIX . "mc_cron SET `task_id` = '" . (int)$task_id . "', `value` = '" . $this->db->escape(json_encode($value)) . "'");
        }
      }
		$this->session->data['mc_success'] = 'Настройки планировщика сохранены!';
    }
	$this->response->redirect($this->url->link('extension/module/multycurrgoods', 'token=' . $this->session->data['token'], 'SSL'));
  }	

public function settings  () {
	if (($this->request->server['REQUEST_METHOD'] == 'POST')&&$this->validate()) {
    if (isset($this->request->post['round_mode'])) {
      $this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `code` = 'multycurrgoods' AND `key` = 'round_mode'");
			$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET `code` = 'multycurrgoods', `key` = 'round_mode', `value` = '" . $this->db->escape($this->request->post['round_mode']) . "'");
      }
    if (isset($this->request->post['save_mode'])) {
      $this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `code` = 'multycurrgoods' AND `key` = 'save_mode'");
			$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET `code` = 'multycurrgoods', `key` = 'save_mode', `value` = '" . $this->db->escape($this->request->post['save_mode']) . "'");
      }
    if (isset($this->request->post['task_save_mode'])) {
      $this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `code` = 'multycurrgoods' AND `key` = 'task_save_mode'");
			$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET `code` = 'multycurrgoods', `key` = 'task_save_mode', `value` = '" . $this->db->escape($this->request->post['task_save_mode']) . "'");
      }
    if (isset($this->request->post['cron_mc_access_key'])) {
      $this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `code` = 'multycurrgoods' AND `key` = 'cron_mc_access_key'");
			$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET `code` = 'multycurrgoods', `key` = 'cron_mc_access_key', `value` = '" . $this->db->escape($this->request->post['cron_mc_access_key']) . "'");
      }
    if (isset($this->request->post['ro_support'])) {
      $this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `code` = 'multycurrgoods' AND `key` = 'ro_support'");
			$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET `code` = 'multycurrgoods', `key` = 'ro_support', `value` = '" . $this->db->escape($this->request->post['ro_support']) . "'");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "relatedoptions` ADD COLUMN `mc_price` decimal(15,4) NOT NULL " );
      }
		$this->session->data['mc_success'] = 'Настройки сохранены!';
    }
	$this->response->redirect($this->url->link('extension/module/multycurrgoods', 'token=' . $this->session->data['token'], 'SSL'));
  }	

public function del_history_row () {
	if (($this->request->server['REQUEST_METHOD'] == 'POST')&&$this->validate()&&(isset($this->request->post['id']))) {
    $this->db->query("DELETE FROM " . DB_PREFIX . "product_multycurr_history WHERE `id` = '" . $this->request->post['id'] . "'");
		$this->session->data['mc_success'] = 'Запись удалена!';
    }
	$this->response->redirect($this->url->link('extension/module/multycurrgoods', 'token=' . $this->session->data['token'], 'SSL'));
  }	

public function del_history () {
	if (($this->request->server['REQUEST_METHOD'] == 'POST')&&$this->validate()) {
    $this->db->query("TRUNCATE TABLE " . DB_PREFIX . "product_multycurr_history");
		$this->session->data['mc_success'] = 'Лог очищен!';
    }
	$this->response->redirect($this->url->link('extension/module/multycurrgoods', 'token=' . $this->session->data['token'], 'SSL'));
  }	

  protected function getCronData() {
		$mydata = array(); 
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "mc_cron ORDER BY task_id");
		foreach ($query->rows as $result) $mydata[$result['task_id']] = json_decode($result['value'], true);
    return $mydata; 
  }

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/multycurrgoods')) $this->error['warning'] = $this->language->get('error_permission');
		return !$this->error;
	}

  public function install() {
  	$this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "product_multycurr (product_id INT(11), price decimal(15,4), currency_id INT(11), PRIMARY KEY (product_id)) ENGINE  =  MyISAM  DEFAULT CHARSET  = utf8");
  	$this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "product_multycurr_history (id INT(11) AUTO_INCREMENT, dateof DATETIME NOT NULL, user VARCHAR(32), currency_id INT(11), kurs decimal(15,4), vendor INT(11), total INT(11), PRIMARY KEY (id)) ENGINE  =  MyISAM  DEFAULT CHARSET  = utf8");
  	$this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "product_multycurr_special (product_special_id INT(11), product_id INT(11), mc_price decimal(15,4), currency_id INT(11), PRIMARY KEY (product_special_id)) ENGINE  =  MyISAM  DEFAULT CHARSET  = utf8");
  	$this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "product_multycurr_discount (product_discount_id INT(11), product_id INT(11), mc_price decimal(15,4), currency_id INT(11), PRIMARY KEY (product_discount_id)) ENGINE  =  MyISAM  DEFAULT CHARSET  = utf8");
  	$this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "product_multycurr_option (id INT(11) AUTO_INCREMENT, product_option_value_id INT(11), product_id INT(11), mc_price decimal(15,4), currency_id INT(11), PRIMARY KEY (id)) ENGINE  =  MyISAM  DEFAULT CHARSET  = utf8");
  	$this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "product_input_price (product_id INT(11), iprice decimal(15,4), currency_id INT(11), PRIMARY KEY (product_id)) ENGINE  =  MyISAM  DEFAULT CHARSET  = utf8");
  	$this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "product_to_ym (product_id INT(11), ym INT(11), PRIMARY KEY (product_id)) ENGINE  =  MyISAM  DEFAULT CHARSET  = utf8");
  	$this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "product_to_supplier (product_id INT(11), supplier VARCHAR(32), PRIMARY KEY (product_id)) ENGINE  =  MyISAM  DEFAULT CHARSET  = utf8");
  	$this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "product_sliv (product_id INT(11), sliv INT(2), PRIMARY KEY (product_id)) ENGINE  =  MyISAM  DEFAULT CHARSET  = utf8");
  	$this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "product_country_of_origin (product_id INT(11), country VARCHAR(32), PRIMARY KEY (product_id)) ENGINE  =  MyISAM  DEFAULT CHARSET  = utf8");
  	$this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "mc_cron (task_id INT(11) AUTO_INCREMENT, value  TEXT NOT NULL, PRIMARY KEY (task_id)) ENGINE  =  MyISAM  DEFAULT CHARSET  = utf8");
  	//adding table for changing default product price by some percents
      $this->db->query("DROP TABLE " . DB_PREFIX . "product_myltycurr_default_extra_charge");
      $this->db->query("DROP TABLE " . DB_PREFIX . "product_myltycurr_custom_extra_charge");
      $this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "product_myltycurr_default_extra_charge (id INT(11) NOT NULL AUTO_INCREMENT, price_from DOUBLE NOT NULL, price_min DOUBLE NOT NULL, price_max DOUBLE NOT NULL, price_to DOUBLE NOT NULL, sp_from DOUBLE NOT NULL, sp_min_max DOUBLE NOT NULL, sp_to DOUBLE NOT NULL, pop_from DOUBLE NOT NULL, pop_min_max DOUBLE NOT NULL, pop_to DOUBLE NOT NULL, PRIMARY KEY (id)) ENGINE  =  MyISAM  DEFAULT CHARSET  = utf8;");
      $this->db->query("INSERT INTO " . DB_PREFIX . "product_myltycurr_default_extra_charge SET id = '1', price_from = '0', price_min = '0', price_max = '0', price_to = '0', sp_from = '0', sp_min_max = '0', sp_to = '0', pop_from = '0', pop_min_max = '0', pop_to = '0'");
      $this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "product_myltycurr_custom_extra_charge (id INT(11) NOT NULL, product_id INT(11) NOT NULL, price_from DOUBLE NOT NULL, price_min DOUBLE NOT NULL, price_max DOUBLE NOT NULL, price_to DOUBLE NOT NULL, sp_from DOUBLE NOT NULL, sp_min_max DOUBLE NOT NULL, sp_to DOUBLE NOT NULL, pop_from DOUBLE NOT NULL, pop_min_max DOUBLE NOT NULL, pop_to DOUBLE NOT NULL, count_in_pack DOUBLE NOT NULL, sp_to_res_price DOUBLE NOT NULL , sp_min_max_res_price DOUBLE NOT NULL , sp_from_res_price DOUBLE NOT NULL , pop_to_res_price DOUBLE NOT NULL , pop_min_max_res_price DOUBLE NOT NULL , pop_from_res_price DOUBLE NOT NULL , active INT NOT NULL, PRIMARY KEY (id)) ENGINE  =  MyISAM  DEFAULT CHARSET  = utf8;");
    }

}