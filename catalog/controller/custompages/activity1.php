<?php 
class ControllerCustompagesActivity1 extends Controller {

	public function index() {

	// set title of the page
    $this->document->setTitle("上海交通大学纪念品商城-活动1");

    // define template file

	$data['column_left'] = $this->load->controller('common/column_left');
	$data['column_right'] = $this->load->controller('common/column_right');
	$data['content_top'] = $this->load->controller('common/content_top');
	$data['content_bottom'] = $this->load->controller('common/content_bottom');
	$data['footer'] = $this->load->controller('common/footer');
	$data['header'] = $this->load->controller('common/header');
    
    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/custompages/custompagescommon.tpl')) 
    {
		$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/custompages/custompagescommon.tpl', $data));
	} 
	else 
	{
		$this->response->setOutput($this->load->view('default/template/custompages/custompagescommon.tpl', $data));
	}
	
  }

}
?>
