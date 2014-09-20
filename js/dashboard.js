function getYoutubeID(url) {
	var p = /^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/;
	return (url.match(p)) ? RegExp.$1 : false;
}

function play(video){
	document.getElementById('youtube-main').innerHTML = '<iframe width="560" height="315" src="'+video.attr("data-youtubeurl")+'?autoplay=1" frameborder="0"></iframe>';
}
	
$(document).ready(function() {
	
	/* Color Scheme Selectot */
	$('#color_scheme_select input[type="radio"], #layout_select input[type="radio"]') .hide();
	$('#color_scheme_select input[type="radio"], #layout_select input[type="radio"').click(function(){
		$('#color_scheme_select input[type="radio"], #layout_select input[type="radio"]').closest('.radio').removeClass('checked');
		$('#color_scheme_select input[type="radio"]:checked, #layout_select input[type="radio"]:checked').closest('.radio').addClass('checked');
	});
	/* Color Scheme Selectot */
    
  	/* preview page */
  	var 	$widgets = $('#widgets'),
  		$sidebar = $('#left-sidebar'),
  		$left = $('#main-content-left'),
  		$right = $('#main-content-right'),
  		$footer = $('#footer-widget'),
  		widget_id = 1;
  	
	$( ".widget", $widgets ).draggable({
		revert: "valid", // when not dropped, the item will revert back to its initial position
		containment: "document",
		helper: "clone",
		cursor: "crosshair",
		connectToSortable: '.widget-container'
	});
	
	$sidebar.droppable({
		accept: "#widgets .widget",
		activeClass: "ui-state-highlight",
		drop: function( event, ui ) {
			move_item( ui.draggable, $sidebar);
		}
	});
	
	$left.droppable({
		accept: "#widgets .widget",
		activeClass: "ui-state-highlight",
		drop: function( event, ui ) {
			move_item( ui.draggable, $left);
		}
	});
	
	$right.droppable({
		accept: "#widgets .widget",
		activeClass: "ui-state-highlight",
		drop: function( event, ui ) {
			move_item( ui.draggable, $right);
		}
	});
	
	$footer.droppable({
		accept: "#widgets .widget",
		activeClass: "ui-state-highlight",
		drop: function( event, ui ) {
			console.log(ui);
			console.log(ui.attr('id'));
			move_item( ui.draggable, $footer);
		}
	});
	
	$('.widget-container').sortable({
		connectWith: '.widget-container',
		placeholder: 'placeholder',
		beforeStop: function( event, ui ) {
			//save or update widget
			if ( ui.item[0].id == '' ){
				ui.item[0].id = 'widget-' + widget_id;
				widget_id++;
			}
			//console.log(ui);
			//console.log($('#'+ui.item[0].id).data("type"));
			//console.log($('#'+ui.item[0].id).id());
			
			$.ajax({
				type: "POST",
					url: ajax_url+'/save_widget/',
					data: {user_id: $('#user_id').val(), template_id: $('#template_id').val(), widget_type: $('#'+ui.item[0].id).data("type"), widget_id: ui.item[0].id, csrf_b2b: $( "input[name='csrf_b2b']" ).val() },
					dataType : "json",
				})
				.done(function( msg ) {
					if ( msg.success == true && msg.widget_id )
						ui.item[0].id = msg.widget_id
			});
		},
		stop: function( event, ui ) {
			//save layout
			save_layout();
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
				data: {user_id: $('#user_id').val(), template_id: $('#template_id').val(), sidebar: getItems('#left-sidebar'), left: getItems('#main-content-left'), right: getItems('#main-content-right'), footer: getItems('#footer') },
				dataType : "json",
			})
			.done(function( msg ) {
				if ( msg.success == true )
					console.log('Layout saved!');
		});
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
		var main_video = '';
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
				alert("Please enter valid video link (youtube vimeo only)!");
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
	/* preview page */
  });