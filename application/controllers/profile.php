<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends CI_Controller {

	public function __construct() {
	        parent::__construct();
	        if (! $this->session->userdata('logged_in')) :
	            $this->session->set_userdata('url', uri_string());
	            redirect(base_url().'login');
	        endif;
	        $themes = get_themes();
	        if ( $this->input->get('theme') && in_array($this->input->get('theme'), $themes) )
	        	set_theme( $this->input->get('theme') );
	        $this->form_validation->set_error_delimiters('<p class="bg-danger">', '</p>');
	}
	
	public function index() {
		$title = 'Update Profile';
		$this->load->view(get_client() . '/header', array('title' => $title));
		
		$data = array();
		
		if ( $this->input->post() && ! $this->input->post('verify_password') ) :
			//update profile
			$this->form_validation->set_rules('first_name', 'First Name', "trim|required");
			$this->form_validation->set_rules('last_name', 'Last Name', "trim|required");
			$this->form_validation->set_rules('email', 'Email', "trim|required|valid_email|callback__check_duplicate_email");
			if ( $this->input->post('password') || ! is_profile_complete() ):
				$this->form_validation->set_rules('password', 'Password', "trim|required|min_length[8]|max_length[16]");
			endif;
			$this->form_validation->set_rules('phone', 'Phone Number', "trim|required|callback__isValidPhone");
			$this->form_validation->set_rules('zipcode', 'Zip Code', "trim|required|callback__isValidZipCode");
			if ($this->form_validation->run() == TRUE):
				$data['last_name'] = $this->input->post('last_name');
				$data['first_name'] =  $this->input->post('first_name');
				$data['email_address'] =  $this->input->post('email');
				$data['gender'] =  $this->input->post('gender');
				$data['phone_number'] =  $this->input->post('phone');
				$data['zip_code'] =  $this->input->post('zipcode');
				if ( $this->input->post('password') ):
					$data['password'] =  md5($this->input->post('password'));
				endif;
				$this->db->where('id', $this->session->userdata('id'));
				$this->db->update('users', $data);
				if ( $this->db->affected_rows() > 0 ):
					$data['success_msg'] = 'Update profile success!';
					if ( is_profile_complete() ):
						$this->session->set_userdata('password_verified', TRUE);
					endif;
					if ( $this->session->userdata('url') ):
						redirect($this->session->userdata('url'));
					endif;
				endif;
			else:
				$data['error_msg'] = 'Update profile failed. Please check field below!';
			endif;
		elseif ( $this->input->post('verify_password') ):
			$this->form_validation->set_rules('verify_password', 'Password', "trim|required");
			if ($this->form_validation->run() == TRUE):
				$this->db->where('id', $this->session->userdata('id'));
				$this->db->where('password', md5($this->input->post('verify_password')));
				$query = $this->db->get('users', 1);
				if ( $query->num_rows() > 0 ):
					$this->session->set_userdata('password_verified', TRUE);
				else:
					$data['error_msg'] = 'Wrong password, please try again!';
				endif;
			endif;
		endif;
				
		$data['user'] = get_user_detail();
		
		if ( ! is_profile_complete() || ! $data['user']->password ):
			//profile not complete -> no need to verified the password
			$data['success_msg'] = 'Please complete data below!';
			$this->load->view(get_client() . '/update_profile', $data);
		elseif ( ! $this->session->userdata('password_verified') ):
			$this->load->view(get_client() . '/verify_password', $data);
		else:
			$this->load->view(get_client() . '/update_profile', $data);
		endif;
		
		$this->load->view(get_client() . '/footer', array('picture_upload' => TRUE));
	}
	
	function _check_duplicate_email($mail){
		
		if ( $mail == $this->session->userdata('email_address') )
			return TRUE;
			
		$this->db->where('email_address', $mail);
		$result = $this->db->get('users');
		if ( $result->num_rows() > 0 ):
			$this->form_validation->set_message('_check_duplicate_email', 'This email address already registered on our system. Please enter another email address or try to login use your email!');
			return FALSE;
		else:
			return TRUE;
		endif;
	}
	
	function _isValidPhone($phone){
		if ( ! isPhone($phone) ):
			$this->form_validation->set_message('_isValidPhone', 'Please enter valid phone number!');
			return FALSE;
		else:
			return TRUE;
		endif;
	}
	
	function _isValidZipCode($zipcode){
		if ( ! IsZipCode($zipcode) ):
			$this->form_validation->set_message('_isValidZipCode', 'Please enter valid zip code!');
			return FALSE;
		else:
			return TRUE;
		endif;
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */