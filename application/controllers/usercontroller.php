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

    		$end_date;
    		if($current_position->isCurrent) 
    		{
    			$end_date = (object)array();
    		}else{
    			$end_date =  $current_position->endDate;
    		}

    		$result[$i] = (object) array( 
										'company_name' 		=> $current_position->company->name,
										'company_industry' 	=> $current_position->company->industry,
										'start_date'        => $current_position->startDate,
										'end_date'	        => $end_date,
										'title'				=> $current_position->title,
										'summary'			=> $current_position->summary,
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
	        
	        $response = $this->linkedin->setTokenAccess($response['linkedin']);
	        $profile = $this->linkedin->profile('~:(id,first-name,last-name,picture-url,headline,email-address,summary,skills,languages,positions)');

	        $user = json_decode($profile['linkedin']);

	        $user_profile = (object)array(
		        					'first_name' 			=> $user->firstName,
		        					'last_name' 			=> $user->lastName,
		        					'picture' 				=> $user->pictureUrl,
		        					'headline_linkedIn' 	=> $user->headline,
		        					'summary_linkedIn'      => $user->summary,
		        					'positions_linkedIn'    => $this->parse_positions($user->positions),
		        					'skills' 				=> $this->parse_skills($user->skills),
		        					'languages' 			=> $this->parse_languages($user->languages),
		        				);

	        var_dump($user_profile) ; 				
	        die();

        } else {
          // bad token request, display diagnostic information
          echo "Request token retrieval failed:<br /><br />RESPONSE:<br /><br />" . print_r($response, TRUE);
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
            throw new Exception('not implemented');
        }
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