<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( !function_exists('getUserImageSrc'))
{
    function getUserImage($sender_controller, $imgSrc)
    {
        if (!isset($imgSrc) || strlen($imgSrc) == 0)
        {
            return '/img/no-photo.jpeg';
        }
        else
        {
            return $imgSrc;
        }
    }
}

?>