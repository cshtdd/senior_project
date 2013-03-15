<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class NotificationsController extends CI_Controller 
{
    
    public function __construct()
    {
        parent::__construct();

        $this->load->model('SPW_Notification_View_Model');
        $this->load->helper('request');
        //$this->output->cache(60);
    }

    public function display_notifications()
    {
        $this->output->set_output('you have notifications');
    }



    public function accept_notification($notificationId)
    {
        if (!is_POST_request($this))
        {
            redirect('/');
        }
        else
        {
            //TODO with the notification Id take the appropriate action

            $this->output->set_output('notification accepted');
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
            //TODO with the notification Id take the appropriate action

            $this->output->set_output('notification rejected');
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
            //TODO with the notification Id take the appropriate action

            $this->output->set_output('notification hidden');
        }
    }
}