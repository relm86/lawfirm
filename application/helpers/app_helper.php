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
		$widget_id = preg_replace("/[^0-9]/","",$widget_id);
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

if ( ! function_exists('draw_modal')){
	function draw_modal( $template_id ){
		$CI = get_instance();
		$id = preg_replace("/[^0-9]/","",$template_id);
		
		//image modal
		$CI->db->where('template_id', $id);
		$CI->db->order_by('order', 'ASC');
		$query = $CI->db->get('template_images');
		if ( $query->num_rows() > 0 ):
			$main_images = $query;
		else:
			$main_images = FALSE;
		endif;
		draw_main_image_modal($main_images);
			
		//video modal
		$CI->db->where('template_id', $id);
		$CI->db->order_by('order', 'ASC');
		$query = $CI->db->get('template_videos');
		if ( $query->num_rows() > 0 ):
			$videos = $query;
		else:
			$videos = FALSE;
		endif;
		draw_video_modal($videos);
		
		//widget modal
		$CI->db->where('template_id', $id);
		$query = $CI->db->get('widgets');
		if ( $query->num_rows() > 0 ):
			foreach($query->result() as $widget )
			if ( $widget->widget_type == 'greeting' ):
				//draw_modal_greeting($widget);
			elseif ( $widget->widget_type == 'testimonials' ):
				//draw_modal_testimonials($widget);
			elseif ( $widget->widget_type == 'stories' ):
				//draw_modal_stories($widget);
			elseif ( $widget->widget_type == 'contact' ):
				//draw_modal_contact($widget);
			elseif ( $widget->widget_type == 'twitter' ):
				//draw_modal_twitter($widget);
			elseif ( $widget->widget_type == 'faq' ):
				draw_modal_faq($widget);
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
}

if ( ! function_exists('draw_widget_faq')){
	function draw_widget_faq($widget, $preview = FALSE ){
		if ( ! is_object($widget) ) return FALSE;
		
		if ( $preview ):
?>

<div id="widget-<?=$widget->widget_type . '-' . $widget->id;?>" class="widget" data-type="<?=$widget->widget_type;?>">
	<div class="widget-top">
		<div class="widget-title"><h4>FAQ<span class="in-widget-title"></span></h4></div>
	</div>

	<div class="widget-inside">
		<button type="button" class="btn btn-warning edit-widget" data-toggle="modal" data-target="#widget-<?=$widget->widget_type . '-' . $widget->id;?>-modal">Edit</button><button type="button" class="btn btn-danger btn-sm delete-widget">Delete</button>
<?php
		endif;
?>
		<div class="widget faq">
			<h3 class="title">FAQ</h3>
			<?php
				$faqs = unserialize($widget->widget_data);
				if ( is_array($faqs) && count($faqs) > 0 ):
					echo '<ul>';
					foreach ( $faqs as $faq ):
						echo '<li><a href="'.$faq['url'].'">'.$faq['title'].'</a></li>';
					endforeach;
					echo '</ul>';
			?>
			<?php
				else:
					echo '<div class="blank-widget faqs"><button type="button" class="btn btn-warning edit-widget" data-toggle="modal" data-target="#widget-'.$widget->widget_type . '-' . $widget->id.'-modal">Add FAQ</button></div>';
				endif;
			?>
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
	
if ( ! function_exists('draw_main_image_modal')){
	function draw_main_image_modal( $main_images ){
		if ( ! $main_images ) return FALSE;
		
?>
<div class="modal fade" id="main-image-slider" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Featured Image</h4>
      </div>
      <div class="modal-body" id="main_images_upload">
		<a id="upload_main_image" href="#" class="btn btn-primary btn-sm active" role="button">Upload Image</a>
		<p>Note: Max image file size 10MB, width 1024px and height 768px. Only jpg/jpeg/png allowed. Best Dimension 770x366px.</p>
		<div id="main_image_sort">
			<?php
			if ( isset($main_images) && $main_images): 
				foreach($main_images->result() as $image):
			?>
			<div id="slider-image-<?=$image->id;?>" class="slider-image-form form-inline" data-img-url="<?=base_url() . str_replace('./', '',  create_thumb($image->path, 770, 366) );?>"  role="form">
				<div class="image-wrapper form-group"><img src="<?=base_url() . str_replace('./', '',  create_thumb($image->path, 100, 100) );?>" width="100" height="100" alt="<?=$image->title;?>"/></div>
				<div class="image-title form-group">
					<input type="text" name="image-title[<?=$image->id;?>]" value="<?=$image->title;?>" class="form-control" placeholder="Title"/>
					<span class="image-control">
						<button type="button" class="btn btn-primary btn-sm save-image">Save</button>
						<button type="button" class="btn btn-danger btn-sm delete-image">Delete</button>
						<span class="spinner"></span>
					</span>
				</div>
				<div class="image-desc form-group"><textarea name="image-desc[<?=$image->id;?>]" class="form-control" placeholder="Short Description"><?=$image->description;?></textarea></div>
				
			</div>
			<?php
				endforeach;
			endif;	
			?>
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <span class="spinner"></span>
      </div>
    </div>
  </div>
</div>
<?php
		
	}
}

if ( ! function_exists('draw_video_modal')){
	function draw_video_modal( $videos ){
		if ( ! $videos ) return FALSE;
?>
<div class="modal fade" id="videos-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Video</h4>
      </div>
      <div class="modal-body" id="videos-sort-container">
		<a id="upload_video_thumb" href="#" class="btn btn-primary btn-sm active" role="button">Add Video</a>
		<p>Press Add Video button to select your video thumbnail and then place Youtube URL.</p>
		<p>Note: Maximum video thumbnail size is 10MB, width 1024px and height 768px. Only jpg/jpeg/png allowed. Best Dimension 746x439px.</p>
		<div id="videos-sort">
			<?php
			if ( isset($videos) && $videos ):
				foreach($videos->result() as $video):
			?>
			<div id="video-thumb-<?=$video->id;?>" class="video-form form-inline" data-img-url="<?=base_url() . str_replace('./', '',  create_thumb($video->thumb, 746, 439) );?>"  role="form">
				<div class="image-wrapper form-group"><img src="<?=base_url() . str_replace('./', '',  create_thumb($video->thumb, 211, 126) );?>" width="211" height="126" alt=""/></div>
				<div class="image-title form-group">
					<input type="text" name="video-url[<?=$video->id;?>]" value="<?=$video->url;?>" class="form-control" placeholder="Youtube URL"/>
					<span class="image-control">
						<button type="button" class="btn btn-primary btn-sm save-video">Save</button>
						<button type="button" class="btn btn-danger btn-sm delete-video">Delete</button>
						<span class="spinner"></span>
					</span>
				</div>
			</div>
			<?php
				endforeach;
			endif;	
			?>
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <span class="spinner"></span>
      </div>
    </div>
  </div>
</div>
<?php
	}
}

if ( ! function_exists('draw_modal_faq')){
	function draw_modal_faq( $widget ){
		if ( ! is_object($widget) ) return FALSE;
		$faqs = unserialize($widget->widget_data);
?>
<div class="modal fade faq_modal" id="widget-<?=$widget->widget_type . '-' . $widget->id;?>-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">FAQ</h4>
			</div>
			<div class="modal-body">
				<a href="#" class="btn btn-primary btn-sm active add-faq" role="button">Add FAQ</a>
				<div id="widget-<?=$widget->id;?>-sort" class="faq_sort">
				<?php
				if ( is_array($faqs) && count($faqs) > 0 ):
				$i = 1;
				foreach ( $faqs as $faq ):
				?>
					<div id="widget-<?=$widget->widget_type . '-' . $widget->id.'-modal-'.$i;?>" class="faq_form form-inline">
						<div class="form-group">
							<input type="text" name="faq-title[<?=$i;?>]" value="<?=$faq['title'];?>" class="form-control" placeholder="Title"/>
						</div>
						<div class="form-group">
							<input type="text" name="faq-url[<?=$i;?>]" value="<?=$faq['url'];?>" class="form-control" placeholder="External Link"/>
						</div>
						<div class="form-group action-button">
							<button type="button" class="btn btn-danger btn-sm delete-faq">Delete</button>
							<span class="ui-icon ui-icon-arrowthick-2-n-s sort-handle"></span>
							<span class="spinner"></span>
						</div>
					</div>
				<?php
				$i++;
				endforeach;
				endif;
				?>
				</div>
			</div>
			<div class="modal-footer">
				<p>Close this to save your data!</p>
				<span class="spinner"></span>
			</div>
		</div>
	</div>
</div>
<?php
	}
}