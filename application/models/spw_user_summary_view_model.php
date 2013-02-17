<?php

class SPW_User_Summary_View_Model extends CI_Model
{
	//a SPW_User_Model object
	public $user;

	public function __construct()
	{
		parent::__construct();
	}

	public function getFullName()
	{
		return ucwords($this->user->first_name.' '.$this->user->last_name);
	}
}
	
?>