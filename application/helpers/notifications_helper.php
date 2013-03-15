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
                throw new Exception('not implemented');
            }
        }
        else
        {
            return 0;
        }
    }
}

?>