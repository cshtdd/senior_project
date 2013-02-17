<?php

class SPW_User_Model extends CI_Model
{
	public $id;
	public $first_name;
	public $last_name;
	public $email;
	//this will be a url
	public $picture;
	//didn't we talk about a separate table... ahhh I remember,
	//this field will be blank fro google users 
	public $hash_pwd;
	public $summary;

	//the id of the SPW_Term
	public $graduation_term;
	//the id of the SPW_Project
	public $project;


	public function __construct()
	{
		parent::__construct();
	}
}
	
?>