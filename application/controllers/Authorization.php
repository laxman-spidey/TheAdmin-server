<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authorization extends CI_Controller {
	
	public $TAG_HTTP_REQUEST_CODE = "HTTP_REQUESTCODE";
	public $TAG_REQUEST_CODE = "requestCode";
	public $TAG_RESULT_CODE = "resultCode";

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	 
	public function checkauthorization()
	{
		$request = $this->getRequestData();
		$this->setRequestCodeHeaderToResponse();
		$this->load->library('AuthorizationAPI');
		$response = $this->authorizationapi->checkauthorization($request);
		$this->setResultCode($response["responseCode"]);
		echo json_encode($response["data"]);
	}
	
	public function validateotp()
	{
		$request = $this->getRequestData();
		$this->setRequestCodeHeaderToResponse();
		$this->load->library('AuthorizationAPI');
		$response = $this->authorizationapi->validateotp($request);
		$this->setResultCode($response["responseCode"]);
		echo json_encode($response["data"]);
	}
	
	public function userData()
	{
		$request = $this->getRequestData();
		$this->setRequestCodeHeaderToResponse();
		$this->load->library('AuthorizationAPI');
		$response = $this->authorizationapi->userData($request);
		$this->setResultCode($response["responseCode"]);
		echo json_encode($response["data"]);
	}
	
	
	private function getRequestData()
	{
		$postdata = file_get_contents("php://input");
		return json_decode($postdata);
	}
	
	private function setRequestCodeHeaderToResponse()
	{
		// $requestCodeArray = $this->input->get_request_header($this->TAG_REQUEST_CODE, TRUE);
		// var_dump($requestCodeArray);
		// $requestCode = $requestCodeArray[0];
		// echo "---------------------------- $requestCode ----------------------";
		header("$this->TAG_REQUEST_CODE: " . $_SERVER['HTTP_REQUESTCODE']  . "");
	}
	private function setSuccess($success)
	{
		header("success:".$success);
	}
	private function setResultCode($resultCode)
	{				
		$this->output->set_header(''.$this->TAG_RESULT_CODE .': '. $resultCode .'');
		
	}
		

}