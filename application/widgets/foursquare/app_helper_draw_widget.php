<?php
if ( ! function_exists('draw_widget_foursquare')){
	function draw_widget_foursquare($widget, $position = FALSE, $preview = FALSE ){
		if ( ! is_object($widget) ) return FALSE;
		
		$CI = get_instance();
//		$CI->load->library('twitteroauth');
//		$CI->config->load('twitter');
		$foursquare_key = $CI->config->item('foursquare_key');
		$foursquare_secret = $CI->config->item('foursquare_secret');
		// check if user and pass are set
		if( !isset($foursquare_key) || !isset($foursquare_secret) || !$foursquare_key || !$foursquare_secret )
		{
			die('ERROR: Foursquare secret and key is not defined.'.PHP_EOL);
		}
		if($widget->widget_data=='') {
			$widget->widget_data = 'a:1:{i:1;a:2:{s:5:"title";s:15:"Foursquare Feed";s:7:"hashtag";s:24:"5321b871498e22c8856f46e8";}}';
		}
		require_once(APPPATH.'widgets/foursquare/FoursquareAPI.class.php');

		if ( $preview ):
?>
<div id="widget-<?=$widget->widget_type . '-' . $widget->id;?>" class="widget widget-wrapper" data-type="<?=$widget->widget_type;?>">
	<div class="widget-top">
		<div class="widget-title"><h4>Foursquare Feed<span class="in-widget-title"></span></h4></div>
		<div class="widget-action">
			<span class="glyphicon glyphicon-move move-widget" title="Move"></span>
			<span class="glyphicon glyphicon-edit edit-widget" data-toggle="modal" data-target="#widget-<?=$widget->widget_type . '-' . $widget->id;?>-modal" title="Edit"></span>
			<span class="glyphicon glyphicon-remove delete-widget" title="Delete"></span>
		</div>
	</div>

	<div class="widget-inside">
<?php
		endif;
		
		$foursquares = unserialize($widget->widget_data);
		//i don't what is the plan, do you plan to support multiple hashtag?
		//for now I assume it store in wrong way
		if ( is_array($foursquares) && count($foursquares) > 0 ):
		
			$foursquares = $foursquares[1]; //-> remove this after fix store data function
		
?>
		<div class="widget foursquare">
			<div class="panel panel-default widget-testimonials-full">
				<div class="panel-heading">
					<h3 class="panel-title">People in your neighborhood are checking in at <?=$foursquares['hashtag'];?></h3>
				</div>
				<div class="panel-body">
<?php
		
			//$foursquare_connection = $CI->twitteroauth->create($consumer_token, $consumer_secret);
			$foursquare_connection = new FoursquareAPI($foursquare_key,$foursquare_secret);
			

			if($foursquares['hashtag']=='') {
				$foursquares['hashtag'] = 'KFC';
			}
			
			$params = array(
			'near' => 'Salt Lake City, UT',
			'query'=> $foursquares['hashtag']
			);
			$response = $foursquare_connection->GetPublic("venues/search",$params);
			$venues = json_decode($response);
			if(!isset($venues->response->venues[0])) {
				echo 'Business name is not found';
				echo '</div>';echo '</div>';echo '</div>';echo '</div>';echo '</div>';
				return;
			}
			$venue_id = $venues->response->venues[0]->id;

			$params = array(
//			'near' => 'Salt Lake City, UT',
//			'query'=> $foursquares['hashtag']
				'VENUE_ID' => $venue_id
			);

			$response = $foursquare_connection->GetPublic("venues/".$venue_id,$params);

			$venues = json_decode($response);
//			print_r($venues);
			//$results = $foursquare_connection->get('search/tweets', $query);
			
			$i = 0;
			$venue = $venues->response->venue;
//			foreach ($venues->response->venue as $venue):
				$i++;
//				print_r($venue);
				if(isset($venue->categories['0']))
				{
					$img = $venue->categories['0']->icon->prefix.'64.png';
				}
				else
					$img = 'https://foursquare.com/img/categories/building/default_64.png';

				if($i<=1):
?>
					   <div class="panel-review pull-left"> 
<!--
					   <img src="<?=$img;?>" width="64" height="64" alt="" class="foursquareicon pull-left"/>
						<div class="media-body">
						  <h4 class="media-heading"><a target="_blank" href="<?=$venue->canonicalUrl;?>"><?=$venue->name;?></a></h4>
-->						  
<?php
/*
                    if(isset($venue->categories['0']))
                    {
						if(property_exists($venue->categories['0'],"name"))
						{
							echo ' <i> '.$venue->categories['0']->name.'</i><br/>';
						}
					}
					
					if(isset($venue->location->formattedAddress)) {
						echo $venue->location->formattedAddress[0].'<br />';
						if(isset($venue->location->formattedAddress[1]) && $venue->location->formattedAddress[1]!='United States') {
							echo $venue->location->formattedAddress[1].'<br />';
						}
						if(isset($venue->location->formattedAddress[2]) && $venue->location->formattedAddress[2]!='United States') {
							echo $venue->location->formattedAddress[2].'<br />';	
						}
					}
					if(isset($venue->contact->formattedPhone)) {
						echo 'Phone '.$venue->contact->formattedPhone.'<br />';	
					}

					if(property_exists($venue->hereNow,"count"))
					{
							echo $venue->hereNow->count ." people currently here <br/> ";
					}

                    echo '<b><i>Stats</i></b> :'.$venue->stats->usersCount." visitors, ".
					$venue->stats->checkinsCount." visits, ".$venue->stats->tipCount.' tips</div>';
*/					
//					echo '</div>';
					if($venue->stats->tipCount> 0) {
						$tip_params = array('VENUE_ID', $venue->id, 'sort' => 'recent');
						$r_tips = $foursquare_connection->GetPublic("venues/".$venue->id."/tips",$tip_params);
						$tips = json_decode($r_tips);
						//print_r($tips);
//						echo '<b>Tips</b> : <br />';
						foreach($tips->response->tips->items as $tip) {
							//echo '<image class="icon" src="'.$tip->user->photo->prefix.$tip->user->photo->suffix.'"/>';
							//echo '<a href="">';
							echo '<div class="panel-review pull-left">';
							echo '<img src="'.$tip->user->photo->prefix.'64x64'.$tip->user->photo->suffix.'" class="img64 pull-left"/>';
							echo '<div class="media-body">';
							echo '<h4 class="media-heading">';
							if(isset($tip->user->firstName)) echo $tip->user->firstName.' ';
							if(isset($tip->user->lastName)) echo $tip->user->lastName;
							echo ' : </h4>'.$tip->text.'</div></div>';
						}
						// if($tips->response->tips->count == '0') {
						//	 echo 'ZERO';
						// } echo {
							// echo 'HOUSTON, WE GOT TIPS';
						//}
						//print_r($tips->response->tips->count);die();
					}


?>						  

					  </div>
<?php	
				endif;
//			endforeach;
		endif; //if ( is_array($foursquares) && count($foursquares) > 0 ):
?>
				</div>
			</div>
		</div>
<?php 
		if ( $preview ): 
?>		
	</div>
	<div class="widget-description">Add Foursquare Feeds</div>
</div>
<?php 
		endif;
	}
}

