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
								<span><a href="<?=base_url('dashboard/template_preview/'.$template->id);?>" class="btn btn-primary btn-sm active" role="button" target="_blank">Edit</a></span>
								<span><a href="<?=base_url('dashboard/template_preview2/'.$template->id);?>" class="btn btn-primary btn-sm active" role="button" target="_blank">Preview</a></span>
								<span><a href="<?=base_url('dashboard/template_delete/'.$template->id);?>" class="btn btn-danger btn-sm active btn-danger-template" role="button">Delete</a></span>
<!--								
									<span><a href="#" class="btn btn-primary btn-sm active" role="button">Edit</a></span>
									<span><a href="<?=base_url('dashboard/template_preview/'.$template->id);?>" class="btn btn-primary btn-sm active" role="button" target="_blank">Preview</a></span>
									<span><a href="#" class="btn btn-primary btn-sm active" role="button">Stats</a></span>
									<span><a href="#" class="btn btn-danger btn-sm active" role="button">Delete</a></span>
-->									
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