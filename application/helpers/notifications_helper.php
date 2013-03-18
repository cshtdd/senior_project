<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( !function_exists('getPendingNotificationsCount'))
{
    function getPendingNotificationsCount($sender_controller)
    {
        if (isUserLoggedIn($sender_controller))
        {
            $currentUserId = getCurrentUserId($sender_controller);

            if (is_test($sender_controller))
            {
                return 4;
            }
            else
            {
                //TODO get the notifications count pending for the $currentUserId 
                $CI = get_instance();

                $CI->load->model('spw_notification_model');

                $session_data = $sender_controller->session->userdata('logged_in');
                $user_id = $session_data['id'];
                return $CI->spw_notification_model->get_total_notifications($user_id);

            }
        }
        else
        {
            return 0;
        }
    }
}

?>