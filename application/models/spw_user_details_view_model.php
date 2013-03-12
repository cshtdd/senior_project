<?php

class SPW_User_Details_View_Model extends SPW_User_Summary_View_Model
{
	//an array of SPW_Skill_Model objects
	public $lSkills;
	//an array of SPW_Experience_Model objects
	public $lExperiences;
	//an array of SPW_Language_Model objects
	public $lLanguages;
	//a SPW_Role_Model object
	public $role;

	public function __construct()
	{
		parent::__construct();
	}
}
	
?>