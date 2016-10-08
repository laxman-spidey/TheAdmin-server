<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Apptest extends CI_Controller {
	
	public $TAG_HTTP_REQUEST_CODE = "HTTP_REQUESTCODE";
	public $TAG_REQUEST_CODE = "requestCode";
	public $TAG_RESULT_CODE = "resultCode";
	public $WEEKOFF_SHIFTID = '4';
	
	/**
	 * This Page is  used for testing purpose with dummy requests.
	 * 
	 */

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

	public function getAttendanceHistory()
	{
		$request = $this->createDummyHistoryRequest();
		$this->setRequestCodeHeaderToResponse();
		// var_dump($request);
		$this->load->library('AttendanceAPI');
		// $response = $this->attendance->getAttendanceHistory($request);
		$response = $this->attendanceapi->getAttendanceHistory($request);
		$this->setResultCode($response[TAG_RESULT_CODE]);
		// $this->setSuccess($response["success"]);
		echo json_encode($response["data"]);
	}	
	
	
	/*
	*	@url:		/welcome/checkin
	*	@function:	when the user is entered work location and checks in the time for the day.
	*	@type:		POST
	*	@requestCode:  
	*	@in-params: staffId, shiftId, date, timeIn 
	*	@responseCodes: 
	*	
	*/
	public function checkin()
	{
		// echo LOGIN_SUCCESS;
		$request = $this->createDummyCheckinRequest();
		$this->setRequestCodeHeaderToResponse();
		$this->load->library('AttendanceAPI');
		$response = $this->attendanceapi->checkin($request);
		$this->setResultCode($response[TAG_RESULT_CODE]);
		echo json_encode($response["data"]);
	}
	
	
	public function checkout()
	{
		$request = $this->createDummyCheckoutRequest();
		$this->setRequestCodeHeaderToResponse();
		$this->load->library('AttendanceAPI');
		$response = $this->attendanceapi->checkout($request);
		$this->setResultCode($response[TAG_RESULT_CODE]);
		echo json_encode($response["data"]);
	}
	
	public function getRoasterDetails()
	{
		$request = $this->createDummyRoasterRequest();
		$this->setRequestCodeHeaderToResponse();
		$this->load->library('AttendanceAPI');
		$response = $this->attendanceapi->getRoasterDetails($request);
		$this->setResultCode($response[TAG_RESULT_CODE]);
		echo json_encode($response["data"]);
	}
	
	public function verifyPhone()
	{
		$request = $this->createAuthorizationDummyRequest();
		$this->setRequestCodeHeaderToResponse();
		$this->load->library('AuthorizationAPI');
		$response = $this->authorizationapi->verifyPhone($request);
		$this->setResultCode($response[TAG_RESULT_CODE]);
		echo json_encode($response["data"]);
	}
	
	public function validateotp()
	{
		$request = $this->createValidateOtpDummyRequest();
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
	
	public function userData()
	{
		$request = $this->createUserDummyRequest();
		$this->setRequestCodeHeaderToResponse();
		$this->load->library('AuthorizationAPI');
		$response = $this->authorizationapi->userData($request);
		$this->setResultCode($response[TAG_RESULT_CODE]);
		echo json_encode($response["data"]);
	}
	
		public function applyLeave()
	{
		$request = $this->createLeaveApplyDummyRequest();
		$this->setRequestCodeHeaderToResponse();
		$this->load->library('LeavesAPI');
		$response = $this->leavesapi->applyLeave($request);
		$this->setResultCode($response[TAG_RESULT_CODE]);
		echo json_encode($response["data"]);
	}
	
		public function checkLeave()
	{
		$request = $this->createLeaveCheckDummyRequest();
		$this->setRequestCodeHeaderToResponse();
		$this->load->library('LeavesAPI');
		$response = $this->leavesapi->checkLeave($request);
		$this->setResultCode($response[TAG_RESULT_CODE]);
		echo json_encode($response["data"]);
	}
	
	public function leavesSummary()
	{
		$request = $this->createLeaveSummaryDummyRequest();
		$this->setRequestCodeHeaderToResponse();
		$this->load->library('LeavesAPI');
		$response = $this->leavesapi->leavesSummary($request);
		$this->setResultCode($response[TAG_RESULT_CODE]);
		echo json_encode($response["data"]);
	}
	
	public function showSwap()
	{
		$request = $this->createShowSwapDummyRequest();
		$this->setRequestCodeHeaderToResponse();
		$this->load->library('SwapsAPI');
		$response = $this->swapsapi->showSwap($request);
		$this->setResultCode($response[TAG_RESULT_CODE]);
		echo json_encode($response["data"]);
	}
	
	public function applySwapByEligibility()
	{
		$request = $this->createApplySwapDummyRequest();
		$this->setRequestCodeHeaderToResponse();
		$this->load->library('SwapsAPI');
		$response = $this->swapsapi->applySwapByEligibility($request);
		$this->setResultCode($response[TAG_RESULT_CODE]);
		echo json_encode($response["data"]);
	}
	
	public function swapStatus()
	{
		$request = $this->createSwapStatusDummyRequest();
		$this->setRequestCodeHeaderToResponse();
		$this->load->library('SwapsAPI');
		$response = $this->swapsapi->swapStatus($request);
		$this->setResultCode($response[TAG_RESULT_CODE]);
		echo json_encode($response["data"]);
	}
	
	private function loadModel($model)
    {
    	$CI =& get_instance();
		$CI->load->model($model);
		return $CI->$model;
    }
	/* creates dummy checkin request */
	private function createDummyCheckinRequest()
	{
		$_SERVER[$this->TAG_HTTP_REQUEST_CODE] = "100";
		$request = array();
		$request["staffId"] = 1;
		// $request["shiftId"] = 1;
		$request["date"] = '2016-10-04';
		// $request["date"] = date("Y-m-d"); //"00-00-0000";
		$request["timeIn"] = date("Y-m-d H:i:s");//"08:00:00";
		//var_dump($request);
		return Json_decode(json_encode($request));
	}
	
	/* creates dummy checkin request */
	private function createDummyCheckoutRequest()
	{
		$_SERVER[$this->TAG_HTTP_REQUEST_CODE] = "110";
		$request = array();
		$request["staffId"] = 1;
		$request["date"] = '2016-10-04';
		// $request["shiftId"] = 1;
		// $request["date"] = date("Y-m-d"); //"00-00-0000";
		$request["timeOut"] = date("Y-m-d H:i:s");//"08:00:00";
		//var_dump($request);
		return Json_decode(json_encode($request));
	}
	/* creates dummy checkin request */
	private function createDummyHistoryRequest()
	{
		$_SERVER[$this->TAG_HTTP_REQUEST_CODE] = "130";
		$request = array();
		$request["staffId"] = 1;
		$request["limit"] = 10;
		// $request["fromDate"] = '2016-10-01';
		// $request["toDate"] = '2016-10-02';
		
		
		var_dump($request);	
		return Json_decode(json_encode($request));
	}
	private function createDummyRoasterRequest()
	{
		$_SERVER[$this->TAG_HTTP_REQUEST_CODE] = "120";
		$request = array();
		$request["staffId"] = 1;
		$request["limit"] = 10;
		$request["fromDate"] = '2016-10-01';
		$request["toDate"] = '2016-10-02';
		
		
		// var_dump($request);	
		return Json_decode(json_encode($request));
	}
	
	private function createAuthorizationDummyRequest()
	{
		$_SERVER[$this->TAG_HTTP_REQUEST_CODE] = "140";
		$request = array();
		$request["phoneNumber"] = 9505878984;
		return Json_decode(json_encode($request));
		
	}
	
	private function createValidateOtpDummyRequest()
	{
		$_SERVER[$this->TAG_HTTP_REQUEST_CODE] = "160";
		$request = array();
		$request["phoneNumber"] = 9505878984;
		$request["otp"] = 123456;
		return Json_decode(json_encode($request));
		
	}
	
	private function createUserDummyRequest()
	{
		$_SERVER[$this->TAG_HTTP_REQUEST_CODE] = "150";
		$request = array();
		$request["phoneNumber"] = 9505878984;
		$request["otp"] = 123456;
		return Json_decode(json_encode($request));
		
	}
	
	private function createLeaveApplyDummyRequest()
	{
		$_SERVER[$this->TAG_HTTP_REQUEST_CODE] = "170";
		$request = array();
		$request["staffId"] = 1;
		$request["leaveDate"] = "2016-09-21";
 		$request["leaveTypeID"] = 1;
		
		return Json_decode(json_encode($request));
	}
	
	private function createLeaveCheckDummyRequest()
	{
		$_SERVER[$this->TAG_HTTP_REQUEST_CODE] = "180";
		$request = array();
		$request["staffId"] = 10;
		$request["listLimit"] = 10;
 		$request["status"] = 0; //completed , pending
		return Json_decode(json_encode($request));
		
	}
	
	private function createLeaveSummaryDummyRequest()
	{
		$_SERVER[$this->TAG_HTTP_REQUEST_CODE] = "190";
		$request = array();
		$request["staffId"] = 6;
		
		return Json_decode(json_encode($request));
		
	}
	
	private function createShowSwapDummyRequest()
	{
		$_SERVER[$this->TAG_HTTP_REQUEST_CODE] = "200";
		$request = array();
		$request["swapDate"] = "2016-09-12";
		$request["shiftId"] = 1;
 	
		
		return Json_decode(json_encode($request));
	}
	
	private function createApplySwapDummyRequest()
	{
		$_SERVER[$this->TAG_HTTP_REQUEST_CODE] = "210";
		$request = array();
		$request["reqRoasterId"] = 14;
		$request["reqShiftId"] = 2;
		$request["reqSwapDate"] = '2016-09-22';
		$request["reqSwapTo"] = 3;
 		$request["staffId"] = 6;
 		// $request["acceptedRoasterId"] = 13;
		
		return Json_decode(json_encode($request));
	}
	
	private function createSwapStatusDummyRequest()
	{
		$_SERVER[$this->TAG_HTTP_REQUEST_CODE] = "220";
		$request = array();
		$request["swapStatus"] = 5;
 		$request["acceptStaffId"] = 5;
		$request["reqSwapDate"] = '2016-09-22';
		$request["reqRoasterId"] = 14;
		$request["acceptRoasterId"] = 13;
		$request["reqSwapId"] = 26;
		return Json_decode(json_encode($request));
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
