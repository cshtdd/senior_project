<?php

class SPW_Notification_Model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_total_notifications($user_id)
    {
        $query = $this->db
                      ->where('to_user', $user_id)
                      ->where('is_read_flag',0)
                      ->get('spw_notification');

        return $query->num_rows();
    }

    public function get_notifications_by_user($user_id)
    {
        $query = $this->db
                      ->where('to_user', $user_id)
                      ->where('is_read_flag',0)
                      ->get('spw_notification');

        if($query->num_rows() > 0)
        {
            return $query->result();
        }
        else
        {
            return false; 
        }
    }

    public function set_notification_to_read($notificationId)
    {
        $data = array(
            'is_read_flag' => 1
        );

        $this->db->where('id',$notificationId);
        $this->db->update('spw_user', $data);
    }
}
    
?>