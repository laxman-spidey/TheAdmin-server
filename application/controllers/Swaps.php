<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Swaps extends CI_Controller {
	
	public $TAG_REQUEST_CODE = "requestCode";
	public $TAG_RESULT_CODE = "resultCode";

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/Swaps
	 *	- or -
	 * 		http://example.com/index.php/Swaps/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/Swaps/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('welcome_message');
	}
	
	/**
	*	@url:		/Swaps/showSwap
	*	@function:	To display the eligible swap details for the user for the requested shift id
	*	@type:		POST
	*	@requestCode:
	*	@in-params: staffId , swapDate
	*	@responseCodes: SHOW_SWAP_AVAILABLE: 201
	*					SHOW_SWAP_UNAVAILABLE: 202
	*	
	*/
	
	public function showSwap()
	{
		$request = $this->getRequestData();
		$this->setRequestCodeHeaderToResponse();
		$this->load->library('SwapsAPI');
		$response = $this->swapsapi->showSwap($request);
		$this->setResultCode($response[TAG_RESULT_CODE]);
		echo json_encode($response["data"]);
	}
	
	
	/**
	*	@url:		/Swaps/applySwapByEligibility
	*	@function:	To apply swap if the requested user is eligible for swapping, based on max swap rules
	*	@type:		POST
	*	@requestCode:
	*	@in-params: reqRoasterId , reqShiftId, reqSwapDate, reqSwapTo, staffId
	*	@responseCodes: APPLY_SWAP_BY_ELIGIBILITY_SUCCESS: 211
	*					APPLY_SWAP_BY_ELIGIBILITY_FAIL: 212
	*					APPLY_SWAP_BY_ELIGIBILITY_SWAPS_COMPLETED: 213
	*	
	*/
	
	public function applySwapByEligibility()
	{
		$request = $this->getRequestData();
		$this->setRequestCodeHeaderToResponse();
		$this->load->library('SwapsAPI');
		$response = $this->swapsapi->applySwapByEligibility($request);
		$this->setResultCode($response[TAG_RESULT_CODE]);
		echo json_encode($response["data"]);
	}
	
	
	/**
	*	@url:		/Swaps/swapStatus
	*	@function:	To update the swap status and roaster details if any one accepts the users swap request
	*	@type:		POST
	*	@requestCode:
	*	@in-params: reqRoasterId , acceptRoasterId, reqSwapDate, reqSwapId, swapStatus, acceptStaffId
	*	@responseCodes: SWAP_STATUS_SUCCESS: 221
	*					SWAP_STATUS_FAIL: 222
	* 
	*/
	
	public function swapStatus()
	{
		$request = $this->getRequestData();
		$this->setRequestCodeHeaderToResponse();
		$this->load->library('SwapsAPI');
		$response = $this->swapsapi->swapStatus($request);
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
		header("success: ".$success);
	}
	private function setResultCode($resultCode)
	{				
		$this->output->set_header(''.TAG_RESULT_CODE .': '. $resultCode .'');
	}
	
}
