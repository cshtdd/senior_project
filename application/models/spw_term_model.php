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

	public function searchQueriesOnTermForUsers($keyword)
	{
		$keyword = '%'.$keyword.'%';
		
		$param[0] = $keyword;
		$param[1] = $keyword;

		$sql = "select spw_project.id
				from spw_project, spw_term
				where (spw_project.delivery_term = spw_term.id) and (spw_project.status = 3)
					   and ((spw_term.name like ?) or (spw_term.description like ?))";
		$query = $this->db->query($sql, $param);

		if ($query->num_rows() > 0)
			return $this->dumpQueryIdsOnArray($query);
		else
			return NULL;
	}

	public function searchQueriesOnTermForProjects($keyword)
	{
		$keyword = '%'.$keyword.'%';
		
		$param[0] = $keyword;
		$param[1] = $keyword;

		$sql = "select spw_user.id
				from spw_user, spw_term
				where (spw_user.graduation_term = spw_term.id)
					   and ((spw_term.name like ?) or (spw_term.description like ?))";
		$query = $this->db->query($sql, $param);

		if ($query->num_rows() > 0)
			return $this->dumpQueryIdsOnArray($query);
		else
			return NULL;
	}

	private function dumpQueryIdsOnArray($query)
	{
		$res = array();

		if (isset($query))
		{
			foreach ($query->result() as $row)
			{
				$res[] = $row->id;
			}
		}

		return $res;
	}
}
	
?>