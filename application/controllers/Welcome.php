<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
	
	public $TAG_HTTP_REQUEST_CODE = "HTTP_REQUESTCODE";
	public $TAG_REQUEST_CODE = "requestCode";
	public $TAG_RESULT_CODE = "resultCode";
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
	
	public function getAttendanceHistory()
	{
		$request = $this->getRequestData();
		$this->setRequestCodeHeaderToResponse();
		var_dump($request);
		$this->load->library('Attendance');
		var_dump($request);
		$response = $this->attendance->getAttendanceHistory($request);
		
		$this->setResultCode($response["responseCode"]);
		$this->setSuccess($response["success"]);
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
		$request = $this->getRequestData();
		$this->setRequestCodeHeaderToResponse();
		$this->load->library('AttendanceAPI');
		$response = $this->attendanceAPI->checkin($request);
		$this->setResultCode($response["responseCode"]);
		echo json_encode($response["data"]);
	}
	public function checkout()
	{
		$request = $this->getRequestData();
		$this->setRequestCodeHeaderToResponse();
		$this->load->library('AttendanceAPI');
		$response = $this->attendanceAPI->checkout($request);
		$this->setResultCode($response["responseCode"]);
		echo json_encode($response["data"]);
	}
	public function getRoasterDetails()
	{
		$request = $this->getRequestData();
		$this->setRequestCodeHeaderToResponse();
		$this->load->library('AttendanceAPI');
		$response = $this->attendanceAPI->getRoasterDetails($request);
		$this->setResultCode($response["responseCode"]);
		echo json_encode($response["data"]);
	}
	
	
	private function loadModel($model)
    {
    	$CI =& get_instance();
		$CI->load->model($model);
		return $CI->$model;
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
