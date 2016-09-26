<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LeavesAPI {
    
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
    
    	
	public function applyLeave($request)
	{
		$data = array();
		$response = array();
		$leavesModel = $this->loadModel('LeavesModel');
		$applyleave = $leavesModel->isOnLeaveAlready($request->staffId, $request->leaveDate);
		if($applyleave != -1)
		{
			$response[TAG_RESULT_CODE] = APPLY_LEAVE_ALREADY_ON_LEAVE;
			$data["msg"] = "You are already on leave "; //.$attendance[0]->time_in;
		}
		else
		{
		    
			$LeaveId = $leavesModel->insertLeave($request->staffId, $request->leaveDate, $request->leaveTypeID);
			if($LeaveId > 0)
			{
				$response[TAG_RESULT_CODE] = APPLY_LEAVE_SUCCESS;
				$data["msg"] = "leave applied succesfully.";
			}
			else
			{
				$response[TAG_RESULT_CODE] = APPLY_LEAVE_FAIL;
				$data["msg"] = "Error 102: Something went wrong. Try again or report the issue to admin";
			}
		}
		$response["data"] = $data;
		return $response;
		
	}
	
		public function checkLeave($request)
	{
		$data = array();
		$response = array();
		$leavesModel = $this->loadModel('LeavesModel');
		$leaves = $leavesModel->countLeave($request->staffId, $request->listLimit,$request->status );
		if($leaves != null)
		{
		    $response[TAG_RESULT_CODE] = CHECK_LEAVE_EXISTS;
		    $data["count"] = count($leaves);
			$data["msg"] = "You have these many leaves " ; 
		}
		
		else
		{
		  	$response[TAG_RESULT_CODE] = CHECK_LEAVE_DOES_NOT_EXIST;
			$data["count"] = 0; 
			
		}
		$response["data"] = $data;
		return $response;
	}
    
    public function leavesSummary($request)
	{
	    $data = array();
		$response = array();
		$leavesModel = $this->loadModel('LeavesModel');
		$leavesData = $leavesModel->leaveSummary($request->staffId);
			if($leavesData != null)
		{
			$response[TAG_RESULT_CODE] = LEAVE_SUMMARY_EXISTS;
			$data["count"] = count($leavesData);
			$data["leavesData"] = array();
			$index = 0;
			
			foreach($leavesData as $leave)
			{
			    $data["leavesData"][$index]["count"] = $leave->count;
			    $data["leavesData"][$index]["leaveStatus"] = $leave->leave_status;
			    $index++;
			}
			
		}
		
		else
		{
		  	$response[TAG_RESULT_CODE] = LEAVE_SUMMARY_DOES_NOT_EXIST;
			$data["count"] = 0; 
			
		}
		$response["data"] = $data;
		return $response;
	}


	
	
	
	
}