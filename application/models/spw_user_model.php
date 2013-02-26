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

	public function verify_user($email_address, $pwd)
	{
	    $query = $this->db
					  ->where('email', $email_address)
					  ->where('hash_pwd', sha1( $pwd))
					  ->limit(1)
					  ->get('spw_user');
		if($query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			return false; 
		}
	}

	public function check_already_registered($email_address)
	{
		$query = $this->db
					  //->where('google_id','NULL')
					  //->where('linkedin_id','NULL')
					  ->where('email',$email_address)
					  ->get('spw_user');
		if($query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			return false;
		}
	}

	public function create_new_user($email_address, $password)
	{
		$data = array(
		   'email' =>  $email_address ,
		   'hash_pwd' =>  sha1($password),
		);

		$this->db->insert('spw_user', $data);
		return $this->db->insert_id();
	}
}
	
?>