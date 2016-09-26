<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Swaps extends CI_Controller {
	
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
	
	public function showSwap()
	{
		$request = $this->getRequestData();
		$this->setRequestCodeHeaderToResponse();
		$this->load->library('SwapsAPI');
		$response = $this->swapsapi->showSwap($request);
		$this->setResultCode($response[TAG_RESULT_CODE]);
		echo json_encode($response["data"]);
	}
	
	public function applySwapByEligibility()
	{
		$request = $this->getRequestData();
		$this->setRequestCodeHeaderToResponse();
		$this->load->library('SwapsAPI');
		$response = $this->swapsapi->applySwapByEligibility($request);
		$this->setResultCode($response[TAG_RESULT_CODE]);
		echo json_encode($response["data"]);
	}
	
	public function swapStatus()
	{
		$request = $this->getRequestData();
		$this->setRequestCodeHeaderToResponse();
		$this->load->library('SwapsAPI');
		$response = $this->swapsapi->swapStatus($request);
		$this->setResultCode($response[TAG_RESULT_CODE]);
		echo json_encode($response["data"]);
	}
	
	
	// public function applyswap()
	// {
	// 	echo "applyswap";
	// 	//$request = $this->getRequestData();
	// 	$request = $this->createApplySwapDummyRequest();
	// 	$this->setRequestCodeHeaderToResponse(604);
	// 	$response = array();
	// 	$this->load->model('SwapModel');
	// 	$success = $this->SwapModel->applySwap( $request->reqRoasterId,$request->reqShiftId,$request->reqSwapDate, $request->reqSwapTo );
	// 	if($success == 0)
	// 	{
			
	// 	    $this->setResultCode(605);
	// 			$response["msg"] = "swap request submitted Successfully.";
	// 	}
		
	// 	else
	// 	{
				
	// 	  	$this->setResultCode(606);
	// 		$response["msg"] = "swap request submission failed.";
			
	// 	}
	// 	echo json_encode($response);
	// }
// public function swapeligibility()
// 	{
// 		echo "swapeligibility";
// 		//$request = $this->getRequestData();
// 		$request = $this->createSwapEligibilityDummyRequest();
// 		$this->setRequestCodeHeaderToResponse(607);
// 		$response = array();
// 		$this->load->model('SwapModel');
// 		$eligibility = $this->SwapModel->swapEligibility($request->staffId);
// 		if($eligibility != null)
// 		{
			
// 		    $this->setResultCode(608);
// 				$response["msg"] = "Eligible for swapping.";
// 		}
		
// 		else
// 		{
				
// 		  	$this->setResultCode(609);
// 			$response["msg"] = "you have crossed the swap limit.";
			
// 		}
// 		echo json_encode($response);
// 	}
		
	
	 
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
		header("success: ".$success);
	}
	private function setResultCode($resultCode)
	{				
		$this->output->set_header(''.TAG_RESULT_CODE .': '. $resultCode .'');
	}
	
	/* creates dummy Leave request */
	
	
		private function createSwapEligibilityDummyRequest()
	{
		$_SERVER[$this->TAG_REQUEST_CODE] = "600";
		$request = array();
		$request["staffId"] = 1;
		return Json_decode(json_encode($request));
	}
	
	
}
