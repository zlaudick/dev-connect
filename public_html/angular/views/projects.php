<!-- Page Title -->
<h2 class="text-center">Featured Projects</h2>

<!-- Begin Search Bar -->
<div class="row">
	<div class="form-group">
		<div class="col-xs-6">
			<input type="text" class="form-control" placeholder="What causes do you support?">
		</div>
		<button type="submit" class="btn btn-default">Submit</button>
	</div>
</div>

<!-- Begin Tags Listing -->
<div class="row">
	<div class="col-xs-12">
		<div class="well well-large">
			<div class="project-tags">
				<div class="row">
					<div class="col-md-4 col-xs-12">
						<div class="well">
							<ul>
								<li><a href="#">Economic</a></li>
								<li><a href="#">Social</a></li>
								<li><a href="#">Education</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<table class="table table-responsive table-striped table-hover">
	<tr>
		<th>Project Name</th>
		<th>Description</th>
		<th>Date Created</th>
	</tr>
	<tr ng-repeat="project in projects">
		<td>{{ project.projectName }}</td>
		<td>{{ project.content }}</td>
		<td>{{ project.date }}</td>
	</tr>
</table>
