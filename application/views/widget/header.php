<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php if ( isset($title) ) echo $title; ?></title>
	<meta name="" content="">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url();?>css/theme-blank.css" />
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	<script type="text/javascript">
	//<![CDATA[
	var ajax_url = "<?php echo base_url();?>ajax/";
	var base_url = "<?php echo base_url();?>";
	var fb_app_id = "<?php echo$this->config->item('fb_app_id');?>";
	var google_client_id = "<?php echo $this->config->item('google_client_id');?>";
	var linkedin_api_key = "<?php echo $this->config->item('linkedin_api_key');?>";
	// ]]>
	</script>
</head>
<body role="document">
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="widget-header">
        <div class="widget-header-logo"><img src="<?php echo base_url(); ?>img/sample-logo.png" width="94" height="94" alt=""/></div>
        <div class="pull-right">
          <div class="header-nav"><a href="javascript:void()">Contact an Associate</a> | <a href="<?php echo base_url('logout'); ?>">Logout</a></div>
          <div class="header-chat"><a href="javascript:void()">Chat Widget</a></div>
        </div>
      </div>
    </div>
  </div>