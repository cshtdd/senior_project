<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class NotificationsController extends CI_Controller 
{
    
    public function __construct()
    {
        parent::__construct();

        $this->load->model('SPW_Notification_View_Model');
        $this->load->helper('request');
    }

    public function display_notifications()
    {
        //$this->output->set_output('you have notifications');

        if (!isUserLoggedIn($this))
        {
            redirect('/');
        }
        else
        {
            $currentUserId = getCurrentUserId($this);
            $lNotifications = $this->getPendingNotificationsForUserInternal($currentUserId);

            if (isset($lNotifications) && count($lNotifications) > 0)
            {
                $data['no_results'] = false;
                $data['lNotifications'] = $lNotifications;
            }
            else
            {
                $data['no_results'] = true;
            }

            $data['title'] = 'Notifications';
            $this->load->view('notifications_display_notifications', $data);
        }
    }



    public function accept_notification($notificationId)
    {
        if (!is_POST_request($this))
        {
            redirect('/');
        }
        else
        {
            
            $this->load->model('spw_notification_model');
            $this->spw_notification_model->set_notification_to_read($notificationId);

            $this->output->set_output('notification accepted');

            //redirect back to the previous page
            $pbUrl = $this->input->post('pbUrl');
            if (isset($pbUrl) && strlen($pbUrl))
            {
                redirect($pbUrl);
            }
        }
    }

    public function reject_notification($notificationId)
    {
        if (!is_POST_request($this))
        {
            redirect('/');
        }
        else
        {
            $this->load->model('spw_notification_model');
            $this->spw_notification_model->set_notification_to_read($notificationId);

            $this->output->set_output('notification rejected');

            //redirect back to the previous page
            $pbUrl = $this->input->post('pbUrl');
            if (isset($pbUrl) && strlen($pbUrl))
            {
                redirect($pbUrl);
            }
        }
    }

    public function hide_notification($notificationId)
    {
        if (!is_POST_request($this))
        {
            redirect('/');
        }
        else
        {
            $this->load->model('spw_notification_model');
            $this->spw_notification_model->set_notification_to_read($notificationId);

            $this->output->set_output('notification hidden');

            //redirect back to the previous page
            $pbUrl = $this->input->post('pbUrl');
            if (isset($pbUrl) && strlen($pbUrl))
            {
                redirect($pbUrl);
            }
        }
    }

    private function getPendingNotificationsForUserInternal($userId)
    {
        if (is_test($this))
        {
            return $this->getPendingNotificationsForUserInternalTest($userId);
        }
        else
        {
            return $this->getPendingNotificationsForUser($userId);
        }
    }

    private function isALeaveMsg($msg)
    {
        if(strpos($msg, 'leave') != false){
            return true;
        }else{
            return false;
        }
    }

    private function getPendingNotificationsForUser($userId)
    {
        $this->load->model('swp_notification_model');
        $query = $this->get_notifications_by_user($user_id); 

        $notifications = array();
        foreach ($query->result() as $row)
        {
            $notification = new SPW_Notification_View_Model();
            $notification->id = $row->id;
            $msg = $row->body;
            $notification->message = $msg;
            $notification->displayTwoButtons = !isALeaveMsg($msg);
            array_push($notifications, $notification);
        }

        return $notifications;
    }


    private function getPendingNotificationsForUserInternalTest($userId)
    {
        $notification_vm1 = new SPW_Notification_View_Model();
        $notification_vm1->id = 1;
        $notification_vm1->message = 'Lolo Gonzalez wants to join your team';
        $notification_vm1->displayTwoButtons = true;


        $notification_vm2 = new SPW_Notification_View_Model();
        $notification_vm2->id = 2;
        $notification_vm2->message = 'Kyle Broflovsky left your team';


        $notification_vm3 = new SPW_Notification_View_Model();
        $notification_vm3->id = 3;
        $notification_vm3->message = 'You have been invited to join the Facebook Moodle integration project';
        $notification_vm3->displayTwoButtons = true;


        $notification_vm4 = new SPW_Notification_View_Model();
        $notification_vm4->id = 4;
        $notification_vm4->message = 'Your new project NASA in FIU has been approved';


        $lNotifications = array(
                $notification_vm1,
                $notification_vm2,
                $notification_vm3,
                $notification_vm4
            );
        return $lNotifications;
    }
}