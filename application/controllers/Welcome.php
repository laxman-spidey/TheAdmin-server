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
		
		$request = $this->createDummyHistoryRequest();
		// $request = $this->getRequestData();
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
	// public function checkin()
	// {
	// 	//$request = getRequestData();
	// 	$request = $this->createDummyCheckinRequest();
	// 	$this->setRequestCodeHeaderToResponse();
		
		
	// 	$this->load->model('AttendanceModel');
	// 	$checkedInAlready = $this->AttendanceModel->isCheckedInAlready($request->staffId, $request->date);
	// 	if($checkedInAlready != null)
	// 	{
	// 		//If the user is checked in for the already, he is not allowed to update later timing unless the admin wants to.
	// 		$this->setResultCode(101);
	// 		$response["msg"] = "You have already checked in at " .$attendance[0]->time_in;
	// 	}
	// 	else
	// 	{
	// 		$attendanceId = $this->AttendanceModel->checkin($request->staffId, $request->date, $request->shiftId, $request->timeIn);	
	// 		if($attendanceId > 0)
	// 		{
	// 			$this->setResultCode(100);
	// 			$response["msg"] = "Attendance registered Successfully.";
	// 		}
	// 		else
	// 		{
	// 			$this->setResultCode(102);
	// 			$response["msg"] = "Error 102: Something went wrong. Try again or report the issue to admin";
	// 		}
	// 	} 
	// 	echo json_encode($response);	
	// }
	
	
	public function checkin()
	{
		$request = $this->createDummyCheckinRequest();
		$this->setRequestCodeHeaderToResponse();

		$this->load->model('AttendanceModel');
		$roaster = $this->AttendanceModel->checkRoaster($request->staffId, $request->date);
		if($roaster != null )
		{
			//handling if the user is on week off
			if($roaster->shift_id != $this->AttendanceModel->WEEKOFF_SHIFTID)
			{
				echo "roaster id fetched";
				$checkedInAlready = $this->AttendanceModel->isCheckedInAlready($roaster->roaster_id);
				if($checkedInAlready != null)
				{
					//If the user is checked in for the already, he is not allowed to update later timing unless the admin wants to.
					$this->setResultCode(101);
					$response["msg"] = "You have already checked in at " .$checkedInAlready[0]->time_in;
				}
				else
				{
					$attendanceId = $this->AttendanceModel->checkin($roaster->roaster_id,  $request->timeIn);	
					if($attendanceId > 0)
					{
						$this->setResultCode(100);
						$response["msg"] = "Attendance registered Successfully.";
					}
					else
					{
						$this->setResultCode(102);
						$response["msg"] = "Error 102: Something went wrong. Try again or report the issue to admin";
					}
				}
			}
			else
			{
				$this->setResultCode(104);
				$response["msg"] = "It's your week off.Please contact administrator for registering attendance ";
			}
		}	
		else 
		{
			$this->setResultCode(105);
			$response["msg"] = "You are not in roaster id.Please contact administrator to register your attendance";
		}

					echo json_encode($response);
		
	}
	
	// public function checkin1()
	// {
	// 	//$request = getRequestData();
	// 	$request = $this->createDummyCheckinRequest();
	// 	$this->setRequestCodeHeaderToResponse();
		
		
	// 	$this->load->model('AttendanceModel');
	// 	$checkedInAlready = $this->AttendanceModel->isCheckedInAlready($request->staffId, $request->date);
	// 	if($checkedInAlready != null)
	// 	{
	// 		//If the user is checked in for the already, he is not allowed to update later timing unless the admin wants to.
	// 		$this->setResultCode(101);
	// 		$response["msg"] = "You have already checked in at " .$checkedInAlready[0]->time_in;
	// 	}
	// 	else
	// 	{
	// 		$attendanceId = $this->AttendanceModel->checkin($request->staffId, $request->date,  $request->timeIn);	
	// 		if($attendanceId > 0)
	// 		{
	// 			$this->setResultCode(100);
	// 			$response["msg"] = "Attendance registered Successfully.";
	// 		}
	// 		else
	// 		{
	// 			$this->setResultCode(102);
	// 			$response["msg"] = "Error 102: Something went wrong. Try again or report the issue to admin";
	// 		}
	// 	} 
	// 	echo json_encode($response);	
	// }
	

	// public function checkout()
	// {
	// 	//$request = getRequestData();
	// 	$request = $this->createDummyCheckoutRequest();
	// 	$this->setRequestCodeHeaderToResponse();
	// 	//Extract: build data key,value pairs for inserting
	// 	$response = array();
		
	// 	//load Attendance model
	// 	$this->load->model('AttendanceModel');
	// 	$attendance = $this->AttendanceModel->isCheckedInAlready($request->staffId, $request->date);
		
	// 	if($attendance != null)
	// 	{
	// 		$success = $this->AttendanceModel->checkout($request->staffId, $request->date, $request->shiftId, $request->timeOut);
	// 		echo "\nsuccess :". $success;
	// 		if($success >= 0)
	// 		{
	// 			$this->setResultCode(100);
	// 			$response["msg"] = "checked out Successfully.";
	// 		}
	// 		else
	// 		{
				
	// 		}
	// 	}	
	// 	else 
	// 	{	
	// 		$attendance = $this->AttendanceModel->insertCheckout($request->staffId, $request->date, $request->timeOut);
	// 		echo "\nattendanceId ". $attendanceId;
	// 		if($attendanceId > 0)
	// 		{
	// 			$this->setResultCode(103);
	// 			$response["msg"] = "Checked out successfully, you haven't checked in today. Please contact administrator";
	// 		}
	// 		else
	// 		{
				
	// 		}
			
	// 	}
	// 	echo json_encode($response);
	// }
	public function checkout()
	{
		//$request = getRequestData();
		$request = $this->createDummyCheckoutRequest();
		$this->setRequestCodeHeaderToResponse();
		
		$response = array();
		//load Attendance model
		$this->load->model('AttendanceModel');
		$roaster = $this->AttendanceModel->checkRoaster($request->staffId, $request->date);
		
		if($roaster != null)
		{
			if($roaster->shift_id != $this->AttendanceModel->WEEKOFF_SHIFTID)
			{
				$attendance = $this->AttendanceModel->isCheckedInAlready($roaster->roaster_id);
				if($attendance != null)
				{
					$success = $this->AttendanceModel->checkout($roaster->roaster_id, $request->timeOut);
					if($success >= 0)
					{
						$this->setResultCode(100);
						$response["msg"] = "checked out Successfully.";
					}
					else
					{
						$this->setResultCode(101);
						$response["msg"] = "checked out failed.Please try again later";
					}
				}
				else
				{
					
					echo "in checkin not available logic";
					$attendance = $this->AttendanceModel->insertCheckout($request->staffId, $request->date, $request->timeOut);
					echo "in insert checkout call logic";
					echo "\nattendanceId ".$attendance;
					if($attendance > 0)
					{
						echo "insert query logic";
						$this->setResultCode(103);
						$response["msg"] = "Checked out successfully, you haven't checked in today. Please contact administrator";
					}
					else
					{
						$this->setResultCode(103);
						$response["msg"] = "Something went wrong in registering your checkout. Please contact administrator";
					}
				}
			
			}
			else
			{
				$this->setResultCode(104);
				$response["msg"] = "It's your week off.Please contact administrator for registering attendance ";	
			}
		}
		else 
		{

			$this->setResultCode(104);
			$response["msg"] = "You are not in roaster id.Please contact administrator to register your attendance";

		}
		
		echo json_encode($response);
			
			
	}
	// public function checkRoasterDetails()
	// {
	// 	//$request = getRequestData();
	// 	echo "\nroaster details";
		
	// 	$request = $this->createDummyRoasterRequest();
	// 	$this->setRequestCodeHeaderToResponse();
	// 	//load Attendance model
	// 	var_dump($request);
	// 	$this->load->model('AttendanceModel');
	// 	$roasterDetails = $this->AttendanceModel->getRoasterDetails($request->staffId, $request->limit, $request->fromDate, $request->toDate);
	// 	if($roasterDetails != null)
	// 	{
	// 		$data["count"] = count($roasterDetails);
	// 		$data["roasterDetails"] = array();
	// 		$index = 0;
			
	// 		foreach($roasterDetails as $row)
	// 		{
	// 		    $data["roasterDetails"][$index]["roaster_id"] = $row->roaster_id;
	// 		    $data["roasterDetails"][$index]["date"] = $row->date;
	// 		    $data["roasterDetails"][$index]["shift_id"] = $row->shift_id;
	// 		    $index++;
	// 		}
	// 	}
	// 	else 
	// 	{
	// 		// code...
	// 		$this->setResultCode(104);
	// 		$response["msg"] = "Your details not in roaster for the given date range.Please contact administrator for details";
	// 	}
		
	// 	echo json_encode($data);
			
			
	// }
	private function loadModel($model)
    {
    	$CI =& get_instance();
		$CI->load->model($model);
		return $CI->$model;
    }
	public function checkRoasterDetails()
	{
		//$request = getRequestData();
		echo "\nroaster details1";
		
		$request = $this->createDummyRoasterRequest();
		$this->setRequestCodeHeaderToResponse();
		//load Attendance model
		//var_dump($request);
		
		$attendanceModel = $this->loadModel('AttendanceModel');
		$roasterDetails = null;
		echo "\nloading model";
		if(isset($request->fromDate) && isset($request->toDate))
		{
			echo "\n in roaster params logic";
			$roasterDetails = $attendanceModel->getRoasterDetails($request->staffId, $request->limit, $request->fromDate, $request->toDate);	
		}
		else
		{
			$roasterDetails = $attendanceModel->getRoasterDetails($request->staffId, $request->limit);
		}
		
		if($roasterDetails == null)
		{
			$this->setResultCode(801);
			$response["count"] = count($roasterDetails);
			$data['msg'] = "No records found";
		}
		else 
		{
			$this->setResultCode(802);
			$response["count"] = count($roasterDetails);
			$response["roasterDetails"] = array();
			$index = 0;
			// var_dump($roasterDetails);
			foreach($roasterDetails as $row)
			{
			    $response["roasterDetails"][$index]["roaster_id"] = $row->roaster_id;
			    $response["roasterDetails"][$index]["date"] = $row->date;
			    $response["roasterDetails"][$index]["shift_id"] = $row->shift_id;
			    $response["roasterDetails"][$index]["shift"] = $row->shift;
			    $response["roasterDetails"][$index]["description"] = $row->description;
			    $response["roasterDetails"][$index]["time_in"] = $row->time_in;
			    $response["roasterDetails"][$index]["time_out"] = $row->time_out;
			    
			    $index++;
			}
		}
		echo json_encode($response);
	}
	
			
			
	

	// public function checkout1()
	// {
	// 	//$request = getRequestData();
	// 	$request = $this->createDummyCheckoutRequest();
	// 	$this->setRequestCodeHeaderToResponse();
	// 	//Extract: build data key,value pairs for inserting
	// 	// $response = array();
		
	// 	//load Attendance model
	// 	$this->load->model('AttendanceModel');
	// 	echo "loading model";
	// 	$attendance = $this->AttendanceModel->isCheckedInAlready($request->staffId, $request->date);
		
	// 	if($attendance != null)
	// 	{
	// 		$success = $this->AttendanceModel->checkout($request->staffId, $request->date, $request->timeOut);
	// 		echo "\nsuccess :". $success;
	// 		if($success >= 0)
	// 		{
	// 			$this->setResultCode(100);
	// 			$response["msg"] = "checked out Successfully.";
	// 		}
	// 		else
	// 		{
				
	// 		}
	// 	}	
	// 	else 
	// 	{	
	// 		$attendance = $this->AttendanceModel->insertCheckout($request->staffId, $request->date, $request->timeOut);
	// 		echo "\nattendanceId ". $attendanceId;
	// 		if($attendanceId > 0)
	// 		{
	// 			$this->setResultCode(103);
	// 			$response["msg"] = "Checked out successfully, you haven't checked in today. Please contact administrator";
	// 		}
	// 		else
	// 		{
				
	// 		}
			
	// 	}
	// 	echo json_encode($response);
	// }
	/*
	public function getAttendanceHistory()
	{
		$request = $this->getRequestData();
		//$request = $this->createDummyHistoryRequest();
		$this->setRequestCodeHeaderToResponse();
		//Extract: build data key,value pairs for inserting
		$response = array();
		
		//load Attendance model
		$this->load->model('AttendanceModel');
		$history = $this->AttendanceModel->getHistory($request->staffId, $request->limit);
		if($history == null)
		{
			$this->setResultCode(107);
			$this->setSuccess("false");
			$response['msg'] = "No records found";
		}
		else {
			$response["count"] = count($history);
			$response["history"] = array();
			$index = 0;
			
			foreach($history as $row)
			{
			    $response["history"][$index]["date"] = $row->date;
			    $response["history"][$index]["timeIn"] = $row->time_in;
			    $response["history"][$index]["timeOut"] = $row->time_out;
			    $index++;
			}
			
		}
		echo json_encode($response);
		
	}
	*/
	/* creates dummy checkin request */
	private function createDummyCheckinRequest()
	{
		$_SERVER[$this->TAG_HTTP_REQUEST_CODE] = "100";
		$request = array();
		$request["staffId"] = 13;
		// $request["shiftId"] = 1;
		$request["date"] = '2016-08-31';
		// $request["date"] = date("Y-m-d"); //"00-00-0000";
		$request["timeIn"] = date("Y-m-d H:i:s");//"08:00:00";
		//var_dump($request);
		return Json_decode(json_encode($request));
	}
	
	/* creates dummy checkin request */
	private function createDummyCheckoutRequest()
	{
		$_SERVER[$this->TAG_HTTP_REQUEST_CODE] = "101";
		$request = array();
		$request["staffId"] = 13;
		$request["date"] = '2016-08-31';
		// $request["shiftId"] = 1;
		// $request["date"] = date("Y-m-d"); //"00-00-0000";
		$request["timeOut"] = date("Y-m-d H:i:s");//"08:00:00";
		//var_dump($request);
		return Json_decode(json_encode($request));
	}
	/* creates dummy checkin request */
	private function createDummyHistoryRequest()
	{
		$_SERVER[$this->TAG_HTTP_REQUEST_CODE] = "102";
		$request = array();
		$request["staffId"] = 7;
		$request["limit"] = 3;
		// $request["fromDate"] = '2016-08-01';
		// $request["toDate"] = '2016-08-03';
		
		
		var_dump($request);	
		return Json_decode(json_encode($request));
	}
	private function createDummyRoasterRequest()
	{
		$_SERVER[$this->TAG_HTTP_REQUEST_CODE] = "102";
		$request = array();
		$request["staffId"] = 8;
		$request["limit"] = 3;
		// $request["fromDate"] = '2016-07-30';
		// $request["toDate"] = '2016-10-03';
		
		
		// var_dump($request);	
		return Json_decode(json_encode($request));
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
