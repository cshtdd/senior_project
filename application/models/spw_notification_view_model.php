<?php

class SPW_Notification_View_Model extends CI_Model
{
    public $id;
    public $message;


    public $buttonText = 'Hide';
    public $buttonActionWithoutParameters = 'notificationscontroller/hide_notification/';

    //wether we are going to display the accept/reject buttons
    //or just the close
    public $displayTwoButtons;

    public $buttonLeftText = 'Accept';
    public $buttonLeftActionWithoutParameters = 'notificationscontroller/accept_notification/';

    public $buttonRightText = 'Reject';
    public $buttonRightActionWithoutParameters = 'notificationscontroller/reject_notification/';

    public function __construct()
    {
        parent::__construct();
    }

    public function getSingleButtonAction()
    {
        return $buttonActionWithoutParameters.$id;
    }

    public function getLeftButtonAction()
    {
        return $buttonLeftActionWithoutParameters.$id;
    }

    public function getRightButtonAction()
    {
        return $buttonRightActionWithoutParameters.$id;
    }
}
    
?>