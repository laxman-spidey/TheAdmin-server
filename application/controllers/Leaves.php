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
	
	public function applyLeave()
	{
		$request = $this->getRequestData();
		$this->setRequestCodeHeaderToResponse();
		$this->load->library('LeavesAPI');
		$response = $this->leavesapi->applyLeave($request);
		$this->setResultCode($response[TAG_RESULT_CODE]);
		echo json_encode($response["data"]);
	}
	
	public function checkLeave()
	{
		$request = $this->getRequestData();
		$this->setRequestCodeHeaderToResponse();
		$this->load->library('LeavesAPI');
		$response = $this->leavesapi->checkLeave($request);
		$this->setResultCode($response[TAG_RESULT_CODE]);
		echo json_encode($response["data"]);
	}
	
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
