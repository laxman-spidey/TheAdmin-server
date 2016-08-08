<?php
class AttendanceModel extends CI_Model 
{
    public function __construct()
    {
        $this->load->database();
    }
    public function checkin($staffId, $date, $shiftId, $timein)
    {
        $data = array(
                        'staff_id' => $staffId,
                        'date' => $date,
                        'shift_id' => $shiftId,
                        'time_in' => $timein
            );
            var_dump($data);
            return $this->attendanceInsert($data);
    }
    
    
    public function checkout($staffId, $date, $shiftId, $timeout)
    {
        $data = array('time_out' => $timeout);
        $this->db->where('staff_id',$staffId)
                ->where('date',$date)
                ->where('shift_id',$shiftId);
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
        
        // if($this->db->affected_rows() > 0)
        // {
        //     return true;
        // }
        // return false;
    }
    
    public function insertCheckout($staffId, $date, $timeout)
    {
        $data = array(  'staff_id'=> $staffId, 
                        'date' => $date,
                        'time_out' => $timeout
                     );
        return $this->attendanceInsert($data);
    }
    public function isCheckedInAlready($staffId, $date)
    {
        $this->db->select("*")
                ->from('attendance')
                ->where('staff_id',$staffId)
                ->where('date',$date)
                ->where('time_in !=', null)
                ;
        $query = $this->db->get();
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
    }
    
}
?>