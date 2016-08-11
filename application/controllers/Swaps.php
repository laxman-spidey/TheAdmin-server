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
	
	public function showswap()
	{
		echo "showswap";
		//$request = $this->getRequestData();
		$request = $this->createSwapDummyRequest();
		$this->setRequestCodeHeaderToResponse(601);
		$response = array();
		$this->load->model('SwapModel');
		$showSwap = $this->SwapModel->availableSwap( $request->swapDate,$request->shiftId);
		if($showSwap != null)
		{
			//var_dump($leaves);
			$response["count"] = count($showSwap);
			$response["showSwap"] = array();
			$index = 0;
			
			foreach($showSwap as $swap)
			{
			    $response["showSwap"][$index]["staffId"] = $swap->staff_id;
			    $index++;
			}
			$this->setResultCode(602);
			$response["msg"] = "swap is available "; //.$attendance[0]->time_in;
		}
		else
		{
			$this->setResultCode(603);
			$response["msg"] = "No swap is available.";
		}
		
		echo json_encode($response);
		
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
		public function applyswapeligibility()
	{
		echo "applyswapeligibility";
		//$request = $this->getRequestData();
		$request = $this->createApplySwapDummyRequest1();
		$this->setRequestCodeHeaderToResponse(604);
		$response = array();
		$this->load->model('SwapModel');
		$eligibility = $this->SwapModel->swapEligibility( $request->staffId);
		if($eligibility != null)
		{
			$response["msg"] = "Eligible for swapping.";
		    $success = $this->SwapModel->applySwap( $request->reqRoasterId,$request->reqShiftId,$request->reqSwapDate, $request->reqSwapTo );
		    if($success == 0)
		    {
		    	$this->setResultCode(605);
				$response["msg"] = "swap request submitted Successfully.";
		    }
		    else{
		    	$this->setResultCode(606);
				$response["msg"] = "swap request submission failed.";
		    }
				
		}
		
		else
		{
				
		  	$this->setResultCode(606);
			$response["msg"] = "Your swaps for this month are completed.";
			
		}
		echo json_encode($response);
	}
	
	 public function swapstatus()
	{
		echo "swapstatus";
		//$request = $this->getRequestData();
		$request = $this->createSwapStatusDummyRequest();
		$this->setRequestCodeHeaderToResponse(604);
		$response = array();
		$this->load->model('SwapModel');
		$swapStatus = $this->SwapModel->swapStatus( $request->swapStatus,$request->acceptStaffId,$request->reqRoasterId,$request->reqSwapDate,$request->acceptRoasterId,$request->reqSwapId);
		if($swapStatus == 0)
		{
			
		    $this->setResultCode(605);
				$response["msg"] = "swap status updated Successfully.";
		}
		
		else
		{
				
		  	$this->setResultCode(606);
			$response["msg"] = "swap status updation failed.";
			
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
	private function createSwapDummyRequest()
	{
		$_SERVER[$this->TAG_REQUEST_CODE] = "600";
		$request = array();
		$request["swapDate"] = "2016-08-01";
		$request["shiftId"] = 1;
 	
		
		return Json_decode(json_encode($request));
	}
	private function createApplySwapDummyRequest()
	{
		$_SERVER[$this->TAG_REQUEST_CODE] = "600";
		$request = array();
		$request["reqRoasterId"] = 1;
		$request["reqShiftId"] = 1;
		$request["reqSwapDate"] = '2016-08-01';
		$request["reqSwapTo"] = 2;
 	
		
		return Json_decode(json_encode($request));
	}
		private function createSwapEligibilityDummyRequest()
	{
		$_SERVER[$this->TAG_REQUEST_CODE] = "600";
		$request = array();
		$request["staffId"] = 1;
		return Json_decode(json_encode($request));
	}
	private function createApplySwapDummyRequest1()
	{
		$_SERVER[$this->TAG_REQUEST_CODE] = "600";
		$request = array();
		$request["reqRoasterId"] = 1;
		$request["reqShiftId"] = 1;
		$request["reqSwapDate"] = '2016-08-01';
		$request["reqSwapTo"] = 2;
 		$request["staffId"] = 1;
		
		return Json_decode(json_encode($request));
	}
		private function createSwapStatusDummyRequest()
	{
		$_SERVER[$this->TAG_REQUEST_CODE] = "600";
		$request = array();
		$request["swapStatus"] = 5;
 		$request["acceptStaffId"] = 2;
		$request["reqSwapDate"] = '2016-08-01';
		$request["reqRoasterId"] = 1;
		$request["acceptRoasterId"] = 2;
		$request["reqSwapId"] = 1;
		return Json_decode(json_encode($request));
	}
	
}
