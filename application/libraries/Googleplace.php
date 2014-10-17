<?php
/*
 * Asep Mulyawan (asep@wordpress-services.com)
 *
 */
 
defined('BASEPATH') OR exit('No direct script access allowed');

class GooglePlace {
	public $serverkey = 'AIzaSyAWfhrmr810574WVvYX8fNT6ov7H88LLl4';
	public $output = 'json'; //json or xml
	private $place_detail_url = 'https://maps.googleapis.com/maps/api/place/details/';
	
	
	function place_detail( $placeID = FALSE, $reference = FALSE ){
		if ( !$placeID && !$reference ) return FALSE;
		
		$url = $this->place_detail_url . $this->output . '?key=' . $this->serverkey;
		
		if ( $placeID ) $url .= '&placeid=' . $placeID;
		elseif ( $reference ) $url .= '&reference=' . $reference;
		
		$data = FALSE;
		$data = file_get_contents($url);
		
		return $data;
	}
	
	function picasa_user_data( $plusID ){
		$plusID = preg_replace("/[^0-9]/","", $plusID);
		
		if ( !$plusID ) return FALSE;
		
		$url = 'http://picasaweb.google.com/data/entry/api/user/' . $plusID . '?alt=json';
		
		$data = FALSE;
		$data = file_get_contents($url);
		
		return $data;
	}
	
	function user_image_url ( $plusID ){
		if ( !$plusID ) return FALSE;
		
		$picasa_data = json_decode($this->picasa_user_data($plusID));
		$picasa_data = (array) $picasa_data->entry;
		if ( isset($picasa_data['gphoto$thumbnail']) )
			$picasa_data = (array) $picasa_data['gphoto$thumbnail'];
		if ( isset($picasa_data['$t']) ) 
			return $picasa_data['$t'];
			
		return FALSE;
		
	}
}
?>