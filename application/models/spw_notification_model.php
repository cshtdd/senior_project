<?php

class SPW_Notification_Model extends CI_Model
{

    public $id; 
    public $from; 
    public $to_project;
    public $to_user; 
    public $subject;
    public $body; 
    public $type;
    public $is_read_flag;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('spw_user_model');
        $this->load->model('spw_project_model');
    }

    public function get_total_notifications($user_id)
    {
        $query = $this->db
                      ->where('to_user', $user_id)
                      ->where('is_read_flag',0)
                      ->get('spw_notification');

        return $query->num_rows();
    }

    public function get_notifications_to_user($user_id)
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

    public function get_notification_by_id($notification_id)
    {
        $notification = new SPW_Notification_Model();
        $query = $this->db
                      ->where('id', $notification_id)
                      ->get('spw_notification');

        if($query->num_rows() > 0)
        {
            $notification->from = $query->row()->from; 
            $notification->to_project = $query->row()->to_project;
            $notification->to_user = $query->row()->to_user;
            $notification->subject = $query->row()->subject;
            $notification->body = $query->row()->body;
            $notification->type = $query->row()->type;
            $notification->is_read_flag = $query->row()->is_read_flag;

            return $notification;
        }
        else
        {
            return null;
        }              
   
    }

    public function get_active_join_notification_for_project_from_user($from_user, $project_id)
    {
         $query = $this->db
                      ->where('from', $from_user)
                      ->where('to_project', $project_id)
                      ->where('is_read_flag', 0)
                      ->get('spw_notification');

        if($query->num_rows() > 0)
        {
            return true;
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
        $this->db->update('spw_notification', $data);
    }

    public function set_join_notification_to_read($from_user, $to_user, $to_project)
    {

        $query = $this->db
                     ->where('from', $from_user)
                     ->where('to_user', $to_user)
                     ->where('to_project', $to_project)
                     ->where('type', 'join')
                     ->select('id')
                     ->get('spw_notification'); 


        if($query->num_rows() > 0)
        {

            $this->set_notification_to_read($query->row()->id);
        }
        else
        {
            return false;
        }
    }

    public function create_leave_notification_for_user($from_user_id, $to_user_id, $project_id)
    {
        $project_title = $this->spw_project_model->get_project_title($project_id);

        $fullname = $this->spw_user_model->get_fullname($from_user_id);
        $data = array(
                    'from' => $from_user_id,
                    'to_user'  => $to_user_id,
                    'to_project'  => $project_id,
                    'body'      => $fullname." left your project ".$project_title,
                    'type'    => 'leave',
                    'datetime' => date("Y-m-d H:i:s", time())
                    );

        $this->db->insert('spw_notification',$data);
    }

    public function create_join_notification_for_user($from_user_id, $to_user_id, $project_id)
    {
        $project_title = $this->spw_project_model->get_project_title($project_id);
        $fullname = $this->spw_user_model->get_fullname($from_user_id);


        $data = array(
                    'from' => $from_user_id,
                    'to_user' => $to_user_id,
                    'to_project'  => $project_id,
                    'body'    => $fullname." wants to join your project ".$project_title,
                    'type'    => 'join',
                    'datetime' => date("Y-m-d H:i:s", time())
                    );

        $this->db->insert('spw_notification',$data);
    }

    public function create_join_approved_notification_for_user($from_user_id, $to_user_id, $project_id)
    {
        $project_title = $this->spw_project_model->get_project_title($project_id);

        $fullname = $this->spw_user_model->get_fullname($from_user_id);
        $data = array(
                    'from' => $from_user_id,
                    'to_user' => $to_user_id,
                    'to_project'  => $project_id,
                    'body'    => "Congratulations! You have been accepted by ".$fullname." to join the project ".$project_title,
                    'type'    => 'join_approved',
                    'datetime' => date("Y-m-d H:i:s", time())
                    );

        $this->db->insert('spw_notification',$data);
    }

    public function create_change_project_notification_for_user($approver_user, $approved_user, $project_id)
    {
        $project_title = $this->spw_project_model->get_project_title($project_id);

        $fullname = $this->spw_user_model->get_fullname($approver_user);
        $data = array(
                    'from' => $approver_user,
                    'to_user' => $approved_user,
                    'to_project'  => $project_id,
                    'body'    => "Congratulations! You have been accepted by ".$fullname." to join the project ".$project_title. "Please click \"Accept\" if you want to leave your current ptoject and join this one. \"Reject\" otherwise",
                    'type'    => 'join_approved',
                    'datetime' => date("Y-m-d H:i:s", time())
                    );

        $this->db->insert('spw_notification',$data);
    }

    public function create_change_project_accepted_notification_for_project($approver_user,$approved_user, $project_id)
    {
        //
        //Do it!
        // $team_members = $this->spw_project_model->get_team_members($project_id);
        // for($i = 0; $i < count($team_members); $i++) {
        //     create_professor_approval_rejected_notification_to_user( $team_members[$i], $project_id);
        // }
    }

    public function create_join_rejected_notification_for_user($from_user_id, $to_user_id, $project_id)
    {
        $project_title = $this->spw_project_model->get_project_title($project_id);

        $fullname = $this->spw_user_model->get_fullname($from_user_id);
        $data = array(
                    'from' => $from_user_id,
                    'to_user' => $to_user_id,
                    'to_project'  => $project_id,
                    'body'    =>  "Your request to join the project ".$project_title. "has been denied by".$fullname,
                    'type'    => 'join_rejected',
                    'datetime' => date("Y-m-d H:i:s", time())
                    );

        $this->db->insert('spw_notification',$data);
    }

    public function create_professor_approval_rejected_notification($project_id)
    {
         $team_members = $this->spw_project_model->get_team_members($project_id);
         for($i = 0; $i < count($team_members); $i++) {
             create_professor_approval_rejected_notification_to_user( $team_members[$i], $project_id);
         }
    }


    public function create_professor_approval_approved_notification($project_id)
    {
         $team_members = $this->spw_project_model->get_team_members($project_id);
         for($i = 0; $i < count($team_members); $i++) {
             create_professor_approval_approved_notification_to_user( $team_members[$i], $project_id);
         }
    }

    public function create_professor_approval_approved_notification_to_user($to_user_id, $project_id)
    {
        $project_title = $this->spw_project_model->get_project_title($project_id);

        $data = array(
                    'to_user' => $to_user_id,
                    'to_project'  => $project_id,
                    'body'    =>  "Your project ".$project_title. "has been approved by the professor",
                    'type'    => 'professor_approval',
                    'datetime' => date("Y-m-d H:i:s", time())
                    );

        $this->db->insert('spw_notification',$data);
    }

    public function create_professor_approval_rejected_notification_to_user($user_id, $project_id)
    {
        $project_title = $this->spw_project_model->get_project_title($project_id);

        $data = array(
                    'to_user' => $to_user_id,
                    'to_project'  => $project_id,
                    'body'    =>  "Your project ".$project_title. "has been rejected by the professor",
                    'type'    => 'professor_approval',
                     'datetime' => date("Y-m-d H:i:s", time())
                    );

        $this->db->insert('spw_notification',$data);
    }

    public function create_member_added_notification_for_project($approver_user, $approved_user, $project_id)
    {
         $team_members = $this->spw_project_model->get_team_members($project_id);


         for($i = 0; $i < count($team_members); $i++) {
            if($team_members[$i] != $approver_user && $team_members[$i] != $approved_user){
                $this->create_member_added_notification_for_user($approver_user, $approved_user, $team_members[$i], $project_id);
            }
                
         }
    }

    private function create_member_added_notification_for_user($approver_user, $approved_user, $user_id, $project_id)
    {

        $approver_user_fullname = $this->spw_user_model->get_fullname($approver_user);
        $approved_user_fullname = $this->spw_user_model->get_fullname($approved_user);
        $project_title = $this->spw_project_model->get_project_title($project_id);

        $data = array(
                    'from'    => $approver_user,  
                    'to_user' => $user_id,
                    'to_project'  => $project_id,
                    'body'    =>  $approved_user_fullname." was approved by ".$approver_user_fullname." to join your project ". $project_title,
                    'type'    => 'member_added',
                    'datetime' => date("Y-m-d H:i:s", time())
                    );

        $this->db->insert('spw_notification',$data);
    }

    public function create_member_rejected_notification_for_project($approver_user, $approved_user, $project_id)
    {
         $team_members = $this->spw_project_model->get_team_members($project_id);
         for($i = 0; $i < count($team_members); $i++) {
            if($team_members[$i] != $approver_user){
                $this->create_member_rejected_notification_for_user($approver_user, $approved_user, $team_members[$i] , $project_id);
            }
        }
    }

    private function create_member_rejected_notification_for_user($approver_user, $approved_user, $user_id, $project_id)
    {
        $approver_user_fullname = $this->spw_user_model->get_fullname($approver_user);
        $approved_user_fullname = $this->spw_user_model->get_fullname($approved_user);
        $project_title = $this->spw_project_model->get_project_title($project_id);

        $data = array(
                    'from'    => $approver_user,  
                    'to_user' => $user_id,
                    'to_project'  => $project_id,
                    'body'    =>  $approved_user_fullname." was rejected by ".$approver_user_fullname."to join your project ". $project_title,
                    'type'    => 'member_added',
                    'datetime' => date("Y-m-d H:i:s", time())
                    );

        $this->db->insert('spw_notification',$data);
    }


    public function get_active_notification_for_user($user_id)
    {
        $query = $this->db
                      ->where('to_user', $user_id)
                      ->where('is_read_flag', 0)
                      ->order_by('id', "desc")
                      ->get('spw_notification');

        if($query->num_rows() > 0)
        {
            return $query->result();
        }
        else
        {
            return null;
        }
    }
}
    
?>