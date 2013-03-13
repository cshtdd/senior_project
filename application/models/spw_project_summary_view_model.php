<?php

class SPW_Project_Summary_View_Model extends CI_Model
{
	//a SPW_Project_Model object
	public $project;

	//a SPW_Term_Model object
	public $term;

	//an array of SPW_Skill_Model objects
	public $lSkills;

	//an array of SPW_User_Summary_View_Model
	public $lMentorSummaries;

	//a SPW_User_Summary object
	public $proposedBySummary;

	//an array of SPW_User_Summary_View_Model
	public $lTeamMemberSummaries;

	public $justList;

	public $displayJoin;
	public $displayLeave;

	public function __construct()
	{
		parent::__construct();
	}

	public function getShortDescription()
	{
		return substr($this->project->description, 0, min(200, strlen($this->project->description)));
	}

	public function getlSkillNames()
	{
		$resultArray = array();

		foreach ($this->lSkills as $iSkill)
		{
			$resultArray[] = $iSkill->name;
		}

		$resultStr = join(', ', $resultArray);
		return $resultStr;
	}

	/* obtaining the term info for a list of projects with the same delivery_term to determine
	   if the closed_requests date passed so the users will be able to see the projects but not
	   able to join any of them */
	public function getProjectTermInfo($project_Id)
	{
		$param[0] = $project_Id;
		$sql = 'select spw_term.*
		        from spw_term, spw_project
		        where (spw_project.id = ?) and (spw_project.delivery_term = spw_term.id)';
		$query = $this->db->query($sql, $param);

		if ($query->num_rows() > 0)
		{
			$res = $query->row(0, 'SPW_Term_Model');

			return $res;
		}

		return NULL;
	}

	/* this function fills a list of projects with their data */
	public function prepareProjectsDataToShow($lProjectIds, $belongProjectIdsList, $pastProjects)
	{
		$length = count($lProjectIds);

		$lProjects = array();

		for ($i = 0; $i < $length; $i++)
		{
			$project_summ_vm = new SPW_Project_Summary_View_Model();

			$param[0] = $lProjectIds[$i];
			//get project info
			$sql = 'select *
					from spw_project
					where (id = ?)';
			$query = $this->db->query($sql, $param);

			if ($query->num_rows() > 0)
			{
				$row = $query->row(0, 'SPW_Project_Model');
				$project = $row;
				$project_summ_vm->project = $project;

				$project_summ_vm->justList = true;

				$term = $this->getProjectTermInfo($project->id);

				if (isset($term))
				{
					$currentDate = date('Y-m-d');
					if ($term->closed_requests > $currentDate)
					{
						$project_summ_vm->justList = false;
					}

					$project_summ_vm->term = $term;
				}

				$param[0] = $project->id;
				//get list of skills info of the project
				$sql2 = 'select spw_skill.*
						 from spw_skill, (select skill
						 				  from spw_skill_project
						 				  where (project = ?)) as sIds
						 where (spw_skill.id = sIds.skill)';
				$query2 = $this->db->query($sql2, $param);
				$skillNum = $query2->num_rows();
				$lSkills = array();
				if ($skillNum > 0)
				{
					for ($j = 0; $j < $skillNum; $j++)
					{
						$row = $query2->row($j, 'SPW_Skill_Model');
						$lSkills[] = $row;
					}

				}
				$project_summ_vm->lSkills = $lSkills;

				//get the users that belong to the project as mentors
				$sql3 = 'select spw_user.*
						 from spw_user, spw_mentor_project
						 where (spw_mentor_project.project = ?) and (spw_mentor_project.mentor = spw_user.id)';
				$query3 = $this->db->query($sql3, $param);
				$mentorsNum = $query3->num_rows();
				$lMentorsSumm = array();
				if ($mentorsNum > 0)
				{
					for ($j = 0; $j < $mentorsNum; $j++)
					{
						$row = $query3->row($j, 'SPW_User_Model');
						$user_summ_vm = new SPW_User_Summary_View_Model();
						$user_summ_vm->user = $row;
						$lMentorsSumm[] = $user_summ_vm;
					}
				}
				$project_summ_vm->lMentorSummaries = $lMentorsSumm;

				//get the proposed_by user info of the project
				$sql4 = 'select spw_user.*
						 from spw_user, (select proposed_by
						 	             from spw_project
						 	             where (id = ?)) as uId
						 where (spw_user.id = uId.proposed_by)';
				$query4 = $this->db->query($sql4, $param);
				$row = $query4->row(0, 'SPW_User_Model');
				$proposedBySumm = new SPW_User_Summary_View_Model();
				$proposedBySumm->user = $row;
				$project_summ_vm->proposedBySummary = $proposedBySumm;

				//get the students info that belong to the project
				$sql5 = 'select allUsersBelong.*
						 from spw_role_user, (select *
						 					  from spw_user
						 					  where (project = ?)) as allUsersBelong
						 where (spw_role_user.user = allUsersBelong.id) and (spw_role_user.role = 5)';
				$query5 = $this->db->query($sql5, $param);
				$studentsNum = $query5->num_rows();
				$lStudentsSumm = array();
				if ($studentsNum > 0)
				{
					for ($j = 0; $j < $studentsNum; $j++)
					{
						$row = $query5->row($j, 'SPW_User_Model');
						$user_summ_vm = new SPW_User_Summary_View_Model();
						$user_summ_vm->user = $row;
						$lStudentsSumm[] = $user_summ_vm;
					}
				}
				$project_summ_vm->lTeamMemberSummaries = $lStudentsSumm;

				if (!($project_summ_vm->justList))
				{
					if (!$pastProjects)
					{
						//if ($this->isProjectInList($belongProjectIdsList, $lProjectIds[$i]))
						if (in_array($lProjectIds[$i], $belongProjectIdsList))
						{
							$project_summ_vm->displayLeave = TRUE;
							$project_summ_vm->displayJoin = FALSE;
						}
						else
						{
							$project_summ_vm->displayLeave = FALSE;
							$project_summ_vm->displayJoin = TRUE;
						}	
					}
				} 
			}
			else
			{
				throw new Exception('spw_project table error in db...');
			}

			$lProjects[] = $project_summ_vm;
		}
		return $lProjects;
	}

	public function isProjectInList($belongProjIdsList, $project_Id)
	{
		$length = count($belongProjIdsList);

		for ($i = 0; $i < $length; $i++)
		{
			if ($belongProjIdsList[$i] == $project_Id)
				return true;
		}

		return NULL;
	}

}
	
?>