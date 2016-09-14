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
		//echo "checkauthorization";
		$authorizationModel = $this->loadModel('AuthorizationModel');
		$authorization = $authorizationModel->checkAuthorization($request->phoneNumber);
		if($authorization != null)
		{
			$response["responseCode"] = CHECK_AUTHORIZATION_SUCCESS;
			$expireOtp = $authorizationModel->expireOtp( $request->phoneNumber);
			//echo "after expire";
			if($expireOtp >= 0) 
			{
		
	      		//echo "otp generation";
	    		$string = '0123456789';
	    		$string_shuffled = str_shuffle($string);
	    		$password = substr($string_shuffled, 1, 6);
	
	    		file_get_contents("http://login.smsgatewayhub.com/smsapi/pushsms.aspx?user=abc&pwd=$password&to=919898123456&sid=senderid&msg=test%20message&fl=0");
	
	        	$generateOtp = $authorizationModel->otpGeneration( $request->phoneNumber,$password);
	        	
				$data["msg"] = "You are authorized and otp will be sending ";
			}
			else
			{
			  	$response["responseCode"] = CHECK_AUTHORIZATION_FAIL;
				$data["msg"] = "You are not authorized.Please enter valid credentials"; 
				
			}
		}
		else
		{
			$response["responseCode"] = CHECK_AUTHORIZATION_EXPIRATION_UPDATE_FAIL;
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
		    $response["responseCode"] = LOGIN_SUCCESS;
			$data["msg"] = "otp is valid and you are logged in ";
			$otpUpdation = $authorizationModel->updateOtpStatus( $request->phoneNumber,$request->otp);
			if($otpUpdation >= 0)
			{
				$response["responseCode"] = VALIDATE_OTP_STATUS_UPDATE_SUCCESS;
				$data["msg"] = "otp status updated ";
			}
			else
			{
				$response["responseCode"] = VALIDATE_OTP_STATUS_UPDATE_FAIL;
				$data["msg"] = "otp status updation failed. ";
			}

		}
		
		else
		{
		  	$response["responseCode"] = LOGIN_FAIL;
			$data["msg"] = "Otp you have entered is wrong.Please enter valid otp"; 
			
		}
		$response["data"] = $data;
		return $response;
	}
	
	public function userData($request)
	{
	    $data = array();
		$response = array();
		//echo "userData";
		$authorizationModel = $this->loadModel('AuthorizationModel');
		$validation = $authorizationModel->validateOtp($request->phoneNumber,$request->otp);
		if($validation != null)
		{
			//echo "inside validateOtp";
		    //var_dump($validation);
			$response["responseCode"] = LOGIN_SUCCESS;
			$data["msg"] = "otp is valid and you are logged in ";
			$authorization = $authorizationModel->checkAuthorization( $request->phoneNumber);
			if($authorization != null)
			{
				$response["responseCode"] = USER_DATA_SUCCESS;
				$data["authorization"] = array();
				
				$data["authorization"]["staff_id"] = $authorization[0]->staff_id;
			    $data["authorization"]["phone_number"] = $authorization[0]->phone_number;
			    $data["authorization"]["staff_name"] = $authorization[0]->staff_name;

			}
			else
			{
				$response["responseCode"] = USER_DATA_FAIL;
				$data["msg"] = "Did not find staff details.checking";
			}

		}
		
		else
		{
			//echo "outside validateOtp";
		  	$response["responseCode"] = LOGIN_FAIL;
			$data["msg"] = "Otp you have entered is wrong.Please enter valid otp"; 
			
		}
		$response["data"] = $data;
		return $response;
	}
	
	
	
}