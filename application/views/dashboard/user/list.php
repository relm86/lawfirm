<div class="container">
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Users</h3>
			</div>
			<div class="panel-body">
				<p><a href="#" class="btn btn-primary active pull-right" role="button">Add User</a></p>
				<div class="table-responsive">
					<table class="table  table-striped">
						<thead>
							<tr>
								<th>First Name</th>
								<th>Last Name</th>
								<th>Email</th>
								<th>Phone Number</th>
								<th>Zip Code</th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody
						<?php if ( isset($users) && $users->num_rows() > 0 ): ?>
							<?php foreach ($users->result() as $user): ?>
							<tr>
								<td><?=$user->first_name;?></td>
								<td><?=$user->last_name;?></td>
								<td><?=$user->email_address;?></td>
								<td><?=$user->phone_number;?></td>
								<td><?=$user->zip_code;?></td>
								<td>
									<span><a href="#" class="btn btn-primary btn-sm active" role="button">Edit</a></span>
									<span><a href="#" class="btn btn-primary btn-sm active" role="button">Login as</a></span>
									<span><a href="#" class="btn btn-danger btn-sm active" role="button">Suspend</a></span>
									<span><a href="#" class="btn btn-danger btn-sm active" role="button">Delete</a></span>
								</td>
							</tr>
							<?php endforeach; ?>
						<?php else: ?>
							<tr><td colspan="6">User not found.</td></tr>
						<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
  </div>
  </div>