<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ProjectController extends CI_Controller 
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


	public function details($project_id='')
	{
		$this->output->set_output('project details '.$project_id);
	}

	public function current_project()
	{
		$current_project_id = $this->getCurrentProjectId();
		$this->details($current_project_id);
	}

	private function getCurrentProjectId()
	{
		if ($this->is_test)
		{
			return 0;
		}
		else
		{
			throw new Exception('not implemented');
		}
	}
}