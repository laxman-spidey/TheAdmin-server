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
		$attendanceModel = $this->loadModel('AttendanceModel');
		$history = null;
		if(isset($request->from) && isset($request->to))
		{
			$history = $attendanceModel->getHistory($request->staffId, $request->limit, $request->fromDate, $request->toDate);	
		}
		else
		{
			$history = $attendanceModel->getHistory($request->staffId, $request->limit);
		}
		$data = array();
		if($history == null)
		{
			$response[TAG_RESULT_CODE] = ATTENDANCE_HISTORY_NOT_EXIST;
			$response['success'] = false;
			$data['msg'] = "No records found";
		}
		else 
		{
			$response[TAG_RESULT_CODE] = ATTENDANCE_HISTORY_EXIST;
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
					$response[TAG_RESULT_CODE] = CHECKIN_ALREADY_CHECKEDIN;
					$data["msg"] = "You have already checked in at " .$checkedInAlready[0]->time_in;
				}
				else
				{
					$attendanceId = $attendanceModel->checkin($roaster->roaster_id,  $request->timeIn);	
					if($attendanceId > 0)
					{
						$response[TAG_RESULT_CODE] = CHECKIN_SUCCESS;
						$data["msg"] = "Attendance registered Successfully.";
					}
					else
					{
						$response[TAG_RESULT_CODE] = CHECKIN_INSERT_DBERROR;
						$data["msg"] = "Error 102: Something went wrong. Try again or report the issue to admin";
					}
				}
			}
			else
			{
				$response[TAG_RESULT_CODE] = INFO_WEEKOFF;
				$data["msg"] = "It's your week off.Please contact administrator for registering attendance ";
			}
		}	
		else 
		{
			$response[TAG_RESULT_CODE] = WARNING_ROASTER_DOES_NOT_EXIST;
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
						$response[TAG_RESULT_CODE] = CHECKOUT_SUCCESS;
						$data["msg"] = "checked out Successfully.";
					}
					else
					{
						$response[TAG_RESULT_CODE] = CHECKOUT_INSERT_DBERROR;
						$data["msg"] = "checked out failed.Please try again later";
					}
				}
				else
				{
					$attendance = $attendanceModel->insertCheckout($roaster->roaster_id, $request->timeOut);
					echo "in insert checkout call logic";
					echo "\nattendanceId ".$attendance;
					if($attendance > 0)
					{
						$response[TAG_RESULT_CODE] = CHECKOUT_NOT_CHECKEDIN;
						$data["msg"] = "Checked out successfully, you haven't checked in today. Please contact administrator";
					}
					else
					{
						$response[TAG_RESULT_CODE] = CHECKOUT_INSERT_DBERROR;
						$data["msg"] = "Something went wrong in registering your checkout. Please contact administrator";
					}
				}
			
			}
			else
			{
				$response[TAG_RESULT_CODE] = INFO_WEEKOFF;
				$data["msg"] = "It's your week off.Please contact administrator for registering attendance ";	
			}
		}
		else 
		{
			$response[TAG_RESULT_CODE] = WARNING_ROASTER_DOES_NOT_EXIST; 
			$data["msg"] = "You are not in roaster id.Please contact administrator to register your attendance";

		}
		
		$response["data"] = $data;
		return $response;
			
			
	}
	
	public function getRoasterDetails($request)
	{
		$data = array();
		$response = array();
		$attendanceModel = $this->loadModel('AttendanceModel');
		$roasterDetails = null;
		if(isset($request->fromDate) && isset($request->toDate))
		{
			$roasterDetails = $attendanceModel->getRoasterDetails($request->staffId, $request->limit, $request->fromDate, $request->toDate);	
		}
		else
		{
			$roasterDetails = $attendanceModel->getRoasterDetails($request->staffId,$request->limit);
		}
		
		if($roasterDetails == null)
		{
			$response[TAG_RESULT_CODE] = ROASTER_DETAILS_NOT_EXIST;
			$data["count"] = count($roasterDetails);
			$data['msg'] = "No records found";
		}
		else 
		{
			$response[TAG_RESULT_CODE] = ROASTER_DETAILS_EXIST;
			$data["count"] = count($roasterDetails);
			$data["roasterDetails"] = array();
			$index = 0;
			foreach($roasterDetails as $row)
			{
			    $data["roasterDetails"][$index]["roasterId"] = $row->roaster_id;
			    $data["roasterDetails"][$index]["date"] = $row->date;
			    $data["roasterDetails"][$index]["shiftId"] = $row->shift_id;
			    $data["roasterDetails"][$index]["shift"] = $row->shift;
			    $data["roasterDetails"][$index]["description"] = $row->description;
			    $data["roasterDetails"][$index]["shiftTimeIn"] = $row->shift_time_in;
			    $data["roasterDetails"][$index]["shiftTimeOut"] = $row->shift_time_out;
			    $data["roasterDetails"][$index]["timeIn"] = $row->time_in;
			    $data["roasterDetails"][$index]["timeOut"] = $row->time_out;
			    $data["roasterDetails"][$index]["leaveStatus"] = $row->leave_status;
			    $index++;
			}
		}
		
		$response["data"] = $data;
		return $response;
	}
	
}