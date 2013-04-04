<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( !function_exists('shouldPresentWarningOnCreateProject'))
{
    function shouldPresentWarningOnCreateProject($sender_controller)
    {

        if (is_test($sender_controller))
        {
            return true;
        }
        else
        {
            $currentUserId = getCurrentUserId($sender_controller);

            $sender_controller->load->model('SPW_User_Model');

            if ($sender_controller->SPW_User_Model->isUserAStudent($currentUserId))
            {
                $lBelongProjects = $sender_controller->SPW_User_Model->userHaveProjects($currentUserId);

                if (isset($lBelongProjects) && count($lBelongProjects)>0)
                    return true;
            }
            
            return false;
        }
    }
}

?>