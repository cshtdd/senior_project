<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( !function_exists('getCurrentUserHeaderName'))
{
    function getCurrentUserHeaderName($sender_controller)
    {
        if (is_test($sender_controller))
        {
            return 'Camilo';
        }
        else
        {
            throw new Exception('not implemented');
        }
    }
}

if ( !function_exists('getCurrentUserHeaderImg'))
{
    function getCurrentUserHeaderImg($sender_controller)
    {
        if (is_test($sender_controller))
        {
            return 'https://si0.twimg.com/profile_images/635660229/camilin87_bigger.jpg';
        }
        else
        {
            throw new Exception('not implemented');
        }
    }
}

if ( !function_exists('getCurrentUserHeaderFullName'))
{
    function getCurrentUserHeaderFullName($sender_controller)
    {
        if (is_test($sender_controller))
        {
            return 'Camilo Sanchez';
        }
        else
        {
            throw new Exception('not implemented');
        }
    }
}

?>
