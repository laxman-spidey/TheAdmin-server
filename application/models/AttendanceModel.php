<?php
class AttendanceModel extends CI_Model 
{
    public function __construct()
    {
        $this->load->database();
    }
    public function checkRoaster($staffId, $date)
    {
        $this->db->select("*")
                ->from('roaster')
                ->where('staff_id',$staffId)
                ->where('date',$date)
                
                ;
        $query = $this->db->get();
        echo $this->db->last_query();
        if($query->num_rows() > 0)
        {
            $attendance = $query->result()[0];
        }
        else 
        {
            $attendance = null;
        }
        return $attendance;
    }
    
    public function checkin1( $staffId,$date,$timein)
    {
        $query ="select roaster_id from roaster where staff_id=$staffId and date=$date";
        var_dump($query);
        $query = $this->db->query($query);
        echo $this->db->last_query();
        foreach ($query->result() as $row)  
        {
            $roasterId= $row->roaster_id; 
        }
        
        $data = array(
                        'roaster_id' => $roasterId,
                        'time_in' => $timein
            );
        var_dump($data);
        return $this->attendanceInsert($data);
    }
    
    public function checkin( $roasterId, $timein)
    {
        $data = array(
                        'roaster_id' => $roasterId,
                        'time_in' => $timein
            );
        var_dump($data);
        return $this->attendanceInsert($data);
    }
    
    public function checkout($roasterId, $timeout)
    {
        $data = array('time_out' => $timeout);
        $this->db->where('roaster_id',$roasterId);
        $success = $this->db->update('attendance',$data);
        echo $this->db->last_query();
        if ($success == 1) 
        {
            return $this->db->affected_rows();
        } 
        else 
        {
            ///$this->db->_error_message()
            return -1; // Or do whatever you gotta do here to raise an error
        }
    }
    
    public function checkout1($staffId, $date, $timeout)
    {
        $query ="select roaster_id from roaster where staff_id=$staffId and date=$date";
        var_dump($query);
        
        $query = $this->db->query($query);
        // $roasterId = 0;
        foreach ($query->result() as $row)  
        {
            $roasterId= $row->roaster_id; 
        
        }
        $data = array('time_out' => $timeout);
        $this->db->where('roaster_id',$roaster_id);
                // ->where('date',$date)
                // ->where('shift_id',$shiftId);
        $success = $this->db->update('attendance',$data);
        if ($success == 1) 
        {
            return $this->db->affected_rows();
        } 
        else 
        {
            ///$this->db->_error_message()
            return -1; // Or do whatever you gotta do here to raise an error
        }
    }
    public function insertCheckout($roasterId, $timeout)
    {
        $data = array(  'roaster_id'=> $roasterId, 
                        'time_out' => $timeout
                     );
        return $this->attendanceInsert($data);
        echo $this->db->last_query();
    }
    
    
    public function insertCheckout1($staffId, $date, $timeout)
    {
        $query ="select roaster_id from roaster where staff_id=$staffId and date=$date";
        var_dump($query);
        
        $query = $this->db->query($query);
        $roasterId = 0;
        foreach ($query->result() as $row)  
        {
            $roasterId= $row->roaster_id; 
        
        }
        // echo "\n checkkkkkkkkkkkkkkkkkkkkkkkkk"
        $data = array(  'roaster_id'=> $roasterId, 
                        
                        'time_out' => $timeout
                     );
        return $this->attendanceInsert($data);
    }
    public function isCheckedInAlready1($staffId, $date)
    {
        $query ="select roaster_id from roaster where staff_id=$staffId and date=$date";
        var_dump($query);
        $query = $this->db->query($query);
        $roasterId = 0;
        foreach ($query->result() as $row)  
        {
            $roasterId= $row->roaster_id; 
        }
        echo "\n roaster :". $roasterId;
        $this->db->select("*")
                ->from('attendance')
                ->where('roaster_id',$roasterId)
                // ->where('date',$date)
                ->where('time_in !=', null)
                ;
        $query = $this->db->get();
        // var_dump($query);
        if($query->num_rows() > 0)
        {
            $attendance = $query->result();
        }
        else 
        {
            $attendance = null;
        }
        return $attendance;
    }
    
    public function isCheckedInAlready($roasterId)
    {
        //$query ="select roaster_id from roaster where staff_id=$staffId and date=$date";
        $this->db->select("time_in")
                ->from("attendance")
                ->where("roaster_id",$roasterId);
        $query = $this->db->get();
        echo $this->db->last_query();
        if($query->num_rows() > 0)
        {
            $attendance = $query->result();
        }
        else 
        {
            $attendance = null;
        }
        return $attendance;
    }
    
    
    private function attendanceInsert($data)
    {
        
        $this->db->insert('attendance',$data);
        return $this->db->insert_id();
        echo $this->db->last_query();
    }
    

    public function getHistory($staffId, $limit, $fromDate = null, $toDate = null)
    {
        $this->db->select('date, time_in, time_out')
                ->from('attendance a')
                ->join('roaster r', 'a.roaster_id = r.roaster_id')
                ->where('staff_id', $staffId)
                ->order_by('date','desc')
                ->limit($limit);
        if($fromDate != null)
        {
            $this->db->where('date >=', $fromDate);
        }
        if($toDate != null)
        {
            $this->db->where('date <=', $toDate);
        }
        echo $this->db->last_query();
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            $history = $query->result();
        }
        else 
        {
            $history = null;
        }
        return $history;
        
    }
    
}
?>