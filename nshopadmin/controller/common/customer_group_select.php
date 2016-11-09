<?php 
class ControllerCommonCustomerGroupSelect extends Controller {
	protected function index() {
		$this->load->language('common/customer_group_select');

                $this->data['entry_customer_group'] = $this->language->get('entry_customer_group');

		$this->load->model('sale/customer_group');
		
		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
		
		$this->id       = 'customer_group_select';
		$this->template = 'common/customer_group_select.tpl';
		
		$this->render();
	}
}
?>