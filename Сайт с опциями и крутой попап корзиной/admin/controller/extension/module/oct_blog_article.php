<?php
/**************************************************************/
/*	@copyright	OCTemplates 2018.							  */
/*	@support	https://octemplates.net/					  */
/*	@license	LICENSE.txt									  */
/**************************************************************/

class ControllerExtensionModuleOctBlogArticle extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('extension/module/oct_blog_article');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/module');
        $this->load->model('localisation/language');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            if (!isset($this->request->get['module_id'])) {
                $this->model_extension_module->addModule('oct_blog_article', $this->request->post);
            } else {
                $this->model_extension_module->editModule($this->request->get['module_id'], $this->request->post);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_edit']         = $this->language->get('text_edit');
        $data['text_enabled']      = $this->language->get('text_enabled');
        $data['text_disabled']     = $this->language->get('text_disabled');
        $data['text_blog_setting'] = $this->language->get('text_blog_setting');
        $data['text_sort_order_1'] = $this->language->get('text_sort_order_1');
        $data['text_sort_order_2'] = $this->language->get('text_sort_order_2');
        $data['text_sort_order_3'] = $this->language->get('text_sort_order_3');
        $data['text_sort_order_4'] = $this->language->get('text_sort_order_4');
        $data['text_sort_order_5'] = $this->language->get('text_sort_order_5');
        $data['text_sort_order_6'] = $this->language->get('text_sort_order_6');

        $data['entry_category']   = $this->language->get('entry_category');
        $data['entry_name']       = $this->language->get('entry_name');
        $data['entry_heading']    = $this->language->get('entry_heading');
        $data['entry_link']       = $this->language->get('entry_link');
        $data['entry_limit']      = $this->language->get('entry_limit');
        $data['entry_width']      = $this->language->get('entry_width');
        $data['entry_height']     = $this->language->get('entry_height');
        $data['entry_status']     = $this->language->get('entry_status');
        $data['entry_desc_limit'] = $this->language->get('entry_desc_limit');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $data['entry_show_image'] = $this->language->get('entry_show_image');

        $data['help_category'] = $this->language->get('help_category');

        $data['button_save']   = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = '';
        }

        if (isset($this->error['heading'])) {
            $data['error_heading'] = $this->error['heading'];
        } else {
            $data['error_heading'] = array();
        }

        if (isset($this->error['link'])) {
            $data['error_link'] = $this->error['link'];
        } else {
            $data['error_link'] = array();
        }

        if (isset($this->error['desc_limit'])) {
            $data['error_desc_limit'] = $this->error['desc_limit'];
        } else {
            $data['error_desc_limit'] = '';
        }

        if (isset($this->error['width'])) {
            $data['error_width'] = $this->error['width'];
        } else {
            $data['error_width'] = '';
        }

        if (isset($this->error['height'])) {
            $data['error_height'] = $this->error['height'];
        } else {
            $data['error_height'] = '';
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

        if (!isset($this->request->get['module_id'])) {
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('extension/module/oct_blog_article', 'token=' . $this->session->data['token'], true)
            );
        } else {
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('extension/module/oct_blog_article', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], true)
            );
        }

        $data['blog_setting'] = $this->url->link('octemplates/blog_setting', 'token=' . $this->session->data['token'], true);

        if (!isset($this->request->get['module_id'])) {
            $data['action'] = $this->url->link('extension/module/oct_blog_article', 'token=' . $this->session->data['token'], true);
        } else {
            $data['action'] = $this->url->link('extension/module/oct_blog_article', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], true);
        }

        $data['token'] = $this->session->data['token'];

        $data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);

        if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $module_info = $this->model_extension_module->getModule($this->request->get['module_id']);
        }

        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } elseif (!empty($module_info)) {
            $data['name'] = $module_info['name'];
        } else {
            $data['name'] = '';
        }

        if (isset($this->request->post['heading'])) {
            $data['heading'] = $this->request->post['heading'];
        } elseif (!empty($module_info) && isset($module_info['heading'])) {
            $data['heading'] = $module_info['heading'];
        } else {
            $data['heading'] = array();
        }

        if (isset($this->request->post['link'])) {
            $data['link'] = $this->request->post['link'];
        } elseif (!empty($module_info) && isset($module_info['link'])) {
            $data['link'] = $module_info['link'];
        } else {
            $data['link'] = array();
        }

        if (isset($this->request->post['limit'])) {
            $data['limit'] = $this->request->post['limit'];
        } elseif (!empty($module_info)) {
            $data['limit'] = $module_info['limit'];
        } else {
            $data['limit'] = 5;
        }

        if (isset($this->request->post['desc_limit'])) {
            $data['desc_limit'] = $this->request->post['desc_limit'];
        } elseif (!empty($module_info)) {
            $data['desc_limit'] = $module_info['desc_limit'];
        } else {
            $data['desc_limit'] = 250;
        }

        if (isset($this->request->post['width'])) {
            $data['width'] = $this->request->post['width'];
        } elseif (!empty($module_info)) {
            $data['width'] = $module_info['width'];
        } else {
            $data['width'] = 25;
        }

        if (isset($this->request->post['height'])) {
            $data['height'] = $this->request->post['height'];
        } elseif (!empty($module_info)) {
            $data['height'] = $module_info['height'];
        } else {
            $data['height'] = 25;
        }

        $data['oct_blogs'] = array();

        if (isset($this->request->post['oct_blog'])) {
            $blogs = $this->request->post['oct_blog'];
        } elseif (!empty($module_info['oct_blog'])) {
            $blogs = $module_info['oct_blog'];
        } else {
            $blogs = array();
        }

        $this->load->model('octemplates/blog_category');

        if ($blogs) {
            foreach ($blogs as $oct_blog_category_id) {
                $blog_info = $this->model_octemplates_blog_category->getCategory($oct_blog_category_id);

                if ($blog_info) {
                    $data['oct_blogs'][] = array(
                        'oct_blog_category_id' => $blog_info['oct_blog_category_id'],
                        'name' => $blog_info['name']
                    );
                }
            }
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($module_info)) {
            $data['status'] = $module_info['status'];
        } else {
            $data['status'] = '';
        }

        if (isset($this->request->post['sort_order'])) {
            $data['sort_order'] = $this->request->post['sort_order'];
        } elseif (!empty($module_info)) {
            $data['sort_order'] = $module_info['sort_order'];
        } else {
            $data['sort_order'] = '';
        }

        if (isset($this->request->post['show_image'])) {
            $data['show_image'] = $this->request->post['show_image'];
        } elseif (!empty($module_info)) {
            $data['show_image'] = $module_info['show_image'];
        } else {
            $data['show_image'] = '';
        }

        $data['languages'] = $this->model_localisation_language->getLanguages();

        $data['header']      = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer']      = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/oct_blog_article.tpl', $data));
    }

	public function install() {
		$this->load->model('extension/extension');
        $this->load->model('setting/setting');
		$this->load->model('octemplates/blog_setting');
		$this->load->model('user/user_group');
        $this->load->model('localisation/language');

		if (!in_array('oct_blog_setting', $this->model_extension_extension->getInstalled('octemplates'))) {
			$this->model_octemplates_blog_setting->installTables();

			$this->model_user_user_group->addPermission($this->user->getId(), 'access', 'octemplates/blog_setting');
			$this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'octemplates/blog_setting');
			$this->model_user_user_group->addPermission($this->user->getId(), 'access', 'octemplates/blog_category');
			$this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'octemplates/blog_category');
			$this->model_user_user_group->addPermission($this->user->getId(), 'access', 'octemplates/blog_article');
			$this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'octemplates/blog_article');
			$this->model_user_user_group->addPermission($this->user->getId(), 'access', 'octemplates/blog_comments');
			$this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'octemplates/blog_comments');

            foreach ($this->model_localisation_language->getLanguages() as $language) {
				$default_language_data[$language['language_id']] = array(
					'seo_title' => '',
					'seo_meta_description' => '',
					'seo_meta_keywords' => '',
					'seo_h1' => '',
					'seo_description' => ''
				);
			}

            $this->model_setting_setting->editSetting('oct_blog_setting', array(
				'oct_blog_setting_data' => array(
                    'status' => '1',
                    'comment_moderation' => '1',
                    'desc_limit' => '280',
                    'main_image_width' => '1000',
                    'main_image_height' => '760',
                    'main_image_popup_width' => '500',
                    'main_image_popup_height' => '500',
                    'sub_image_width' => '228',
                    'sub_image_height' => '228',
                    'r_p_image_width' => '228',
                    'r_p_image_height' => '228',
                    'r_a_image_width' => '258',
                    'r_a_image_height' => '220',
                    'a_image_width_in_category' => '400',
                    'a_image_height_in_category' => '300',
                    'comment_write' => '1',
                    'comment_show' => '1',
                    'c_image_width' => '80',
                    'c_image_height' => '80',
                    'language' => $default_language_data
				)
			));

			$this->model_extension_extension->install('octemplates', 'oct_blog_setting');
		}
	}

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/module/oct_blog_article')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if (!$this->request->post['desc_limit']) {
            $this->error['desc_limit'] = $this->language->get('error_desc_limit');
        }

        if (!$this->request->post['width']) {
            $this->error['width'] = $this->language->get('error_width');
        }

        if (!$this->request->post['height']) {
            $this->error['height'] = $this->language->get('error_height');
        }

        if (is_array($this->request->post['heading'])) {
            foreach ($this->request->post['heading'] as $language_code => $heading) {
                if ((utf8_strlen($heading) < 1) || (utf8_strlen($heading) > 255)) {
                    $this->error['heading'][$language_code] = $this->language->get('error_heading');
                    $this->error['warning']                 = $this->language->get('error_heading');
                }
            }

            foreach ($this->request->post['link'] as $language_code => $link) {
                if ((utf8_strlen($link) < 1) || (utf8_strlen($link) > 1000)) {
                    $this->error['link'][$language_code] = $this->language->get('error_link');
                    $this->error['warning']              = $this->language->get('error_link');
                }
            }
        }

        return !$this->error;
    }
}
