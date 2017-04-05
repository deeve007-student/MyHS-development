<?php include 'includes/header.php'; ?>
				
			<div class="content">
			
				<?php include 'includes/header-patient.php'; // common header for all patient info pages ?>
				
				<div class="subpage-titlebar">
				
					<h2 class="subpage-title hidden-xs hidden-sm">Attachments</h2>
					
					<?php // start mobile sub-menu ?>
					<div class="btn-group visible-xs visible-sm">
						<h2 class="subpage-title" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Attachments <span class="caret"></span></h2>
						
						<?php include 'includes/patients-menu-mobile.php'; // mobile menu ?>
					</div>
					<?php // end mobile sub-menu ?>
					
							
					<div class="subpage-actions">
						<a href="#" class="btn btn-labeled btn-default"><span class="btn-label"><i class="glyphicon glyphicon-pencil"></i></span>Add attachment</a>
					</div>
				
				</div>
				
				<div class="row">
					
					<div class="col-md-8 col-lg-9">
						
						<section class="content-panel">
							
							<form class="list-filter gray-box">
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
						
						</section>
						
						
					</div>
					
					<?php include 'includes/patients-menu.php'; ?>
					
				</div><!-- .row -->
				
			</div>
			
<?php include 'includes/footer.php'; ?>