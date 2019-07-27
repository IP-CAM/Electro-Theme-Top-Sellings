<?php
/** 
 * 
 * electrothemetopsellings.php
 * 
 * ControllerExtensionModuleElectrothemetopsellings
 * 
 * Controller file for a front page.
 * 
*/
class ControllerExtensionModuleElectrothemetopsellings extends Controller
{
	/** 
	 * 
	 * index()
	 * 
	*/
	public function index($settings)
	{
		$data=array();
		$this->load->language('extension/module/electrothemetopsellings');

		$this->load->model('extension/module/electrothemetopsellings');
		$currency=$this->session->data['currency'];

		$data=$this->model_extension_module_electrothemetopsellings->getProducts($settings['quantity'],$currency);

		return $this->load->view('extension/module/electrothemetopsellings', $data);
	}
}