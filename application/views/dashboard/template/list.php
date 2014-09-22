<div class="container">
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Templates</h3>
			</div>
			<div class="panel-body">
				<p><a href="<?=base_url('dashboard/new_template');?>" class="btn btn-primary active pull-right" role="button">Create Template</a></p>
				<div class="table-responsive">
					<table class="table  table-striped">
						<thead>
							<tr>
								<th>Name</th>
								<th>Clicks</th>
								<th>Users</th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody
						<?php if ( isset($templates) && $templates->num_rows() > 0 ): ?>
							<?php foreach ($templates->result() as $template): ?>
							<tr>
								<td><?=$template->name;?></td>
								<td>n/a</td>
								<td>n/a</td>
								<td>
									<span><a href="dashboard/dev_template_preview/<?php echo $template->id; ?>" target="_blank" class="btn btn-primary btn-sm active" role="button">Edit</a></span>
									<span><a href="<?=base_url('dashboard/template_preview/'.$template->id);?>" class="btn btn-primary btn-sm active" role="button" target="_blank">Preview</a></span>
									<span><a href="dashboard/template_status/<?php echo $template->id; ?>/<?php echo $template->status; ?>" data-status="<?php echo $template->status; ?>" class="btn btn-primary btn-sm active btn-danger-template" role="button">Stats</a></span>
									<span><a href="dashboard/template_delete/<?php echo $template->id; ?>" class="btn btn-danger btn-sm active btn-danger-template" role="button">Delete</a></span>
								</td>
							</tr>
							<?php endforeach; ?>
						<?php else: ?>
							<tr><td colspan="3">Template not found.</td></tr>
						<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
  </div>
  </div>