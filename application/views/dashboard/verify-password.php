 <div class="container">
 	<div class="row">
		<div class="col-md-12">
	  		<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Verify Password</h3>
				</div>
				<div class="panel-body">
					<?php echo form_open('dashboard', array('class' => 'form-horizontal', 'id' => 'verify_password') ); ?>
			  		<div class="form-group">
			  			<label class="col-lg-3 control-label"></label>
				  		<div class="col-lg-5">
				  			<div class="alert alert-success" role="alert">Please enter your password to continue.</div>
				  			<?php
						  		if ( isset($success_msg) ) echo '<div class="alert alert-success" role="alert">' . $success_msg . '</div>';
						  		if ( isset($error_msg) ) echo '<div class="alert alert-danger" role="alert">' . $error_msg . '</div>';
						  	?>
				  		</div>
				  	</div>
			  		
					<div class="form-group">
			                        <label class="col-lg-3 control-label">Password</label>
			                        <div class="col-lg-5">
			                            <?php echo form_error('verify_password'); ?>
			                            <input type="password" class="form-control" name="verify_password" />
			                        </div>
			                    </div>
			                    
			                    <div class="form-group">
			                        <div class="col-lg-9 col-lg-offset-3">
			                            <button type="submit" class="btn btn-default">Continue</button>
			                        </div>
			                    </div>
					
				<?php echo form_close(); ?>
				</div>
			</div>
	  	</div>
	  </div>
</div>