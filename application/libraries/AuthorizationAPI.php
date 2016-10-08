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
    
    
	 /**
	*	@function:	To check if the user is valid staff or not.If valid sends OTP to authorize the login credentials.
	*	@type:		POST
	*	@in-params: phoneNumber
	*	@responseCodes: CHECK_AUTHORIZATION_SUCCESS: 141
	*					CHECK_AUTHORIZATION_FAIL: 142
	*					CHECK_AUTHORIZATION_EXPIRATION_UPDATE_FAIL: 143
	*	
	*/
	
    public function verifyPhone($request)
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
				$smsString = "OTP to login to Register your mobile with TheAdmin App is: $password. Valid for 15 min.";

	        	$this->sendSMS($request->phoneNumber, $smsString);
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
	
	/**
	*	@function:  To validate the login credentials of user , retrieves the user data and updates the Otp status if valid
	*	@type:		POST
	*	@in-params: phoneNumber , Otp
	*	@responseCodes: VALIDATE_OTP_STATUS_UPDATE_SUCCESS: 161
	*					VALIDATE_OTP_STATUS_UPDATE_FAIL: 162
	*					INVALID_OTP: 163
	*	
	*	
	*/
	
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
				$data["msg"] = "otp status update failed. ";
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
	
	
	/**
	*	@function:	To validate the login credentials of user
	*	@type:		POST
	*	@in-params: phoneNumber , Otp
	*	@responseCodes: LOGIN_SUCCESS: 151
	*					USER_DATA_SUCCESS: 152
	*					USER_DATA_FAIL: 153
	*/
	
	
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
				
			$data["userdata"]["staffId"] = $authorization[0]->staff_id;
		    $data["userdata"]["phoneNumber"] = $authorization[0]->phone_number;
		    $data["userdata"]["firstName"] = $authorization[0]->first_name;
		    $data["userdata"]["lastName"] = $authorization[0]->last_name;
		    $data["userdata"]["staffRole"] = $authorization[0]->staff_role;
		    $data["userdata"]["staffImage"] = $authorization[0]->staff_image;
			// $path = $authorization[0]->staff_image;
			// $path = '/home/ubuntu/workspace/TheAdmin-server/application/models/err.png';
			// $type = pathinfo($path, PATHINFO_EXTENSION);
			// $data1 = file_get_contents($path);
			// $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data1);
			//  //$base64 = base64_encode($data1);
			//  echo $base64;
			//  header("Content-type: image/gif");
			// $data = "/9j/4AAQSkZJRgABAQEAYABgAAD........";
			// echo base64_decode($data);
			// 	echo base64_decode(base64_encode('/'.$data1));
			 //echo '<img src="$base64 "/>';
			  //echo '<img src="data:image/gif;base64,' . $data . '" />';
			  //$imageData = base64_encode(file_get_contents($path));
			  ////$src = 'data: '.mime_content_type($image).';base64,'.$imageData;
			  //$src = 'data: ;base64,'.$imageData;
			  //echo '<img src="', $src, '">';
			  
			// $data["userdata"]["staffImage"] = $src;
		    $data["userdata"]["staffStatus"] = $authorization[0]->staff_status;
		}
		else
		{
			$response[TAG_RESULT_CODE] = USER_DATA_FAIL;
			$data["msg"] = "Did not find staff details.";
		}

		$response["data"] = $data;
		return $response;
	}
	
	// function getDataURI($image, $mime = '') {
	// return 'data: '.(function_exists('mime_content_type') ? mime_content_type($image) : $mime).';base64,'.base64_encode(file_get_contents($image));
	// }
	
	public function sendSMS($phone, $msg)
	{
		$url = "http://sms1.brandebuzz.in/API/sms.php"; //http://www.smsstriker.com/API/sms.php?";
        $data = array(
            'username'=> 'APBSEL', //'ucdsyousee',
            'password'=> 'APBSEL', //'SMS_for_UCDS',
            'from'=>'YOUSEE',
            'to'=> "$phone",
            'msg'=>$msg,
            'type'=>1
        );
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
	//End of send SMS.

	}
}