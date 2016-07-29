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
    public function checkout($data)
    {
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
            $attendanceId = 0;
        }
        else 
        {
            $attendanceId = -1;
        }
        return $attendanceId;
    }
}
?>