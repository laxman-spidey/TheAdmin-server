<?php
class LeaveModel extends CI_Model 
{
    
    
    public function __construct()
    {
        $this->load->database();
    }
    
    
    public function insertLeave($staffId, $leaveDate,$leaveTypeID)
    {
        //echo "------------------".$leaveTypeID."----------";
        $data = array(
                'staff_id' => $staffId,
                'leave_date' => $leaveDate,
                'leave_type_id' => $leaveTypeID
                
            );
        $this->db->insert('leave',$data);
        return $this->db->insert_id();
    }
    public function isOnLeaveAlready($staffId, $leaveDate)
    {
        $this->db->select("*")
                ->from('leave')
                ->where('staff_id',$staffId)
                ->where('leave_date',$leaveDate)
                ;
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            echo "has a row";
            $LeaveId = 0;
        }
        else 
        {
            echo "no rows";
            $LeaveId = -1;
        }
        return $LeaveId;
    }
    public function countLeave($staffId, $listLimit, $status )
    {
        $this->db->select("*")
                ->from('leave')
                ->where('staff_id',$staffId)
                ->where('leave_status',$status)
                ->limit($listLimit)
                ;
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            $leaves = $query->result();
            echo "has a row";
            $countId = 0;
        }
        else 
        {
            echo "no rows";
            $countId = -1;
            $leaves = null;
        }
        return $leaves;
    }
    
    
     public function leaveSummary($staffId)
    {
        $this->db->select("count(*) as count, leave_status")
                ->from('leave')
                ->where('staff_id',$staffId)
                ->group_by('leave_status')
                ;
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            $leavesData = $query->result();
            echo "has a row";
            $summaryId = 0;
        }
        else 
        {
            echo "no rows";
            $summaryId = -1;
            $leavesData = null;
        }
        //var_dump($leavesData);
        return $leavesData;
    }
    
   
}
?>