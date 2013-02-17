<?php

class SPW_Project_Summary_View_Model extends CI_Model
{
	//a SPW_Project_Model object
	public $project;

	//a SPW_Term_Model object
	public $term;

	public function __construct()
	{
		parent::__construct();
	}

	public function getShortDescription()
	{
		return substr($this->project->description, 0, min(20, strlen($this->project->description)));
	}
}
	
?>