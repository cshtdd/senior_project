<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( !function_exists('is_POST_request'))
{
    function is_POST_request($sender_controller)
    {
        $reqVerb = $sender_controller->input->server('REQUEST_METHOD');
        if (!isset($reqVerb) || strlen($reqVerb) == 0)
        {
            $reqVerb = 'GET';
        }

        return strtoupper($reqVerb) == 'POST';
    }
}

if ( !function_exists('is_GET_request'))
{
    function is_GET_request($sender_controller)
    {
        $reqVerb = $sender_controller->input->server('REQUEST_METHOD');
        if (!isset($reqVerb) || strlen($reqVerb) == 0)
        {
            $reqVerb = 'GET';
        }

        return strtoupper($reqVerb) == 'GET';
    }
}

?>