<?php include 'includes/header.php'; ?>
			
				<?php include 'includes/header-patient.php'; // common header for all patient info pages ?>
				
				
				<div class="subpage-titlebar">
				
					<h2 class="subpage-title">Editing patient</h2>
							
					<div class="subpage-actions">
						<button type="button" class="btn btn-labeled btn-success"><span class="btn-label"><i class="glyphicon glyphicon-ok"></i></span>Save</button>
						<a class="btn btn-labeled btn-danger" href="patient-details.php"><span class="btn-label"><i class="glyphicon glyphicon-remove"></i></span>Cancel</a>
					</div>
				
				</div>
				
				
				<div class="row">	
					
					<div class="col-md-8 col-lg-9">
						
						<section class="content-panel">
							
							<form class="edit-info">
								
								<section class="detail-section patient-mandatory">
									<h3>Mandatory information</h3>
									
									<div class="row">
										<div class="form-group col-sm-2">
											<label>Title</label>
											<select class="form-control">
											  <option></option>
											  <option>Dr</option>
											  <option>Mr</option>
											  <option>Ms</option>
											  <option>Mrs</option>
											  <option>Miss</option>
											  <option>Professor</option>
											  <option>Master</option>
											  <option>Sir</option>
											  <option>Madam</option>
											</select>
										</div>
										
										<div class="form-group col-sm-5">
											<label>First name</label>
											<input type="text" class="form-control">
										</div>
										
										<div class="form-group col-sm-5">
											<label>Last name</label>
											<input type="text" class="form-control">
										</div>
									</div>
									
									<div class="row">
										<div class="form-group col-sm-6">
											<label>Date of birth</label>
											<div class='input-group date' id='datepicker1'>
												<input type='text' class="form-control" />
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
										</div>
										
										<div class="form-group col-sm-6">
											<label>Gender</label>
											<select class="form-control">
											  <option></option>
											  <option>Male</option>
											  <option>Female</option>
											  <option>Not applicable</option>
											</select>
										</div>
									</div>
									
									<div class="form-group">
										<label>Email</label>
										<input type="email" class="form-control">
									</div>
									
									<div class="form-group">
										<label>Mobile phone</label>
										<input type="text" class="form-control">
									</div>
									
									<div class="form-group">
										<label>Referrer</label>
										<p class="text-muted">To enter name of patient in system start typing their name</p>
										<input type="text" class="form-control">
									</div>
								
								</section>
								
								
								<section class="detail-section">
									<h3>Related patients</h3>
									<a href="#" class="btn btn-default">Add related patient</a>
								</section>
								
								
								<section class="detail-section">
									<h3>Contact information</h3>
									
									<div class="form-group edit-phone">
										<label class="block-element">Other phone numbers</label>
										<a href="#" class="btn btn-default">Add phone number</a>
									</div>
									
									<div class="form-group">
										<label>Address</label>
										<input type="text" class="form-control">
										<input type="text" class="form-control second-field">
									</div>
									
									
									<div class="row">
										<div class="form-group col-sm-6">
											<label>City</label>
											<input type="text" class="form-control">
										</div>
										
										<div class="form-group col-xs-6 col-sm-3">
											<label>State</label>
											<select class="form-control">
											  <option></option>
											  <option>NSW</option>
											  <option>VIC</option>
											  <option>QLD</option>
											  <option>TAS</option>
											  <option>SA</option>
											  <option>WA</option>
											  <option>NT</option>
											  <option>ACT</option>
											</select>
										</div>
										
										<div class="form-group col-xs-6 col-sm-3">
											<label>Postcode</label>
											<input type="text" class="form-control">
										</div>
									</div>
									
								</section>

								
								<section class="detail-section">
									<h3>Other information</h3>
									
									<div class="form-group">
										<label>Occupation</label>
										<input type="text" class="form-control">
									</div>
									
									<div class="form-group">
										<label>Emergency contact</label>
										<input type="text" class="form-control">
									</div>
									
									<div class="form-group">
										<label>Health fund</label>
										<textarea class="form-control" rows="3"></textarea>
									</div>
									
									<div class="form-group">
										<label>Notes</label>
										<textarea class="form-control" rows="3"></textarea>
									</div>
									
								</section>
								
								
								<section class="detail-section">
									<h3>Notifications settings</h3>
									
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
								</section>
								
							
								<div class="footer-actions">
									<button type="button" class="btn btn-labeled btn-success"><span class="btn-label"><i class="glyphicon glyphicon-ok"></i></span>Save</button>
									<a class="btn btn-labeled btn-danger" href="patient-details.php"><span class="btn-label"><i class="glyphicon glyphicon-remove"></i></span>Cancel</a>
								</div>
							</form>
						
						</section>
						
					</div>
					
					<div class="col-md-4 col-lg-3">
						<?php include 'includes/patients-menu.php'; ?>
					</div>
					
				</div><!-- .row -->

			
<?php include 'includes/footer.php'; ?>