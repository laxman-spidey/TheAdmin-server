<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('/home/ubuntu/workspace/TheAdmin/application/controllers/CustomController.php')
class CustomController extends CI_Controller {
	
	public $TAG_REQUEST_CODE = "requestCode";
	public $TAG_RESULT_CODE = "resultCode";
	
	public function getRequestData()
	{
		$postdata = file_get_contents("php://input");
		return json_decode($postdata);
	}
	
	public function setRequestCodeHeaderToResponse()
	{
		header("$this->TAG_REQUEST_CODE: " . 100 . "");
	}
	public function setResultCode($resultCode)
	{				
		$this->output->set_header(''.$this->TAG_RESULT_CODE .': '. $resultCode .'');
		
	}

}
