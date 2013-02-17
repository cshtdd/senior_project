<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SearchController extends CI_Controller 
{
	private $is_test = true;

	public function __construct()
	{
		parent::__construct();

		$this->load->model('SPW_Project_Model');

		//$this->output->cache(60);
	}


	public function search($search_param='')
	{
		if (isset($search_param) && strlen($search_param) > 0)
		{
			$search_query = urldecode($search_param);

			$lProjects = $this->getProjectsWithSearchParam($search_query);

			if (isset($lProjects) && count($lProjects) > 0)
			{
				$data['lProjects'] = $lProjects;
				$data['no_results'] = false;
			}
			else
			{
				$data['no_results'] = true;
			}
		}
		else
		{
			$data['no_results'] = true;
		}

		$this->load->view('search_controller_search', $data);
	}

	private function getProjectsWithSearchParam($search_query)
	{
		if ($this->is_test)
		{
			return $this->getProjectsWithSearchParamTest($search_query);
		}
		else
		{
			throw new Exception('not implemented'); 
		}
	}
	private function getProjectsWithSearchParamTest($search_query)
	{
		$project1 = new SPW_Project_Model();
		$project1->id = 1;
		$project1->project_title = 'Free Music Sharing Platform';
		$project1->project_short_description = 'Poor students need an easy way to access all the music in the world for free.';
		$project1->project_close_date = '02-28-2013';

		$project2 = new SPW_Project_Model();
		$project2->id = 2;
		$project2->project_title = 'Moodle on Facebook';
		$project2->project_short_description = 'Poor students need an easy way to access all the music in the world for free. This Project will make every student really happy.';
		$project2->project_close_date = '02-28-2013';

		$lProjects = array(
			$project1, 
			$project2,
			$project1, 
			$project2,
			$project1, 
			$project2,
			$project1, 
			$project2,
			$project1, 
			$project2
		);

		return $lProjects;
	}
}