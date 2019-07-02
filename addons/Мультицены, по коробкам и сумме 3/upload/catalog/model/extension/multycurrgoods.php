<?php
class ModelExtensionMultycurrgoods extends Model {
    public function add_extra_charge($data) {
        // - updating default extra charge price --
        $this->db->query("UPDATE " . DB_PREFIX . "product_myltycurr_default_extra_charge SET price_from = '" . $this->db->escape($data['price_from']) . "', price_min = '" . $this->db->escape($data['price_min']) . "', price_max = '" . $this->db->escape($data['price_max']) . "', price_to = '" . $this->db->escape($data['price_to']) . "', sp_from = '" . $this->db->escape($data['sp_from']) . "', sp_min_max = '" . $this->db->escape($data['sp_min_max']) . "', sp_to = '" . $this->db->escape($data['sp_to']) . "', pop_from = '" . $this->db->escape($data['pop_from']) . "', pop_min_max = '" . (int)$data['pop_min_max'] . "', pop_to = '" . (int)$data['pop_to'] . "' WHERE id = '1'");
    }

    public function get_extra_charge_info() {
        // - Закрузка цен для пересчета товаров --
        $info = array();
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_myltycurr_default_extra_charge");
        foreach ($query->rows as $result) {
            $info = $result;
        }
        return $info;
    }

    public function get_setting_currency(){
        // - Закрузка активной валюты из админки --
        $info = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `key` = 'config_currency'");
        foreach ($query->rows as $result) {
            $info = $result;
        }
        return $info;
    }

    public function set_custom_extra_charge_info($product_id, $data){

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_myltycurr_custom_extra_charge WHERE `product_id` = '" . (int)$product_id . "'");

        if(count($query->rows) != 0){
            $this->db->query("UPDATE " . DB_PREFIX . "product_myltycurr_custom_extra_charge SET sp_from = '" . $this->db->escape($data['sp_from']) . "', sp_min_max = '" . $this->db->escape($data['sp_min_max']) . "', sp_to = '" . $this->db->escape($data['sp_to']) . "', pop_from = '" . $this->db->escape($data['pop_from']) . "', pop_min_max = '" . (int)$data['pop_min_max'] . "', pop_to = '" . (int)$data['pop_to'] . "', count_in_pack = '" . (int)$data['boxing'] . "', active = '1' WHERE `product_id` = '" . (int)$product_id . "'");
        }
        else{
            $this->db->query("INSERT INTO " . DB_PREFIX . "product_myltycurr_custom_extra_charge SET product_id = '" . $this->db->escape($product_id) . "', sp_from = '" . $this->db->escape($data['sp_from']) . "', sp_min_max = '" . $this->db->escape($data['sp_min_max']) . "', sp_to = '" . $this->db->escape($data['sp_to']) . "', pop_from = '" . $this->db->escape($data['pop_from']) . "', pop_min_max = '" . $this->db->escape($data['pop_min_max']) . "', pop_to = '" . $this->db->escape($data['pop_to']) . "', count_in_pack = '" . $this->db->escape($data['boxing']) . "', active = '1'");
        }

//        echo '<pre>';
//        var_dump($query->rows);
//        echo count($query->rows);
//        echo '</pre>';
//        die;
        //$this->db->query("INSERT INTO " . DB_PREFIX . "product_myltycurr_default_extra_charge SET id = '1', price_from = '0', price_min = '0', price_max = '0', price_to = '0', sp_from = '0', sp_min_max = '0', sp_to = '0', pop_from = '0', pop_min_max = '0', pop_to = '0'");
    }

    public function get_custom_extra_charge_info($product_id){
// - Закрузка цен для пересчета товаров --
        $info = array();
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_myltycurr_custom_extra_charge WHERE `product_id` = '" . (int)$product_id . "'");
        foreach ($query->rows as $result) {
            $info = $result;
        }
        return $info;
    }

    public function disable_custom_extra_charge_prices($product_id){

    }

    public function get_currency_info_by_id($currency_id){
        $info = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency WHERE `currency_id` = '" . (int)$currency_id . "'");
        foreach ($query->rows as $result) {
            $info = $result;
        }
        return $info;
    }

    //doesn't work in admin panel, just in catalog
    public function calculate_product_extra_charge_prices($data){

//        $default_extra_charge_info = get_extra_charge_info();
//
        $input_currency_id = (int)$data['input_currency_id'];
        $currency_info = $this->get_currency_info_by_id($input_currency_id);
        $setting_currency = $this->get_setting_currency();
//
//        $single_product_minimum_price = $default_extra_charge_info['price_to'];
//        $single_product_diapason_min_price = $default_extra_charge_info['price_min'];
//        $single_product_diapason_max_price = $default_extra_charge_info['price_max'];
//        $single_product_maximum_price = $default_extra_charge_info['price_from'];
//
        $input_iprice = (double)$data['input_iprice'];//закупочная цена из админки
//
//
//
//
//

        $sp_to = (double)$data['sp_to'];
        $sp_min_max = (double)$data['sp_min_max'];
        $sp_from = (double)$data['sp_from'];
        $pop_to = (double)$data['pop_to'];
        $pop_min_max = (double)$data['pop_min_max'];
        $pop_from = (double)$data['pop_from'];


        $result['sp_to_res']= (($input_iprice / (double)$currency_info['value']) * ($sp_to / 100)) + ($input_iprice / (double)$currency_info['value']);
        $result['sp_min_max_res']= (($input_iprice / (double)$currency_info['value']) * ($sp_min_max / 100)) + ($input_iprice / (double)$currency_info['value']);
        $result['sp_from_res']= (($input_iprice / (double)$currency_info['value']) * ($sp_from / 100)) + ($input_iprice / (double)$currency_info['value']);
        $result['pop_to_res']= (($input_iprice / (double)$currency_info['value']) * ($pop_to / 100)) + ($input_iprice / (double)$currency_info['value']);
        $result['pop_min_max_res']= (($input_iprice / (double)$currency_info['value']) * ($pop_min_max / 100)) + ($input_iprice / (double)$currency_info['value']);
        $result['pop_from_res']= (($input_iprice / (double)$currency_info['value']) * ($pop_from / 100)) + ($input_iprice / (double)$currency_info['value']);


        $result['sp_to_res']= round($result['sp_to_res'], 2, PHP_ROUND_HALF_UP) . ' ' . $setting_currency['value'];
        $result['sp_min_max_res']= round($result['sp_min_max_res'], 2, PHP_ROUND_HALF_UP) . ' ' . $setting_currency['value'];
        $result['sp_from_res']= round($result['sp_from_res'], 2, PHP_ROUND_HALF_UP) . ' ' . $setting_currency['value'];
        $result['pop_to_res']= round($result['pop_to_res'], 2, PHP_ROUND_HALF_UP) . ' ' . $setting_currency['value'];
        $result['pop_min_max_res']= round($result['pop_min_max_res'], 2, PHP_ROUND_HALF_UP) . ' ' . $setting_currency['value'];
        $result['pop_from_res']= round($result['pop_from_res'], 2, PHP_ROUND_HALF_UP) . ' ' . $setting_currency['value'];


        //price's values without currency
        $result['sp_to_val']= round($result['sp_to_res'], 2, PHP_ROUND_HALF_UP);
        $result['sp_min_max_val']= round($result['sp_min_max_res'], 2, PHP_ROUND_HALF_UP);
        $result['sp_from_val']= round($result['sp_from_res'], 2, PHP_ROUND_HALF_UP);
        $result['pop_to_val']= round($result['pop_to_res'], 2, PHP_ROUND_HALF_UP);
        $result['pop_min_max_val']= round($result['pop_min_max_res'], 2, PHP_ROUND_HALF_UP);
        $result['pop_from_val']= round($result['pop_from_res'], 2, PHP_ROUND_HALF_UP);



        $result = json_encode($result);
        echo $result;
    }
}