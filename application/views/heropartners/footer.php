<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="<?php echo base_url();?>js/libs/bootstrap.min.js"></script>
<?php if ( isset($picture_upload) ): ?>
<script type="text/javascript" src="<?php echo base_url(); ?>js/libs/plupload.full.min.js"></script>
<script type="text/javascript">
// Custom example logic

var uploader = new plupload.Uploader({
	runtimes : 'html5,flash,silverlight,html4',
	browse_button : 'upload_foto_button', // you can pass in id...
	container: document.getElementById('upload_foto'), // ... or DOM Element itself
	url : '<?php echo base_url();?>ajax/upload_foto/',
	flash_swf_url : '<?php echo base_url();?>js/libs/plupload.swf',
	silverlight_xap_url : '<?php echo base_url();?>js/libs/plupload.xap',
	multipart_params : {
        "id" : "<?php if ( isset($user->id) ) echo $user->id;?>",
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
			document.getElementById('upload_foto_button').onclick = function() {
				uploader.start();
				return false;
			};
		},

		FilesAdded: function(up, files) {
			plupload.each(files, function(file) {
				document.getElementById('user_picture').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';
			});
			up.refresh(); // Reposition Flash/Silverlight
            uploader.start();
		},

		UploadProgress: function(up, file) {
			document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
		},

		Error: function(up, err) {
			document.getElementById('console').innerHTML += "\nError #" + err.code + ": " + err.message;
		},
	}
});

uploader.init();

uploader.bind('FileUploaded', function (up, file, response) {
    var result = $.parseJSON(response.response);
    if (result.success) {
		$('#user_picture').html('<img src="'+result.img_url + "?timestamp="  + new Date().getTime() +'" class="img-rounded" />');
    } else {
		//error
		alert(result.error);
		//$('#user_picture').html('');
	}
});

</script>
<?php endif; ?>
<?php if ( isset($login_page) ): ?>
<script type='text/javascript' src='<?php echo base_url();?>js/login.js'></script>
<?php endif; ?>
</body>
</html>