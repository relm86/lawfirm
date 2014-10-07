function getYoutubeID(url) {
	var p = /^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/;
	return (url.match(p)) ? RegExp.$1 : false;
}

function play(video){
	document.getElementById('youtube-main').innerHTML = '<iframe width="560" height="315" src="'+video.attr("data-youtubeurl")+'?autoplay=1" frameborder="0"></iframe>';
}

function isValidURL(url){
	url = url.toLowerCase();
	var urlregex = new RegExp("^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$");
  	return urlregex.test(url);
}
	
$(document).ready(function() {
				
	/* Color Scheme Selectot */
	$('#color_scheme_select input[type=radio], #layout_select input[type=radio]') .hide();
	$('#color_scheme_select input[type=radio], #layout_select input[type=radio]').click(function(){
		$('#color_scheme_select input[type=radio], #layout_select input[type=radio]').closest('.radio').removeClass('checked');
		$('#color_scheme_select input[type=radio]:checked, #layout_select input[type=radio]:checked').closest('.radio').addClass('checked');
	});
	/* Color Scheme Selectot */
    
  	/* preview page */
  	var 	$widgets = $('#widgets'),
  		$sidebar = $('#left-sidebar'),
  		$left = $('#main-content-left'),
  		$right = $('#main-content-right'),
  		$footer = $('#footer-widget'),
  		$drop_here = $('<p class="drop-here">Drop Widget Here</p>'),
  		widget_id = 1;
  	
  	$('.widget-container').not(":has(.widget)").addClass('placeholder').append($drop_here);
  	
	$( ".widget", $widgets ).draggable({
		revert: "valid", // when not dropped, the item will revert back to its initial position
		containment: "document",
		helper: "clone",
		cursor: "move",
		handle: ".move-widget",
		connectToSortable: '.widget-container'
	});
	
	$('.widget-container').sortable({
		connectWith: '.widget-container',
		placeholder: 'placeholder',
		handle: ".move-widget",
		beforeStop: function( event, ui ) {
			//save or update widget
			if ( ui.item[0].id == '' ){
				ui.item[0].id = 'widget-0';
			}
			widget_container = ui.item[0].parentElement.id;
			widget_id = ui.item[0].id;
			
			widget_type =  $('#'+ui.item[0].id).data("type");
			
			if ( widget_container == 'footer' ){
				$('#'+widget_id).addClass('col-md-2');
			} else {
				$('#'+widget_id).removeClass('col-md-2');
			}
						
			$.ajax({
				type: "POST",
				url: ajax_url+'/save_widget/',
				data: {
					user_id: $('#user_id').val(), template_id: $('#template_id').val(), widget_type: widget_type, widget_id: widget_id, csrf_b2b: $( "input[name='csrf_b2b']" ).val() },
					dataType : "json",
				})
			.done(function( msg ) {
				if ( msg.success == true && msg.widget_id ){
					ui.item[0].id = msg.widget_id
					$('#'+msg.widget_id+' .edit-widget').attr('data-target', '#'+msg.widget_id+'-modal');
				}
					
				if ( msg.success == true && msg.widget_modal ){
					//delete modal if exists
					$('#widget-'+widget_type+'-'+widget_id+'-modal').remove();
					$('body').prepend(msg.widget_modal);
				}
				
				//save layout
				save_layout();		
			});
			
		}
			
	});
	
	function getItems(element)
	{
		var columns = [];

		$(element).each(function(){
			columns.push($(this).sortable('toArray').join(','));				
		});

		return columns.join('|');
	}
	
	function save_layout(){
		$.ajax({
			type: "POST",
				url: ajax_url+'/save_layout/',
				data: {user_id: $('#user_id').val(), template_id: $('#template_id').val(), sidebar: getItems('#left-sidebar'), left: getItems('#main-content-left'), right: getItems('#main-content-right'), footer1: getItems('#footer1'), footer2: getItems('#footer2'), footer3: getItems('#footer3'), footer4: getItems('#footer4'), footer5: getItems('#footer5'), footer6: getItems('#footer6'), csrf_b2b: $( "input[name='csrf_b2b']" ).val() },
				dataType : "json",
			})
			.done(function( msg ) {
				if ( msg.success == true )
					console.log('Layout saved!');
		});
		
		$('.widget-container').removeClass('placeholder').find('.drop-here').remove();
		$('.widget-container').not(":has(.widget)").addClass('placeholder').append($drop_here);
	}
	
	/* main image upload */
	var uploader = new plupload.Uploader({
			runtimes : 'html5,flash,silverlight,html4',
			browse_button : 'upload_main_image', // you can pass in id...
			container: document.getElementById('main_images_upload'), // ... or DOM Element itself
			url : ajax_url + '/upload_main_image/',
			flash_swf_url : base_url +'/js/libs/plupload.swf',
			silverlight_xap_url : base_url + '/js/libs/plupload.xap',
			multipart_params : {
			"user_id" : $('#user_id').val(),
			"template_id" : $('#template_id').val(),
			csrf_b2b: $( "input[name='csrf_b2b']" ).val(),
			"ajax" : "1"
		},
		
		filters : {
			max_file_size : '10mb',
			mime_types: [
				{title : "Image files", extensions : "jpg,png"},
			]
		},

		init: {
			PostInit: function() {
				document.getElementById('upload_main_image').onclick = function() {
					uploader.start();
					return false;
				};
			},

			FilesAdded: function(up, files) {
				plupload.each(files, function(file) {
					document.getElementById('main_image_sort').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';
				});
				up.refresh(); // Reposition Flash/Silverlight
	            uploader.start();
			},

			UploadProgress: function(up, file) {
				document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
			},

			Error: function(up, err) {
				alert("\nError #" + err.code + ": " + err.message);
			},
		}
	});

	uploader.init();

	uploader.bind('FileUploaded', function (up, file, response) {
	    var result = $.parseJSON(response.response);
	    if (result.success) {
			$('#'+file.id).remove().delay(800);
			var new_image = $('<div id="slider-image-'+result.image_id+'" class="slider-image-form form-inline" data-img-url="'+result.img_url+'"><div class="image-wrapper form-group"><img src="'+result.img_thumb_url+'" width="100" height="100" alt=""/></div>&nbsp;<div class="image-title form-group"><input type="text" name="image-title['+result.image_id+']" value="" class="form-control" placeholder="Title"/><span class="image-control"><button type="button" class="btn btn-primary btn-sm save-image">Save</button>&nbsp;<button type="button" class="btn btn-danger btn-sm delete-image">Delete</button><span class="spinner"></span></span></div>&nbsp;<div class="image-desc form-group"><textarea name="image-desc['+result.image_id+']" class="form-control" placeholder="Short Description"></textarea></div></div>');
			new_image.appendTo('#main_image_sort');
	    } else {
			//error
			alert(result.error);
			$('#'+file.id).remove().delay(800);
		}
	});
	/* main image upload */
	
	/* main image sort*/
	$('#main_image_sort').sortable({
		connectWith: '#main_image_sort',
		placeholder: 'placeholder',
		stop: function( event, ui ) {
			//save order
			$('#main-image-slider .modal-footer .spinner').html('Save image order ...');
			$('#main-image-slider .modal-footer .spinner').show();
			$.ajax({
				type: "POST",
					url: ajax_url+'/save_image_order/',
					data: {user_id: $('#user_id').val(), template_id: $('#template_id').val(), image_order: getItems('#main_image_sort'),csrf_b2b: $( "input[name='csrf_b2b']" ).val() },
					dataType : "json",
				})
				.done(function( msg ) {
					if ( msg.success == true )
						console.log('Image order saved!');
			});
			$('#main-image-slider .modal-footer .spinner').delay(800).hide(0);
		}
	});
	/* main image sort*/
	
	/*update image title & description*/
	$('#main_image_sort').on('click', '.save-image', function(event){
		var image_id = $(this).closest('.slider-image-form').attr('id');
		var title = $('#' + image_id + ' input[type=text]').val();
		var desc = $('#' + image_id + ' textarea').val();
		$('#'+image_id+' .spinner').html('Saving ...');
		$('#'+image_id+' .spinner').show();
		$.ajax({
			type: "POST",
				url: ajax_url+'/save_image_title/',
				data: {user_id: $('#user_id').val(), template_id: $('#template_id').val(), id: image_id, title: $('#' + image_id + ' input[type=text]').val(), desc: $('#' + image_id + ' textarea').val(), csrf_b2b: $( "input[name='csrf_b2b']" ).val() },
				dataType : "json",
			})
			.done(function( msg ) {
				if ( msg.success == true ){
					console.log('Image title saved!');
					$('#' + image_id + ' input[type=text]').attr('value', title);
					$('#' + image_id + ' textarea').attr('value', desc);
				}
		});
		$('#'+image_id+' .spinner').delay(1000).hide(0);
	});
	/*update image title & description*/
	
	/*delete image*/
	$('#main_image_sort').on('click', '.delete-image', function(event){
		var image_id = $(this).closest('.slider-image-form').attr('id');
		$('#'+image_id+' .spinner').html('Deleting...');
		$('#'+image_id+' .spinner').show();
		$.ajax({
			type: "POST",
				url: ajax_url+'/delete_image/',
				data: {user_id: $('#user_id').val(), template_id: $('#template_id').val(), id: image_id, csrf_b2b: $( "input[name='csrf_b2b']" ).val() },
				dataType : "json",
			})
			.done(function( msg ) {
				if ( msg.success == true )
					console.log('Image deleted!');
		});
		jQuery('#'+image_id).fadeOut("slow").remove();
	});
	/*delete image*/
	
	/*update the slider*/
	$('#main-image-slider').on('hide.bs.modal', function (event) {
		var slider = $('<div class="carousel-inner">');
		var slider_nav = $('<a class="left carousel-control" href="#carousel-main-image" role="button" data-slide="prev"> <span class="glyphicon glyphicon-chevron-left"></span> </a> <a class="right carousel-control" href="#carousel-main-image" role="button" data-slide="next"> <span class="glyphicon glyphicon-chevron-right"></span></a>');
		var i = 0;
		var error = false;
		$('#main_image_sort .slider-image-form').each(function(){
			slideid = $(this).attr('id');
			img_url = $('#'+slideid).attr('data-img-url');
			title = $('#'+slideid+' input[type=text]').val();
			if ( '' == title ){
				$('#'+slideid+' input[type=text]').focus();
				error = true;
				alert("Please enter image title!");
				return false;
			}
			desc = $('#'+slideid+' textarea').val();
			active = '';
			if ( i == 0 ) active = ' active';
			i++;
			slider.append('<div class="item'+active+'"><img src="'+img_url+'" width="770" height="366" alt=""/><div class="carousel-caption"><h3>'+title+'</h3><p>'+desc+'</p></div></div>');
		});
		if ( true == error ) return false;
		if ( i > 0 ){
			$('#carousel-main-image').html('');
			slider.prependTo('#carousel-main-image');
			slider_nav.appendTo('#carousel-main-image');
		} else {
			$('#carousel-main-image').html('<div class="blank-image"><button type="button" class="btn btn-warning edit-widget center-block" data-toggle="modal" data-target="#main-image-slider">Add Image Slider</button></div>');
		}
	});
	/*update the slider*/
	
	/*video*/
	/*video thumb upload*/
	var video_thumb_upload = new plupload.Uploader({
			runtimes : 'html5,flash,silverlight,html4',
			browse_button : 'upload_video_thumb', // you can pass in id...
			container: document.getElementById('videos-sort-container'), // ... or DOM Element itself
			url : ajax_url + '/upload_video_thumb/',
			flash_swf_url : base_url +'/js/libs/plupload.swf',
			silverlight_xap_url : base_url + '/js/libs/plupload.xap',
			multipart_params : {
			"user_id" : $('#user_id').val(),
			"template_id" : $('#template_id').val(),
			csrf_b2b: $( "input[name='csrf_b2b']" ).val(),
			"ajax" : "1"
		},
		
		filters : {
			max_file_size : '10mb',
			mime_types: [
				{title : "Image files", extensions : "jpg,png"},
			]
		},

		init: {
			PostInit: function() {
				document.getElementById('upload_video_thumb').onclick = function() {
					video_thumb_upload.start();
					return false;
				};
			},

			FilesAdded: function(up, files) {
				plupload.each(files, function(file) {
					document.getElementById('videos-sort').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';
				});
				up.refresh(); // Reposition Flash/Silverlight
	            video_thumb_upload.start();
			},

			UploadProgress: function(up, file) {
				document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
			},

			Error: function(up, err) {
				alert("\nError #" + err.code + ": " + err.message);
			},
		}
	});

	video_thumb_upload.init();

	video_thumb_upload.bind('FileUploaded', function (up, file, response) {
	    var result = $.parseJSON(response.response);
	    if (result.success) {
			$('#'+file.id).remove().delay(800);
			var new_video = $('<div id="video-thumb-'+result.video_id+'" class="video-form form-inline" data-img-url="'+result.img_url+'"  role="form"><div class="image-wrapper form-group"><img src="'+result.img_thumb_url+'" width="211" height="126" alt="<?=$image->title;?>"/></div><div class="image-title form-group"><input type="text" name="video-url['+result.video_id+']" value="" class="form-control" placeholder="Youtube URL"/><span class="image-control"><button type="button" class="btn btn-primary btn-sm save-video">Save</button><button type="button" class="btn btn-danger btn-sm delete-video">Delete</button><span class="spinner"></span></span></div></div>');
			new_video.appendTo('#videos-sort');
	    } else {
			//error
			alert(result.error);
			$('#'+file.id).remove().delay(800);
		}
	});
	/*video thumb upload*/
	
	/* video sort*/
	$('#videos-sort').sortable({
		connectWith: '#videos-sort',
		placeholder: 'placeholder',
		stop: function( event, ui ) {
			//save order
			$('#videos-modal .modal-footer .spinner').html('Save video order ...');
			$('#videos-modal .modal-footer .spinner').show();
			$.ajax({
				type: "POST",
					url: ajax_url+'/save_video_order/',
					data: {user_id: $('#user_id').val(), template_id: $('#template_id').val(), video_order: getItems('#videos-sort'),csrf_b2b: $( "input[name='csrf_b2b']" ).val() },
					dataType : "json",
				})
				.done(function( msg ) {
					if ( msg.success == true )
						console.log('Video order saved!');
			});
			$('#videos-modal .modal-footer .spinner').delay(800).hide(0);
		}
	});
	/* video sort*/
	
	/*update video url*/
	$('#videos-sort').on('click', '.save-video', function(event){
		var video_id = $(this).closest('.video-form').attr('id');
		var video_url = $('#' + video_id + ' input[type=text]').val();
		var youtubeID = getYoutubeID(video_url);
		if ( youtubeID ){
			video_url = 'https://www.youtube.com/embed/'+youtubeID;
			$('#' + video_id + ' input[type=text]').val(video_url);
		} else {
			$('#' + video_id + ' input[type=text]').val('').focus();
			alert("Please enter valid video link (youtube vimeo only)!");
			return false;
		}
		$('#'+video_id+' .spinner').html('Saving ...');
		$('#'+video_id+' .spinner').show();
		$.ajax({
			type: "POST",
				url: ajax_url+'/save_video_url/',
				data: {user_id: $('#user_id').val(), template_id: $('#template_id').val(), id: video_id, url: video_url, csrf_b2b: $( "input[name='csrf_b2b']" ).val() },
				dataType : "json",
			})
			.done(function( msg ) {
				if ( msg.success == true ){
					console.log('Video URL saved!');
					$('#' + video_id + ' input[type=text]').attr('value', video_url);
				}
					
		});
		$('#'+video_id+' .spinner').delay(1000).hide(0);
	});
	/*update  video url*/
	
	/*delete video*/
	$('#videos-sort').on('click', '.delete-video', function(event){
		var video_id = $(this).closest('.video-form').attr('id');
		$('#'+video_id+' .spinner').html('Deleting...');
		$('#'+video_id+' .spinner').show();
		$.ajax({
			type: "POST",
				url: ajax_url+'/delete_video/',
				data: {user_id: $('#user_id').val(), template_id: $('#template_id').val(), id: video_id, csrf_b2b: $( "input[name='csrf_b2b']" ).val() },
				dataType : "json",
			})
			.done(function( msg ) {
				if ( msg.success == true )
					console.log('Video deleted!');
		});
		jQuery('#'+video_id).fadeOut("slow").remove();
	});
	/*delete video*/
	
	/*update the video*/
	$('#videos-modal').on('hide.bs.modal', function (event) {
		var video_nav = $('<div class="video-nav">');
		var carousel_video = $('<div id="carousel-video" class="carousel video" data-ride="carousel">');
		var carousel_inner_video = $('<div class="carousel-inner video">');
		var video_nav_item = $('<div class="item active">');
		var main_video = $('<div class="embed-responsive embed-responsive-16by9"></div>');
		var error = false;
		var i = 0;
		$('#videos-sort .video-form').each(function(){
			videoid = $(this).attr('id');
			img_url = $('#'+videoid).attr('data-img-url');
			thumb_url = $('#'+videoid+' img').attr('src');
			video_url = $('#'+videoid+' input[type=text]').val();
			
			if ( false == getYoutubeID(video_url) ){
				$('#' + videoid + ' input[type=text]').val('').focus();
				error = true;
				alert("Please enter valid video link (youtube only)!");
				return false;
			}
			
			if ( i == 0 ){
				main_video = $('<div class="embed-responsive embed-responsive-16by9"><div onclick="play($(this));" id="youtube-main" data-youtubeurl="'+video_url+'"><img src="'+img_url+'"/></div></div>');
			} 
			if ( i % 3 == 0 & i == 0 )  {
				video_nav_item.append('<div class="widget-video-thumb"><img src="'+thumb_url+'" alt=""  data-youtubeurl="'+video_url+'" onclick="play($(this));"/></div>');
			} else if (  i % 3 == 0 ){
				carousel_inner_video.append(video_nav_item);
				video_nav_item = $('<div class="item">');
				video_nav_item.append('<div class="widget-video-thumb"><img src="'+thumb_url+'" alt=""  data-youtubeurl="'+video_url+'" onclick="play($(this));"/></div>');
			} else if ( i % 3 > 0 ){
				video_nav_item.append('<div class="widget-video-thumb"><img src="'+thumb_url+'" alt=""  data-youtubeurl="'+video_url+'" onclick="play($(this));"/></div>');
			}
			i++;
		});
		if ( true == error ) return false;
		carousel_inner_video.append(video_nav_item);
		carousel_video.append(carousel_inner_video);
		carousel_video.append('<a class="left carousel-control" href="#carousel-video" role="button" data-slide="prev"> <span class="glyphicon glyphicon-chevron-left"></span> </a> <a class="right carousel-control" href="#carousel-video" role="button" data-slide="next"> <span class="glyphicon glyphicon-chevron-right"></span> </a>');
		video_nav.append(carousel_video);
		$('.widget.videos').html('');
		video_nav.prependTo('.widget.videos');
		main_video.prependTo('.widget.videos');
	});
	/*update the video*/
	/*video*/
	
	/* add faq*/
	$('body').on('click', '.add-faq', function(event){
		widget_id = $(this).closest('.faq_modal').attr('id'); 
		n = $('#'+widget_id+' .faq_form').length + 1;
		$('#'+widget_id+' .faq_sort').append('<div id="'+widget_id+'-'+n+'" class="faq_form form-inline"><div class="form-group"><input type="text" name="faq-title['+n+']" value="" class="form-control" placeholder="Title"/></div>&nbsp;<div class=" form-group"><input type="text" name="faq-url['+n+']" value="" class="form-control form_url" placeholder="External Link"/></div>&nbsp;<div class="form-group action-button"><button type="button" class="btn btn-danger btn-sm delete-faq">Delete</button><span class="ui-icon ui-icon-arrowthick-2-n-s sort-handle"></span><span class="spinner"></span></div></div>');
	});
	/* add faq*/
	
	/* sort faq */
	$('.faq_sort').sortable({
		placeholder: 'placeholder',
		handle: ".sort-handle",
	});
	/* sort faq */
	
	/* delete faq */
	$('body').on('click', '.delete-faq', function(event){
		$(this).closest('.faq_form').remove();
		//no further action required
	});
	/* delete faq */
	
	/* update faq */
	$('body').on('hide.bs.modal', '.faq_modal', function (event) {
		widget_id = $(this).attr('id');
		tinymce.triggerSave();
		//no validation
		//just save it
		$.ajax({
			type: "POST",
				url: ajax_url+'/save_widget/',
				data: $('#'+widget_id+' input, #'+widget_id+' textarea').serialize() + '&user_id='+$('#user_id').val()+'&template_id='+$('#template_id').val()+'&widget_type=faq&widget_id='+widget_id+'&csrf_b2b='+$( "input[name='csrf_b2b']" ).val(),
				dataType : "json",
			})
		.done(function( msg ) {
			if ( msg.success == true && msg.widget_html && msg.widget_id ){
				$('#'+msg.widget_id+' .widget-inside .widget.faq').remove();
				$('#'+msg.widget_id+' .widget-inside').append(msg.widget_html);
			}
		});
	});
	/* update faq */

	/* update twitter */
	$('body').on('hide.bs.modal', '.twitter_modal', function (event) {
		widget_id = $(this).attr('id');
		
		var error = false;
		$('#'+widget_id+' input').each(function(){
			if ( $(this).val() == ''){
				error = true;
				$(this).focus();
				alert("Please fill this field!");
				return false;
			}
		});
		
		if ( true == error ) return false;
		
		$('#'+widget_id+' .form_url').each(function(){
			if ( ! isValidURL($(this).val()) ){
				error = true;
				$(this).focus();
				alert("Please enter valid URL!");
				return false;
			}
		});
		
		if ( true == error ) return false;
		
		$.ajax({
			type: "POST",
				url: ajax_url+'/save_widget/',
				data: $('#'+widget_id+' input').serialize() + '&user_id='+$('#user_id').val()+'&template_id='+$('#template_id').val()+'&widget_type=twitter&widget_id='+widget_id+'&csrf_b2b='+$( "input[name='csrf_b2b']" ).val(),
				dataType : "json",
			})
		.done(function( msg ) {
			if ( msg.success == true && msg.widget_html && msg.widget_id ){
				$('#'+msg.widget_id+' .widget-inside .widget.twitter').remove();
				$('#'+msg.widget_id+' .widget-inside').append(msg.widget_html);
			}
		});
	});
	/* update twitter */	


	
	/* add link*/
	$('body').on('click', '.add-link', function(event){
		widget_id = $(this).closest('.links_modal').attr('id');
		n = $('#'+widget_id+' .link_form').length + 1;
		$('#'+widget_id+' .link_sort').append('<div id="'+widget_id+'-'+n+'" class="link_form form-inline"><div class="form-group"><input type="text" name="link-title['+n+']" value="" class="form-control" placeholder="Title"/></div>&nbsp;<div class=" form-group"><input type="text" name="link-url['+n+']" value="" class="form-control form_url" placeholder="External Link"/></div>&nbsp;<div class="form-group action-button"><button type="button" class="btn btn-danger btn-sm delete-link">Delete</button><span class="ui-icon ui-icon-arrowthick-2-n-s sort-handle"></span><span class="spinner"></span></div></div>');
	});
	/* add link*/
	
	/* sort links */
	$('.link_sort').sortable({
		placeholder: 'placeholder',
		handle: ".sort-handle",
	});
	/* sort links */
	
	/* delete link */
	$('body').on('click', '.delete-link', function(event){
		$(this).closest('.link_form').remove();
		//no further action required
	});
	/* delete link */
	
	/* update link */
	$('body').on('hide.bs.modal', '.links_modal', function (event) {
		widget_id = $(this).attr('id');
		
		var error = false;
		$('#'+widget_id+' input').each(function(){
			if ( $(this).val() == ''){
				error = true;
				$(this).focus();
				alert("Please fill this field!");
				return false;
			}
		});
		
		if ( true == error ) return false;
		
		$('#'+widget_id+' .form_url').each(function(){
			if ( ! isValidURL($(this).val()) ){
				error = true;
				$(this).focus();
				alert("Please enter valid URL!");
				return false;
			}
		});
		
		if ( true == error ) return false;
		
		$.ajax({
			type: "POST",
				url: ajax_url+'/save_widget/',
				data: $('#'+widget_id+' input').serialize() + '&user_id='+$('#user_id').val()+'&template_id='+$('#template_id').val()+'&widget_type=links&widget_id='+widget_id+'&csrf_b2b='+$( "input[name='csrf_b2b']" ).val(),
				dataType : "json",
			})
		.done(function( msg ) {
			if ( msg.success == true && msg.widget_html && msg.widget_id ){
				$('#'+msg.widget_id+' .widget-inside .widget.links').remove();
				$('#'+msg.widget_id+' .widget-inside').append(msg.widget_html);
			}
		});
	});
	/* update link */
	
	/* delete widget */
	$('.widget-container').on('click', '.delete-widget', function(event){
		widget_id = $(this).closest('.widget').attr('id');
		$.ajax({
			type: "POST",
				url: ajax_url+'/delete_widget/',
				data:'&user_id='+$('#user_id').val()+'&template_id='+$('#template_id').val()+'&widget_id='+widget_id+'&csrf_b2b='+$( "input[name='csrf_b2b']" ).val(),
				dataType : "json",
			})
		.done(function( msg ) {
			if ( msg.success == true && msg.widget_id ){
				$('#'+widget_id).remove();
				//update layout
				save_layout();
			} else {
				alert(msg.error);
			}
		});
	});
	/* delete widget */
		
	/* update text */
	$('body').on('hide.bs.modal', '.text_modal', function (event) {
		widget_id = $(this).attr('id');
		widget_type = $('#'+widget_id).attr('data-widget-type');
		tinymce.triggerSave();
		//no validation
		//just save it
		$.ajax({
			type: "POST",
				url: ajax_url+'/save_widget/',
				data: $('#'+widget_id+' input, #'+widget_id+' textarea').serialize() + '&user_id='+$('#user_id').val()+'&template_id='+$('#template_id').val()+'&widget_type='+widget_type+'&widget_id='+widget_id+'&csrf_b2b='+$( "input[name='csrf_b2b']" ).val(),
				dataType : "json",
			})
		.done(function( msg ) {
			if ( msg.success == true && msg.widget_html && msg.widget_id ){
				$('#'+msg.widget_id+' .widget-inside .widget.'+widget_type).remove();
				$('#'+msg.widget_id+' .widget-inside').append(msg.widget_html);
			}
		});
	});
	
	$('body').on('shown.bs.modal', '.text_modal', function (event) {
		tinyMCE.init({
			selector: ".tinymce",
			menubar:false,
			width: '99%',

			plugins: [
						"jbimages advlist autolink link image lists charmap hr anchor pagebreak",
						"searchreplace wordcount visualblocks visualchars code media nonbreaking",
						"save table contextmenu directionality template paste textcolor "
			],
			content_css: site_url+"css/editor.css",
			toolbar: "styleselect | bold italic | table | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | forecolor backcolor | link image media pagebreak | code | jbimages",
			relative_urls: false,
			remove_script_host : false,
			convert_urls : true,
		});
	});
	
	/* fix bootstrap for tinymce modal issue */
	$(document).on('focusin', function(e) {
		if ($(e.target).closest(".mce-window").length) {
			e.stopImmediatePropagation();
		}
	});
	/* fix bootstrap for tinymce modal issue */
	/* update text_modal */
	
	/* preview page */

    /* validate edit user form */
    $(document).on('submit', '#edituser_form', function(e) {
        console.log('here');
        var errors = new Array();
        if ($('#password').val() != $('#retype_password').val())
        {
            if ($('#edituser_error p').length > 0)
            {
                $('#edituser_error p').remove();
            }
            errors[errors.length] = 'Passwords does not match';
        }

        var required = $(':input[required]');
        $.each(required, function(key, input) {
            if ($(input).val() == '')
            {
                errors[errors.length] = 'Field ' + $(input).attr('placeholder') + ' is required';
            }
        });

        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if (!re.test($('#email_address').val()))
        {
            errors[errors.length] = 'Email address is not valid';
        }

        if (errors.length > 0)
        {
            for (var i = 0; i < errors.length; i++)
            {
                $('#edituser_error').append("<p>" + errors[i] + "</p>");
            }

            $('#edituser_error').css('display', 'block');
            e.preventDefault();
            $('html, body').animate({
                scrollTop: $("#edituser_error").offset().top
            }, 500);
        }
    });

    /* validate add user form */
    $(document).on('submit', '#adduser_form', function(e) {
        var errors = new Array();
        if ($('#password').val() != $('#retype_password').val())
        {
            if ($('#edituser_error p').length > 0)
            {
                $('#edituser_error p').remove();
            }
            errors[errors.length] = 'Passwords does not match';
        }

        var required = $(':input[required]');
        $.each(required, function(key, input) {
            if ($(input).val() == '')
            {
                errors[errors.length] = 'Field ' + $(input).attr('placeholder') + ' is required';
            }
        });

        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if (!re.test($('#email_address').val()))
        {
            errors[errors.length] = 'Email address is not valid';
        }

        if (errors.length > 0)
        {
            for (var i = 0; i < errors.length; i++)
            {
                $('#edituser_error').append("<p>" + errors[i] + "</p>");
            }

            $('#edituser_error').css('display', 'block');
            e.preventDefault();
            $('html, body').animate({
                scrollTop: $("#edituser_error").offset().top
            }, 500);
        }
    });

    $(document).on('click', '.btn-danger-user', function(e) {
        var msg = '';

        if ($(this).text() == 'Delete')
        {
            msg = 'Are you sure you want to delete this user?';
        }
        else if ($(this).text() == 'Suspend')
        {
            msg = 'Are you sure you want to suspend this user?';
        }
        else
        {
            msg = 'Are you sure you want to allow access to this user?';
        }

        return confirm(msg);
    });
    
	$(".color-picker").colorpicker();
	
	//widget action toolbar
	$(document).on('mouseenter', '#the-page .widget-wrapper', function(e) {
		$( this ).addClass( 'hover' ).find('.widget-action').show();
	});
	$(document).on('mouseleave', '#the-page .widget-wrapper', function(e) {
		$( this ).removeClass('hover').find('.widget-action').hide();
	});
  });