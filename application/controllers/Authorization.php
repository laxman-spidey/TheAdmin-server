<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authorization extends CI_Controller {
	
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/Authorization
	 *	- or -
	 * 		http://example.com/index.php/Authorization/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/Authorization/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	 
	 /**
	*	@url:		/Authorization/verifyPhone
	*	@function:	To check if the user is valid staff or not.If valid sends OTP to authorize the login credentials.
	*	@type:		POST
	*	@requestCode:
	*	@in-params: phoneNumber
	*	@responseCodes: CHECK_AUTHORIZATION_SUCCESS: 141
	*					CHECK_AUTHORIZATION_FAIL: 142
	*					CHECK_AUTHORIZATION_EXPIRATION_UPDATE_FAIL: 143
	*	
	*/
	
	
	public function verifyPhone()
	{
		$request = $this->getRequestData();
		$this->setRequestCodeHeaderToResponse();
		$this->load->library('AuthorizationAPI');
		$response = $this->authorizationapi->verifyPhone($request);
		$this->setResultCode($response[TAG_RESULT_CODE]);
		echo json_encode($response["data"]);
	}
	
	public function verifyPhoneDebug()
	{
		$request = $this->getRequestData();
		$this->setRequestCodeHeaderToResponse();
		$this->load->library('AuthorizationAPI');
		$response = $this->authorizationapi->verifyPhone($request);
		$this->setResultCode($response[TAG_RESULT_CODE]);
		echo json_encode($response["data"]);
	}
	
	 /**
	*	@url:		/Authorization/validateotp
	*	@function:  To validate the login credentials of user , retrieves the user data and updates the Otp status if valid
	*	@type:		POST
	*	@requestCode:
	*	@in-params: phoneNumber , Otp
	*	@responseCodes: VALIDATE_OTP_STATUS_UPDATE_SUCCESS: 161
	*					VALIDATE_OTP_STATUS_UPDATE_FAIL: 162
	*					INVALID_OTP: 163
	*	
	*	
	*/
	
	public function validateotp()
	{
		$request = $this->getRequestData();
		$this->setRequestCodeHeaderToResponse();
		$this->load->library('AuthorizationAPI');
		$response = $this->authorizationapi->validateotp($request);
		if($response[TAG_RESULT_CODE] == LOGIN_SUCCESS)
		{
			$userData = $this->authorizationapi->userData($request);
			$this->setResultCode($userData[TAG_RESULT_CODE]);
			$response = $userData;
		}
		else 
		{
			$this->setResultCode($response[TAG_RESULT_CODE]);
		}
		echo json_encode($response["data"]);
	}
	
	/**
	*	@url:		/Authorization/userData
	*	@function:	To validate the login credentials of user
	*	@type:		POST
	*	@requestCode:
	*	@in-params: phoneNumber , Otp
	*	@responseCodes: LOGIN_SUCCESS: 151
	*					USER_DATA_SUCCESS: 152
	*					USER_DATA_FAIL: 153
	*/
	
	public function userData()
	{
		$request = $this->getRequestData();
		$this->setRequestCodeHeaderToResponse();
		$this->load->library('AuthorizationAPI');
		$response = $this->authorizationapi->userData($request);
		$this->setResultCode($response[TAG_RESULT_CODE]);
		echo json_encode($response["data"]);
	}
	
	
	private function getRequestData()
	{
		$postdata = file_get_contents("php://input");
		return json_decode($postdata);
	}
	
	
	private function setRequestCodeHeaderToResponse()
	{
		header("".TAG_REQUEST_CODE.": " . $_SERVER['HTTP_REQUESTCODE']  . "");
	}
	private function setSuccess($success)
	{
		header("success:".$success);
	}
	private function setResultCode($resultCode)
	{				
		header("".TAG_RESULT_CODE.": " . $resultCode  . "");
	}
		

}