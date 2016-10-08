<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Leaves extends CI_Controller {
	
	public $TAG_REQUEST_CODE = "requestCode";
	public $TAG_RESULT_CODE = "resultCode";

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/Leaves
	 *	- or -
	 * 		http://example.com/index.php/Leaves/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/Leaves/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('welcome_message');
	}
	
	
	 /**
	*	@url:		/Leaves/applyLeave
	*	@function:	To apply Leave by the user ,not applicable if the user is already on leave.
	*	@type:		POST
	*	@requestCode:
	*	@in-params: staffId , leaveDate , leaveTypeID
	*	@responseCodes: APPLY_LEAVE_ALREADY_ON_LEAVE: 171
	*					APPLY_LEAVE_SUCCESS: 172
	*					APPLY_LEAVE_FAIL: 173
	*	
	*/
	
	public function applyLeave()
	{
		$request = $this->getRequestData();
		$this->setRequestCodeHeaderToResponse();
		$this->load->library('LeavesAPI');
		$response = $this->leavesapi->applyLeave($request);
		$this->setResultCode($response[TAG_RESULT_CODE]);
		echo json_encode($response["data"]);
	}
	
	 /**
	*	@url:		/Leaves/checkLeave
	*	@function:	To display number of leaves available for the user
	*	@type:		POST
	*	@requestCode:
	*	@in-params: staffId , listLimit , status if 0 
	*	@responseCodes: CHECK_LEAVE_EXISTS: 181
	*					CHECK_LEAVE_DOES_NOT_EXIST: 182
	*	
	*/
	
	public function checkLeave()
	{
		$request = $this->getRequestData();
		$this->setRequestCodeHeaderToResponse();
		$this->load->library('LeavesAPI');
		$response = $this->leavesapi->checkLeave($request);
		$this->setResultCode($response[TAG_RESULT_CODE]);
		echo json_encode($response["data"]);
	}
	
	/**
	*	@url:		/Leaves/leavesSummary
	*	@function:	To retrieve the leave details of the user
	*	@type:		POST
	*	@requestCode:
	*	@in-params: staffId 
	*	@responseCodes: LEAVE_SUMMARY_EXISTS: 191
	*					LEAVE_SUMMARY_DOES_NOT_EXIST: 192
	*	
	*/
	
	public function leavesSummary()
	{
		$request = $this->getRequestData();
		$this->setRequestCodeHeaderToResponse();
		$this->load->library('LeavesAPI');
		$response = $this->leavesapi->leavesSummary($request);
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
