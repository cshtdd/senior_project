<?php

class SPW_User_Summary_View_Model extends CI_Model
{
    //a SPW_User_Model object
    public $user;

    public $invite;

    public function __construct()
    {
        parent::__construct();
    }

    public function getFullName()
    {
        return ucwords($this->user->first_name.' '.$this->user->last_name);
    }

    public function prepareUsersDataToShow($current_user_id, $lUserIds)
    {
        $length = count($lUserIds);

        $lUsers = array();

        for ($i = 0; $i < $length; $i++)
        {
            $user_summ_vm = new SPW_User_Summary_View_Model();

            $param[0] = $lUserIds[$i];

            //get user info
            $sql = 'select *
                    from spw_user
                    where (id = ?)';
            $query = $this->db->query($sql, $param);

            if ($query->num_rows() > 0)
            {
                $row = $query->row(0, 'SPW_User_Model');
                $user = $row;
                $user_summ_vm->user = $user;

                $user_summ_vm->invite = $user->canInviteUser($current_user_id, $lUserIds[$i]);
            }
            else
            {
                throw new Exception('spw_user table error in db...');
            }   

            $lUsers[] = $user_summ_vm;      
        }

        return $lUsers;
    }

}
    
?>