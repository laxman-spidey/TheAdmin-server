<?php
class AttendanceModel extends CI_Model 
{
    public function __construct()
    {
        $this->load->database();
    }
    public function checkin($data)
    {
        $this->db->insert('attendance',$data);
        return $this->db->insert_id();
    }
    public function checkout($staffId, $date, $timeout)
    {
        $data = array('time_out' => $timeout);
        $this->db->where('staff_id',$staffId)
                ->where('date',$date);
        $this->db->update('attendance',$data);
        return $this->db->insert_id();
    }
    public function insertCheckout($staffId, $date, $timeout)
    {
        $data = array(  'staff_id'=> $staffId, 
                        'date' => $date,
                        'time_out' => $timeout
                     );
        $this->db->insert('attendance',$data);
        return $this->db->insert_id();
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
}
?>