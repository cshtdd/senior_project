<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( !function_exists('isFlashMessageAvailable'))
{
    function isFlashMessageAvailable($sender_controller)
    {
        if (is_test($sender_controller))
        {
            //return true;
        }

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
        if (is_test($sender_controller))
        {
            //return 'This is a flash message';
        }

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

if ( !function_exists('getFlashMessageClass'))
{
    function getFlashMessageClass($sender_controller)
    {
        if (is_test($sender_controller))
        {
            //return 'This is a flash message';
        }

        $flashMessageClass = $sender_controller->session->flashdata('FLASH_MESSAGE_CLASS');

        if (isset($flashMessageClass) && strlen($flashMessageClass) > 0)
        {
            return $flashMessageClass;
        }
        else
        {
            return '';
        }
    }
}

if ( !function_exists('setFlashMessage'))
{
    function setFlashMessage($sender_controller, $msg, $msg_class='')
    {
        $sender_controller->session->set_flashdata('FLASH_MESSAGE', $msg);
        $sender_controller->session->set_flashdata('FLASH_MESSAGE_CLASS', $msg_class);
    }
}

if ( !function_exists('setErrorFlashMessage'))
{
    function setErrorFlashMessage($sender_controller, $msg)
    {
        setFlashMessage($sender_controller, $msg, 'alert-error');
    }
}

?>