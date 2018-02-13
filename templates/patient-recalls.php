<?php include 'includes/header.php'; ?>
				
			<div class="content">
			
				<?php include 'includes/header-patient.php'; // common header for all patient info pages ?>
				
				<div class="subpage-titlebar">
				
					<h2 class="subpage-title hidden-xs hidden-sm">Recalls</h2>
					
					<?php // start mobile sub-menu ?>
					<div class="btn-group visible-xs visible-sm">
						<h2 class="subpage-title" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Attachments <span class="caret"></span></h2>
						
						<?php include 'includes/patients-menu-mobile.php'; // mobile menu ?>
					</div>
					<?php // end mobile sub-menu ?>
					
							
					<div class="subpage-actions">
						<a href="#" class="btn btn-labeled btn-default" data-toggle="modal" data-target=".modal-recall"><span class="btn-label"><i class="glyphicon glyphicon-plus"></i></span>Create recall</a>
					</div>
				
				</div>
				
				<div class="row">
					
					<div class="col-md-8 col-lg-9">
						
						<section class="content-panel">
							
							<div class="table">
							
								<div class="table-row table-header">
									<div class="table-cell">Date</div>
									<div class="table-cell">Recall for</div>
									<div class="table-cell">Recall method</div>
									<div class="table-cell"><!-- Edit options --></div>
								</div>
								
								<div class="table-row">
									<div class="table-cell"><a href="#" data-toggle="modal" data-target=".modal-recall">25 Feb 2017</a></div>
									<div class="table-cell">Post surgery assessment</div>
									<div class="table-cell">
										<select>
											<option>- Choose -</option>
											<option>Phone</option>
											<option>SMS</option>
											<option>Email</option>
											<option>SMS & email</option>
										</select>
									</div>
									<div class="table-cell right-align"><a href="#"><span class="glyphicon glyphicon-remove"></span></div></a>
								</div>
								
								<div class="table-row">
									<div class="table-cell"><a href="#" data-toggle="modal" data-target=".modal-recall">25 Feb 2017</a></div>
									<div class="table-cell">Post surgery assessment</div>
									<div class="table-cell">
										<select>
											<option>- Choose -</option>
											<option>Phone</option>
											<option>SMS</option>
											<option>Email</option>
											<option>SMS & email</option>
										</select>
									</div>
									<div class="table-cell right-align"><a href="#"><span class="glyphicon glyphicon-remove"></span></div></a>
								</div>
							
							</div><!-- .table -->
						
						</section>
						
						
					</div>
					
					<?php include 'includes/patients-menu.php'; ?>
					
				</div><!-- .row -->
				
			</div>
			
<?php include 'includes/footer.php'; ?>