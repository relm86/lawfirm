<?php
//Logout Controller

class Logout extends CI_Controller {
	
 	public function __construct() {
		parent::__construct();
		$this->session->sess_destroy();
		redirect(base_url('login'));
	}
	
}

/* End of file login.php */
/* Location: ./system/application/controllers/logout.php */
?>