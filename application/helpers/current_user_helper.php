<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( !function_exists('getCurrentUserId'))
{
    function getCurrentUserId($sender_controller)
    {
        if (is_test($sender_controller))
        {
            return 101;
        }
        else
        {
            if($sender_controller->session->userdata('logged_in'))
            {
                $session_data = $sender_controller->session->userdata('logged_in');
                    $user_id = $session_data['id'];
                return $user_id;
            }
            else
            {
               return -1;
            }
        }
    }
}

if ( !function_exists('isUserLoggedIn'))
{
    function isUserLoggedIn($sender_controller)
    {
        if (is_test($sender_controller))
        {
            return true;
        }
        else
        {
            if($sender_controller->session->userdata('logged_in'))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }
}

?>
