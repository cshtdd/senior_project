<?php

class SPW_Project_Model extends CI_Model
{
	public $id;
	public $title;
	public $description;
	public $max_students;
	public $proposed_by;
	public $delivery_term;
	public $status;

	//remove this later
	public $project_close_date;
	
	public function __construct()
	{
		parent::__construct();
	}
}
	
?>