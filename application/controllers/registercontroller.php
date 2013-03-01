<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();

class RegisterController extends CI_Controller 
{

	public function __construct()
	{
		parent::__construct();
	}


	public function index()
	{
		$this->load->view('register_index');
	}

	public function register()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('email_address', 'Email Address','required|valid_email'); 
		$this->form_validation->set_rules('password_1', 'Password','required|min_length[6]'); 
		$this->form_validation->set_rules('password_2', 'Password','required|min_length[6]'); 
		$data = array(); 

		if($this->form_validation->run() !== false)
		{
			$this->load->model('spw_user_model');

			$res = $this->spw_user_model->is_owned_registered($this->input->post('email_address'));
			if($res == false)
			{
				$new_user_id = $this->spw_user_model->create_new_user($this->input->post('email_address'), $this->input->post('password_1'));
				

				$sess_array = array(
					'id' => $new_user_id, 
					'email' => $this->input->post('email_address'), 
				);
				$this->session->set_userdata('logged_in', $sess_array);
			
				redirect('home','refresh');
			}
			else
			{
				$data['already_registered'] = true;
			}
		}
		$this->load->view('register_index',$data);

	}
}