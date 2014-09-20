<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CodeIgniter AppCommon  Helpers
 *
 * @package		AppCommon
 * @subpackage	Helpers
 * @category	Helpers
 * @author		asep@wordpress-services.com
 * @link		asep@wordpress-services.com
 */

// ------------------------------------------------------------------------

if ( ! function_exists('create_thumb'))
{
	function create_thumb($source, $width, $height) {
		$CI = get_instance();
		$config['image_library'] = 'gd2';
		$config['source_image']	= $source;
		$ext = pathinfo($source, PATHINFO_EXTENSION);
		$config['new_image']	= str_replace('.'.$ext, '_thumb_' . $width . 'x' . $height . '.'. $ext, $source);
		$config['create_thumb'] = FALSE;
		$config['maintain_ratio'] = TRUE;
		$config['width']	= $width;
		$config['height']	= $height;
		
		if ( file_exists( $config['new_image'] ) ) return $config['new_image'];

		$CI->load->library('image_lib'); 
		$CI->image_lib->clear(); 
		$CI->image_lib->initialize($config);
		
		if ( ! $CI->image_lib->resize()):
			echo $this->image_lib->display_errors();
			return FALSE;
		else:
			return $config['new_image'];
		endif;
	}
}

if ( ! function_exists('is_profile_complete'))
{
	function is_profile_complete() {
		$row = get_user_detail();
		//if ( ! $row || ! $row->email_address || ! $row->password || ! $row->level || ($row->first_name . $row->last_name == '') || ! $row->phone_number || ! $row->zip_code ):
		if ( ! $row || ! $row->level || ($row->first_name . $row->last_name == '') ):
			return FALSE;
		endif;
		
		return TRUE;
	}
}

if ( ! function_exists('get_user_detail'))
{
	function get_user_detail( $userid = FALSE ) {
		$CI = get_instance();
		if ( ! $userid )$userid = $CI->session->userdata('id');
		
		$CI->db->where('id', $userid);
		$query = $CI->db->get('users', 1);
		if ( $query->num_rows() > 0 ):
			return  $query->row();
		else:
			return FALSE;
		endif;
		
		return FALSE;
	}
}

if ( ! function_exists('get_user_fullname'))
{
	function get_user_fullname( $userid = FALSE ) {
		$CI = get_instance();
		if ( ! $userid )$userid = $CI->session->userdata('id');
		
		$CI->db->where('id', $userid);
		$query = $CI->db->get('users', 1);
		if ( $query->num_rows() > 0 ):
			$row =  $query->row();
			return $row->first_name . ' ' . $row->last_name;
		else:
			return FALSE;
		endif;
		
		return FALSE;
	}
}

if ( ! function_exists('get_user_picture'))
{
	function get_user_picture( $userid = FALSE ) {
		$CI = get_instance();
		if ( ! $userid )$userid = $CI->session->userdata('id');
		
		$CI->db->where('id', $userid);
		$query = $CI->db->get('users', 1);
		if ( $query->num_rows() > 0 ):
			$row =  $query->row();
			if ( ! $row->picture ) 
				return base_url() . 'img/img100.png';
			return base_url() . str_replace('./', '', $row->picture );
		else:
			return FALSE;
		endif;
		
		return FALSE;
	}
}

if ( ! function_exists('get_user_picture_thumb'))
{
	function get_user_picture_thumb( $userid = FALSE, $width = 100, $height = 100 ) {
		$CI = get_instance();
		if ( ! $userid )$userid = $CI->session->userdata('id');
		
		$CI->db->where('id', $userid);
		$query = $CI->db->get('users', 1);
		if ( $query->num_rows() > 0 ):
			$row =  $query->row();
			if ( ! $row->picture ) 
				$row->picture = './img/img100.png';
			return base_url() . str_replace('./', '',  create_thumb($row->picture, $width, $height) );
		else:
			return FALSE;
		endif;
		
		return FALSE;
	}
}

if ( ! function_exists('isPhone'))
{
	function isPhone( $phone ) {
		//if(preg_match("/\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/", $phone))
		if(preg_match("/^(\d[\s-]?)?[\(\[\s-]{0,2}?\d{3}[\)\]\s-]{0,2}?\d{3}[\s-]?\d{4}$/i", $phone))
			return TRUE;
		elseif( ctype_digit($phone) )
			return TRUE;
		else
			return FALSE;
	}
}

