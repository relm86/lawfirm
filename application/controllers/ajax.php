<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//Ajax Controller

class Ajax extends CI_Controller {
	
	public function __construct() {
        	parent::__construct();
	}
	
	function fb_login(){
		if ($this->input->post()) :
			$this->form_validation->set_rules('id', 'Facebook ID', "trim|required");
			$this->form_validation->set_rules('email', 'Email', "trim|required|valid_email");
			$this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
			$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
			$this->form_validation->set_rules('name', 'Name', 'trim|required');
			$this->form_validation->set_rules('id', 'FB ID', 'trim|required');
			$this->form_validation->set_rules('gender', 'Gender', 'trim|required');
			$this->form_validation->set_rules('fb_link', 'FB Link', 'trim|required');
			$this->form_validation->set_rules('timezone', 'Timezone', 'trim|required');
			if ($this->form_validation->run() == TRUE):
				//collect data
				$data['login_from'] = 'fb';
				$data['f_id'] = $this->input->post('id');
				$data['f_email'] = $this->input->post('email');
				$data['f_first_name'] = $this->input->post('first_name');
				$data['f_last_name'] = $this->input->post('last_name');
				$data['f_name'] = $this->input->post('name');
				$data['f_gender'] = $this->input->post('gender');
				$data['f_link'] = $this->input->post('fb_link');
				$data['login_ip'] = $this->session->userdata('ip_address');
				$data['user_agent'] = $this->session->userdata('user_agent');
				$data['last_login'] = $this->session->userdata('last_activity');
				
				$this->db->where('f_id', $this->input->post('id'));
				$this->db->or_where('email_address', $data['f_email']); 
				$query = $this->db->get('users', 1);
				if ( $query->num_rows() < 1 ):
					//store image
					$img = file_get_contents('https://graph.facebook.com/'.$data['f_id'].'/picture?width=100&height=100');
					$file = './img/users/f_'.$data['f_id'].'.jpg';
					file_put_contents($file, $img);
					
					//FB ID not found -> insert
					$data['email_address'] = $data['f_email'];
					$data['level'] = 1;
					$data['first_name'] = $data['f_first_name'];
					$data['last_name'] = $data['f_last_name'];
					$data['gender'] = $data['f_gender'];
					$data['picture'] = $file;
					
					$this->db->insert('users', $data); 
					$data['id'] = $this->db->insert_id();
				else:
					//update login info
					$row = $query->row();
					$this->db->where('id', $row->id);
					$this->db->update('users', $data); 
					unset($row->password);
					$data = array_merge($data, (array) $row);
				endif;
				
				//save session
				$data['logged_in'] = TRUE;
				$this->session->set_userdata($data);
				//$this->output->set_content_type('application/json')->set_output(json_encode(array('login' => 'true')));
				if ( ! $this->session->userdata('url') ) $url = base_url('/welcome/');
				else $url = $this->session->userdata('url');
				echo json_encode(array('login' => 'true', 'go' => $url));
				die;
			endif;
		endif;
		
		$data = array(
			'email' => form_error('email'),
			'first_name' => form_error('first_name'),
			'last_name' => form_error('last_name'),
			'name' => form_error('name'),
			'fb_id' => form_error('fb_id'),
			'gender' => form_error('gender'),
			'fb_link' => form_error('fb_link'),
			'timezone' => form_error('timezone')
		);
		$this->output->set_content_type('application/json')->set_output(json_encode(array('login' => 'false', 'error' => $data)));
	}
	
	function google_login(){
		if ($this->input->post()) :
			$this->form_validation->set_rules('email', 'Email', "trim|required|valid_email");
			$this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
			$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
			$this->form_validation->set_rules('name', 'Name', 'trim|required');
			$this->form_validation->set_rules('id', 'Google ID', 'trim|required');
			$this->form_validation->set_rules('gender', 'Gender', 'trim|required');
			$this->form_validation->set_rules('g_link', 'Google Link', 'trim|required');
			$this->form_validation->set_rules('picture', 'Google Picture', 'trim|required');
			//$this->form_validation->set_rules('timezone', 'Timezone', 'trim|required');
			if ($this->form_validation->run() == TRUE):
				//collect data
				$data['login_from'] = 'google';
				$data['g_email'] = $this->input->post('email');
				$data['g_first_name'] = $this->input->post('first_name');
				$data['g_last_name'] = $this->input->post('last_name');
				$data['g_id'] = $this->input->post('id');
				$data['g_gender'] = $this->input->post('gender');
				$data['g_link'] = $this->input->post('g_link');
				$data['login_ip'] = $this->session->userdata('ip_address');
				$data['user_agent'] = $this->session->userdata('user_agent');
				$data['last_login'] = $this->session->userdata('last_activity');
				
				$this->db->where('g_id', $this->input->post('id'));
				$this->db->or_where('email_address', $data['g_email']); 
				$query = $this->db->get('users', 1);
				if ( $query->num_rows() < 1 ):
					//store image
					$img = str_replace('?sz=50', '?sz=100', $this->input->post('picture'));
					$img = file_get_contents( $img );
					$file = './img/users/g_'.$data['g_id'].'.jpg';
					file_put_contents($file, $img);
					
					//FB ID not found -> insert
					$data['email_address'] = $data['g_email'];
					$data['level'] = 1;
					$data['first_name'] = $data['g_first_name'];
					$data['last_name'] = $data['g_last_name'];
					$data['gender'] = $data['g_gender'];
					$data['picture'] = $file;
					
					$this->db->insert('users', $data); 
					$data['id'] = $this->db->insert_id();
				else:
					//update login info
					$row = $query->row(); 
					$this->db->where('id', $row->id);
					$this->db->update('users', $data); 
					unset($row->password);
					$data = array_merge($data, (array) $row);
				endif;
				
				//save session
				$data['logged_in'] = TRUE;
				$this->session->set_userdata($data);
				if ( ! $this->session->userdata('url') ) $url = base_url('/welcome/');
				else $url = $this->session->userdata('url');
				echo json_encode(array('login' => 'true', 'go' => $url));
				die;
			endif;
		endif;
		
		$data = array(
			'email' => form_error('email'),
			'first_name' => form_error('first_name'),
			'last_name' => form_error('last_name'),
			'name' => form_error('name'),
			'fb_id' => form_error('fb_id'),
			'gender' => form_error('gender'),
			'fb_link' => form_error('fb_link'),
			'timezone' => form_error('timezone')
		);
		$this->output->set_content_type('application/json')->set_output(json_encode(array('login' => 'false', 'error' => $data)));
	}
	
	function linkedin_login(){
		if ($this->input->post()) :
			$this->form_validation->set_rules('email', 'Email', "trim|required|valid_email");
			$this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
			$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
			//$this->form_validation->set_rules('name', 'Name', 'trim|required');
			$this->form_validation->set_rules('id', 'LinkedIn ID', 'trim|required');
			$this->form_validation->set_rules('picture', 'Picture', 'trim|required');
			//$this->form_validation->set_rules('g_link', 'Google Link', 'trim|required');
			//$this->form_validation->set_rules('timezone', 'Timezone', 'trim|required');
			if ($this->form_validation->run() == TRUE):
				//collect data
				$data['login_from'] = 'linkedin';
				$data['l_email'] = $this->input->post('email');
				$data['l_first_name'] = $this->input->post('first_name');
				$data['l_last_name'] = $this->input->post('last_name');
				$data['l_id'] = $this->input->post('id');
				$data['login_ip'] = $this->session->userdata('ip_address');
				$data['user_agent'] = $this->session->userdata('user_agent');
				$data['last_login'] = $this->session->userdata('last_activity');
				
				$this->db->where('l_id', $this->input->post('id'));
				$this->db->or_where('email_address', $data['l_email']); 
				$query = $this->db->get('users', 1);
				if ( $query->num_rows() < 1 ):
					//store image
					$img = file_get_contents( $this->input->post('picture') );
					$file = './img/users/l_'.$data['l_id'].'.jpg';
					file_put_contents($file, $img);
					
					//FB ID not found -> insert
					$data['email_address'] = $data['l_email'];
					$data['level'] = 1;
					$data['first_name'] = $data['l_first_name'];
					$data['last_name'] = $data['l_last_name'];
					$data['picture'] = $file;
					
					$this->db->insert('users', $data); 
					$data['id'] = $this->db->insert_id();
				else:
					//update login info
					$row = $query->row(); 
					$this->db->where('id', $row->id);
					$this->db->update('users', $data); 
					unset($row->password);
					$data = array_merge($data, (array) $row);
				endif;
				
				//save session
				$data['logged_in'] = TRUE;
				$this->session->set_userdata($data);
				//$this->output->set_content_type('application/json')->set_output(json_encode(array('login' => 'true')));
				if ( ! $this->session->userdata('url') ) $url = base_url('/welcome/');
				else $url = $this->session->userdata('url');
				echo json_encode(array('login' => 'true', 'go' => $url));
				die;
			endif;
		endif;
		
	}
	
	public function upload_foto(){
		if (! $this->session->userdata('logged_in')) die();
		
		$config['upload_path'] = './img/users/';
		$config['allowed_types'] = 'jpeg|jpg|png';
		$config['max_size']	= '1024';
		$config['max_width']  = '500';
		$config['max_height']  = '500';
		$config['overwrite']  = TRUE;
		$config['file_name']  = 'user_picture_' . $this->input->post('id');

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload('file')):
			$error = $this->upload->display_errors();
			//var_dump($error);
			$result = FALSE;
			$img_url = '';
		else:
			$data = $this->upload->data();
			
			//update data
			$update['picture'] = './img/users/' . $data['raw_name'] . $data['file_ext'];
			$this->db->where('id', $this->input->post('id'));
           		$this->db->update('users', $update);
			
			$result = TRUE;
			$img_url = base_url() . str_replace('./', '',  create_thumb($update['picture'], 100, 100) );
			$error = '';
		endif;
		
		$response = array(
				            'success' => $result,
				            'img_url' => $img_url,
					    'error'   => strip_tags($error)
				        );

        	echo json_encode($response);
        	exit;
	}	
}