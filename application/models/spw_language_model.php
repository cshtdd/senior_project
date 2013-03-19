<?php

class SPW_Language_Model extends CI_Model
{
	public $id;
	public $name;

	public function __construct()
	{
		parent::__construct();
	}

	public function get_language_by_name($language_name)
	{
		$query = $this->db
					   ->where('name',$language_name)
					   ->get('spw_language');

		if($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{
			   return $row->id;
			}
		}else
		{
			return -1;
		}
	}

	public function get_languagename($id)
	{
		$query = $this->db
					   ->where('id',$id)
					   ->select('name')
					   ->get('spw_language');

		if ($query->num_rows() > 0)
		{
			return $query->row()->name;
		}
		else
		{
			throw new Exception('Language Id not found');
		}
	}
}
	
?>