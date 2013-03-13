<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( !function_exists('is_test'))
{
    function is_test($sender_controller)
    {
        if ($sender_controller->config->item('IS_TEST'))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}

?>