if ( ! function_exists('IsZipCode'))
{
	function IsZipCode( $zipcode ) {
		if(preg_match("/^\d{5}(?:[\s-]\d{4})?$/", $zipcode))
			return TRUE;
		else
			return FALSE;
	}
}

if ( ! function_exists('get_themes'))
{
	function get_themes() {
		return  array('widget', 'siegfriedJensen', 'audi1', 'audi2', 'audi3');
	}
}

if ( ! function_exists('get_default_theme'))
{
	function get_default_theme() {
		$CI = get_instance();
		return $CI->config->item('default_theme');
	}
}

if ( ! function_exists('get_current_theme'))
{
	function get_current_theme() {
		$CI = get_instance();
		$theme = $CI->session->userdata('theme');
		$themes = get_themes();
		$default_theme = get_default_theme();
		if ( in_array($theme, $themes) ):
			return $theme;
		else:
			set_theme( $default_theme );
			return $default_theme;
		endif;
	}
}

if ( ! function_exists('set_theme'))
{
	function set_theme( $theme ) {
		$CI = get_instance();
		$themes = get_themes();
		$default_theme = get_default_theme();
		if ( in_array($theme, $themes) ):
			$result = TRUE;
		else:
			$theme = $default_theme;
			$result = FALSE;
		endif;
		
		//set theme
		$userid = $CI->session->userdata('id');
		$data = array('theme' => $theme );
		$CI->db->where('id', $userid);
		$CI->db->update('users', $data); 
		$CI->session->set_userdata($data);
		
		return $result;
	}
}

if ( ! function_exists('valid_widget_id')){
	function valid_widget_id( $widget_id ) {
		$widget_id = (int) $widget_id;
		$CI = get_instance();
		$CI->db->where('id', $widget_id);
		$query = $CI->db->get('widgets',1);
		if ( $query->num_rows() > 0 )
			return TRUE;
		else
			return FALSE;
	}
}

if ( ! function_exists('draw_widgets')){
	function draw_widgets( $template_id, $position, $preview = FALSE ){
		$CI = get_instance();
		$CI->db->where('id', $template_id);
		$query = $CI->db->get('templates',1);
		if ( $query->num_rows() > 0 ):
			$row = $query->row();
			$widgets =unserialize($row->widgets);
			
			if ( ! isset($widgets[$position]) )
				return FALSE;
			$widgets[$position] = explode(',', $widgets[$position]);
			foreach( $widgets[$position] as $widget ):
				draw_widget($widget, $preview);
			endforeach;
		else:
			return FALSE;
		endif;
	}
}

if ( ! function_exists('draw_widget')){
	function draw_widget( $widget_id, $preview = FALSE ){
		$CI = get_instance();
		$id = preg_replace("/[^0-9]/","",$widget_id);
		$CI->db->where('id', $id);
		$query = $CI->db->get('widgets',1);
		if ( $query->num_rows() > 0 ):
			$widget = $query->row();
			if ( $widget->widget_type == 'greeting' ):
				draw_widget_greeting($widget, $preview);
			elseif ( $widget->widget_type == 'testimonials' ):
				draw_widget_testimonials($widget, $preview);
			elseif ( $widget->widget_type == 'stories' ):
				draw_widget_stories($widget, $preview);
			elseif ( $widget->widget_type == 'contact' ):
				draw_widget_contact($widget, $preview);
			elseif ( $widget->widget_type == 'twitter' ):
				draw_widget_twitter($widget, $preview);
			elseif ( $widget->widget_type == 'faq' ):
				draw_widget_faq($widget, $preview);
			endif;
		else:
			return FALSE;
		endif;
	}
}

if ( ! function_exists('draw_widget_greeting')){
	function draw_widget_greeting($widget, $preview = FALSE ){
		if ( ! is_object($widget) ) return FALSE;
		
		if ( $preview ):
?>

<div id="widget-<?=$widget->widget_type . '-' . $widget->id;?>" class="widget" data-type="<?=$widget->widget_type;?>">
	<div class="widget-top">
		<div class="widget-title"><h4>Welcome<span class="in-widget-title"></span></h4></div>
	</div>

	<div class="widget-inside">
		<button type="button" class="btn btn-warning edit-widget">Edit</button>
<?php
		endif;
?>
		<div class="widget photo" id="upload_foto">
			<?php echo form_open(); ?>
			<div class="pull-left">
			     <div id="user_picture" style="max-width: 100px;"><img src="<?php echo get_user_picture_thumb(FALSE, 100, 100); ?>" alt="<?php if ( isset($user->first_name) ) echo $user->first_name; ?> Picture" class="img-rounded"></div>
			    <div class="clearfix"></div>
                        </div>
		        <div class="media-body">
				<h4 class="media-heading"><?php echo get_user_fullname(); ?></h4>
				<p>We've put together this page to provide customized information just for you.</p>
			</div>
			<div class="clearfix"></div>
			<?php echo form_close(); ?>
		</div>
<?php
		if ( $preview ):
?>
	</div>
	<div class="widget-description">User picture and welcome words.</div>
</div>
<?php
		endif;
	}
}

