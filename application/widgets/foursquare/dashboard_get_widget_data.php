<?php

elseif ( 'foursquare' == $this->input->post('widget_type')):
	$foursquare_title = $this->input->post('foursquare-title');
	$foursquare_hashtag = $this->input->post('foursquare-hashtag');
	$i = 1;
	if(strlen($foursquare_title[1])==0) {
			$foursquare_title[1] = 'Foursquare Feed';
			$foursquare_title[1] = 'KFC';
	} else {
		foreach($foursquare_title as $title):
			$foursquare[$i]['title'] = $title;
			$foursquare[$i]['hashtag'] = $foursquare_hashtag[$i];
			$i++;
		endforeach;
	}
	print_r($foursquare);
	if ( isset($foursquare) && count($foursquare) > 0 ):
		return serialize($foursquare);
	endif;

