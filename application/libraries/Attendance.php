<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance {
    
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
    	//$this->setRequestCodeHeaderToResponse();
		
		//load Attendance model
		//$this->load->model('AttendanceModel');
		$attendanceModel = $this->loadModel('AttendanceModel');
		$history = null;
		if(isset($request->from) && isset($request->to))
		{
			$history = $attendanceModel->getHistory($request->staffId, $request->limit, $request->from, $request->to);	
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
			$response['responseCode'] = 107;
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
	
}