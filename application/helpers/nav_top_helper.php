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
            $CI = get_instance();

            $CI->load->model('spw_user_model');

            $session_data = $sender_controller->session->userdata('logged_in');
            $user_id = $session_data['id'];
            return $CI->spw_user_model->get_first_name($user_id);
        }
    }
}

if ( !function_exists('getCurrentUserHeaderImg'))
{
    function getCurrentUserHeaderImg($sender_controller)
    {
        $result = '';

        if (is_test($sender_controller))
        {
            $result = 'https://si0.twimg.com/profile_images/635660229/camilin87_bigger.jpg';
            //$result = '';
        }
        else
        {
            $CI = get_instance();

            $CI->load->model('spw_user_model');

            $session_data = $sender_controller->session->userdata('logged_in');
            $user_id = $session_data['id'];
            $result = $CI->spw_user_model->get_picture($user_id);
        }

        if (!isset($result) || strlen($result) == 0)
        {
            $result = '/img/no-photo.jpeg';
        }

        return $result;
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
            $CI = get_instance();

            $CI->load->model('spw_user_model');

            $session_data = $sender_controller->session->userdata('logged_in');
            $user_id = $session_data['id'];
            return $CI->spw_user_model->get_fullname($user_id);
        }
    }
}

?>
