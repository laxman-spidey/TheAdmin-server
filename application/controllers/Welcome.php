<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
	
	/**
	 * @var type $WEEKOFF_SHIFTID is constant value for week off (value used in Database) ;
	 */
	public $WEEKOFF_SHIFTID = '4';

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
	 
	 
	public function index()
	{
		$this->load->view('welcome_message');
	}
	
	/**
	*	@url:	/welcome/getAttendanceHistory
	*	@function:	To retrive the attendance of the user based on limit entered.
	*	@type:		POST
	*	@requestCode: 
	*	@in-params: staffId, limit 
	*	@responseCodes: ATTENDANCE_HISTORY_NOT_EXIST:131
	*					ATTENDANCE_HISTORY_EXIST:132
	*	
	*/
	
	public function getAttendanceHistory()
	{
		$request = $this->getRequestData();
		$this->setRequestCodeHeaderToResponse();
		$this->load->library('AttendanceAPI');
		$response = $this->attendanceapi->getAttendanceHistory($request);
		$this->setResultCode($response[TAG_RESULT_CODE]);
		// $this->setSuccess($response["success"]);
		echo json_encode($response["data"]);
	}	
	
	
	/**
	*	@url:		/welcome/checkin
	*	@function:	when the user is entered work location and checks in the time for the day.
	*	@type:		POST
	*	@requestCode:
	*	@in-params: staffId, shiftId, date, timeIn 
	*	@responseCodes: CHECKIN_ALREADY_CHECKEDIN:101
	*					CHECKIN_SUCCESS:102 
	*					CHECKIN_INSERT_DBERROR:103
	*					INFO_WEEKOFF:104
	*					WARNING_ROASTER_DOES_NOT_EXIST:105
	*	
	*/
	
	public function checkin()
	{
		$request = $this->getRequestData();
		$this->setRequestCodeHeaderToResponse();
		$this->load->library('AttendanceAPI');
		$response = $this->attendanceapi->checkin($request);
		$this->setResultCode($response[TAG_RESULT_CODE]);
		echo json_encode($response["data"]);
	}
	
	/**
	*	@url:		/Welcome/checkout
	*	@function:	when the user is leaving from work location and checks out the time for the day.
	*	@type:		POST
	*	@requestCode: 
	*	@in-params: staffId, date, timeOut
	*	@responseCodes: CHECKOUT_SUCCESS:111
	*					CHECKOUT_INSERT_DBERROR:112 
	*					CHECKOUT_NOT_CHECKEDIN:113
	*					CHECKOUT_INSERT_DBERROR:114
	*	
	*/
	
	public function checkout()
	{
		$request = $this->getRequestData();
		$this->setRequestCodeHeaderToResponse();
		$this->load->library('AttendanceAPI');
		$response = $this->attendanceapi->checkout($request);
		$this->setResultCode($response[TAG_RESULT_CODE]);
		echo json_encode($response["data"]);
	}
	
	/**
	*	@url:		/Welcome/getRoasterDetails
	*	@function:	To retrieve the roaster details of user for 5days (today, past 2 days and future 2 days)
	*	@type:		POST
	*	@requestCode:
	*	@in-params: staffId, limit 
	*	@responseCodes: ROASTER_DETAILS_NOT_EXIST: 121
	*					ROASTER_DETAILS_EXIST: 122
	*	
	*/
	
	public function getRoasterDetails()
	{
		$request = $this->getRequestData();
		$this->setRequestCodeHeaderToResponse();
		$this->load->library('AttendanceAPI');
		$response = $this->attendanceapi->getRoasterDetails($request);
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
		$this->output->set_header(''.TAG_RESULT_CODE .': '. $resultCode .'');
	}
	
}
