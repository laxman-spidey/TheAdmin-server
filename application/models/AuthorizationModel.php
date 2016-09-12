<?php
class AuthorizationModel extends CI_Model 
{
    
    
    public function __construct()
    {
        $this->load->database();
    }
    
    
    public function checkAuthorization( $phoneNumber )
    {
        $this->db->select("*")
                ->from('staff')
                // ->where('staff_id',$staffId)
                ->where('phone_number',$phoneNumber)
                ;
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            $authorization = $query->result();
            //echo "has a row";
            $authorizationId = 0;
        }
        else 
        
        {
            //echo "no rows";
            $authorizationId = -1;
            $authorization = null;
        }
        return $authorization;
    }
    
    public function expireOtp($phoneNumber)
    {
        
        $this->db->set('otp_status', 'expired');
        $this->db->where('phone_number',$phoneNumber)
                // ->where('otp',$otp)
                ->where_in('otp_status','Generated');
        $expireOtp = $this->db->update('otp_log');
        if ($expireOtp == 1) 
        {
            return $this->db->affected_rows();
        } 
        else 
        {
            ///$this->db->_error_message()
            return -1; // Or do whatever you gotta do here to raise an error
        }
        
    }
    
      
    public function validateOtp($phoneNumber , $otp)
    {
        $this->db->select("*")
                ->from('otp_log')
                // ->where('staff_id',$staffId)
                ->where('phone_number',$phoneNumber)
                ->where('otp',$otp)
                ->where('timestamp<','DATE_ADD(NOW(), INTERVAL 15 MINUTE)', FALSE)
                ->where('otp_status','Generated')
                
                ;
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            $validation = $query->result();
            $validationId = 0;
        }
        else 
        
        {
            $validationId = -1;
            $validation = null;
        }
        return $validation;
    }
     
     
     public function updateOtpStatus($phoneNumber , $otp)
    {
        
        $this->db->set('otp_status', 'used');
        $this->db->where('phone_number',$phoneNumber)
                ->where('otp',$otp)
                ->where_in('otp_status',array('Generated','used'));
        $otpUpdation = $this->db->update('otp_log');
        if ($otpUpdation == 1) 
        {
            return $this->db->affected_rows();
    
    
    
        } 
        else 
        {
            ///$this->db->_error_message()
            return -1; // Or do whatever you gotta do here to raise an error
        }
        
    }
    public function otpGeneration( $phoneNumber, $password)
    {
        $password = "123456";
        $query ="select staff_id from staff where phone_number=$phoneNumber";
        $query = $this->db->query($query);
        foreach ($query->result() as $row)  
        {// $staffId this will be a row.. not a value directly..
            $staffId= $row->staff_id; // first time ragane override aithundhi 
            // and staff_id gali lo undhi.. 
        }
        $data = array(
                        'staff_id' => $staffId ,
                        // 'staff_id' => $query->result() ,
                        'phone_number' => $phoneNumber,
                        'otp' => $password
        );
            //var_dump($data);
        $this->db->insert('otp_log',$data);
        
    }
    
    
   
}
?>