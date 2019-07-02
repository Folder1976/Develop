<?php
class ControllerExtensionModuleMultycurrgoods extends Controller {

	public function calculate_ajax(){
        $this->load->model('extension/multycurrgoods');
        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {

            $this->model_extension_multycurrgoods->calculate_product_extra_charge_prices($this->request->post);

        }
    }
}