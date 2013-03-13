<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( !function_exists('load_project_summary_models'))
{
    function load_project_summary_models($sender_controller)
    {
        $sender_controller->load->model('SPW_Project_Model');
        $sender_controller->load->model('SPW_Term_Model');
        $sender_controller->load->model('SPW_Skill_Model');
        $sender_controller->load->model('SPW_User_Model');
        $sender_controller->load->model('SPW_Project_Status_Model');
        $sender_controller->load->model('SPW_User_Summary_View_Model');
        $sender_controller->load->model('SPW_Project_Summary_View_Model');
    }
}

?>