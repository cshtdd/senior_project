<?php

class SPW_Project_Model extends CI_Model
{
	public $id;
	public $title;
	public $description;
	public $max_students;

	//the id of the SPW_User
	public $proposed_by;
	//the id of the SPW_Term
	public $delivery_term;
	//The id of the SPW_Project_Status
	public $status;

	//TODO: remove this later
	public $project_close_date;
	
	public function __construct()
	{
		parent::__construct();
	}
}
	
?>