if ( ! function_exists('draw_widget_testimonials')){
	function draw_widget_testimonials($widget, $preview = FALSE ){
		if ( ! is_object($widget) ) return FALSE;
		
		if ( $preview ):
?>

<div id="widget-<?=$widget->widget_type . '-' . $widget->id;?>" class="widget" data-type="<?=$widget->widget_type;?>">
	<div class="widget-top">
		<div class="widget-title"><h4>Testimonials (Reviews)<span class="in-widget-title"></span></h4></div>
	</div>

	<div class="widget-inside">
		<button type="button" class="btn btn-warning edit-widget">Edit</button>
<?php
		endif;
?>
		<div class="widget reviews">
			<div class="panel panel-default widget-testimonials-full">
			        <div class="panel-heading">
			          <h3 class="panel-title">Testimonials</h3>
			        </div>
			        <div class="panel-body">
			          <p class="pull-left">Other people in your area who we've helped:</p>
			          <div class="clearfix"></div>
			           <div class="panel-review pull-left"> <img src="<?php echo base_url(); ?>img/barbara-canon.jpg" width="64" height="64" alt="" class="img64 pull-left"/>
			            <div class="media-body">
			              <h4 class="media-heading">Barbara Cannon</h4>
			              <a href="http://www.siegfriedandjensen.com/barbara-cannon">Barbara Cannon was enjoying ...</a> </div>
			          </div>
			           <div class="panel-review pull-left"> <img src="<?php echo base_url(); ?>img/ashley.jpg" width="64" height="64" alt="" class="img64 pull-left"/>
			            <div class="media-body">
			              <h4 class="media-heading">Ashley Merrill </h4>
			              <a href="http://www.siegfriedandjensen.com/our-clients/ashley-merrill">We need to make sure...</a> </div>
			          </div>
			           <div class="panel-review pull-left"> <img src="<?php echo base_url(); ?>img/bill.jpg" width="64" height="64" alt="" class="img64 pull-left"/>
			            <div class="media-body">
			              <h4 class="media-heading">Bill Thompson</h4>
			              <a href="http://www.siegfriedandjensen.com/our-clients/bill-thompson">A Bill learned firsthand...</a> </div>
			          </div>
			          <div class="panel-review pull-left"> <img src="<?php echo base_url(); ?>img/sorenson.jpg" width="64" height="64" alt="" class="img64 pull-left"/>
			            <div class="media-body">
			              <h4 class="media-heading">Lisa Holcombe</h4>
			              <a href="http://www.siegfriedandjensen.com/our-clients/lisa-holcombe">Lisa is a vivacious, energetic...</a> </div>
			          </div>
			        </div>
			</div>
		</div>
<?php
		if ( $preview ):
?>
	</div>

	<div class="widget-description">Add Testimonials</div>
</div>
<?php
		endif;
	}
}

if ( ! function_exists('draw_widget_stories')){
	function draw_widget_stories($widget, $preview = FALSE ){
		if ( ! is_object($widget) ) return FALSE;
		
		if ( $preview ):
?>

<div id="widget-<?=$widget->widget_type . '-' . $widget->id;?>" class="widget" data-type="<?=$widget->widget_type;?>">
	<div class="widget-top">
		<div class="widget-title"><h4>Stories<span class="in-widget-title"></span></h4></div>
	</div>

	<div class="widget-inside">
		<button type="button" class="btn btn-warning edit-widget">Edit</button>
<?php
		endif;
?>
		<div class="widget stories">
			<h3 class="title">Client Stories</h3>
		        <ul>
		          <li><a href="http://www.siegfriedandjensen.com/tuesday-sorenson">Tuesday Sorenson</a></li>
		          <li><a href="http://www.siegfriedandjensen.com/barbara-cannon">Barbara Cannon</a></li>
		          <li><a href="http://www.siegfriedandjensen.com/our-clients/ashley-merrill">Ashley Merrill - Auto accident, wrongful death</a></li>
		          <li><a href="http://www.siegfriedandjensen.com/our-clients/bill-thompson">Bill Thompson - Auto accident, catastrophic injury</a></li>
		          <li><a href="http://www.siegfriedandjensen.com/our-clients/carl-fisher">Carl Fisher - Auto accident</a></li>
		          <li><a href="http://www.siegfriedandjensen.com/our-clients/lisa-holcombe">Lisa Holcombe - Catastrophic injury, spinal cord injury, paralysis</a></li>
		        </ul>
		</div>
<?php
		if ( $preview ):
?>
	</div>

	<div class="widget-description">Client stories link</div>
</div>
<?php
		endif;
	}
}

