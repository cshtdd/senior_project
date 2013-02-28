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

	/* get the current term information */
	public function getCurrentTermInfo()
	{
		$sql = 'select *
				from spw_term
				where (start_date <= NOW()) and (end_date > NOW())';
		$query = $this->db->query($sql);

		if ($query->num_rows() > 0)
		{
			$res = $query->row(0, 'SPW_Term_Model');

			return $res;
		}

		return NULL;
	}
}
	
?>