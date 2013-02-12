<?php

class Project_Summary_Model extends CI_Model
{
	public $project_id;
	public $project_title;
	public $project_short_description;
	public $project_close_date;
	
	public function __construct()
	{
		parent::__construct();
	}
}
	
?>