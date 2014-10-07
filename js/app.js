function getYoutubeID(url) {
	var p = /^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/;
	return (url.match(p)) ? RegExp.$1 : false;
}

function getvimeoID(url) {
	vimeo_Reg = /https?:\/\/(?:www\.)?vimeo.com\/(?:channels\/|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|)(\d+)(?:$|\/|\?)/;
	var match = url.match(vimeo_Reg);

	if (match){
		return match[3];
	}else{
		return false;
	}
}

function play(video){
	if ( false != getYoutubeID(video.attr("data-youtubeurl")) ) {
		document.getElementById('youtube-main').innerHTML = '<iframe width="560" height="315" src="'+video.attr("data-youtubeurl")+'?autoplay=1" frameborder="0"></iframe>';
	} 
	
	vimeoID = getvimeoID(video.attr("data-youtubeurl"));
	if( false != vimeoID ) {
		embedSrc = 'https://player.vimeo.com/video/'+vimeoID
		document.getElementById('youtube-main').innerHTML = '<iframe src="'+embedSrc+'?autoplay=1" width="560" height="315" frameborder="0" webkitallowfullscreen="" mozallowfullscreen="" allowfullscreen=""></iframe>';
	}
	
}

function isValidURL(url){
	url = url.toLowerCase();
	var urlregex = new RegExp("^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$");
  	return urlregex.test(url);
}