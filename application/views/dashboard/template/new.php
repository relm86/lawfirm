<div class="container">
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Create Template</h3>
			</div>
			<div class="panel-body">
				<?php echo form_open('dashboard/new_template', array('class' => 'form-horizontal', 'id' => 'new_page') ); ?>
		  		<div class="form-group">
		  			<label class="col-lg-3 control-label"></label>
			  		<div class="col-lg-5">
			  			<?php
					  		if ( isset($success_msg) ) echo '<div class="alert alert-success" role="alert">' . $success_msg . '</div>';
					  		if ( isset($error_msg) ) echo '<div class="alert alert-danger" role="alert">' . $error_msg . '</div>';
					  	?>
			  		</div>
			  	</div>
		  		
				<div class="form-group">
		                        <label class="col-lg-3 control-label">Template Name<p>(Leave blank for default template)</p></label>
		                        <div class="col-lg-5">
		                            	<?php echo form_error('name'); ?>
						<div class="input-group">
							<div class="input-group-addon"><?=base_url();?></div>
							<input type="text" class="form-control display-inline" name="name"/>
						</div>
		                        </div>
		                    </div>
		                    

		                    <div class="form-group">
		                    	<label class="col-lg-3 control-label">Color Scheme</label>
		                    	<?php echo form_error('color_scheme'); ?>
		                    	<div class="col-lg-9" id="color_scheme_select">
	                    			<div class="radio pull-left color-scheme checked"><label>White<input type="radio" name="color_scheme" value="white" checked="checked"/><img src="<?=base_url('img/color-scheme-white.png');?>" width="66" height="34"/></label></div>
	                    			<div class="radio pull-left color-scheme"><label>Black<input type="radio" name="color_scheme" value="black"/><img src="<?=base_url('img/color-scheme-black.png');?>" width="66" height="34"/></label></div>
	                    			<div class="radio pull-left color-scheme"><label>Gray, dark<input type="radio" name="color_scheme" value="gray-dark"/><img src="<?=base_url('img/color-scheme-gray-dark.png');?>" width="66" height="34"/></label></div>
	                    			<div class="radio pull-left color-scheme"><label>Gray, lght<input type="radio" name="color_scheme" value="gray-light"/><img src="<?=base_url('img/color-scheme-gray-light.png');?>" width="66" height="34"/></label></div>
	                    			<div class="radio pull-left color-scheme"><label>Tan<input type="radio" name="color_scheme" value="tan"/><img src="<?=base_url('img/color-scheme-tan.png');?>" width="66" height="34"/></label></div>
	                    			<div class="radio pull-left color-scheme"><label>Brown<input type="radio" name="color_scheme" value="brown"/><img src="<?=base_url('img/color-scheme-brown.png');?>" width="66" height="34"/></label></div>
	                    			<div class="clearfix"></div>
		                    	</div>
		                    </div>
		                    
		                     <div class="form-group">
		                    	<label class="col-lg-3 control-label">Layout</label>
		                    	<?php echo form_error('layout'); ?>
		                    	<div class="col-lg-9" id="layout_select">
	                    			<div class="radio pull-left layout checked"><label><input type="radio" name="layout" value="layout_a" checked="checked"/><img src="<?=base_url('img/theme-layout-a.png');?>" width="200" height="300"/></label></div>
	                    			<div class="radio pull-left layout"><label><input type="radio" name="layout" value="layout_b"/><img src="<?=base_url('img/theme-layout-b.png');?>" width="200" height="300"/></label></div>
	                    			<div class="radio pull-left layout"><label><input type="radio" name="layout" value="layout_c"/><img src="<?=base_url('img/theme-layout-c.png');?>" width="200" height="300"/></label></div>
	                    			<div class="clearfix"></div>
		                    	</div>
		                    </div>
		                    
		                    <div class="form-group">
		                        <div class="col-lg-9 col-lg-offset-3">
		                            <button type="submit" class="btn btn-default">Generate</button>
		                        </div>
		                    </div>
				
			<?php echo form_close(); ?>
			</div>
		</div>
	</div>
  </div>
  </div>