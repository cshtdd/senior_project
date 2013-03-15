<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( !function_exists('isFlashMessageAvailable'))
{
    function isFlashMessageAvailable($sender_controller)
    {
        $flashMessage = $sender_controller->session->flashdata('FLASH_MESSAGE');

        if (isset($flashMessage) && strlen($flashMessage) > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}

if ( !function_exists('getFlashMessage'))
{
    function getFlashMessage($sender_controller)
    {
        $flashMessage = $sender_controller->session->flashdata('FLASH_MESSAGE');

        if (isset($flashMessage) && strlen($flashMessage) > 0)
        {
            return $flashMessage;
        }
        else
        {
            return '';
        }
    }
}

if ( !function_exists('setFlashMessage'))
{
    function setFlashMessage($sender_controller, $msg)
    {
        $sender_controller->session->set_flashdata('FLASH_MESSAGE', $msg);
    }
}

?>