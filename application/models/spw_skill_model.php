<?php

class SPW_Skill_Model extends CI_Model
{
	public $id;
	public $name;
	//IsEnabled?
	public $website_active = true;

	public function __construct()
	{
		parent::__construct();
	}
}
	
?>