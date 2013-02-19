<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UserController extends CI_Controller 
{
	private $is_test = true;

	public function __construct()
	{
		parent::__construct();

		/*
		$this->load->model('SPW_Project_Model');
		$this->load->model('SPW_Term_Model');
		$this->load->model('SPW_Skill_Model');
		$this->load->model('SPW_User_Model');
		$this->load->model('SPW_Project_Status_Model');
		$this->load->model('SPW_User_Summary_View_Model');
		$this->load->model('SPW_Project_Summary_View_Model');
		*/
		//$this->output->cache(60);
	}


	public function profile($user_id='')
	{
		$this->output->set_output('user profile '.$user_id);


		//$this->output->set_output(base_url());
	}

	public function current_user()
	{
		$current_user_id = $this->getCurrentUserId();
		$this->profile($current_user_id);
	} 

	private function getCurrentUserId()
	{
		if ($this->is_test)
		{
			return 101;
		}
		else
		{
			throw new Exception('not implemented');
		}
	}
}