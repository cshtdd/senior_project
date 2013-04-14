<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UserController extends CI_Controller 
{
    
    public function __construct()
    {
        parent::__construct();

        $this->load->helper('request');
        $this->load->helper('invitation');
        $this->load->helper('flash_message');

        $this->load->helper('project_summary_view_model');
        load_project_summary_models($this);
        
        $this->load->model('spw_skill_model');
        $this->load->model('spw_skill_user_model');
        $this->load->model('spw_experience_model');
        $this->load->model('spw_language_model');
        $this->load->model('spw_language_user_model');
        $this->load->model('spw_term_model');
        $this->load->model('spw_user_model');
        $this->load->model('spw_experience_model');
        $this->load->model('spw_role_model');
        $this->load->model('spw_role_user_model');
        $this->load->model('spw_notification_model');

        $this->load->model('SPW_User_Details_View_Model');
    }


    public function profile($user_id='')
    {
        //$this->output->set_output('user profile '.$user_id);
        $currentUserId = getCurrentUserId($this);
        $user_details = $this->getUserDetailsInternal($user_id);

        if ($user_id == $currentUserId) //we are viewing the current user profile
        {
            $data['canChangePassword'] = $this->getUserCanChangePassword($currentUserId);
            $resulting_view_name = 'user_profile_edit';
        }
        else //we are viewing somebody else's profile
        {
            $resulting_view_name = 'user_profile';
        }

        if (isset($user_details))
        {
            $data['no_results'] = false;
        }
        else
        {
            $data['no_results'] = true;
        }

        $data['userDetails'] = $user_details;
        $data['title'] = 'User Details';

        $this->load->view($resulting_view_name, $data);
    }

    public function current_user()
    {
        $current_user_id = getCurrentUserId($this);
        $this->profile($current_user_id);
    } 

    public function update()
    {
        if (!is_POST_request($this))
        {
            redirect('/');
        }
        else
        {
            $spw_id = getCurrentUserId($this);

            if($spw_id < 0)
            {
                redirect('/home');
            }

            $updatedFirstName = $this->input->post('text-first-name');
            $updatedLastName = $this->input->post('text-last-name');
            $updatedSPWSummary = $this->input->post('text-description');
            $updatedRoleId = $this->input->post('radio-role');
            $updatedUserPictureUrl = $this->input->post('hidden-img-src');            

            $new_profile = (object)array(
                            'first_name' =>     $updatedFirstName,
                            'last_name' =>      $updatedLastName,
                            'spw_summary' =>    $updatedSPWSummary,
                            'picture' => $updatedUserPictureUrl,
                            'updatedRoleId' =>  $updatedRoleId,
                            );

            if($updatedRoleId == 5) //TODO: Not hardcode this
            {
                $new_profile->dropdown_term = $this->input->post('dropdown-term');
            }

            if (!is_test($this))
            {
                //TODO validate the data against XSS and CSRF and SQL Injection
                $this->spw_user_model->update_summary_profile($spw_id,$new_profile);
            }

            setFlashMessage($this, 'Your profile has been updated');
            redirect('/me');
        }
    }

    public function invite()
    {
        if (!is_POST_request($this))
        {
            redirect('/');
        }
        else
        {
            if (isUserLoggedIn($this))
            {
                $currentUserId = getCurrentUserId($this);
                $invitedUserId = $this->input->post('uid');
                $invitedProjectId = $this->input->post('pid');

                //if the projectId parameter was not specified
                //we'll get any project (usually there's only one) from the current user
                if (!isset($invitedProjectId) || strlen($invitedProjectId) == 0) 
                {
                    $invitedProjectId = getAnyProjectIdForCurrentUser($this);
                }

                inviteUserInternal($currentUserId, $invitedUserId, $invitedProjectId);
            }
            else
            {
                redirect('/');
            }
        }
    } 

    public function parse_positions($positions)
    {


        $positions = $positions->values; 
        $result =  array();

        for($i = 0; $i < count($positions); $i++)
        {
            $current_position = $positions[$i];

            $start_date = $current_position->startDate->year.'-'.$current_position->startDate->month; 

            $end_date;
            if($current_position->isCurrent) 
            {
                $end_date = null;
            }else{
                $end_date =  $current_position->endDate->year.'-'.$current_position->endDate->month;
            }


            $company =      array( 
                                'start_date'        => $start_date,
                                'end_date'          => $end_date,
                            );
            

            if(property_exists($current_position, 'title') )
            {
                 $company['title'] = $current_position->title;
            }else{
                 $company['title'] = "";
            }   

            if(property_exists($current_position, 'summary'))
            {
                 $company['summary'] = $current_position->summary;
            }else{
                 $company['summary'] = "";
            }

            if(property_exists($current_position->company, 'name'))
            {
                 $company['company_name'] = $current_position->company->name;
            }else{
                 $company['company_name'] = "";
            }

             if(property_exists($current_position->company, 'industry'))
            {
                 $company['company_industry'] = $current_position->company->industry;
            }else{
                 $company['company_industry'] = "";
            }

             $result[$i] = (object)$company;

        }

        return $result;
    }

    public function parse_skills($skills)
    {
        
        $skills = $skills->values; 
        $result =  array();

        for($i = 0; $i < count($skills); $i++)
        {
            $current_skill = $skills[$i];

            $result[$i] = (object) array( 
                                        'name' => $current_skill->skill->name,
                                        );
        }

        return $result;
    }

    public function parse_languages($languages)
    {
        $languages = $languages->values; 
        $result =  array();

        for($i = 0; $i < count($languages); $i++)
        {
            $current_language = $languages[$i];

            $result[$i] = (object) array( 
                                        'name' => $current_language->language->name,
                                        );
        }

        return $result;
    }

    public function linkedIn_initiate()
    {
        // setup before redirecting to Linkedin for authentication.
         $linkedin_config = array(
             'appKey'       => '1ky0pyoc0rpe',
             'appSecret'    => '7WIPfrEkya3QT3LR',
             'callbackUrl'  => 'http://srprog-spr13-01.aul.fiu.edu/senior-projects/user/linkedIn_callback'
         );
        
        $this->load->library('linkedin', $linkedin_config);
        $this->linkedin->setResponseFormat(LINKEDIN::_RESPONSE_JSON);
        $token = $this->linkedin->retrieveTokenRequest();
        
        $this->session->set_flashdata('oauth_request_token_secret',$token['linkedin']['oauth_token_secret']);
        $this->session->set_flashdata('oauth_request_token',$token['linkedin']['oauth_token']);
        
        $link = "https://api.linkedin.com/uas/oauth/authorize?oauth_token=". $token['linkedin']['oauth_token']; 

        $this->session->set_flashdata('linkedIn_sync', 'false'); 

        redirect($link);
    }

    public function linkedIn_sync()
    {
        // setup before redirecting to Linkedin for authentication.
         $linkedin_config = array(
             'appKey'       => '1ky0pyoc0rpe',
             'appSecret'    => '7WIPfrEkya3QT3LR',
             'callbackUrl'  => 'http://srprog-spr13-01.aul.fiu.edu/senior-projects/user/linkedIn_callback'
         );
        
        $this->load->library('linkedin', $linkedin_config);
        $this->linkedin->setResponseFormat(LINKEDIN::_RESPONSE_JSON);
        $token = $this->linkedin->retrieveTokenRequest();
        
        $this->session->set_flashdata('oauth_request_token_secret',$token['linkedin']['oauth_token_secret']);
        $this->session->set_flashdata('oauth_request_token',$token['linkedin']['oauth_token']);
        
        $link = "https://api.linkedin.com/uas/oauth/authorize?oauth_token=". $token['linkedin']['oauth_token'];  

        $this->session->set_flashdata('linkedIn_sync', 'true');

        redirect($link);
    }


    public  function linkedIn_cancel() {
    
        redirect('/');            
    }

    public  function linkedIn_callback() {

        $linkedin_config = array(
                     'appKey'       => '1ky0pyoc0rpe',
                     'appSecret'    => '7WIPfrEkya3QT3LR',
                     'callbackUrl'  => 'http://srprog-spr13-01.aul.fiu.edu/senior-projects/user/linkedIn_callback'
                 );
                
        $this->load->library('linkedin', $linkedin_config);
        $this->linkedin->setResponseFormat(LINKEDIN::_RESPONSE_JSON);
         
        $oauth_token = $this->session->flashdata('oauth_request_token');
        $oauth_token_secret = $this->session->flashdata('oauth_request_token_secret');
        
        $oauth_verifier = $this->input->get('oauth_verifier');

        $response = $this->linkedin->retrieveTokenAccess($oauth_token, $oauth_token_secret, $oauth_verifier);
        
        if($response['success'] === TRUE) {
            
            $oauth_expires_in = $response['linkedin']['oauth_expires_in'];
            $oauth_authorization_expires_in = $response['linkedin']['oauth_authorization_expires_in'];
            
            $this->session->keep_flashdata('linkedIn_sync');

            $response = $this->linkedin->setTokenAccess($response['linkedin']);
            $this->session->keep_flashdata('linkedIn_sync');
            $profile = $this->linkedin->profile('~:(id,first-name,last-name,picture-url,headline,email-address,summary,skills,languages,positions)');

            $user = json_decode($profile['linkedin']);

            $user_profile = (object)array(
                    'id'                    => $user->id,
                    'email'                 => $user->emailAddress,
                    'first_name'            => $user->firstName,
                    'last_name'             => $user->lastName,
                    'picture'               => property_exists($user, 'pictureUrl')? $user->pictureUrl: null,
                    'headline_linkedIn'     => property_exists($user, 'headline')? $user->headline: null,
                    'summary_linkedIn'      => property_exists($user, 'summary')? $user->summary: null,
                    'positions_linkedIn'    => property_exists($user, 'positions')? $this->parse_positions($user->positions): null,
                    'skills'                => property_exists($user, 'skills')? $this->parse_skills($user->skills): null,
                    'languages'             => property_exists($user, 'languages')? $this->parse_languages($user->languages): null,
                );

            $spw_id = getCurrentUserId($this);

            if($this->session->flashdata('linkedIn_sync') == 'false')
            {   
                 $spw_id = $this->spw_user_model->is_linkedin_registered($user_profile->id);
                $is_linkedin_registered = true;

                if($spw_id == 0){
                    $spw_id  = $this->spw_user_model->create_new_linkedin_user( $user_profile->email,  $user_profile->first_name,  $user_profile->last_name,  $user_profile->id);
                    $is_linkedin_registered = false;
                }

                $sess_array = array(
                            'id' => $spw_id,
                            'linkedIn_id' => $user_profile->id, 
                            'email' => $user_profile->email, 
                            'using' => 'linkedin'
                        );

                $this->session->set_userdata('logged_in', $sess_array);

                if($is_linkedin_registered){
                    redirect('home','refresh');
                }else{
                    //is a new user
                    $this->spw_user_model->create_linkedin_profile($spw_id, $user_profile);
                    $this->profile($spw_id);
                }

            } else {
                //TODO: Update LinkedIn Profile for Logged In student
                 $this->spw_user_model->update_linkedin_profile($spw_id, $user_profile);
                 $this->profile($spw_id);
                 setFlashMessage($this,"Updated profile");
            }
        }else{
            echo "Bad Request";
        }                  
    }

    public function display_change_password()
    {
        if (isUserLoggedIn($this))
        {
            if ($this->getUserCanChangePassword(getCurrentUserId($this)))
            {
                $this->load->view('user_change_password');
            }
            else
            {
                redirect('/');
            }
        }
        else
        {
            redirect('/');
        }
    }

    public function change_password()
    {
        if (!is_POST_request($this))
        {
            redirect('/');
        }
        else
        {
            if (isUserLoggedIn($this))
            {
                $currentUserId = getCurrentUserId($this);
                $currentPassword = $this->input->post('current-password');
                $newPassword = $this->input->post('password_1');


                $this->load->library('form_validation');
                $this->form_validation->set_rules('current-password', 'Current Password','required'); 
                $this->form_validation->set_rules('password_1', 'New Password','required|min_length[6]'); 
                $this->form_validation->set_rules('current-password', 'Current Password', 'callback_validateCurrentUserPassword');

                if ($this->form_validation->run() == FALSE)
                {
                    $this->load->view('user_change_password');
                }
                else 
                {
                    if (!is_test($this))
                    {
                        $this->changeUserPasswordInternal($currentUserId, $newPassword);
                    }

                    setFlashMessage($this, 'Your password has been changed');
                    redirect('/me');
                }                
            }
            else
            {
                redirect('/');
            }
        }
    }

    public function validateCurrentUserPassword($str)
    {
        if (is_test($this))
        {
            if ($str == '123')
            {
                return true;
            }
            else
            {

                $this->form_validation->set_message('validateCurrentUserPassword', 'The entered current password does not match our records');
                return false;
            }
        }
        else
        {
            $user_id = getCurrentUserId($this);
            $pwd = $this->spw_user_model->get_pwd($user_id);

            if(isset($pwd) && $pwd == sha1($str))
            {   
                return true;
            }else{
                $this->form_validation->set_message('validateCurrentUserPassword', 'The entered current password does not match our records');
                return false;
            }
        }
    }

    private function changeUserPasswordInternal($currentUserId, $newPassword)
    {
         $this->spw_user_model->change_pwd($currentUserId, $newPassword);
    }

    private function inviteUserInternal($currentUserId, $invitedUserId, $invitedProject)
    {
        if (is_test($this))
        {
            $this->output->set_output(
                'Current User '.$currentUser.
                ' InvitedUser '.$invitedUser.
                ' Invited to project '.$invitedProject);
            return true;
        }
        else
        {
            $this->spw_notification_model->create_invite_user_notification($currentUserId, $invitedUserId, $invitedProject);
            
            $project_team = $this->spw_project_model->get_team_members($project_id);
           
            for($i = 0; $i < count($project_team); $i++)
            {
                $member_id = $project_team[$i];
                if($member_id != $currentUserId && $member_id != $invitedUserId)
                {
                    $this->spw_notification_model->create_invite_project_notification($currentUserId, $invitedUserId, $member_id, $invitedProjectId);
                }
            }

            setFlashMessage($this, 'Your invitation has been sent');
        }
    }
    
    private function getUserDetailsInternal($user_id)
    {
        if (is_test($this))
        {
            return $this->getUserDetailsInternalTest($user_id);
        }
        else
        {
            return $this->getUserDetails($user_id);
        }
    }

    private function get_all_available_terms()
    {
        $this->load->helper('date');
        
        $result_terms = array();
        $terms = $this->spw_term_model->getFutureTerms();

        foreach ($terms as $row) {
            $term = new SPW_Term_Model();
            $term->id = $row['id'];
            $term->name = $row['name'];
            $term->description = $row['description'];
            $term->start_date = $row['start_date'];
            $term->end_date = $row['end_date'];

            array_push($result_terms, $term); 
        }

        return $result_terms;
    }

    private function get_profile($user_id, $lTerms)
    {       
        $result = $this->spw_user_model->get_profile_by_id($user_id);

        $user = new SPW_User_Model();
        $user->id = $user_id;
        $user->email = $result->email;
        $user->first_name = $result->first_name ;
        $user->last_name = $result->last_name;
        $user->picture = $result->picture;
        $user->summary_spw = $result->summary_spw;
        $user->headline_linkedIn = $result->headline_linkedIn;
        $user->summary_linkedIn = $result->summary_linkedIn;
        $user->graduation_term = $result->graduation_term;

        return $user;
    }

    private function getUserDetails($user_id)
    {
        $lTerms = $this->get_all_available_terms();        

        $user = $this->get_profile($user_id, $lTerms);
    
        $skills_ids = $this->spw_skill_user_model->get_skills_for_user($user_id);

        $lSkills = array(); 
        for($i = 0; $i < count($skills_ids); $i++)
        {
            $skill = new SPW_Skill_Model();
            $skill->id = $skills_ids[$i]->skill;
            $skill->name = $this->spw_skill_model->get_skillname($skill->id);
            array_push($lSkills,$skill);
        }        

        $languages_ids = $this->spw_language_user_model->get_languages_for_user($user_id);
        $lLanguages = array(); 

        for($i = 0; $i < count($languages_ids); $i++)
        {
            $language = new SPW_Language_Model();
            $language->id = $languages_ids[$i]->language;
            $language->name = $this->spw_language_model->get_languagename($language->id);
            array_push($lLanguages,$language);
        }        

        $positions = $this->spw_experience_model->get_positions_by_user($user_id);

        $lExperiences = array();
        for($i = 0; $i < count($positions); $i++)
        {
            $position = new SPW_Experience_Model();
            $position->company_name = $positions[$i]->company_name;
            $position->company_industry = $positions[$i]->company_industry;
            $position->title = $positions[$i]->title;
            $position->summary = $positions[$i]->summary;
            $position->start_date = $positions[$i]->start_date;
            $position->end_date = $positions[$i]->end_date;
            array_push($lExperiences, $position);
        }

        $roles = $this->spw_role_model->get_roles();

        $lRoles = array();
        for($i = 0; $i < count($roles); $i++)
        {
            $role = new SPW_Role_Model();
            $role->id = $roles[$i]->id;
            $role->name = $roles[$i]->name; 
            array_push($lRoles,$role);
        }

        $userrole = $this->spw_role_user_model->get_role($user_id);
        
        if(isset($userrole))
        {
            $role = new SPW_Role_Model();
            $role->id = $userrole->role;    
        }
        
        $current_user_id = getCurrentUserId($this);
        $invite = $this->SPW_User_Model->canInviteUser($current_user_id, $user_id);

        $userDetailsViewModel = new SPW_User_Details_View_Model();
        $userDetailsViewModel->user = $user;
        $userDetailsViewModel->lSkills = $lSkills;
        $userDetailsViewModel->lExperiences = $lExperiences;
        $userDetailsViewModel->lLanguages = $lLanguages;
        $userDetailsViewModel->role = $role;
        $userDetailsViewModel->lTerms = $lTerms;
        $userDetailsViewModel->lRoles = $lRoles;

        return $userDetailsViewModel;

    }




    private function getUserDetailsInternalTest($user_id)
    {
        $term1 = new SPW_Term_Model();
        $term1->id = 1;
        $term1->name = 'Spring 2013';
        $term1->description = 'Spring 2013';
        $term1->start_date = '1-8-2013';
        $term1->end_date = '4-26-2013';

        $term2 = new SPW_Term_Model();
        $term2->id = 2;
        $term2->name = 'Summer 2013';
        $term2->description = 'Summer 2013';
        $term2->start_date = '4-26-2013';
        $term2->end_date = '1-8-2013';

        $term3 = new SPW_Term_Model();
        $term3->id = 3;
        $term3->name = 'Fall 2013';
        $term3->description = 'Fall 2013';
        $term3->start_date = '1-8-2013';
        $term3->end_date = '12-28-2013';

        $lTerms = array(
                $term1,
                $term2,
                $term3
            );



        $user1 = new SPW_User_Model();
        $user1->id = $user_id;
        $user1->first_name = 'Flash';
        $user1->last_name = 'Gordon';
        $user1->email = 'lolo@gmail.com';
        $user1->picture = 'http://i0.kym-cdn.com/photos/images/newsfeed/000/162/317/2vA1a.png?1313349760';
        $user1->summary_spw = 'Mobile oriented developer. Has worked for the biggest players in the field.';
        $user1->summary_linkedIn = 'Worked as a security expert at LinkedIn right after they lost all of their passwords';
        $user1->graduation_term = $term1;

        $skill1 = new SPW_Skill_Model();
        $skill1->id = 0;
        $skill1->name = 'Cobol';

        $skill2 = new SPW_Skill_Model();
        $skill2->id = 1;
        $skill2->name = 'Matlab';

        $skill3 = new SPW_Skill_Model();
        $skill3->id = 2;
        $skill3->name = 'Gopher';

        $skill4 = new SPW_Skill_Model();
        $skill4->id = 3;
        $skill4->name = 'bash';

        $lSkills = array(
            $skill1,
            $skill2,
            $skill3,
            $skill4
        );

        $language1 = new SPW_Language_Model();
        $language1->id = 1;
        $language1->name = 'English';

        $language2 = new SPW_Language_Model();
        $language2->id = 2;
        $language2->name = 'Spanish';

        $lLanguages = array(
            $language1,
            $language2
        );

        $experience1 = new SPW_Experience_Model();
        $experience1->id = 1;
        $experience1->company_name = 'Apple';
        $experience1->start_date = '2007-12';
        $experience1->company_industry = 'IT';
        $experience1->title = 'Senior iOS developer';
        $experience1->summary = 'Participated in the initial development of the iOS operating system. Specialized in iOS kernel process scheduling';

        $experience2 = new SPW_Experience_Model();
        $experience2->id = 2;
        $experience2->company_name = 'Google';
        $experience2->start_date = '2010-9';
        $experience2->end_date = '2010-12';
        $experience2->title = 'Senior Android developer';
        $experience2->summary = 'Reingeneered Android core to make it work like iOSs kernel. Enhanced multitasking support';

        $experience3 = new SPW_Experience_Model();
        $experience3->id = 3;
        $experience3->company_name = 'Microsoft';
        $experience2->start_date = '2013-1';
        $experience2->end_date = '2013-3';
        $experience3->company_industry = 'FastFood';
        $experience3->title = 'Senior Mobile developer';
        $experience3->summary = 'Worked on the migration of Microsoft mobile apps from version 7.8 to 8.0. Ported Office 2012 to ARM';


        $lExperiences = array(
            $experience1,
            $experience2,
            $experience3
        );


        $role1 = new SPW_Role_Model();
        $role1->id = 4;
        $role1->name = 'Client';

        $role2 = new SPW_Role_Model();
        $role2->id = 3;
        $role2->name = 'Professor';

        $role = new SPW_Role_Model();
        $role->id = 5;
        $role->name = 'Student';


        $lRoles = array(
            $role,
            $role2,
            $role1
        );


        $userDetailsViewModel = new SPW_User_Details_View_Model();
        $userDetailsViewModel->user = $user1;
        $userDetailsViewModel->lSkills = $lSkills;
        $userDetailsViewModel->lExperiences = $lExperiences;
        $userDetailsViewModel->lLanguages = $lLanguages;
        $userDetailsViewModel->role = $role;
        $userDetailsViewModel->lTerms = $lTerms;
        $userDetailsViewModel->lRoles = $lRoles;
        $userDetailsViewModel->invite = true;

        return $userDetailsViewModel;
    }

    private function getUserCanChangePassword($userId)
    {
        if (is_test($this))
        {
            return true;
        }
        else
        {
            return $this->spw_user_model->is_spw_registered_by_id($userId);
        }
    }
}