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
    
    
}