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

            throw new Exception('not implemented');
        }
    }
}

?>