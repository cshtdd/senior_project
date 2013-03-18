<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UserController extends CI_Controller 
{
    
    public function __construct()
    {
        parent::__construct();

        $this->load->helper('request');

        $this->load->model('SPW_Term_Model');
        $this->load->model('SPW_Skill_Model');
        $this->load->model('SPW_Experience_Model');
        $this->load->model('SPW_Language_Model');
        $this->load->model('SPW_Role_Model');
        $this->load->model('SPW_User_Model');
        $this->load->model('SPW_User_Summary_View_Model');
        $this->load->model('SPW_User_Details_View_Model');

        //$this->output->cache(60);
    }


    public function profile($user_id='')
    {
        //$this->output->set_output('user profile '.$user_id);
        $currentUserId = getCurrentUserId($this);
        $user_details = $this->getUserDetailsInternal($user_id);

        if ($user_id == $currentUserId) //we are viewing the current user profile
        {
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
            //TODO redirect to login page if not logged in

            $currentUserId = getCurrentUserId($this); 

            $updatedFirstName = $this->input->post('text-first-name');
            $updatedLastName = $this->input->post('text-last-name');
            $updatedSPWSummary = $this->input->post('text-description');
            $updatedRoleId = $this->input->post('radio-role');
            $updatedUserPictureUrl = $this->input->post('hidden-img-src');            

            //only consider this if the role is a student
            $updatedTermId = $this->input->post('dropdown-term');

            //TODO validate the data against XSS and CSRF and SQL Injection

            /*
            $this->output->set_output(
                $updatedFirstName.' '.
                $updatedLastName.' '.
                $updatedSPWSummary.' '.
                $updatedRoleId.' '.
                $updatedTermId.' '.
                $updatedUserPictureUrl
            );
            */

            //TODO update the user data to the DB

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
                $currentUser = getCurrentUserId($this);
                $invitedUser = $this->input->post('uid');

               inviteUserInternal($currentUser, $invitedUser);
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

            $result[$i] = (object) array( 
                                        'company_name'      => $current_position->company->name,
                                        'company_industry'  => $current_position->company->industry,
                                        'start_date'        => $start_date,
                                        'end_date'          => $end_date,
                                        'title'             => $current_position->title,
                                        'summary'           => $current_position->summary,
                                        );
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
    
        echo 'Linkedin user cancelled login';            
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
                    'picture'               => $user->pictureUrl,
                    'headline_linkedIn'     => $user->headline,
                    'summary_linkedIn'      => $user->summary,
                    'positions_linkedIn'    => $this->parse_positions($user->positions),
                    'skills'                => $this->parse_skills($user->skills),
                    'languages'             => $this->parse_languages($user->languages),
                );

            $this->load->model('spw_user_model');
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

            }
        }else{
            echo "Bad Request";
        }                  
    }

    private function inviteUserInternal($currentUserId, $invitedUserId)
    {
        if (is_test($this))
        {
            $this->output->set_output('Current User '.$currentUser.' InvitedUser '.$invitedUser);
            return true;
        }
        else
        {
            throw new Exception('not implemented');
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
        $this->load->model('spw_term_model');

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
        $this->load->model('spw_user_model');
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
        $user->graduation_term = $lTerms[0];

        return $user;
    }

    private function getUserDetails($user_id)
    {
        $lTerms = $this->get_all_available_terms();

        $user = $this->get_profile($user_id, $lTerms);

        $this->load->model('spw_skill_user_model');
        $this->load->model('spw_skill_model');
        $skills_ids = $this->spw_skill_user_model->get_skills_for_user($user_id);


        $lSkills = array(); 
        for($i = 0; $i < count($skills_ids); $i++)
        {
            $skill = new SPW_Skill_Model();
            $skill->id = $skills_ids[$i]->skill;
            $skill->name = $this->spw_skill_model->get_skillname($skill->id);
            array_push($lSkills,$skill);
        }        

        $this->load->model('spw_language_user_model');
        $this->load->model('spw_language_model');
        $languages_ids = $this->spw_language_user_model->get_languages_for_user($user_id);
        $lLanguages = array(); 

        for($i = 0; $i < count($languages_ids); $i++)
        {
            $language = new SPW_Language_Model();
            $language->id = $languages_ids[$i]->language;
            $language->name = $this->spw_language_model->get_languagename($language->id);
            array_push($lLanguages,$language);
        }        

        $this->load->model('spw_experience_model');
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
        
        $this->load->model('spw_role_model');
        $roles = $this->spw_role_model->get_roles();

        $lRoles = array();
        for($i = 0; $i < count($roles); $i++)
        {
            $role = new SPW_Role_Model();
            $role->id = $roles[$i]->id;
            $role->name = $roles[$i]->name; 
            array_push($lRoles,$role);
        }

        $this->load->model('spw_role_user_model');
        $role = $this->spw_role_user_model->get_role($user_id);
        
        $role = new SPW_Role_Model();
        $role->id = $role->id;
        $role->name = $role->name; 

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
        $experience1->title = 'Senior iOS developer at Apple';
        $experience1->description = 'Participated in the initial development of the iOS operating system. Specialized in iOS kernel process scheduling';

        $experience2 = new SPW_Experience_Model();
        $experience2->id = 2;
        $experience2->title = 'Senior Android developer at Google';
        $experience2->description = 'Reingeneered Android core to make it work like iOSs kernel. Enhanced multitasking support';

        $experience3 = new SPW_Experience_Model();
        $experience3->id = 3;
        $experience3->title = 'Senior Mobile developer at Microsoft';
        $experience3->description = 'Worked on the migration of Microsoft mobile apps from version 7.8 to 8.0. Ported Office 2012 to ARM';


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

        return $userDetailsViewModel;
    }
}