<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();

class HomeController extends CI_Controller 
{
	private $is_test = true;

	public function __construct()
	{
		parent::__construct();

		$this->load->helper('project_summary_view_model');
		load_project_summary_models($this);

		//$this->output->cache(60);
	}

	public function index()
	{
        if($this->session->userdata('logged_in'))
        {
			$lSuggestedProjectIds = $this->getSuggestedProjectsForCurrentUser();

			$lRegularProjectsIds =  $this->getRegularProjectsForCurrentUser($lSuggestedProjectIds);

			$lSuggestedProjects = $this->prepareProjectsToShow($lSuggestedProjectIds);

			$lRegularProjects = $this->prepareProjectsToShow($lRegularProjectsIds);

			if ( (!isset($lSuggestedProjects) || count($lSuggestedProjects) == 0) &&
				(!isset($lRegularProjects) || count($lRegularProjects) == 0))
			{
				$no_results = true;
			}
			else
			{
				$no_results = false;
			}

			$data['title'] = 'Project Suggestions';
			$data['no_results'] = $no_results;
			$data['lSuggestedProjects'] = $lSuggestedProjects;
			$data['lRegularProjects'] = $lRegularProjects;

			$this->load->view('home_index', $data);

			//$this->output->set_output('home ');
        }
        else
        {
        	redirect('login','refresh');
        }
	}

	public function logout()
	{
		$this->session->unset_userdata('logged_in');
	    session_destroy();
	    redirect('login', 'refresh');
	}	


	private function getSuggestedProjectsForCurrentUser()
	{
		return $this->SPW_User_Model->getSuggestedProjectsGivenCurrentUser(getCurrentUserId($this));
	}

	private function getRegularProjectsForCurrentUser($lSuggProjectIds)
	{
		return $this->SPW_Project_Model->getRegularProjectIds($lSuggProjectIds);
	}

	private function prepareProjectsToShow($lProjectsIds)
	{
		$projectId = $this->SPW_User_Model->userHaveProject(getCurrentUserId($this));
		return $this->SPW_Project_Summary_View_Model->prepareProjectsDataToShow($lProjectsIds, $projectId);
	}

}