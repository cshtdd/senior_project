<?php

class SPW_Term_Model extends CI_Model
{
	public $id;
	public $name;
	public $description;
	public $start_date;
	public $end_date;
	//TODO: WTF is this field, could it be that there are no more requests to be taken
	public $closed_requests;

	public function __construct()
	{
		parent::__construct();
	}
}
	
?>