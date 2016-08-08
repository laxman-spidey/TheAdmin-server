<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Leaves extends CI_Controller {
	
	public $TAG_REQUEST_CODE = "requestCode";
	public $TAG_RESULT_CODE = "resultCode";

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
	
	public function applyleave()
	{
		echo "applyleave";
		//$request = $this->getRequestData();
		$request = $this->createLeaveDummyRequest();
		$this->setRequestCodeHeaderToResponse(301);
		$response = array();
		$this->load->model('LeaveModel');
		$applyleave = $this->LeaveModel->isOnLeaveAlready($request->staffId, $request->leaveDate);
		if($applyleave != -1)
		{
			$this->setResultCode(301);
			$response["msg"] = "You are already on leave "; //.$attendance[0]->time_in;
		}
		else
		{
		    
			$LeaveId = $this->LeaveModel->insertLeave($request->staffId, $request->leaveDate, $request->leaveTypeID);	
			echo "\nLeaveId ". $LeaveId;
			if($LeaveId > 0)
			{
				$this->setResultCode(302);
				$response["msg"] = "leave applied succesfully.";
			}
			else
			{
				$this->setResultCode(303);
				$response["msg"] = "Error 102: Something went wrong. Try again or report the issue to admin";
			}
		}
		echo json_encode($response);
		

		
	}
	public function checkleave()
	{
		echo "checkleave";
		//$request = $this->getRequestData();
		$request = $this->createLeaveDummyRequest1();
		$this->setRequestCodeHeaderToResponse(401);
		$response = array();
		$this->load->model('LeaveModel');
		$leaves = $this->LeaveModel->countLeave($request->staffId, $request->listLimit,$request->status );
		if($leaves != null)
		{
		    //var_dump($leaves);
			$this->setResultCode(402);
			$response["count"] = count($leaves);
			$response["leaves"] = array();
			$index = 0;
			
			foreach($leaves as $leave)
			{
			    $response["leaves"][$index]["date"] = $leave->leave_date;
			    $index++;
			}
			//$response["msg"] = "You have these many leaves "; //.$attendance[0]->time_in;
		}
		
		else
		{
		  	$this->setResultCode(403);
			$response["count"] = 0; //.$attendance[0]->time_in;
			
		}
		echo json_encode($response);
	}
	public function leavesSummary()
	{
	   echo "leavesSummary";
		//$request = $this->getRequestData();
		$request = $this->createLeaveDummyRequest2();
		
		$this->setRequestCodeHeaderToResponse(501);
		$this->load->model('LeaveModel');
		$leavesData = $this->LeaveModel->leaveSummary($request->staffId);
			if($leavesData != null)
		{
		    //var_dump($leaves);
			$this->setResultCode(502);
			$response["count"] = count($leavesData);
			$response["leavesData"] = array();
			$index = 0;
			
			foreach($leavesData as $leave)
			{
			    $response["leavesData"][$index]["count"] = $leave->count;
			    $response["leavesData"][$index]["leaveStatus"] = $leave->leave_status;
			    $index++;
			}
			//$response["msg"] = "You have these many leaves "; //.$attendance[0]->time_in;
		}
		
		else
		{
		  	$this->setResultCode(503);
			$response["count"] = 0; //.$attendance[0]->time_in;
			
		}
		echo json_encode($response);
	}
	
	
	private function getRequestData()
	{
		$postdata = file_get_contents("php://input");
		return json_decode($postdata);
	}
	
	private function setRequestCodeHeaderToResponse($requestCode)
	{
		header("$this->TAG_REQUEST_CODE: " . $requestCode . "");
	}
	private function setResultCode($resultCode)
	{				
		$this->output->set_header(''.$this->TAG_RESULT_CODE .': '. $resultCode .'');
		
	}
	
	/* creates dummy Leave request */
	private function createLeaveDummyRequest()
	{
		$_SERVER[$this->TAG_REQUEST_CODE] = "300";
		$request = array();
		$request["staffId"] = 6;
		$request["leaveDate"] = "2016-08-12";
 		$request["leaveTypeID"] = 1;
		
		return Json_decode(json_encode($request));
	}
	private function createLeaveDummyRequest1()
	{
		$_SERVER[$this->TAG_REQUEST_CODE] = "400";
		$request = array();
		$request["staffId"] = 6;
		$request["listLimit"] = 10;
 		$request["status"] = 0; //completed , pending
		return Json_decode(json_encode($request));
		
	}
	private function createLeaveDummyRequest2()
	{
		$_SERVER[$this->TAG_REQUEST_CODE] = "500";
		$request = array();
		$request["staffId"] = 6;
		
		return Json_decode(json_encode($request));
		
	}
	
}
