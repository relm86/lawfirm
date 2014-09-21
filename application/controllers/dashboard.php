<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct() {
	        parent::__construct();


	        if (! $this->session->userdata('logged_in') && $this->uri->segment(2) != 'login' ) :
			$this->session->set_userdata('url', uri_string());
			redirect(base_url('dashboard/login') );
		elseif ( $this->session->userdata('level') < 2 && $this->uri->segment(2) != 'login'):
			redirect(base_url('welcome'));
		elseif ( ! is_profile_complete()  && $this->uri->segment(2) != 'login' ):
			$this->session->set_userdata('url', uri_string());
			redirect(base_url('profile'));
	        endif;
	        
	        $themes = get_themes();
	        if ( $this->input->get('theme') && in_array($this->input->get('theme'), $themes) )
	        	set_theme( $this->input->get('theme') );
	        
	        $this->form_validation->set_error_delimiters('<div class="alert alert-danger" role="alert">','</div>');
	        
	}
	
	public function index() {
		if ( ! $this->session->userdata('password_verified') ):
			$this->session->set_userdata('url', uri_string());
			$this->verify_password();
			return;
		endif;

		$this->load->view( 'dashboard/header' );
		$this->load->view( 'dashboard/top-nav' );
		
		//super admin
		if ( $this->session->userdata('level') == 3 ):
			$this->db->where('id !=', 1);
			$this->db->where('id !=', 2);
			$this->db->where('id !=', 3);
			$this->db->where('id !=', 4);
			$data['users'] = $this->db->get('users');
			$this->load->view( 'dashboard/user/list', $data );
		endif;
		
		$this->db->select('*');
		$this->db->from('templates');
		$data['templates'] = $this->db->get();
		$this->load->view( 'dashboard/template/list', $data );
		
		$this->load->view( 'dashboard/footer' );
		//var_dump($this->session->all_userdata());
	}
	
	public function login(){
		$data = array();
		if ($this->input->post()) :
			$this->form_validation->set_rules('email', 'Email', "trim|required|valid_email");
			$this->form_validation->set_rules('password', 'Password', "trim|required");
			
			if ($this->form_validation->run() == TRUE):
				$data['login_from'] = 'form';
				$data['login_ip'] = $this->session->userdata('ip_address');
				$data['user_agent'] = $this->session->userdata('user_agent');
				$data['last_login'] = $this->session->userdata('last_login');
				
				$this->db->where('email_address', $this->input->post('email'));
				$this->db->where('password', md5($this->input->post('password')) );
				$query = $this->db->get('users', 1);
				if ( $query->num_rows() < 1 ):
					$data['error_msg'] = 'Wrong email address or password, please try again!';
				else:
					//update login info
					$row = $query->row(); 
					unset($row->password);

					$this->db->where('id', $row->id);
					$this->db->update('users', $data); 
					$data = array_merge($data, (array) $row);
					$data['logged_in'] = TRUE;
					$data['password_verified'] = TRUE;
					$this->session->set_userdata($data);
					redirect(base_url('/dashboard/'));
				endif;
				
			endif;
		endif;
		
		$this->load->view( 'dashboard/header', array('title' => 'Login', 'page_preview' => TRUE) );
		$this->load->view( 'dashboard/login', $data );
		$this->load->view( 'dashboard/footer' );
		//var_dump($this->session->all_userdata());
	}
	
	public function verify_password(){
		$data = array();
		
		if ( $this->input->post() ):
			$this->form_validation->set_rules('verify_password', 'Password', "trim|required");
			if ($this->form_validation->run() == TRUE):
				$this->db->where('id', $this->session->userdata('id'));
				$this->db->where('password', md5($this->input->post('verify_password')));
				$query = $this->db->get('users', 1);
				if ( $query->num_rows() > 0 ):
					$this->session->set_userdata('password_verified', TRUE);
					$this->index();
					return;
				else:
					$data['error_msg'] = 'Wrong password, please try again!';
				endif;
			endif;
		endif;
		
		$this->load->view( 'dashboard/header' );
		$this->load->view( 'dashboard/top-nav' );
		$this->load->view( 'dashboard/verify-password', $data );
		$this->load->view( 'dashboard/footer' );
	}
	
	public function new_template( $data = array() ){
				
		if ($this->input->post()):
			$this->form_validation->set_rules('name', 'Template URL', "trim|required|callback__unique_template_name");
			$this->form_validation->set_rules('color_scheme', 'Color Scheme', "trim|required");
			$this->form_validation->set_rules('layout', 'Template Layout', "trim|required");
			if ($this->form_validation->run() == TRUE):
				$insert['name'] = $this->input->post('name');
				$insert['color_scheme'] = $this->input->post('color_scheme');
				$insert['layout'] = $this->input->post('layout');
				$insert['view_count'] = 0;
				$insert['owner'] = $this->session->userdata('id');
				$this->db->insert('templates', $insert); 
				$template_id = $this->db->insert_id();
				
				if ( $template_id ){
					$this->template_preview($template_id);
					return;
				} else {
					$data = array_merge($data, $insert);
				}
			endif;
		endif;
		
		$this->load->view( 'dashboard/header' );
		$this->load->view( 'dashboard/top-nav' );
		$this->load->view( 'dashboard/template/new', $data );
		$this->load->view( 'dashboard/footer' );
	} 
	
	public function template_preview( $template_id = NULL){
		$template_id = (int) $template_id;
		$this->db->where('id', $template_id );
		$query = $this->db->get('templates', 1);
		if ( $query->num_rows() > 0 ):
			$data['template'] =  $query->row();
		else:
			$this->new_template(array('error_msg' => 'Template not found. You may try to create new template or hit back to go to previous page!'));
			return;
		endif;
		
		$this->load->view( 'dashboard/header', array('jqueryui' => TRUE, 'page_preview' => TRUE, 'layout'=> $data['template']->layout, 'color_scheme' => $data['template']->color_scheme) );
		$this->load->view( 'dashboard/template/preview_' . $data['template']->layout, $data );
		$this->load->view( 'dashboard/footer', array('jqueryui' => TRUE) );
	}
	
	public function dev_template_preview( $template_id = NULL){
		$template_id = (int) $template_id;
		$this->db->where('id', $template_id );
		$query = $this->db->get('templates', 1);
		if ( $query->num_rows() > 0 ):
			$data['template'] =  $query->row();
		else:
			$this->new_template(array('error_msg' => 'Template not found. You may try to create new template or hit back to go to previous page!'));
			return;
		endif;
		
		$this->load->view( 'dashboard/header', array('jqueryui' => TRUE, 'page_preview' => TRUE, 'layout'=> $data['template']->layout, 'color_scheme' => $data['template']->color_scheme) );
		$this->load->view( 'dashboard/template/dev_preview_' . $data['template']->layout, $data );
		$this->load->view( 'dashboard/footer', array('jqueryui' => TRUE) );
	}
	
	function _unique_template_name(){
		
		$name = $this->input->post('name');
		$this->db->where('name', $name);
		$result = $this->db->get('templates');
		if ( $result->num_rows() > 0 ):
			$this->form_validation->set_message('_unique_template_name', 'Template name is exist. Please enter another template name!');
			return FALSE;
		else:
			return TRUE;
		endif;
	}
	
	function ajax( $action ){
		if ( $action == 'save_widget' )
			$this->_save_widget();
		elseif ( $action == 'save_layout')
			$this->_save_layout();
		elseif ( $action == 'upload_main_image')
			$this->_upload_main_image();
	}

	function _save_widget(){
		$user = get_user_detail( $this->input->post('user_id'));
		if ( ! $user || $user->level != 3 )
			die('You have no role for this action');

		//var_dump($this->session->all_userdata());

		$response = array(
	            'success' => FALSE
	        );
			
		if ($this->input->post()) :
			$this->form_validation->set_rules('widget_type', 'Widget Type', "trim|required");
			$this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
			$this->form_validation->set_rules('template_id', 'Template ID', 'trim|required');
			if ($this->form_validation->run() == TRUE):
				if ( ! $this->input->post('widget_id') || ! valid_widget_id($this->input->post('widget_id')) ):
					//save widget 
					$insert['template_id'] = (int) $this->input->post('template_id');
					$insert['widget_type'] = $this->input->post('widget_type');
					
					$this->db->insert('widgets', $insert);
					$widget_id = $this->db->insert_id();
					
					$response = array(
				            'success' => TRUE,
				            'widget_id' => 'widget_' . $insert['widget_type'] . '_' . $widget_id
				        );

				else:
				endif;
			endif;
		endif;
		
		echo json_encode($response);
		exit;
	}
	
	function _save_layout(){
		$user = get_user_detail( $this->input->post('user_id'));
		if ( ! $user || $user->level != 3 )
			die('You have no role for this action');

		//var_dump($this->session->all_userdata());

		$response = array(
	            'success' => TRUE
	        );
			
		if ($this->input->post()) :
			$this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
			$this->form_validation->set_rules('template_id', 'Template ID', 'trim|required');
			if ($this->form_validation->run() == TRUE):
				//save layout 
				$template_id = (int) $this->input->post('template_id');
				$sidebar = $this->input->post('sidebar');
				$left = $this->input->post('left');
				$right = $this->input->post('right');
				$footer = $this->input->post('footer');
				$widgets = array(
										'sidebar' => $sidebar,
										'left' => $left,
										'right' => $right,
										'footer' => $footer
									);
				$update['widgets'] = serialize($widgets);
				$this->db->where('id', $template_id);
				$this->db->update('templates', $update);
			endif;
		endif;
		
		echo json_encode($response);
		exit;
	}
	
	function _upload_main_image(){
		$user = get_user_detail( $this->input->post('user_id'));
		if ( ! $user || $user->level != 3 )
			die('You have no role for this action');

		//var_dump($this->session->all_userdata());

		$response = array(
	            'success' => FALSE
	        );
			
		if ($this->input->post()) :
			$this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
			$this->form_validation->set_rules('template_id', 'Template ID', 'trim|required');
			if ($this->form_validation->run() == TRUE):
				$config['upload_path'] = './img/slider/' . $this->input->post('template_id') . '/';
				$config['allowed_types'] = 'jpeg|jpg|png';
				$config['max_size']	= '10240';
				$config['max_width']  = '500';
				$config['max_height']  = '500';
				$config['overwrite']  = TRUE;

				$this->load->library('upload', $config);

				if ( ! $this->upload->do_upload('file')):
					$error = $this->upload->display_errors();
					//var_dump($error);
					$result = FALSE;
					$img_url = '';
				else:
					$data = $this->upload->data();
					
					//update data
					$update['picture'] = './img/slider/' . $this->input->post('template_id') . '/' . $data['raw_name'] . $data['file_ext'];
					$this->db->where('id', $this->input->post('id'));
		           		$this->db->update('users', $update);
					
					$result = TRUE;
					$img_url = base_url() . str_replace('./', '',  create_thumb($update['picture'], 100, 100) );
					$error = '';
				endif;
			endif;
		endif;
		
		echo json_encode($response);
		exit;
	}

    function edituser($id)
    {
        $this->db->where('id', $id);

        if (!$this->input->post())
        {
            $query = $this->db->get('users', 1);
            $data['row'] = $query->row();
            $data['selects'] = array('level', 'gender');
            $data['select_values'] = array(
                'level'  => array(1 => '1', 2 => 2, 3 => '3', 4 => '4'),
                'gender' => array('m' => 'male', 'f' => 'female')
            );
            $this->load->view( 'dashboard/header' );
            $this->load->view( 'dashboard/top-nav' );
            $this->load->view( 'dashboard/user/edit', $data );
            $this->load->view( 'dashboard/footer' );
        }
        else
        {
            $data = $this->input->post();

            if (!$data['password'] || $data['password'] == '')
            {
                unset($data['password']);
            }
            else
            {
                $data['password'] = md5($data['password']);
            }

            $this->db->update('users', $data);
            redirect('dashboard');
        }
    }

    function loginas($id)
    {
        var_dump($id);
    }

    function suspend($id)
    {
        var_dump($id);
    }

    function deleteuser($id)
    {
        var_dump($id);
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */