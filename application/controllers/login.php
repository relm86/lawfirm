<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {
	private $twitter_connection;
	
	public function __construct() {
	        parent::__construct();
	        $this->form_validation->set_error_delimiters('<div class="alert alert-danger" role="alert">', '</div>');
	}
	
	public function index() {
		$data = array();
		$data['states'] = $this->config->item('us_states');
		$data['show_state'] = TRUE;
		//geo ip
		$loc = get_location_info();
		if ( is_object($loc) && isset($loc->ip) ):
			$data['login_ip'] = $loc->ip;
			$data['city'] = $loc->city;
			if ( isset($loc->region_name) ) $data['state'] = $loc->region_name;
			if ( isset($loc->region) ) $data['state'] = $loc->region;
			if ( isset($loc->country_name) ) $data['country'] = $loc->country_name;
			if ( isset($loc->country) ) $data['country'] = $loc->country;
			$data['latitude'] = $loc->latitude;
			$data['longitude'] = $loc->longitude;
		endif;
		$data['show_gender'] = TRUE;
		if ($this->input->post()) :
			$this->form_validation->set_rules('name', 'Name', "trim|required");
			if ( $this->input->post('business') ) $this->form_validation->set_rules('business', 'Business Name', "trim|required");
			if ( $this->input->post('password') ) $this->form_validation->set_rules('password', 'Password', "trim|required|callback__check_password");
			$this->form_validation->set_rules('email', 'Email', "trim|required|valid_email");
			if ( $this->input->post('phone') ) $this->form_validation->set_rules('phone', 'Phone Number', "trim|required|callback__isValidPhone");
			if ( $this->input->post('zipcode') ) $this->form_validation->set_rules('zipcode', 'Zip Code', "trim|required|callback__isValidZipCode");
			
			if ($this->form_validation->run() == TRUE):
                		$data['login_from'] = 'form';
				$data['login_ip'] = $this->session->userdata('ip_address');
				$data['user_agent'] = $this->session->userdata('user_agent');
				$data['last_login'] = $this->session->userdata('last_login');
				
				$this->db->where('email_address', $this->input->post('email'));
				$query = $this->db->get('users', 1);
				$user = $query->row();
				
				$suspend = ($query->num_rows() == 1 && $user->suspend == 1) ? true : false;

				if ( $query->num_rows() < 1 ):
					$data['level'] = 1;
					$parts = explode(" ", $this->input->post('name'));
					$data['last_name'] = array_pop($parts);
					$data['first_name'] =  implode(" ", $parts);
					if ( $this->input->post('business') ) $data['business'] =  $this->input->post('business');
					if ( $this->input->post('phone_number') ) $data['phone_number'] =  $this->input->post('phone');
					if ( $this->input->post('zip_code') ) $data['zip_code'] =  $this->input->post('zipcode');
					if ( $this->input->post('state') ) $data['state'] =  $this->input->post('state');
					if ( $this->input->post('gender') ) $data['gender'] =  $this->input->post('gender');
					$data['email_address'] =  $this->input->post('email');
					if ( $this->input->post('password') ) $data['password'] =  md5($this->input->post('password'));
					
					unset($data['states']);
					unset($data['show_state']);
					unset($data['show_gender']);
					
					$this->db->insert('users', $data);
					$data['id'] = $this->db->insert_id();
				elseif ( $suspend === false ):
					//update login info
					$row = $query->row();
					unset($row->password);
					$parts = explode(" ", $this->input->post('name'));
					$data['last_name'] = array_pop($parts);
					$data['first_name'] =  implode(" ", $parts);
					if ( $this->input->post('password') ) $data['password'] = md5($this->input->post('password'));
					if ( $this->input->post('business') ) $data['business'] =  $this->input->post('business');
					if ( $this->input->post('phone_number') ) $data['phone_number'] =  $this->input->post('phone');
					if ( $this->input->post('zip_code') ) $data['zip_code'] =  $this->input->post('zipcode');
					if ( $this->input->post('state') ) $data['state'] =  $this->input->post('state');
					if ( $this->input->post('gender') ) $data['gender'] =  $this->input->post('gender');
					
					unset($data['states']);
					unset($data['show_state']);
					unset($data['show_gender']);
					
					$this->db->where('id', $row->id);
					$this->db->update('users', $data);
					$data = array_merge($data, (array) $row);
				else:
					$data['error_msg'] = 'Your account is suspended. Please contact admin for more info!';
             		  	endif; //if ( $query->num_rows() < 1 ):
             		  	 
             		  	 if ( $suspend === false ):
             		  	 	$data['logged_in'] = TRUE;
					$this->session->set_userdata($data);
					if ( ! $this->session->userdata('url') ) redirect(base_url('/welcome/'));
					else redirect($this->session->userdata('url'));
				endif;

			endif; //if ($this->form_validation->run() == TRUE):
		endif; //if ($this->input->post()) :
		$page_name = 'login-page';
		if ( $this->input->get('alt-theme') ) $page_name .= ' ' . $this->input->get('alt-theme');
		$this->load->view(get_client() . '/header', array('title' => 'Login', 'login_page' => TRUE, 'page_name' => $page_name));
		$this->load->view(get_client() . '/login-form', $data);
		$this->load->view(get_client() . '/footer', array('login_page' => TRUE));
	}
	
	public function twitter() {
		$this->load->library('twitteroauth');
		$this->config->load('twitter');
		
		if($this->session->userdata('access_token') && $this->session->userdata('access_token_secret')) {
			// If user already logged in
			$this->twitter_connection = $this->twitteroauth->create($this->config->item('twitter_consumer_key'), $this->config->item('twitter_consumer_secret'), $this->session->userdata('access_token'),  $this->session->userdata('access_token_secret'));
		} elseif($this->session->userdata('request_token') && $this->session->userdata('request_token_secret')) {
			// If user in process of authentication
			$this->twitter_connection = $this->twitteroauth->create($this->config->item('twitter_consumer_key'), $this->config->item('twitter_consumer_secret'), $this->session->userdata('request_token'), $this->session->userdata('request_token_secret'));
		} else {
			// Unknown user
			$this->twitter_connection = $this->twitteroauth->create($this->config->item('twitter_consumer_key'), $this->config->item('twitter_consumer_secret'));
		}
		
		if($this->session->userdata('access_token') && $this->session->userdata('access_token_secret')) {
			// User is already authenticated. Add your user notification code here.
			redirect(base_url('/welcome/'));
		} else {
			// Making a request for request_token
			$request_token = $this->twitter_connection->getRequestToken(base_url('login/twitter_callback'));

			$this->session->set_userdata('request_token', $request_token['oauth_token']);
			$this->session->set_userdata('request_token_secret', $request_token['oauth_token_secret']);
			
			if($this->twitter_connection->http_code == 200) {
				$url = $this->twitter_connection->getAuthorizeURL($request_token);
				redirect($url);
			} else {
				// An error occured. Make sure to put your error notification code here.
				redirect(base_url('/login/'));
			}
		}
	}
	
	public function twitter_callback() {
		$this->load->library('twitteroauth');
		$this->config->load('twitter');
		
		if($this->session->userdata('access_token') && $this->session->userdata('access_token_secret')) {
			// If user already logged in
			$this->twitter_connection = $this->twitteroauth->create($this->config->item('twitter_consumer_key'), $this->config->item('twitter_consumer_secret'), $this->session->userdata('access_token'),  $this->session->userdata('access_token_secret'));
		} elseif($this->session->userdata('request_token') && $this->session->userdata('request_token_secret')) {
			// If user in process of authentication
			$this->twitter_connection = $this->twitteroauth->create($this->config->item('twitter_consumer_key'), $this->config->item('twitter_consumer_secret'), $this->session->userdata('request_token'), $this->session->userdata('request_token_secret'));
		} else {
			// Unknown user
			$this->twitter_connection = $this->twitteroauth->create($this->config->item('twitter_consumer_key'), $this->config->item('twitter_consumer_secret'));
		}
		
		if($this->input->get('oauth_token') && $this->session->userdata('request_token') !== $this->input->get('oauth_token')) {
			$this->reset_session();
			redirect(base_url('/login/twitter/'));
		} else {
			$access_token = $this->twitter_connection->getAccessToken($this->input->get('oauth_verifier'));
			$credentials = (array) $this->twitter_connection->get('account/verify_credentials');
			
			if ($this->twitter_connection->http_code == 200) {
				//collect data
				$data['login_from'] = 'twitter';
				$data['t_id'] = $credentials['id'];
				$data['t_name'] = $credentials['name'];
				$data['t_screen_name'] = $credentials['screen_name'];
				$data['t_location'] = $credentials['location'];
				$data['t_image'] = $credentials['profile_image_url'];
				$data['t_image_https'] = $credentials['profile_image_url_https'];
				$data['login_ip'] = $this->session->userdata('ip_address');
				$data['user_agent'] = $this->session->userdata('user_agent');
				$data['last_login'] = $this->session->userdata('last_login');
				
				$this->db->where('t_id', $credentials['id']);
				$query = $this->db->get('users', 1);
				if ( $query->num_rows() < 1 ):
					//store image
					$img = file_get_contents( str_replace('_normal.jpeg', '.jpeg', $data['t_image_https']) );
					$file = './img/users/t_'.$data['t_id'].'.jpg';
					file_put_contents($file, $img);
					
					$data['level'] = 1;
					$parts = explode(" ", $credentials['name']);
					$data['last_name'] = array_pop($parts);
					$data['first_name'] =  implode(" ", $parts);
					$data['picture'] = $file;
					
					$this->db->insert('users', $data); 
					$data['id'] = $this->db->insert_id();
				else:
					//update login info
					$row = $query->row(); 
					unset($row->password);
					$this->db->where('id', $row->id);
					$this->db->update('users', $data); 
					$data = array_merge($data, (array) $row);
				endif;
				
				$data['logged_in'] = TRUE;
				$this->session->set_userdata($data);
				if ( ! $this->session->userdata('url') ) redirect(base_url('/welcome/'));
				else redirect($this->session->userdata('url'));
			} else {
				// An error occured. Add your notification code here.
				redirect(base_url('/login/'));
			}
		}
	}
	
	/**
	 * Reset session data
	 * @access	private
	 * @return	void
	 */
	private function reset_session() {
		$this->session->unset_userdata('access_token');
		$this->session->unset_userdata('access_token_secret');
		$this->session->unset_userdata('request_token');
		$this->session->unset_userdata('request_token_secret');
		$this->session->unset_userdata('twitter_user_id');
		$this->session->unset_userdata('twitter_screen_name');
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
	
	function _check_password(){
		
		$mail = $this->input->post('email');
		$password = $this->input->post('password');
		$this->db->where('email_address', $mail);
		$result = $this->db->get('users');
		if ( $result->num_rows() > 0 ):
			$this->db->where('email_address', $mail);
			$this->db->where('password', md5($password));
			$result = $this->db->get('users'); //echo $this->db->last_query();
			$this->db->where('email_address', $mail);
			$this->db->where('password', '');
			$result2 = $this->db->get('users'); //echo $this->db->last_query();
			if ( $result->num_rows() > 0 ):
				return TRUE;
			elseif ( $result2->num_rows() > 0 ):
				return TRUE; //not set password, previous login is from social network
			else:
				$this->form_validation->set_message('_check_password', 'Wrong email address or password!');
				return FALSE;
			endif;
		else:
			return TRUE;
		endif;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */