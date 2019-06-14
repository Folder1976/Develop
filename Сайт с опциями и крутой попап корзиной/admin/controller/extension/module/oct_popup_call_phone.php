<?php
/**************************************************************/
/*	@copyright	OCTemplates 2018.							  */
/*	@support	https://octemplates.net/					  */
/*	@license	LICENSE.txt									  */
/**************************************************************/

class ControllerExtensionModuleOctPopupCallPhone extends Controller {
    private $error = array();
    
    public function index() {
        $this->load->language('extension/module/oct_popup_call_phone');
        
        $this->document->setTitle($this->language->get('heading_title'));
        
        $this->load->model('setting/setting');
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('oct_popup_call_phone', $this->request->post);
            
            $this->session->data['success'] = $this->language->get('text_success');
            
            $this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));
        }
        
        $data['heading_title'] = $this->language->get('heading_title');
        
        $data['text_edit']             = $this->language->get('text_edit');
        $data['text_enabled']          = $this->language->get('text_enabled');
        $data['text_disabled']         = $this->language->get('text_disabled');
        $data['text_enabled_required'] = $this->language->get('text_enabled_required');
        $data['text_select_all']       = $this->language->get('text_select_all');
        $data['text_unselect_all']     = $this->language->get('text_unselect_all');
        
        $data['tab_setting'] = $this->language->get('tab_setting');
        $data['tab_list']    = $this->language->get('tab_list');
        
        $data['entry_status']        = $this->language->get('entry_status');
        $data['entry_notify_status'] = $this->language->get('entry_notify_status');
        $data['entry_notify_email']  = $this->language->get('entry_notify_email');
        $data['entry_name']          = $this->language->get('entry_name');
        $data['entry_telephone']     = $this->language->get('entry_telephone');
        $data['entry_comment']       = $this->language->get('entry_comment');
        $data['entry_time']          = $this->language->get('entry_time');
        $data['entry_mask']          = $this->language->get('entry_mask');
        $data['entry_mask_info']     = $this->language->get('entry_mask_info');
        
        $data['help_email'] = $this->language->get('help_email');
        
        $data['button_save']   = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        
        $data['token'] = $this->session->data['token'];
        
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
        
        if (isset($this->error['notify_email'])) {
            $data['error_notify_email'] = $this->error['notify_email'];
        } else {
            $data['error_notify_email'] = '';
        }
        
        $data['breadcrumbs'] = array();
        
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );
        
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
        );
        
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/oct_popup_call_phone', 'token=' . $this->session->data['token'], true)
        );
        
        $data['action'] = $this->url->link('extension/module/oct_popup_call_phone', 'token=' . $this->session->data['token'], true);
        
        $data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);
        
        if (isset($this->request->post['oct_popup_call_phone_data'])) {
            $data['oct_popup_call_phone_data'] = $this->request->post['oct_popup_call_phone_data'];
        } else {
            $data['oct_popup_call_phone_data'] = $this->config->get('oct_popup_call_phone_data');
        }
        
        $data['header']      = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer']      = $this->load->controller('common/footer');
        
        $this->response->setOutput($this->load->view('extension/module/oct_popup_call_phone.tpl', $data));
    }
    
    public function history() {
        $data = array();
        $this->load->model('extension/module/oct_popup_call_phone');
        $this->language->load('extension/module/oct_popup_call_phone');
        
        $data['text_no_results'] = $this->language->get('text_no_results');
        
        $data['button_delete_menu']     = $this->language->get('button_delete_menu');
        $data['button_delete_selected'] = $this->language->get('button_delete_selected');
        $data['button_delete_all']      = $this->language->get('button_delete_all');
        $data['button_delete']          = $this->language->get('button_delete');
        
        $data['column_action']     = $this->language->get('column_action');
        $data['column_info']       = $this->language->get('column_info');
        $data['column_date_added'] = $this->language->get('column_date_added');
        
        $page          = (isset($this->request->get['page'])) ? $this->request->get['page'] : 1;
        $data['token'] = $this->session->data['token'];
        
        $data['histories'] = array();
        
        $filter_data = array(
            'start' => ($page - 1) * 20,
            'limit' => 20,
            'sort' => 'r.date_added',
            'order' => 'DESC'
        );
        
        $results = $this->model_extension_module_oct_popup_call_phone->getCallArray($filter_data);
        
        foreach ($results as $result) {
            $info = array();
            
            $fields = unserialize($result['info']);
            
            foreach ($fields as $field) {
                $info[] = array(
                    'name' => $field['name'],
                    'value' => $field['value']
                );
            }
            
            $data['histories'][] = array(
                'request_id' => $result['request_id'],
                'info' => $info,
                'date_added' => $result['date_added']
            );
        }
        
        $history_total = $this->model_extension_module_oct_popup_call_phone->getTotalCallArray();
        
        $pagination        = new Pagination();
        $pagination->total = $history_total;
        $pagination->page  = $page;
        $pagination->limit = 20;
        $pagination->url   = $this->url->link('extension/module/oct_popup_call_phone/history', 'token=' . $this->session->data['token'] . '&page={page}', true);
        
        $data['pagination'] = $pagination->render();
        
        $data['results'] = sprintf($this->language->get('text_pagination'), ($history_total) ? (($page - 1) * 20) + 1 : 0, ((($page - 1) * 20) > ($history_total - 20)) ? $history_total : ((($page - 1) * 20) + 20), $history_total, ceil($history_total / 20));
        
        $this->response->setOutput($this->load->view('extension/module/oct_popup_call_phone_history.tpl', $data));
    }
    
    public function delete_selected() {
        $json = array();
        $this->load->model('extension/module/oct_popup_call_phone');
        
        $info = $this->model_extension_module_oct_popup_call_phone->getCall((int) $this->request->get['delete']);
        
        if ($info) {
            $this->model_extension_module_oct_popup_call_phone->deleteCall((int) $this->request->get['delete']);
        }
        
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    
    public function delete_all() {
        $json = array();
        $this->load->model('extension/module/oct_popup_call_phone');
        
        $this->model_extension_module_oct_popup_call_phone->deleteAllCallArray();
        
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    
    public function delete_all_selected() {
        $json = array();
        $this->load->model('extension/module/oct_popup_call_phone');
        
        if (isset($this->request->request['selected'])) {
            foreach ($this->request->request['selected'] as $request_id) {
                $info = $this->model_extension_module_oct_popup_call_phone->getCall((int) $request_id);
                
                if ($info) {
                    $this->model_extension_module_oct_popup_call_phone->deleteCall((int) $request_id);
                }
            }
        }
        
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    
    public function install() {
        $this->load->language('extension/module/oct_popup_call_phone');
        $this->load->model('extension/module/oct_popup_call_phone');
        $this->load->model('extension/extension');
        $this->load->model('setting/setting');
        $this->load->model('user/user_group');
        
        $this->model_user_user_group->addPermission($this->user->getId(), 'access', 'extension/module/oct_popup_call_phone');
        $this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'extension/module/oct_popup_call_phone');
        
        $this->model_extension_module_oct_popup_call_phone->makeDB();
        
        $this->model_setting_setting->editSetting('oct_popup_call_phone', array(
            'oct_popup_call_phone_data' => array(
                'status' => '1',
                'notify_status' => '1',
                'notify_email' => $this->config->get('config_email'),
                'name' => '2',
                'telephone' => '2',
                'comment' => '2',
                'time' => '2',
                'mask' => '8 (000) 000-00-00'
            )
        ));
        
        if (!in_array('oct_popup_call_phone', $this->model_extension_extension->getInstalled('module'))) {
            $this->model_extension_extension->install('module', 'oct_popup_call_phone');
        }
        
        $this->session->data['success'] = $this->language->get('text_success_install');
    }
    
    public function uninstall() {
        $this->load->model('extension/extension');
        $this->load->model('setting/setting');
        $this->load->model('extension/module/oct_popup_call_phone');
        
        $this->model_extension_module_oct_popup_call_phone->deleteDB();
        $this->model_extension_extension->uninstall('module', 'oct_popup_call_phone');
        $this->model_setting_setting->deleteSetting('oct_popup_call_phone_data');
    }
    
    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/module/oct_popup_call_phone')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        
        foreach ($this->request->post['oct_popup_call_phone_data'] as $key => $field) {
            if (empty($field) && $key == "notify_email") {
                $this->error['notify_email'] = $this->language->get('error_notify_email');
            }
        }
        
        return !$this->error;
    }
}