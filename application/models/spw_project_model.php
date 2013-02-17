<?php

class SPW_Project_Model extends CI_Model
{
	public $id;
	public $title;
	public $project_short_description;
	public $project_close_date;
	
	public function __construct()
	{
		parent::__construct();
	}
}
	
?> 