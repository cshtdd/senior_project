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

	public function __construct()
	{
		parent::__construct();
	}
}
	
?>