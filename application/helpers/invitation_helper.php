<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( !function_exists('currentUserHasMultipleProjects'))
{
    function currentUserHasMultipleProjects($sender_controller)
    {
        if (is_test($sender_controller))
        {
            return true;
        }
        else
        {
            $sender_controller->load->model('SPW_User_Model');

            $currentUserId = getCurrentUserId($sender_controller);
            $lProjectIds = $sender_controller->SPW_User_Model->userHaveProjects($currentUserId);
            return isset($lProjectIds) && count($lProjectIds) > 1;
        }
    }
}

if ( !function_exists('getAnyProjectIdForCurrentUser'))
{
    function getAnyProjectIdForCurrentUser($sender_controller)
    {
        if (is_test($sender_controller))
        {
            return 100;
        }
        else
        {
            $sender_controller->load->model('SPW_User_Model');

            $currentUserId = getCurrentUserId($sender_controller);
            $lProjectIds = $sender_controller->SPW_User_Model->userHaveProjects($currentUserId);
            if (isset($lProjectIds) && count($lProjectIds) > 0)
            {
                return $lProjectIds[0];
            }
            else
            {
                return null;
            }
        }
    }
}

?>