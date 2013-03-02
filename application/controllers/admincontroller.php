<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();

class AdminController extends CI_Controller 
{

	public function __construct()
	{
		parent::__construct();
	}


	public function index()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('email_address', 'Email Address','required|valid_email'); 
		$this->form_validation->set_rules('password', 'Password','required|min_length[6]'); 
		$data = array();

		if($this->form_validation->run() !== false)
		{
			$data['credentials_error'] = "";
			$this->load->model('spw_user_model');
			$res = $this->spw_user_model->verify_user($this->input->post('email_address'), $this->input->post('password'));
			if($res !== false)
			{
				foreach ($res as $row) {

					$sess_array = array(
						'id' => $row->id, 
						'email' => $row->email,
						'using' => 'fiu_senior_project' 
					);
					$this->session->set_userdata('logged_in', $sess_array);
				}
				redirect('home','refresh');
			}
			else
			{
				$data['credentials_error'] = "Invalid Credentials";
			}
		}
		$this->load->view('login_index',$data);
	}
}