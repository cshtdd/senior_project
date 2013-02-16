<?php

class Person_Model extends CI_Model
{
	public $first_name;
	public $last_name;
	public $age = 15;
	
	public function __construct()
	{
        parent::__construct();
	}
}
	
?>