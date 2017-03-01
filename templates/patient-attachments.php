<?php include 'includes/header.php'; ?>
				
			<div class="content">
			
				<?php include 'includes/header-patient.php'; // common header for all patient info pages ?>
				
				<div class="row">
					
					<div class="col-sm-3">
						<?php include 'includes/patients-menu.php'; ?>
					</div>
					
					<div class="col-sm-9">
						
						<h2 class="subpage-title">Attachments</h2>
						
						<div class="subpage-actions">
							<a class="btn btn-labeled btn-default"><span class="btn-label"><i class="glyphicon glyphicon-plus"></i></span>Add attachment</a>
						</div>
						
						<form class="list-filter">
							<input type="text" class="form-control" id="" placeholder="Search attachment names...">
						</form>
						
						<table class="table">
							<thead>
								<tr>
									<th>File name</th>
									<th>Size (kb)</th>
									<th>Date</th>
									<th>Actions</th>
								<tr>
							</thead>
							
							<tr>
								<td><a href="#">file-name.doc</a></td>
								<td>45</td>
								<td>25 Feb 2017</td>
								<td><a href="#" data-toggle="modal" data-target=".modal-delete">Delete</a></td>
							<tr>
							
							<tr>
								<td><a href="#">file-name.doc</a></td>
								<td>45</td>
								<td>25 Feb 2017</td>
								<td><a href="#" data-toggle="modal" data-target=".modal-delete">Delete</a></td>
							<tr>
							
							<tr>
								<td><a href="#">file-name.doc</a></td>
								<td>45</td>
								<td>25 Feb 2017</td>
								<td><a href="#" data-toggle="modal" data-target=".modal-delete">Delete</a></td>
							<tr>
							
							<tr>
								<td><a href="#">file-name.doc</a></td>
								<td>45</td>
								<td>25 Feb 2017</td>
								<td><a href="#" data-toggle="modal" data-target=".modal-delete">Delete</a></td>
							<tr>
							
						</table>
						
						
					</div>
					
				</div>
				
			</div>
			
<?php include 'includes/footer.php'; ?>