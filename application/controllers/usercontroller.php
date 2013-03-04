<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UserController extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();

		/*
		$this->load->model('SPW_Project_Model');
		$this->load->model('SPW_Term_Model');
		$this->load->model('SPW_Skill_Model');
		$this->load->model('SPW_User_Model');
		$this->load->model('SPW_Project_Status_Model');
		$this->load->model('SPW_User_Summary_View_Model');
		$this->load->model('SPW_Project_Summary_View_Model');
		*/
		//$this->output->cache(60);
	}


	public function profile($user_id='')
	{
		$this->output->set_output('user profile '.$user_id);
	}

	public function current_user()
	{
		$current_user_id = getCurrentUserId($this);
		$this->profile($current_user_id);
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

  
}