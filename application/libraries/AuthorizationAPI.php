<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AuthorizationAPI {
    
	public $TAG_REQUEST_CODE = "requestCode";
	public $TAG_RESULT_CODE = "resultCode";

	private $CI;
    public function __construct()
    {
    	$this->CI =& get_instance();
    }
    private function loadModel($model)
    {
    	$CI =& get_instance();
		$CI->load->model($model);
		return $CI->$model;
    }
    
    public function checkauthorization($request)
	{
	    $data = array();
		$response = array();
		$authorizationModel = $this->loadModel('AuthorizationModel');
		$authorization = $authorizationModel->checkAuthorization($request->phoneNumber);
		if($authorization != null)
		{
			$expireOtp = $authorizationModel->expireOtp( $request->phoneNumber);
			if($expireOtp >= 0) 
			{
	    		$string = '0123456789';
	    		$string_shuffled = str_shuffle($string);
	    		$password = substr($string_shuffled, 1, 6);
	
	    		file_get_contents("http://login.smsgatewayhub.com/smsapi/pushsms.aspx?user=abc&pwd=$password&to=919898123456&sid=senderid&msg=test%20message&fl=0");
	
	        	$generateOtp = $authorizationModel->otpGeneration( $request->phoneNumber,$password);
	        	$response[TAG_RESULT_CODE] = CHECK_AUTHORIZATION_SUCCESS;
				$data["msg"] = "You are authorized and otp will be sending ";
			}
			else
			{
			  	$response[TAG_RESULT_CODE] = CHECK_AUTHORIZATION_FAIL;
				$data["msg"] = "You are not authorized.Please enter valid credentials"; 
				
			}
		}
		else
		{
			$response[TAG_RESULT_CODE] = CHECK_AUTHORIZATION_EXPIRATION_UPDATE_FAIL;
			$data["msg"] = "otp expiration status update failed"; 
		}
		$response["data"] = $data;
		return $response;
	}
	
	public function validateotp($request)
	{
	    $data = array();
		$response = array();
		$authorizationModel = $this->loadModel('AuthorizationModel');
		$validation = $authorizationModel->validateOtp($request->phoneNumber,$request->otp);
		if($validation != null)
		{
			$otpUpdation = $authorizationModel->updateOtpStatus( $request->phoneNumber,$request->otp);
			if($otpUpdation >= 0)
			{
				$response[TAG_RESULT_CODE] = LOGIN_SUCCESS;
				$data["msg"] = "otp is valid and you are logged in ";
			}
			else
			{
				$response[TAG_RESULT_CODE] = VALIDATE_OTP_STATUS_UPDATE_FAIL;
				$data["msg"] = "otp status updation failed. ";
			}

		}
		
		else
		{
		  	$response[TAG_RESULT_CODE] = INVALID_OTP;
			$data["msg"] = "Otp you have entered is wrong.Please enter valid otp"; 
			
		}
		$response["data"] = $data;
		return $response;
	}
	
	public function userData($request)
	{
	    $data = array();
		$response = array();
		$authorizationModel = $this->loadModel('AuthorizationModel');
		$authorization = $authorizationModel->checkAuthorization( $request->phoneNumber);
		if($authorization != null)
		{
			$response[TAG_RESULT_CODE] = USER_DATA_SUCCESS;
			$data["userdata"] = array();
				
			$data["userdata"]["staff_id"] = $authorization[0]->staff_id;
		    $data["userdata"]["phone_number"] = $authorization[0]->phone_number;
		    $data["userdata"]["staff_name"] = $authorization[0]->staff_name;
		}
		else
		{
			$response[TAG_RESULT_CODE] = USER_DATA_FAIL;
			$data["msg"] = "Did not find staff details.";
		}

		$response["data"] = $data;
		return $response;
	}
	
	
	
	
}