if ( ! function_exists('draw_modal_foursquare')){
	function draw_modal_foursquare( $widget ){
		if ( ! is_object($widget) ) return FALSE;
		$foursquares = unserialize($widget->widget_data);
?>
<div class="modal fade foursquare_modal" id="widget-<?=$widget->widget_type . '-' . $widget->id;?>-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Foursquare</h4>
			</div>
			<div class="modal-body">
				<div id="widget-<?=$widget->id;?>-sort" class="foursquare_sort">
				<?php
		$i = 1;
		if ( is_array($foursquares) && count($foursquares) > 0 ):
			foreach ( $foursquares as $foursquare ):
				?>
					<div id="widget-<?=$widget->widget_type . '-' . $widget->id.'-modal-'.$i;?>" class="foursquare_form form-inline">
						<div class="form-group">
							<input type="text" name="foursquare-title[<?=$i;?>]" value="<?=$foursquare['title'];?>" class="form-control" placeholder="Title"/>
						</div>
						<div class="form-group">
							<div class="input-group">
								<div class="input-group-addon">Business Name</div>
								<input type="text" name="foursquare-hashtag[<?=$i;?>]" value="<?=$foursquare['hashtag'];?>" class="form-control" placeholder="Business name"/>
							</div>
						</div>
						<div class="form-group action-button">
							<span class="spinner"></span>
						</div>
					</div>
				<?php
			endforeach;
		else:
				?>
					<div id="widget-<?=$widget->widget_type . '-' . $widget->id.'-modal-'.$i;?>" class="foursquare_form form-inline">
						<div class="form-group">
							<input type="text" name="foursquare-title[<?=$i;?>]" value="Foursquare Feed" class="form-control" placeholder="Title"/>
						</div>
						<div class="form-group">
							<div class="input-group">
								<div class="input-group-addon">Business Name</div>
								<input type="text" name="foursquare-hashtag[<?=$i;?>]" value="Hero Partners" class="form-control" placeholder="Business name"/>
							</div>
						</div>
						<div class="form-group action-button">
							<span class="spinner"></span>
						</div>
					</div>
				<?php
		endif;
				?>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal">Save</button>
				<span class="spinner"></span>
			</div>
		</div>
	</div>
</div>
<?php
	}
}