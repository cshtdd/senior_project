<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class NotificationsController extends CI_Controller 
{
    
    public function __construct()
    {
        parent::__construct();
        //$this->output->cache(60);
    }

    public function display_notifications()
    {
        $this->output->set_output('you have notifications');
    }



    public function accept_notification($notificationId)
    {
        //with the notification Id take the appropriate action

        $this->output->set_output('notification accepted');
    }

    public function reject_notification($notificationId)
    {
        //with the notification Id take the appropriate action

        $this->output->set_output('notification rejected');
    }

    public function hide_notification($notificationId)
    {
        $this->output->set_output('notification hidden');
    }
}