if ( ! function_exists('draw_widget_contact')){
	function draw_widget_contact($widget, $preview = FALSE ){
		if ( ! is_object($widget) ) return FALSE;
		
		if ( $preview ):
?>

<div id="widget-<?=$widget->widget_type . '-' . $widget->id;?>" class="widget" data-type="<?=$widget->widget_type;?>">
	<div class="widget-top">
		<div class="widget-title"><h4>Contact<span class="in-widget-title"></span></h4></div>
	</div>

	<div class="widget-inside">
		<button type="button" class="btn btn-warning edit-widget">Edit</button>
<?php
		endif;
?>
		<div class="widget contact">
			<img src="<?php echo base_url(); ?>img/img-appointment.png" width="175" height="156" alt="" class="pull-left"/>
			<div class="media-body">
				<div class="widget-box-text">Get a free review of your potential case.</div>
				<a href="http://www.siegfriedandjensen.com/free-case-review" class="btn btn-warning widget-box-btn">Click Here</a>
			</div>
			<div class="clearfix"></div>
		</div>
<?php
		if ( $preview ):
?>
	</div>

	<div class="widget-description">Add contact widget</div>
</div>
<?php
		endif;
	}
}

if ( ! function_exists('draw_widget_twitter')){
	function draw_widget_twitter($widget, $preview = FALSE ){
		if ( ! is_object($widget) ) return FALSE;
		
		if ( $preview ):
?>

<div id="widget-<?=$widget->widget_type . '-' . $widget->id;?>" class="widget" data-type="<?=$widget->widget_type;?>">
	<div class="widget-top">
		<div class="widget-title"><h4>Twitter Feed<span class="in-widget-title"></span></h4></div>
	</div>

	<div class="widget-inside">
		<button type="button" class="btn btn-warning edit-widget">Edit</button>
<?php
		endif;
?>
		<div class="widget twitter">
			<h3 class="title">Twitter Feed</h3>
		<a class="twitter-timeline" href="https://twitter.com/hashtag/autoaccident" data-widget-id="509464469521461248" data-chrome="noheader  noborders transparent" >#autoaccident Tweets</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
		</div>
<?php
		if ( $preview ):
?>
	</div>

	<div class="widget-description">Add Twitter Feed</div>
</div>
<?php
		endif;
	}
}if ( ! function_exists('draw_widget_faq')){
	function draw_widget_faq($widget, $preview = FALSE ){
		if ( ! is_object($widget) ) return FALSE;
		
		if ( $preview ):
?>

<div id="widget-<?=$widget->widget_type . '-' . $widget->id;?>" class="widget" data-type="<?=$widget->widget_type;?>">
	<div class="widget-top">
		<div class="widget-title"><h4>FAQ<span class="in-widget-title"></span></h4></div>
	</div>

	<div class="widget-inside">
		<button type="button" class="btn btn-warning edit-widget">Edit</button>
<?php
		endif;
?>
		<div class="widget faq">
			<h3 class="title">FAQ</h3>
			<ul>
				<li><a href="http://www.siegfriedandjensen.com/faqs/what-to-do-after-an-accident">What to do After an Accident</a></li>
			        <li><a href="http://www.siegfriedandjensen.com/faqs/settlements">Settlements (They&apos;re A Good Thing)</a></li>
			        <li><a href="http://www.siegfriedandjensen.com/faqs/contingency-fees">Contingency Fees Demystified</a></li>
			        <li><a href="http://www.siegfriedandjensen.com/faqs/3-keys-infographic">Ever Wondered If You Have a Valid Injury Claim?</a></li>
			</ul>
		</div>
<?php
		if ( $preview ):
?>
	</div>

	<div class="widget-description">Add FAQ</div>
</div>
<?php
		endif;
	}
}