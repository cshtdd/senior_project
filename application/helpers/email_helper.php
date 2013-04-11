<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( !function_exists('send_email'))
{
    function send_email($sender_controller, $toEmail, $subject, $body)
    {
        //setFlashMessage($sender_controller, 'email sent');

        $sender_controller->email->clear();

        $sender_controller->email->from('noreply@fiu.edu', 'Senior Project Website');
        $sender_controller->email->reply_to('noreply@fiu.edu', 'Senior Project Website');
        $sender_controller->email->to($toEmail);
        $sender_controller->email->subject($subject);
        $sender_controller->email->message($body);
        $sender_controller->email->set_alt_message('Your email client does not support HTML email. Open this message on the web.');

        if (!$sender_controller->email->send())
        {
            //setErrorFlashMessage($sender_controller, 'error sending email');
            print_r('error sending email');
        }
    }
}

?>