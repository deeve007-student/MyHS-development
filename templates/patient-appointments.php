<?php include 'includes/header.php'; ?>
				
			<div class="content">
			
				<?php include 'includes/header-patient.php'; // common header for all patient info pages ?>
				
				<div class="subpage-titlebar">
				
					<h2 class="subpage-title hidden-xs hidden-sm">Appointments</h2>
					
					<?php // start mobile sub-menu ?>
					<div class="btn-group visible-xs visible-sm">
						<h2 class="subpage-title" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Attachments <span class="caret"></span></h2>
						
						<?php include 'includes/patients-menu-mobile.php'; // mobile menu ?>
					</div>
					<?php // end mobile sub-menu ?>
					
							
					<div class="subpage-actions">
						<a href="#" class="btn btn-labeled btn-default"><span class="btn-label"><i class="glyphicon glyphicon-pencil"></i></span>Make appointment</a>
					</div>
				
				</div>
				
				<div class="row">
					
					<div class="col-md-8 col-lg-9">
						
						<section class="content-panel">
							
							<table class="table">
								<thead>
									<tr>
										<th>Date</th>
										<th>Treatment</th>
										<th>Duration</th>
									<tr>
								</thead>
								
								<tr>
									<td><a href="#">25 Feb 2017</a></td>
									<td>One hour sports massage</td>
									<td>60 min</td>
								<tr>
								
								<tr>
									<td><a href="#">25 Mar 2017</a></td>
									<td>Post surgery assessment</td>
									<td>30 min</td>
								<tr>
								
							</table>
						
						</section>
						
						
					</div>
					
					<?php include 'includes/patients-menu.php'; ?>
					
				</div><!-- .row -->
				
			</div>
			
<?php include 'includes/footer.php'; ?>