<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SwapsAPI {
    
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
    
    /**
	*	@function:	To display the eligible swap details for the user for the requested shift id
	*	@type:		POST
	*	@in-params: staffId , swapDate
	*	@responseCodes: SHOW_SWAP_AVAILABLE: 201
	*					SHOW_SWAP_UNAVAILABLE: 202
	*	
	*/
	
    public function showSwap($request)
	{
		$data = array();
		$response = array();
		$swapsModel = $this->loadModel('SwapModel');
		$showSwap = $swapsModel->availableSwap( $request->swapDate,$request->shiftId);
		if($showSwap != null)
		{
			$data["count"] = count($showSwap);
			$data["showSwap"] = array();
			$index = 0;
			
			foreach($showSwap as $swap)
			{
			    $data["showSwap"][$index]["staffId"] = $swap->staff_id;
			    $index++;
			}
			$response[TAG_RESULT_CODE] = SHOW_SWAP_AVAILABLE;
			$data["msg"] = "swap is available "; //.$attendance[0]->time_in;
		}
		else
		{
			$response[TAG_RESULT_CODE] = SHOW_SWAP_UNAVAILABLE;
			$data["msg"] = "No swap is available.";
		}
		
		$response["data"] = $data;
		return $response;
		
	}
	
	/**
	*	@function:	To apply swap if the requested user is eligible for swapping, based on max swap rules
	*	@type:		POST
	*	@in-params: reqRoasterId , reqShiftId, reqSwapDate, reqSwapTo, staffId
	*	@responseCodes: APPLY_SWAP_BY_ELIGIBILITY_SUCCESS: 211
	*					APPLY_SWAP_BY_ELIGIBILITY_FAIL: 212
	*					APPLY_SWAP_BY_ELIGIBILITY_SWAPS_COMPLETED: 213
	*	
	*/
	
	public function applySwapByEligibility($request)
	{
	    $data = array();
		$response = array();
		$swapsModel = $this->loadModel('SwapModel');
		$eligibility = $swapsModel->swapEligibility( $request->staffId);
		if($eligibility != null)
		{
			$data["msg"] = "Eligible for swapping.";
		    $success = $swapsModel->applySwap( $request->reqRoasterId,$request->reqShiftId,$request->reqSwapDate, $request->reqSwapTo );
		    if($success == 0)
		    {
		    	$response[TAG_RESULT_CODE] = APPLY_SWAP_BY_ELIGIBILITY_SUCCESS;
				$data["msg"] = "swap request submitted Successfully.";
		    }
		    else
		    {
		    	$response[TAG_RESULT_CODE] = APPLY_SWAP_BY_ELIGIBILITY_FAIL;
				$data["msg"] = "swap request submission failed.";
		    }
				
		}
		
		else
		{
		    $response[TAG_RESULT_CODE] = APPLY_SWAP_BY_ELIGIBILITY_SWAPS_COMPLETED;
			$data["msg"] = "Your swaps for this month are completed.";
			
		}
		
		$response["data"] = $data;
		return $response;
	}
	
	/**
	*	@function:	To update the swap status and roaster details if any one accepts the users swap request
	*	@type:		POST
	*	@in-params: reqRoasterId , acceptRoasterId, reqSwapDate, reqSwapId, swapStatus, acceptStaffId
	*	@responseCodes: SWAP_STATUS_SUCCESS: 221
	*					SWAP_STATUS_FAIL: 222
	* 
	*/
	
	
	public function swapStatus($request)
	{
		$data = array();
		$response = array();
		$swapsModel = $this->loadModel('SwapModel');
		$swapStatus = $swapsModel->swapStatus( $request->swapStatus,$request->acceptStaffId,$request->reqRoasterId,$request->reqSwapDate,$request->acceptRoasterId,$request->reqSwapId);
		if($swapStatus == 0)
		{
			
		    $response[TAG_RESULT_CODE] = SWAP_STATUS_SUCCESS;
			$data["msg"] = "swap status updated Successfully.";
		}
		
		else
		{
				
		  	$response[TAG_RESULT_CODE] = SWAP_STATUS_FAIL;
			$data["msg"] = "swap status updation failed.";
			
		}
		
		$response["data"] = $data;
		return $response;
	}
    
	
}