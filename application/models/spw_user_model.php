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
    public $summary_spw;

    public $headline_linkedIn;
    public $summary_linkedIn;
    public $positions_linkedIn;

    //the id of the SPW_Term
    public $graduation_term;
    //the id of the SPW_Project
    public $project;
    public $google_id;
    public $linkedin_id;
    public $facebook_id;

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



    public function is_spw_registered($email_address)
    {
        $query = $this->db
                       ->where('google_id',NULL)
                       ->where('linkedin_id',NULL)
                       ->where('email',$email_address)
                       ->get('spw_user');

        if($query->num_rows() > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

     public function is_spw_registered_by_id($spw_id)
    {
        $query = $this->db
                       ->where('google_id',NULL)
                       ->where('linkedin_id',NULL)
                       ->where('id',$spw_id)
                       ->get('spw_user');

        if($query->num_rows() > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function create_new_spw_user($email_address, $password)
    {
        $data = array(
           'email' =>  $email_address ,
           'hash_pwd' =>  sha1($password),
        );

        $this->db->insert('spw_user', $data);
        return $this->db->insert_id();
    }

    public function is_google_registered($id)
    {
        $query = $this->db
                       ->where('google_id',$id)
                       ->get('spw_user');

        if($query->num_rows() > 0)
        {
            foreach ($query->result() as $row)
            {
               return $row->id;
            }
        }
        else
        {
            return 0;
        }
    }


    public function create_new_google_user($email_address, $given_name, $family_name,$google_id)
    {
    
        $data = array(
           'email' =>  $email_address ,
           'first_name' => $given_name, 
           'last_name' => $family_name,
           'google_id' => $google_id,
        );
 
        $this->db->insert('spw_user', $data);
        return $this->db->insert_id();
    }
    
    public function is_facebook_registered($id)
    {
        $query = $this->db
                       ->where('facebook_id',$id)
                       ->get('spw_user');

        if($query->num_rows() > 0)
        {
            foreach ($query->result() as $row)
            {
               return $row->id;
            }
        }
        else
        {
            return 0;
        }
    }

    public function create_new_facebook_user($email_address, $given_name, $family_name,$facebook_id)
    {
    
        $data = array(
           'email' =>  $email_address ,
           'first_name' => $given_name, 
           'last_name' => $family_name,
           'facebook_id' => $facebook_id,
        );
 
        $this->db->insert('spw_user', $data);
        return $this->db->insert_id();
    }
    
    public function is_linkedin_registered($id)
    {
        $query = $this->db
                       ->where('linkedin_id',$id)
                       ->get('spw_user');

        if($query->num_rows() > 0)
        {
            foreach ($query->result() as $row)
            {
               return $row->id;
            }
        }
        else
        {
            return 0;
        }
    }

    public function create_new_linkedin_user($email_address, $given_name, $family_name,$linkedin_id)
    {
        $data = array(
           'email' =>  $email_address ,
           'first_name' => $given_name, 
           'last_name' => $family_name,
           'linkedin_id' => $linkedin_id,
        );
 
        $this->db->insert('spw_user', $data);
        return $this->db->insert_id();
    }

    public function create_linkedin_profile($spw_id, $user_profile)
    {
        
        $this->load->model('spw_skill_model');
        $this->load->model('spw_skill_user_model');
        foreach ($user_profile->skills as $key => $value) {
            $skill_id = $this->spw_skill_model->get_skill_by_name($value->name);
            $this->spw_skill_user_model->insert($spw_id,$skill_id);
        }
        
        
        $this->load->model('spw_language_model');
        $this->load->model('spw_language_user_model');
        foreach ($user_profile->languages as $key => $value) {
            $language_id = $this->spw_language_model->get_language_by_name($value->name);
            if($language_id != -1)
                $this->spw_language_user_model->insert($spw_id,$language_id);
            
        }

        $this->load->model('spw_experience_model');
        foreach ($user_profile->positions_linkedIn as $key => $value) {
            $this->spw_experience_model->insert($spw_id,$value);
        }

        $data = array(
            'picture' => $user_profile->picture,
            'headline_linkedIn'=> $user_profile->headline_linkedIn,
            'summary_linkedIn' => $user_profile->summary_linkedIn,
        );

        $this->db->where('id',$spw_id);
        $this->db->update('spw_user', $data);
        
    }
    
    
    public function update_linkedin_profile($spw_id, $user_profile)
    {

        $this->load->model('spw_skill_user_model');
        $this->spw_skill_user_model->delete_skills_for_user($spw_id);

        $this->load->model('spw_language_user_model');
        $this->spw_language_user_model->delete_languages_for_user($spw_id);     

        $this->load->model('spw_experience_model');
        $this->spw_experience_model->delete($spw_id);       

        $this->create_linkedin_profile($spw_id, $user_profile);
        
    }
    
    public function get_profile_by_id($user_id)
    {
        $query = $this->db
                       ->where('id',$user_id)
                       ->select('email, first_name, last_name, picture, summary_spw, headline_linkedIn,summary_linkedIn, graduation_term')
                       ->get('spw_user');
                   
        if ($query->num_rows() > 0)
        {
            return $query->row();
        }
        else
        {
            throw new Exception('Profile Id not found');
        }
    }

    public function get_first_name($user_id)
    {
        $query = $this->db
                       ->where('id',$user_id)
                       ->select('first_name')
                       ->get('spw_user');

        if ($query->num_rows() > 0)
        {
            return $query->row()->first_name;
        }
        else
        {
            throw new Exception('User Id not found');
        }
    }

    public function get_fullname($user_id)
    {
        $query = $this->db
                       ->where('id',$user_id)
                       ->select('first_name, last_name')
                       ->get('spw_user');

        if ($query->num_rows() > 0)
        {
            $result_obj = $query->row();
            return $result_obj->first_name." ".$result_obj->last_name;
        }
        else
        {
            throw new Exception('User Id not found');
        }
    }

    public function get_picture($user_id)
    {
        $query = $this->db
                       ->where('id',$user_id)
                       ->select('picture')
                       ->get('spw_user');

        if ($query->num_rows() > 0)
        {
            return $query->row()->picture;
        }
        else
        {
            throw new Exception('User Id not found');
        }
    }

    public function get_role($user_id)
    {
        $query = $this->db
                       ->where('user',$user_id)
                       ->select('role')
                       ->get('spw_role');

        if ($query->num_rows() > 0)
        {
            if($query->num_rows() > 1)
            {
                
            }

            foreach ($query->result() as $row) {
                
            }
            return $query->row()->picture;
        }
        else
        {
            throw new Exception('User Id not found');
        }
    }
       
 
    //TODO validate the data against XSS and CSRF and SQL Injection
    public function update_summary_profile($spw_id,$new_profile)
    {

        $data = array(
            'first_name' =>  $new_profile->first_name,
            'last_name' =>  $new_profile->last_name,
            'picture' => $new_profile->picture,
            'summary_spw'=> $new_profile->spw_summary
        );

        if($new_profile->updatedRoleId == 5){
            $data['graduation_term'] = $new_profile->dropdown_term; 
        }
        
        $this->db->where('id',$spw_id);
        $this->db->update('spw_user', $data);

        $this->load->model('spw_role_user_model');
        $this->spw_role_user_model->update_roles_for_user($spw_id, $new_profile->updatedRoleId);
    }

    /* return a SPW_Term_Model info corresponding to the user id */
    public function getUserGraduationTerm($user_id)
    {
        $param[0] = $user_id;
        $sql = 'select spw_term.*
                from spw_user, spw_term
                where (spw_user.id = ?) and (spw_user.graduation_term = spw_term.id)';
        $query = $this->db->query($sql, $param);
        if ($query->num_rows() > 0)
        {
            $row = $query->row(0, 'SPW_Term_Model');
            return $row;
        }
        
        return NULL;
    }


    /* return a SPW_User_Model info corresponding to the user id */
    public function getUserInfo($user_id)
    {
        $param[0] = $user_id;
        $sql = 'select *
                from spw_user
                where id = ?';
        $query = $this->db->query($sql, $param);
        if ($query->num_rows() > 0)
        {
            $row = $query->row(0, 'SPW_User_Model');
            return $row;
        }

        return NULL;
    }

    //get list of skills info of the user
    public function getUserListOfSkills($user_id)
    {
        $param[0] = $user_id;

        $sql = 'select spw_skill.*
                from spw_skill, spw_skill_user
                where (spw_skill_user.user = ?) and (spw_skill.id = spw_skill_user.skill)';
        $query = $this->db->query($sql, $param);
        $skillNum = $query->num_rows();
        $lSkills = array();
        if ($skillNum > 0)
        {
            for ($j = 0; $j < $skillNum; $j++)
            {
                $row = $query->row($j, 'SPW_Skill_Model');
                $lSkills[] = $row;
            }
        }

        return $lSkills;
    }

    //get list of esperiences info of the user
    public function getUserListOfExperiences($user_id)
    {
        $param[0] = $user_id;

        $sql = 'select *
                from spw_experience
                where (user = ?)';
        $query = $this->db->query($sql, $param);
        $experienceNum = $query->num_rows();
        $lExperiences = array();
        if ($experienceNum > 0)
        {
            for ($j = 0; $j < $experienceNum; $j++)
            {
                $row = $query->row($j, 'SPW_Experience_Model');
                $lExperiences[] = $row;
            }
        }

        return $lExperiences;
    }

    //get list of languages info of the user
    public function getUserListOfLanguages($user_id)
    {
        $param[0] = $user_id;

        $sql = 'select spw_language.*
                from spw_language, spw_language_user
                where (spw_language_user.user = ?) and (spw_language.id = spw_language_user.language)';
        $query = $this->db->query($sql, $param);
        $languageNum = $query->num_rows();
        $lLanguages = array();
        if ($languageNum > 0)
        {
            for ($j = 0; $j < $languageNum; $i++)
            {
                $row = $query->row($j, 'SPW_Language_Model');
                $lLanguages[] = $row;
            }
        }

        return $lLanguages;
    }

    //get the role info of the user
    public function getUserRole($user_id)
    {
        $param[0] = $user_id;

        $sql = 'select spw_role.*
                from spw_role, spw_role_user
                where (spw_role_user.user = ?) and (spw_role.id = spw_role_user.role)';
        $query = $this->db->query($sql, $param);

        if ($query->num_rows() > 0)
        {
            $role = $query->row(0, 'SPW_Role_Model');
            return $role;
        }

        return NULL;
    }

    /* return the list of suggested projects IDs with the highest matches having in
       count that the project delivery_term is the same as the user, is not yet the 
       closed_requests date and the project have been aproved */
    public function getSuggestedProjectsGivenCurrentUser($user_id)
    {   
        if ($this->isUserAStudent($user_id))
        {
            $term = $this->getUserGraduationTerm($user_id);

            if (isset($term))
            {
                $param[0] = $user_id;

                $param[1] = $term->id;

                $sql = 'select spw_project.id, count(project_skills.skill) as nSkillMatch
                        from spw_project, (select skill
                                           from spw_skill_user
                                           where user = ?) as skills, (select spw_project.id, skill
                                                                       from spw_project, spw_skill_project, spw_term
                                                                       where (spw_project.id = project) and (spw_project.status <> 4) and
                                                                         (spw_term.id = spw_project.delivery_term) and (spw_term.closed_requests > NOW())
                                                                         and (spw_term.id = ?)) as project_skills
                        where (skills.skill=project_skills.skill) and (spw_project.id=project_skills.id)
                        group by spw_project.id
                        order by nSkillMatch DESC';

                $query = $this->db->query($sql, $param);

                $param1[0] = $param[1];

                $sql1 = 'select id
                         from spw_project
                         where (delivery_term = ?) and (status <> 4)';

                $query1 = $this->db->query($sql1, $param1);
            }
        }
        elseif ($this->isUserPossibleMentor($user_id))
        {
            $param[0] = $user_id;

            $sql = 'select spw_project.id, count(project_skills.skill) as nSkillMatch
                    from spw_project, (select skill
                                       from spw_skill_user
                                       where user = ?) as skills, (select spw_project.id, skill
                                                                   from spw_project, spw_skill_project, spw_term
                                                                   where (spw_project.id = project) and (spw_project.status <> 4) and
                                                                         (spw_term.id = spw_project.delivery_term) and (spw_term.closed_requests > NOW())) 
                                                                         as project_skills
                    where (skills.skill=project_skills.skill) and (spw_project.id=project_skills.id)
                    group by spw_project.id
                    order by nSkillMatch DESC';

            $query = $this->db->query($sql, $param);

            $sql1 = 'select spw_project.id
                     from spw_project, spw_term
                     where (spw_project.status <> 4) and (spw_term.id = spw_project.delivery_term) 
                            and (spw_term.end_date > NOW())';

            $query1 = $this->db->query($sql1);
        }

        if (isset($query))
        {
            $totValidProjects = $query1->num_rows();

            $res = $this->chooseRelevantIds($query, $totValidProjects);

            return $res;
        }

        return NULL;
    }

    /* Given the suggested projects this function returns the regular projects */
    public function getRegularProjectIds($lSuggestedProjectIds, $user_id)
    {
        if ($this->isUserAStudent($user_id))
        {
            $term = $this->getUserGraduationTerm($user_id);

            if (isset($term))
            {
                $param[0] = $term->id;

                $sql = 'select spw_project.id
                        from spw_project, spw_term
                        where (spw_project.status <> 4) and (spw_term.id = spw_project.delivery_term) 
                            and (spw_term.end_date > NOW()) and (spw_term.id = ?)
                        order by id ASC';

                $query = $this->db->query($sql, $param);
            }
        }
        elseif ($this->isUserPossibleMentor($user_id))
        {
            $sql = 'select spw_project.id
                    from spw_project, spw_term
                    where (spw_project.status <> 4) and (spw_term.id = spw_project.delivery_term) 
                          and (spw_term.end_date > NOW())
                    order by id ASC';

             $query = $this->db->query($sql);
        }

        if (isset($query))
        {
            sort($lSuggestedProjectIds);

            $lValidProjects = array();

            foreach ($query->result() as $row)
            {
                $lValidProjects[] = $row->id;
            }

            $res = array_diff($lValidProjects, $lSuggestedProjectIds);

            $res = array_values($res);

            return $res;
        }

        return NULL;
    }

    public function isUserAStudent($user_id)
    {
        $param[0] = $user_id;
        $sql = 'select id
                from spw_role_user
                where (user = ?) and (role = 5)';
        $query = $this->db->query($sql, $param);
        if ($query->num_rows() > 0)
        {
            return true;
        }
        return NULL;
    }

    public function isUserAdmin($user_id)
    {
        $param[0] = $user_id;
        $sql = 'select id
                from spw_role_user
                where (user = ?) and (role = 1)';
        $query = $this->db->query($sql, $param);
        if ($query->num_rows() > 0)
        {
            return true;
        }
        return NULL;
    }

    public function isUserHeadProfessor($user_id)
    {
        $param[0] = $user_id;
        $sql = 'select id
                from spw_role_user
                where (user = ?) and (role = 2)';
        $query = $this->db->query($sql, $param);
        if ($query->num_rows() > 0)
        {
            return true;
        }
        return NULL;
    }

    public function isUserPossibleMentor($user_id)
    {
        $param[0] = $user_id;
        $sql = 'select id
                from spw_role_user
                where (user = ?) and ((role = 2) or (role = 3) or (role = 4))';
        $query = $this->db->query($sql, $param);
        if ($query->num_rows() > 0)
        {
            return true;
        }
        return NULL;
    }

    public function getStudentIdsFromListIds($lUser)
    {
        $res = array();
        if (isset($lUser) && count($lUser)>0)
        {
            $length = count($lUser);
            for ($i = 0; $i < $length; $i++)
            {
                if ($this->isUserAStudent($lUser[$i]))
                    $res[] = $lUser[$i];
            }
        }

        return $res;
    }

    public function getMentorIdsFromListIds($lUser)
    {
        $res = array();
        if (isset($lUser) && count($lUser)>0)
        {
            $length = count($lUser);
            for ($i = 0; $i < $length; $i++)
            {
                if ($this->isUserPossibleMentor($lUser[$i]))
                    $res[] = $lUser[$i];
            }
        }

        return $res;
    }

    /* return the id of the projects the user belong or false if does not have a project */
    public function userHaveProjects($user_id)
    {
        $param[0] = $user_id;

        if ($this->isUserAStudent($user_id))
        {
            $sql = 'select project
                    from spw_user, spw_project
                    where (spw_user.id = ?) and (project = spw_project.id) 
                           and (spw_project.status <> 4)';
        }
        else
        {
            $sql = 'select project
                    from spw_mentor_project, spw_project
                    where (mentor = ?) and (spw_project.id = spw_mentor_project.project) 
                           and (spw_project.status <> 4)';
        }

        $query = $this->db->query($sql, $param);

        if ($query->num_rows() > 0)
        {
            //$res = array();

            foreach ($query->result() as $row)
            {
                $res[] = $row->project;
            }

            return $res;
        }
        else
        {
            return NULL;
        }
    }

    /* return the id of the projects the user belong or false if does not have a project 
       regardless of the project statuses */
    public function userHaveProjectsRegardlessStatus($user_id)
    {
        $param[0] = $user_id;

        if ($this->isUserAStudent($user_id))
        {
            $sql = 'select project
                    from spw_user, spw_project
                    where (spw_user.id = ?) and (project = spw_project.id)';
        }
        else
        {
            $sql = 'select project
                    from spw_mentor_project, spw_project
                    where (mentor = ?) and (spw_project.id = spw_mentor_project.project)';
        }

        $query = $this->db->query($sql, $param);

        if ($query->num_rows() > 0)
        {
            //$res = array();

            foreach ($query->result() as $row)
            {
                $res[] = $row->project;
            }

            return $res;
        }
        else
        {
            return NULL;
        }
    }

    /* given the full list of Ids with at least one match, determines which can
       actually be suggested to */
    private function chooseRelevantIds($allSuggestedIds, $totalValidIds)
    {
        $count = 0;
        $ratio = 3;
        $lSuggestedIds = array();
        $ratioIds = round($totalValidIds / $ratio);
        $flag = true;

        foreach ($allSuggestedIds->result() as $row)
        {
            if ($flag)
            {
                if (($row->nSkillMatch == 1) && ($count == 0))
                {
                    $flag = false;
                    $lSuggestedIds[$count] = $row->id;
                    $count++;
                }
                else
                {
                    if (($row->nSkillMatch >= 2) && ($count < $ratioIds))
                    {
                        $lSuggestedIds[$count] = $row->id;
                        $count++;
                    }
                }
            }
            else
            {
                if ($count < $ratioIds)
                {
                    $lSuggestedIds[$count] = $row->id;
                    $count++;
                }
            }
        }

        return $lSuggestedIds;
    }

    /* checks if a user can invite another user to join to a project, if he has a project, 
       and is a mentor or student */
    public function canInviteUser($current_user_id, $invited_user_id)
    {
        if ($current_user_id != $invited_user_id)
        {
            $currentUserBelongProjects = $this->userHaveProjects($current_user_id);

            $currentDate = date('Y-m-d');

            if (isset($currentUserBelongProjects) && (count($currentUserBelongProjects) > 0))
            {
                $isCurrentUserStudent = $this->isUserAStudent($current_user_id);
                $isInvitedUserStudent = $this->isUserAStudent($invited_user_id);

                if ($isCurrentUserStudent)
                {
                    $currentUserGraduationTerm = $this->getUserGraduationTerm($current_user_id);

                    if ($currentUserGraduationTerm->closed_requests > $currentDate)
                    {
                        if ($isInvitedUserStudent)
                        {
                            $invitedUserGraduationTerm = $this->getUserGraduationTerm($invited_user_id);

                            if ($currentUserGraduationTerm->id == $invitedUserGraduationTerm->id)
                            {
                                $invitedUserBelongProjects = $this->userHaveProjects($invited_user_id);

                                if (isset($invitedUserBelongProjects) && (count($invitedUserBelongProjects)>0))
                                {
                                    if ($invitedUserBelongProjects[0] == $currentUserBelongProjects[0])
                                        return false;
                                }

                                return true;
                            }
                        }
                        else
                        {
                            $invitedUserBelongProjects = $this->userHaveProjects($invited_user_id);
                            if (isset($invitedUserBelongProjects) && count($invitedUserBelongProjects)>0)
                            {
                                if (!in_array($currentUserBelongProjects[0], $invitedUserBelongProjects))
                                    return true;
                            }
                        }   
                    }
                }
                else
                {
                    $invitedUserBelongProjects = $this->userHaveProjects($invited_user_id);

                    if ($isInvitedUserStudent)
                    {
                        $invitedUserGraduationTerm = $this->getUserGraduationTerm($invited_user_id);

                        return $this->canMentorInviteStudentToProjectValid($currentUserBelongProjects, $invitedUserBelongProjects, $invitedUserGraduationTerm);
                    }
                    else
                    {
                        return $this->canMentorInviteMentorToProjectValid($currentUserBelongProjects, $invitedUserBelongProjects);
                    }
                }
            }
        }

        return false;
    }

    private function canMentorInviteStudentToProjectValid($lMentorProjectIds, $lStudentProjectId, $studentTerm)
    {
        $length = count($lMentorProjectIds);

        $currentDate = date('Y-m-d');

        for ($i = 0; $i<$length; $i++)
        {
            $tempProject = new SPW_Project_Model();
            $projectTerm = $tempProject->getProjectDeliveryTerm($lMentorProjectIds[$i]);

            if (($projectTerm->id == $studentTerm->id) && ($projectTerm->closed_requests > $currentDate))
            {
                if (isset($lStudentProjectId) && (count($lStudentProjectId)>0))
                {
                    if ($lStudentProjectId[0] != $lMentorProjectIds[$i])
                        return true;
                }
                else
                    return true;
            }
        }

        return false;
    }

    private function canMentorInviteMentorToProjectValid($lMentorProjectIds, $lInvitedMentorProjectIds)
    {
        $length = count($lMentorProjectIds);

        $currentDate = date('Y-m-d');

        for ($i = 0; $i<$length; $i++)
        {
            $tempProject = new SPW_Project_Model();
            $projectTerm = $tempProject->getProjectDeliveryTerm($lMentorProjectIds[$i]);;

            if ($projectTerm->closed_requests > $currentDate)
            {
                if (isset($lInvitedMentorProjectIds) && (count($lInvitedMentorProjectIds)>0))
                {
                    if (!in_array($lMentorProjectIds[$i], $lInvitedMentorProjectIds))
                        return true;
                }
                else
                    return true;
            }       
        }

        return false;
    }

    /* searching for keyword in students first name and last name records */
    public function searchQueriesOnUserNamesForUsers($keyword)
    {
        $keyword = '%'.$keyword.'%';

        $param[0] = $keyword;
        $param[1] = $keyword;

        $sql = "select spw_user.id
                from spw_user
                where ((spw_user.first_name like ?) or (spw_user.last_name like ?))";

        $query = $this->db->query($sql, $param);
        
        if ($query->num_rows() > 0)
            return $this->dumpQueryIdsOnArray($query);
        else
            return NULL;
    }

    /* searching for keyword in students attributes records */
    public function searchQueriesOnUserAttributesForUsers($keyword)
    {
        $keyword = '%'.$keyword.'%';

        $param[0] = $keyword;
        $param[1] = $keyword;
        $param[2] = $keyword;
        $param[3] = $keyword;

        $sql = "select spw_user.id
                from spw_user
                where ((spw_user.summary_spw like ?) or 
                      (spw_user.headline_linkedIn like ?) or (spw_user.summary_linkedIn like ?) or
                      (spw_user.positions_linkedIn like ?))";

        $query = $this->db->query($sql, $param);
        
        if ($query->num_rows() > 0)
            return $this->dumpQueryIdsOnArray($query);
        else
            return NULL;
    }

    /* searching for keyword in experience records */
    public function searchQueriesOnExperienceForUsers($keyword)
    {
        $keyword = '%'.$keyword.'%';

        $param[0] = $keyword;
        $param[1] = $keyword;
        $param[2] = $keyword;
        $param[3] = $keyword;

        $sql = "select spw_user.id
                from spw_user, spw_experience
                where (spw_experience.user = spw_user.id) and
                      ((spw_experience.title like ?) or (spw_experience.summary like ?) or
                        (spw_experience.company_name like ?) or (spw_experience.company_industry like ?))";

        $query = $this->db->query($sql, $param);

        if ($query->num_rows() > 0)
            return $this->dumpQueryIdsOnArray($query);
        else
            return NULL;
    }

    /* searching for keyword in skill records */
    public function searchQueriesOnSkillsForUsers($keyword)
    {
        $keyword = '%'.$keyword.'%';

        $param[0] = $keyword;

        $sql = "select spw_user.id
                from spw_user, spw_skill, spw_skill_user
                where (spw_skill_user.user = spw_user.id) and 
                      (spw_skill_user.skill = spw_skill.id) and
                      (spw_skill.name like ?)";

        $query = $this->db->query($sql, $param);

        if ($query->num_rows() > 0)
            return $this->dumpQueryIdsOnArray($query);
        else
            return NULL;
    }

    /* checks whether the user can leave a project or not */
    public function canUserLeaveProject($user_id, $project_id)
    {
        $param[0] = $project_id;

        $sql = 'select proposed_by
                from spw_project
                where id = ?';
        $query = $this->db->query($sql, $param);

        if ($query->num_rows() == 1)
        {
            foreach ($query->result() as $row)
                $proposedBy = $row->proposed_by;

            if ($user_id == $proposedBy)
                return false;
            else
                return true;
        }
        else
            return NULL; //the project does not exist
    }

    public function leaveProjectOnDatabase($user_id, $project_id)
    {
        $proj = new SPW_Project_Model();

        $user_belong_project = $proj->doesUserBelongToProject($user_id ,$project_id);
            
        if (isset($user_belong_project) && $user_belong_project) 
        {
            $can_leave = $this->canUserLeaveProject($user_id, $project_id);

            if (isset($can_leave) && $can_leave)
            {
                $param[0] = $user_id;
                $param[1] = $project_id;
        
                if ($this->isUserAStudent($user_id))
                { 
                    $sql = 'update spw_user
                            set project = NULL
                            where id = ?';
                }
                else
                {
                    $sql = 'delete
                            from spw_mentor_project
                            where (mentor = ?) and (project = ?)';
                }

                $query = $this->db->query($sql, $param);  
                return true;
            }
        }

        return false;    
    }

    /* Assigns a project to a user and returns whether was successful or not */
    public function assignProjectToUser($project_id, $user_id)
    {
        $tempProject = new SPW_Project_Model();     
        $project = $tempProject->getProjectInfo($project_id);

        if (isset($project))
        {
            $user = $this->getUserInfo($user_id);
            if (isset($user))
            {
                if ($this->isUserAStudent($user_id))
                {
                    if ($user->project != $project_id)
                    {
                        $user->project = $project_id;
                        $this->db->where('id', $user_id);
                        $this->db->update('spw_user', $user);
                    }
                }
                else
                {
                    $query = $this->db->get_where('spw_mentor_project', array('mentor' => $user_id,'project' => $project_id));
                    if ($query->num_rows() == 0)
                    {
                         $data = array('mentor'  => $user_id, 
                                       'project' => $project_id
                                      );

                        $this->db->insert('spw_mentor_project', $data); 
                    }
                }

                return true;
            }
            else
                throw new Exception('The user does not exists');
        }
        else
            throw new Exception('The project does not exists');

        return false;
    }

    /* get a string with the ids separated by commas and returns a array of ids */
    public function getListOfUserIdsToUpdate($listIdStr)
    {
        $listIdStr = str_replace(' ', '', $listIdStr);

        $listIdArray = explode(',', $listIdStr);

        return $listIdArray;
    }

    /* takes any array of ids and dump it in an array of ids */
    public function dumpQueryIdsOnArray($query)
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