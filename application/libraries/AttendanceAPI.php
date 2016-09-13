<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AttendanceAPI {
    
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
    
    public function getAttendanceHistory($request)
    {
    	var_dump($request);
    	// $this->setRequestCodeHeaderToResponse();
		echo "\nin function";
		//load Attendance model
		//$this->load->model('AttendanceModel');
		$attendanceModel = $this->loadModel('AttendanceModel');
		echo "\n loading model";
		$history = null;
		var_dump($request);
		// echo "\nfromdate" .$request->from;
		if(isset($request->from) && isset($request->to))
		{
			echo "\nfromdate". $request->from;
			echo "\n in history params logic";
			$history = $attendanceModel->getHistory($request->staffId, $request->limit, $request->fromDate, $request->toDate);	
		}
		else
		{
			$history = $attendanceModel->getHistory($request->staffId, $request->limit);
		}
		$response['success'] = false;
		$response['responseCode'] = 0;
		$data = array();
		if($history == null)
		{
			$response['responseCode'] = ATTENDANCE_HISTORY_DETAILS_DOES_NOT_EXISTS_IN_LIMIT;
			$response['success'] = false;
			$data['msg'] = "No records found";
		}
		else {
			$data["count"] = count($history);
			$data["history"] = array();
			$index = 0;
			
			foreach($history as $row)
			{
			    $data["history"][$index]["date"] = $row->date;
			    $data["history"][$index]["timeIn"] = $row->time_in;
			    $data["history"][$index]["timeOut"] = $row->time_out;
			    $index++;
			}
		}
		$response['data'] = $data;
		return $response;
	}
	
	
	public function checkin($request)
	{
		$data = array();
		$response = array();
		$attendanceModel = $this->loadModel('AttendanceModel');
		$roaster = $attendanceModel->checkRoaster($request->staffId, $request->date);
		if($roaster != null )
		{
			//handling if the user is on week off
			if($roaster->shift_id != $attendanceModel->WEEKOFF_SHIFTID)
			{
				echo "roaster id fetched";
				$checkedInAlready = $attendanceModel->isCheckedInAlready($roaster->roaster_id);
				if($checkedInAlready != null)
				{
					//If the user is checked in for the already, he is not allowed to update later timing unless the admin wants to.
					$response["responseCode"] = CHECKIN_ALREADY_CHECKEDIN;
					$data["msg"] = "You have already checked in at " .$checkedInAlready[0]->time_in;
				}
				else
				{
					$attendanceId = $attendanceModel->checkin($roaster->roaster_id,  $request->timeIn);	
					if($attendanceId > 0)
					{
						$response["responseCode"] = CHECKIN_SUCCESS;
						$data["msg"] = "Attendance registered Successfully.";
					}
					else
					{
						$response["responseCode"] = CHECKIN_INSERT_DBERROR;
						$data["msg"] = "Error 102: Something went wrong. Try again or report the issue to admin";
					}
				}
			}
			else
			{
				$response["responseCode"] = INFO_WEEKOFF;
				$data["msg"] = "It's your week off.Please contact administrator for registering attendance ";
			}
		}	
		else 
		{
			$response["responseCode"] = ROASTER_DOES_NOT_EXISTS;
			$data["msg"] = "You are not in roaster id.Please contact administrator to register your attendance";
		}
		$response["data"] = $data;
		return $response;
	}
	
	public function checkout($request)
	{
		$data = array();
		$response = array();
		//load Attendance model
		$attendanceModel = $this->loadModel('AttendanceModel');
		$roaster = $attendanceModel->checkRoaster($request->staffId, $request->date);
		
		if($roaster != null)
		{
			if($roaster->shift_id != $attendanceModel->WEEKOFF_SHIFTID)
			{
				$attendance = $attendanceModel->isCheckedInAlready($roaster->roaster_id);
				if($attendance != null)
				{
					$success = $attendanceModel->checkout($roaster->roaster_id, $request->timeOut);
					if($success >= 0)
					{
						$response["responseCode"] = CHECKOUT_SUCCESS;
						$data["msg"] = "checked out Successfully.";
					}
					else
					{
						$response["responseCode"] = CHECKOUT_INSERT_DBERROR;
						$data["msg"] = "checked out failed.Please try again later";
					}
				}
				else
				{
					
					echo "in checkin not available logic";
					// $attendance = $attendanceModel->insertCheckout($roaster->roaster_id,$request->staffId, $request->date, $request->timeOut);
					$attendance = $attendanceModel->insertCheckout($roaster->roaster_id, $request->timeOut);
					echo "in insert checkout call logic";
					echo "\nattendanceId ".$attendance;
					if($attendance > 0)
					{
						$response["responseCode"] = CHECKOUT_SUCCESS_CHECKIN_DOES_NOT_EXISTS;
						$data["msg"] = "Checked out successfully, you haven't checked in today. Please contact administrator";
					}
					else
					{
						$response["responseCode"] = CHECKOUT_INSERT_DBERROR; //used same 
						$data["msg"] = "Something went wrong in registering your checkout. Please contact administrator";
					}
				}
			
			}
			else
			{
				$response["responseCode"] = INFO_WEEKOFF; //same
				$data["msg"] = "It's your week off.Please contact administrator for registering attendance ";	
			}
		}
		else 
		{
			$response["responseCode"] = WARNING_ROASTER_DOES_NOT_EXISTS; //same
			$data["msg"] = "You are not in roaster id.Please contact administrator to register your attendance";

		}
		
		$response["data"] = $data;
		return $response;
			
			
	}
	
	public function getRoasterDetails($request)
	{
		//load Attendance model
		//var_dump($request);
		$data = array();
		$response = array();
		
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
			$response["responseCode"] = ROASTER_DETAILS_DOES_NOT_EXISTS_IN_LIMIT;
			$data["count"] = count($roasterDetails);
			$data['msg'] = "No records found";
		}
		else 
		{
			$response["responseCode"] = ROASTER_DETAILS_EXISTS_IN_LIMIT;
			$data["count"] = count($roasterDetails);
			$data["roasterDetails"] = array();
			$index = 0;
			// var_dump($roasterDetails);
			foreach($roasterDetails as $row)
			{
			    $data["roasterDetails"][$index]["roaster_id"] = $row->roaster_id;
			    $data["roasterDetails"][$index]["date"] = $row->date;
			    $data["roasterDetails"][$index]["shift_id"] = $row->shift_id;
			    $data["roasterDetails"][$index]["shift"] = $row->shift;
			    $data["roasterDetails"][$index]["description"] = $row->description;
			    $data["roasterDetails"][$index]["time_in"] = $row->time_in;
			    $data["roasterDetails"][$index]["time_out"] = $row->time_out;
			    
			    $index++;
			}
		}
		
		$response["data"] = $data;
		return $response;
	}
	
}