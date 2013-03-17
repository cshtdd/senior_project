<?php

class SPW_Experience_Model extends CI_Model
{
	public $id;

	//just the user id
	public $user;

	public $company_name;

	public $company_industry;

	public $start_date;

	public $end_date;

	public $title;

	public $summary;

	public function __construct()
	{
		parent::__construct();
	}
}
	
?>