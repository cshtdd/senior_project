<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( !function_exists('currentUserHasMultipleProjects'))
{
    function currentUserHasMultipleProjects($sender_controller)
    {
        if (!isUserLoggedIn($sender_controller))
        {
            return false;
        }
        else
        {
            if (is_test($sender_controller))
            {
                return true;
            }
            else
            {
                $sender_controller->load->model('SPW_User_Model');

                $currentUserId = getCurrentUserId($sender_controller);
                $lProjectIds = $SPW_User_Model->userHaveProjects($currentUserId);
                return isset($lProjectIds) && count($lProjectIds) > 1;
            }
        }
    }
}

?>