<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ProjectController extends CI_Controller 
{

	public function __construct()
	{
		parent::__construct();

		$this->load->helper('project_summary_view_model');
		load_project_summary_models($this);

		//$this->output->cache(60);
	}


	public function details($project_id='')
	{
		if (isset($project_id))
		{
			$this->output->set_output('project details '.$project_id);
		}
		else
		{
			$this->output->set_output('You have not joined to a project yet...');
		}
	}

	public function current_project()
	{
		$current_project_id = $this->getCurrentProjectId();
		$this->details($current_project_id);
	}

	public function past_projects()
	{
		$lProjectIds = $this->SPW_Project_Model->getPastProjects();
		$lProjects = $this->SPW_Project_Summary_View_Model->prepareProjectsDataToShow($lProjectIds, NULL, TRUE);
		if ( (!isset($lProjects) || count($lProjects) == 0) )
		{
			$no_results = true;
		}
		else
		{
			$no_results = false;
		}

		$data['title'] = 'Past Projects';
		$data['no_results'] = $no_results;
		$data['lProjects'] = $lProjects;

		$this->load->view('project_past_projects', $data);
	}


	private function getCurrentProjectId()
	{
		if (is_test($this))
		{
			return 100;
		}
		else
		{
			return $this->SPW_User_Model->userHaveProject(getCurrentUserId($this));
		}
	}

	private function getPastProjectsInternal()
	{
		if (is_test($this))
		{
			return $this->getPastProjectsInternalTest();
		}
		else
		{
			throw new Exception('not implemented');
		}
	}
	private function getPastProjectsInternalTest()
	{
		$projStatus = new SPW_Project_Status_Model();
		$projStatus->id = 2;
		$projStatus->name = 'Closed';		

		$term1 = new SPW_Term_Model();
		$term1->id = 2;
		$term1->name = 'Summer 2012';
		$term1->description = 'Summer 2012';
		$term1->start_date = '5-1-2012';
		$term1->end_date = '8-24-2012';


		$skill1 = new SPW_Skill_Model();
		$skill1->id = 0;
		$skill1->name = 'Cobol';

		$skill2 = new SPW_Skill_Model();
		$skill2->id = 1;
		$skill2->name = 'Matlab';

		$skill3 = new SPW_Skill_Model();
		$skill3->id = 2;
		$skill3->name = 'Gopher';

		$skill4 = new SPW_Skill_Model();
		$skill4->id = 3;
		$skill4->name = 'bash';

		$lSkills1 = array(
			$skill1,
			$skill2,
			$skill3,
			$skill4
		);


		$skill5 = new SPW_Skill_Model();
		$skill5->id = 4;
		$skill5->name = 'Modem At commands';

		$skill6 = new SPW_Skill_Model();
		$skill6->id = 5;
		$skill6->name = 'ISAPI';

		$lSkills2 = array(
			$skill5,
			$skill6
		);


		$user1 = new SPW_User_Model();
		$user1->id = 0;
		$user1->first_name = 'Steven';
		$user1->last_name = 'Luis Sr.';

		$user_summ_vm1 = new SPW_User_Summary_View_Model();
		$user_summ_vm1->user = $user1;

		$user2 = new SPW_User_Model();
		$user2->id = 1;
		$user2->first_name = 'Lolo';
		$user2->last_name = 'Gonzalez Sr.';

		$user_summ_vm2 = new SPW_User_Summary_View_Model();
		$user_summ_vm2->user = $user2;

		$user3 = new SPW_User_Model();
		$user3->id = 2;
		$user3->first_name = 'Karen';
		$user3->last_name = 'Rodriguez Sr.';

		$user_summ_vm3 = new SPW_User_Summary_View_Model();
		$user_summ_vm3->user = $user3;

		$user4 = new SPW_User_Model();
		$user4->id = 3;
		$user4->first_name = 'Gregory';
		$user4->last_name = 'Zhao Sr.';

		$user_summ_vm4 = new SPW_User_Summary_View_Model();
		$user_summ_vm4->user = $user4;




		$project1 = new SPW_Project_Model();
		$project1->id = 1;
		$project1->title = 'Cobol Free Music Sharing Platform';
		$project1->description = 'Poor students need an easy way to access all the music in the world for free.';
		$project1->status = $projStatus;

		$project_summ_vm1 = new SPW_Project_Summary_View_Model();
		$project_summ_vm1->project = $project1;
		$project_summ_vm1->term = $term1;
		$project_summ_vm1->lSkills = $lSkills1;
		$project_summ_vm1->lMentorSummaries = array($user_summ_vm1);
		$project_summ_vm1->teamLeaderSummary = $user_summ_vm3;


		$project2 = new SPW_Project_Model();
		$project2->id = 2;
		$project2->title = 'Dialup Moodle on Facebook';
		$project2->description = 'Poor students need an easy way to access all the music in the world for free. This Project will make every student really happy.';
		$project2->status = $projStatus;

		$project_summ_vm2 = new SPW_Project_Summary_View_Model();
		$project_summ_vm2->project = $project2;
		$project_summ_vm2->term = $term1;
		$project_summ_vm2->lSkills = $lSkills2;
		$project_summ_vm2->lMentorSummaries = array($user_summ_vm1);
		$project_summ_vm2->lTeamMemberSummaries = array($user_summ_vm4);
		$project_summ_vm2->teamLeaderSummary = $user_summ_vm2;

		$lProjects = array(
			$project_summ_vm1,
			$project_summ_vm2
		);

		return $lProjects;
	}
}