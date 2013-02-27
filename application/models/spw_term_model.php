<?php

class SPW_Term_Model extends CI_Model
{
	public $id;
	public $name;
	public $description;
	public $start_date;
	public $end_date;
	/* this field is the due date where users can choose projects
	   or leave and join another */
	public $closed_requests;

	public function __construct()
	{
		parent::__construct();
	}
}
	
?>