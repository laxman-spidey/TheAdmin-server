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
		//echo "checkauthorization";
		$request = $this->getRequestData();
		//$request = $this->createAuthorizationDummyRequest1();
		$this->setRequestCodeHeaderToResponse(701);
		$response = array();
		$this->load->model('AuthorizationModel');
		$authorization = $this->AuthorizationModel->checkAuthorization($request->phoneNumber);
		if($authorization != null)
		{
			$this->setResultCode(702);
		
      		//echo "otp generation";
    		$string = '0123456789';
    		$string_shuffled = str_shuffle($string);
    		$password = substr($string_shuffled, 1, 6);

    		file_get_contents("http://login.smsgatewayhub.com/smsapi/pushsms.aspx?user=abc&pwd=$password&to=919898123456&sid=senderid&msg=test%20message&fl=0");

        	$generateOtp = $this->AuthorizationModel->otpGeneration( $request->phoneNumber,$password);
        	
			$response["msg"] = "You are authorized and otp will be sending ";
		}
		else
		{
		  	$this->setResultCode(703);
			$response["msg"] = "You are not authorized.Please enter valid credentials"; 
			
		}
		echo json_encode($response);
	}
	public function validateotp()
	{
		//echo "validateotp";
		$request = $this->getRequestData();
		//$request = $this->createValidateOtpDummyRequest();
		$this->setRequestCodeHeaderToResponse(704);
		$response = array();
		$this->load->model('AuthorizationModel');
		$validation = $this->AuthorizationModel->validateOtp($request->phoneNumber,$request->otp);
		if($validation != null)
		{
		    //var_dump($leaves);
			$this->setResultCode(705);
			$response["msg"] = "otp is valid and you are logged in ";
			$otpUpdation = $this->AuthorizationModel->updateOtpStatus( $request->phoneNumber,$request->otp);
			if($otpUpdation >= 0)
			{
				$this->setResultCode(706);
				$response["msg"] = "otp status updated ";
			}
			else
			{
				$this->setResultCode(707);
				$response["msg"] = "otp status updation failed. ";
			}

		}
		
		else
		{
		  	$this->setResultCode(708);
			$response["msg"] = "Otp you have entered is wrong.Please enter valid otp"; 
			
		}
		echo json_encode($response);
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
		private function createAuthorizationDummyRequest1()
	{
		$_SERVER[$this->TAG_HTTP_REQUEST_CODE] = "700";
		$request = array();
		$request["phoneNumber"] = 9505878984;
		return Json_decode(json_encode($request));
		
	}
	private function createValidateOtpDummyRequest()
	{
		$_SERVER[$this->TAG_HTTP_REQUEST_CODE] = "800";
		$request = array();
		$request["phoneNumber"] = 9505878984;
		$request["otp"] = 359042;
		return Json_decode(json_encode($request));
		
	}
	
}