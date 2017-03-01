<?php include 'includes/header.php'; ?>

			<div class="content">
			
				<?php include 'includes/header-patient.php'; // common header for all patient info pages ?>
				
				<div class="row">
					
					<div class="col-sm-3">
						<?php include 'includes/patients-menu.php'; ?>
					</div>
					
					<div class="col-sm-9">
						
						<h2 class="subpage-title">Patient details</h2>
						
						<div class="subpage-actions">
							<a href="patient-details-edit.php" class="btn btn-labeled btn-default"><span class="btn-label"><i class="glyphicon glyphicon-pencil"></i></span>Edit details</a>
						</div>
						
						<section class="detail-section contact-information">
							<h3>Contact information</h3>
							
							<div class="detail-row">
								<div class="detail-label">Mobile</div>
								<div class="detail-content">
									0412 375809 (preferred)
								</div>
							</div>
							<div class="detail-row">
								<div class="detail-label">Home</div>
								<div class="detail-content">
									0412 375809
								</div>
							</div>
						</section>
						
						<section class="detail-section related-patients">
							<h3>Related patients</h3>
							
							<div class="detail-row">
								<div class="detail-label"><a href="#">Jill Smith</a></div>
								<div class="detail-content">
									Partner
								</div>
							</div>
						</section>
						
						<section class="detail-section general-information">
							<h3>General information</h3>
							
							<div class="detail-row">
								<div class="detail-label">Date of birth</div>
								<div class="detail-content">
									May 25, 1967
								</div>
							</div>
							<div class="detail-row">
								<div class="detail-label">Gender</div>
								<div class="detail-content">
									Male
								</div>
							</div>
							<div class="detail-row">
								<div class="detail-label">Emergency contact</div>
								<div class="detail-content">
									Jill Smith, 0345 678490
								</div>
							</div>
						</section>
						
						<section class="detail-section notification-settings">
							<h3>Notification settings</h3>
							
							<form class="notifications-patient">
								<div class="checkbox">
									<label>
										<input type="checkbox"> Enable SMS automated reminders
									</label>
								</div>
								<div class="checkbox">
									<label>
										<input type="checkbox"> Enable email automated reminders
									</label>
								</div>
								<div class="checkbox">
									<label>
										<input type="checkbox"> Enable booking confirmation emails
									</label>
								</div>
							</form>
						</section>
						
					</div>
					
				</div>
				
			</div>
			
<?php include 'includes/footer.php'; ?>