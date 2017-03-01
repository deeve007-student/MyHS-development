<?php include 'includes/header.php'; ?>
				
			<div class="content">
			
				<?php include 'includes/header-patient.php'; // common header for all patient info pages ?>
				
				<div class="row">
					
					<div class="col-sm-3">
						<?php include 'includes/patients-menu.php'; ?>
					</div>
					
					<div class="col-sm-9">
						
						<h2 class="subpage-title">Editing patient</h2>
						
						<form class="edit-info">
							
							<div class="subpage-actions">
								<button type="button" class="btn btn-labeled btn-success"><span class="btn-label"><i class="glyphicon glyphicon-ok"></i></span>Save</button>
								<a class="btn btn-labeled btn-danger" href="patient-details.php"><span class="btn-label"><i class="glyphicon glyphicon-remove"></i></span>Cancel</a>
							</div>
							
							<section class="edit-patient-information">
							
								<div class="form-group">
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
								
								<div class="form-group">
									<label>First name</label>
									<input type="text" class="form-control">
								</div>
								
								<div class="form-group">
									<label>Last name</label>
									<input type="text" class="form-control">
								</div>
								
								<div class="form-group">
									<label>Date of birth</label>
									<br>[ Birth date selector here ]
								</div>
								
								<div class="form-group">
									<label>Gender</label>
									<select class="form-control">
									  <option></option>
									  <option>Male</option>
									  <option>Female</option>
									  <option>Not applicable</option>
									</select>
								</div>
							
							</section>
							
							
							<section class="detail-section">
								<h3>Related patients</h3>
								<a href="#" class="btn btn-default">Add related patient</a>
							</section>
							
							
							<section class="detail-section">
								<h3>Contact information</h3>
								
								<div class="form-group edit-phone">
									<label class="block-element">Phone</label>
									<a href="#" class="btn btn-default">Add phone number</a>
								</div>
								
								<div class="form-group">
									<label>Email</label>
									<input type="email" class="form-control">
								</div>
								
								<div class="form-group">
									<label>Address</label>
									<input type="text" class="form-control">
									<input type="text" class="form-control second-field">
								</div>
								
								<div class="form-group">
									<label>City</label>
									<input type="text" class="form-control">
								</div>
								
								<div class="form-group">
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
									<label>Referrer</label>
									<input type="text" class="form-control">
								</div>
								
								<div class="form-group">
									<label>Notes</label>
									<textarea class="form-control" rows="3"></textarea>
								</div>
								
							</section>
							
						
							<div class="footer-actions">
								<button type="button" class="btn btn-labeled btn-success"><span class="btn-label"><i class="glyphicon glyphicon-ok"></i></span>Save</button>
								<a class="btn btn-labeled btn-danger" href="patient-details.php"><span class="btn-label"><i class="glyphicon glyphicon-remove"></i></span>Cancel</a>
							</div>
						</form>
						
					</div>
					
				</div>
				
			</div>
			
<?php include 'includes/footer.php'; ?>