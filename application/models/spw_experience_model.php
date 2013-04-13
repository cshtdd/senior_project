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

    public function get_positions_by_user($user_id)
    {
        $query = $this->db
                       ->where('user',$user_id)
                       ->get('spw_experience');

        return $query->result();
    }

    public function insert($spw_id,$position_obj)
    {
        
        $data = array(
                        'user'              => $spw_id, 
                        'company_name'      => $position_obj->company_name,
                        'company_industry'  => $position_obj->company_industry,
                        'start_date'        => $position_obj->start_date,
                        'end_date'          => $position_obj->end_date,
                        'title'             => $position_obj->title,
                        'summary'           => $position_obj->summary
                         );

        $this->db->insert('spw_experience', $data); 

    }


    public function delete($spw_id)
    {
        $this->db->where('user', $spw_id);
        $this->db->delete('spw_experience');

    }
}
    
?>