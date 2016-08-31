<?php
class SwapModel extends CI_Model 
{
    
    
    public function __construct()
    {
        $this->load->database();
    }
    
    
    // public function applySwap($reqRoasterId,$reqShiftId,$reqSwapDate,$reqSwapTo)
    // {
    //     //echo "------------------".$leaveTypeID."----------";
    //     $data = array(
    //             'roaster_id_requested' => $reqRoasterId,
    //             'shift_id_requested' => $reqShiftId,
                
    //             'roaster_id_accepted' => $reqRoasterId
    //         );
    //     $this->db->insert('swap_request',$data);
    //     $swapId = $this->db->insert_id();
        
    //     //sent to table
    //     $data1 = array(
    //         'staff_id_sent_to' => $reqSwapTo,
    //         'swap_id' => $swapId
    //         );
    //     $this->db->insert('swap_request_sent',$data1);
    //     $swapIdto = $this->db->insert_id();
        
    //     if($swapId > 0  && $swapIdto > 0)
    //     {
    //         return 0;
    //     }
    //     else
    //     {
    //         return 1;
    //     }
    // }
    
    public function availableSwap( $swapDate,$shiftId)
    {
        $this->db->select("staff_id,date")
                ->from('roaster')
                
                ->where('date',$swapDate)
                ->where('shift_id !=',$shiftId)
                
                ;
        $query = $this->db->get();
        echo $this->db->last_query();
        if($query->num_rows() > 0)
        {
            echo "has a row";
            $showSwap = $query->result();
            $showswapId = 0;
        }
        else 
        {
            echo "no rows";
            $showswapId = -1;
            $showSwap = null;
        }
        return $showSwap;
    }
    // public function swapEligibility($staffId)
    // {
    //     $this->db->select("count(*) as count")
    //             ->from('roaster')
                
    //             ->where('staff_id',$staffId)
    //             ->where('MONTH(date)=',date('m'))
    //             ->where('swap_status=',0)
    //             ->group_by('staff_id')
    //             ->having('count<(select max_swaps_per_month FROM swap_rule)')
    //             ;
    //     $query = $this->db->get();
    //     echo $this->db->last_query();
    //     if($query->num_rows() > 0)
    //     {
    //         echo "has a row";
    //         $eligibility = $query->result();
    //         $eligibilityId = 0;
    //     }
    //     else 
    //     {
    //         echo "no rows";
    //         $eligibilityId = -1;
    //         $eligibility = null;
    //     }
    //     return $eligibility;
    // }
        public function swapEligibility($staffId)
        
    {
        $this->db->select("count(*) as count")
                ->from('roaster')
                
                ->where('staff_id',$staffId)
                ->where('MONTH(date)=',date('m'))
                ->where('swap_status=',0)
                ->group_by('staff_id')
                ->having('count<(select max_swaps_per_month FROM swap_rule)')
                ;
        $query = $this->db->get();
        echo $this->db->last_query();
        if($query->num_rows() > 0)
        {
            echo "has a row";
            $eligibility = $query->result();
            $eligibilityId = 0;
        }
        else 
        {
            echo "no rows";
            $eligibilityId = -1;
            $eligibility = null;
        }
        return $eligibility;
    }
    public function applySwap($reqRoasterId,$reqShiftId,$reqSwapDate,$reqSwapTo)
    {
        //echo "------------------".$leaveTypeID."----------";
        $data = array(
                'roaster_id_requested' => $reqRoasterId,
                'shift_id_requested' => $reqShiftId,
                
                'roaster_id_accepted' => $reqRoasterId
            );
        $this->db->insert('swap_request',$data);
        $swapId = $this->db->insert_id();
        
        //sent to table
        $data1 = array(
            'staff_id_sent_to' => $reqSwapTo,
            'swap_id' => $swapId
            );
        $this->db->insert('swap_request_sent',$data1);
        $swapIdto = $this->db->insert_id();
        
        if($swapId > 0  && $swapIdto > 0)
        {
            return 0;
        }
        else
        {
            return 1;
        }
    }
    
    public function swapStatus($swapStatus,$acceptStaffId,$reqRoasterId,$reqSwapDate,$acceptRoasterId,$reqSwapId)
    {
        
        // update status
        // select original shift id's of two users
        // update requested shiftid with accepted
        // update accepted shiftid with requested
        
        
        
        
        //echo "------------------".$leaveTypeID."----------";
        $query ="select roaster_id, original_shift_id from roaster where roaster_id in($reqRoasterId,$acceptRoasterId) order by field(roaster_id,$reqRoasterId,$acceptRoasterId)";
        $data = array(
                        'swap_status' => $swapStatus,
                    );
        $this->db->where('roaster_id',$reqRoasterId);
        $this->db->update('roaster', $data);
        
        echo $this->db->last_query();
        echo "<br />";
        //$roastertoupdate  = array($reqRoasterId,$acceptRoasterId);
        //$this->db->where_in('roaster_id',$roastertoupdate)
        // where('staff_id',$acceptStaffId)
                //'date',$reqSwapDate)
                
        //  $this->db->set("shift_id = select shift_id_requested from swap_request where swap_id = $reqSwapId");
         
        $swapSatusId1 = $this->db->update('roaster',$data);
        echo $this->db->last_query();
        $query = $this->db->query($query);
        echo $this->db->last_query();
        echo "<br />";
        $index = 0;
        $reqOriginalShiftId = 0;
        $acceptedOriginalShiftId = 0;
        foreach ($query->result() as $row) 
        {
            if($row->roaster_id == $reqRoasterId)
            {
                $reqOriginalShiftId = $row->original_shift_id;
                
            }
            else if($row->roaster_id == $acceptRoasterId)
            {
                $acceptedOriginalShiftId = $row->original_shift_id;
                
            }
            
        }
        
        $this->updateShiftIdInRoaster($reqRoasterId, $acceptedOriginalShiftId);
        $this->updateShiftIdInRoaster($acceptRoasterId, $reqOriginalShiftId);
        
        
        echo 'Total Results: ' . $query->num_rows();
        echo $this->db->last_query();
        // $swapSatusId = $this->db->insert_id();
        
        //sent to table
        $data1 = array(
             'roaster_id_accepted' => $acceptRoasterId,
             'swap_status' => $swapStatus,
                    );
        $this->db->where('swap_id',$reqSwapId);
        $swapSatusId2 = $this->db->update('swap_request',$data1);
        
        // $swapIdto = $this->db->insert_id();
        
        if($swapSatusId1 > 0  && $swapSatusId2 > 0)
        {
            return 0;
        }
        else
        {
            return 1;
        }
    }
    
    public function updateShiftIdInRoaster($roasterId, $shiftId)
    {
        $this->db->set('shift_id', $shiftId);
        $this->db->where('roaster_id', $roasterId);
        $success = $this->db->update('roaster');
        echo $this->db->last_query();
        echo "<br />";
                
        if($success)
        {
            return $this->db->affected_rows();
        }
        else 
        {
            return -1;
        }
        
        
    }
    
}
?>