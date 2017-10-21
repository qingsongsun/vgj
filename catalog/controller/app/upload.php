<?php
/**
* app上传图片
*/
class ControllerAppUpload extends Controller{
  private $error     = array();
  private $json_data = array();
  private $json      = array();

  public function index(){
    $this->json_data=json_decode(file_get_contents("php://input"),true);
    $target_path = DIR_IMAGE;
    $target_path = $target_path . basename( $_FILES['uploadedfile']['name']);

    // 微信小程序专用上图
    $target_path_wx = $target_path . time().basename($_FILES['file']['name']);
    if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path_wx)) {
      $this->json['success']=basename( $_FILES['file']['name']). " success";
      $this->json['image_path']=time().$_FILES['file']['name'];
    }  else{
      $this->json['success']="fail" . $_FILES['file']['error'];
    }

    if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
      $this->json['success']=basename( $_FILES['uploadedfile']['name']). " success";
      $this->json['image_path']=$_FILES['uploadedfile']['name'];
    }  else{
      $this->json['success']="fail" . $_FILES['uploadedfile']['error'];
    }

    $this->response->addHeader('Content-Type:application/json');
    $this->response->setOutput(json_encode($this->json));
    // $this->response->setOutput($this->json);
  }
}

?>


