<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title> Dashboard - <?php if ( isset($title) ) echo $title; ?></title>
	<meta name="" content="">
	<link rel="stylesheet" href="<?php echo base_url();?>css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url();?>css/bootstrap-theme.min.css">
	<?php if ( isset($jqueryui) ): ?>
	<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/themes/smoothness/jquery-ui.css" />
	<?php endif; ?>
	<?php if ( isset($login_page) ): ?>
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url();?>css/font-awesome.min.css" />
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url();?>css/bootstrap-social.css" />
	<?php endif; ?>
	<?php if ( isset($page_preview) ): ?>
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url();?>css/theme-<?php echo get_client();?>.css" />
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url();?>css/bootstrap-colorpicker.min.css" />
	<?php endif; ?>
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url();?>css/theme-dashboard.css" />
	<?php if( isset($sticky) ): ?>
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url();?>css/sticky.css" />
	<?php endif; ?>
	
	<!--[if gte IE 9]>
	<style type="text/css">
		.gradient {filter: none;}
	</style>
	<![endif]-->
	
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	<script type="text/javascript">
	//<![CDATA[
	var ajax_url = "<?php echo base_url('dashboard/ajax');?>";
	var base_url = "<?php echo base_url('dashboard');?>";
	var site_url = "<?php echo base_url();?>";
	var fb_app_id = "<?php echo$this->config->item('fb_app_id');?>";
	var google_client_id = "<?php echo $this->config->item('google_client_id');?>";
	var linkedin_api_key = "<?php echo $this->config->item('linkedin_api_key');?>";
	var gmap_key = "<?php echo $this->config->item('gmap_key');?>";
	// ]]>
	</script>
</head>
<body role="document" class="<?php if (isset($layout)) echo $layout;?> <?php if (isset($color_scheme)) echo 'scheme_'.$color_scheme;?>">
<?php if( isset($sticky) ): ?>

<div id="headerx">
	<span>
	<a href="<?php echo base_url('dashboard/');?>" class="btn btn-primary btn-lg" role="button">Dashboard</a>
	<a target="_blank" href="<?php echo base_url('dashboard/template_preview2/'.$template_id);?>" class="btn btn-primary btn-lg" role="button">Preview</a>
	<a href="#" class="btn btn-primary btn-lg" role="button" data-toggle="modal" data-target="#template_options" >Options</a>
	</span>
</div>
<?php endif;?>