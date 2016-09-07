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