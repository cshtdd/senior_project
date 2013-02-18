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
	public $teamLeaderSummary;

	//an array of SPW_User_Summary_View_Model
	public $lTeamMemberSummaries;

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
}
	
?>