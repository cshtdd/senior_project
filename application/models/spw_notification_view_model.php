<?php

class SPW_Notification_View_Model extends CI_Model
{
    //these are the only two fields that should be passed when creating this object
    //the notification Id
    public $id;
    //the message to display
    public $message;

    public $alertClass = 'alert-info';

    public $buttonText = 'Dismiss';
    public $buttonActionWithoutParameters = 'notificationscontroller/hide_notification/';

    //wether we are going to display the accept/reject buttons
    //or just the close
    public $displayTwoButtons;

    public $buttonLeftText = 'Reject';
    public $buttonLeftActionWithoutParameters = 'notificationscontroller/reject_notification/';

    public $buttonRightText = 'Accept';
    public $buttonRightActionWithoutParameters = 'notificationscontroller/accept_notification/';

    public function __construct()
    {
        parent::__construct();
    }

    public function getSingleButtonAction()
    {
        return $this->buttonActionWithoutParameters.$this->id;
    }

    public function getLeftButtonAction()
    {
        return $this->buttonLeftActionWithoutParameters.$this->id;
    }

    public function getRightButtonAction()
    {
        return $this->buttonRightActionWithoutParameters.$this->id;
    }
}
    
?>