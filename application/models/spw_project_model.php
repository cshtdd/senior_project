<?php

class SPW_Project_Model extends CI_Model
{
	public $id;
	public $title;
	public $description;
	public $max_students;

	//the id of the SPW_User
	public $team_leader;

	//the id of the SPW_Term
	public $delivery_term;

	//The id of the SPW_Project_Status
	public $status;

	public function __construct()
	{
		parent::__construct();
	}

	public function getRegularProjectIds($lSuggestedProjectIds)
	{
		$lenght = count($lSuggestedProjectIds);

		$count = 0;

		$sql = 'select spw_project.id
	         	from spw_project, spw_term
	         	where (spw_project.status = 3) and (spw_term.id = spw_project.delivery_term) 
	                   and (spw_term.closed_requests > NOW())
             	order by id ASC';

        $query = $this->db->query($sql);

        sort($lSuggestedProjectIds);

        $res = array();

        foreach ($query->result() as $row)
		{
			while ($lSuggestedProjectIds[$count] < $row->id)
			{
				count++;
			}

			if ()
			{

			}
			else
			{
				
			}

		}

		return $res;
	}

}
	
?>