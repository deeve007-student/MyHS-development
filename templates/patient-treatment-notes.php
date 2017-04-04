<?php include 'includes/header.php'; ?>
				
			<div class="content">
			
				<?php include 'includes/header-patient.php'; // common header for all patient info pages ?>
				
				
				<div class="subpage-titlebar">
				
					<h2 class="subpage-title">Treatment notes</h2>
							
					<div class="subpage-actions">
						<a href="patient-details-edit.php" class="btn btn-labeled btn-default"><span class="btn-label"><i class="glyphicon glyphicon-pencil"></i></span>Add <span class="hide-small">treatment note</span></a>
					</div>
				
				</div>
				
				
				<div class="row">
					
					<div class="col-md-8 col-lg-9">
						
						<?php // first two treatment notes are expanded by default ?>
						<section class="content-panel">
						
							<div class="detail-section treatment-note">	
								<div class="note-header" data-toggle="collapse" data-target="#tnote01" aria-expanded="false" aria-controls="tnote01">
									<h3>Another treatment note</h3>
									<div class="date-created">25 Feb 2017</div>
								</div>
								
								<div class="collapse in" id="tnote01">
									<div class="note-body">
										<h5>Field title</h5>
										<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer gravida tristique aliquam. Proin sollicitudin rhoncus sem. Nullam maximus laoreet urna, eu vehicula nunc tincidunt ut.</p>
										
										<h5>Field title</h5>
										<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer gravida tristique aliquam. Proin sollicitudin rhoncus sem. Nullam maximus laoreet urna, eu vehicula nunc tincidunt ut.</p>
									</div>
									
									<div class="note-footer">
										<a href="#" class="btn btn-default">Edit</a>
										<a href="#" class="btn btn-default">Export PDF</a>
										<a href="#" class="btn btn-default" data-toggle="modal" data-target=".modal-delete">Delete</a>
									</div>
								</div>
							</div>
							
						</section>
						
						<section class="content-panel">
						
							<div class="detail-section treatment-note">	
								<div class="note-header" data-toggle="collapse" data-target="#tnote02" aria-expanded="false" aria-controls="tnote02">
									<h3>Another treatment note</h3>
									<div class="date-created">25 Feb 2017</div>
								</div>
								
								<div class="collapse in" id="tnote02">
									<div class="note-body">
										<h5>Field title</h5>
										<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer gravida tristique aliquam. Proin sollicitudin rhoncus sem. Nullam maximus laoreet urna, eu vehicula nunc tincidunt ut.</p>
										
										<h5>Field title</h5>
										<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer gravida tristique aliquam. Proin sollicitudin rhoncus sem. Nullam maximus laoreet urna, eu vehicula nunc tincidunt ut.</p>
									</div>
									
									<div class="note-footer">
										<a href="#" class="btn btn-default">Edit</a>
										<a href="#" class="btn btn-default">Export PDF</a>
										<a href="#" class="btn btn-default" data-toggle="modal" data-target=".modal-delete">Delete</a>
									</div>
								</div>
							</div>
							
						</section>
						<?php // end expanded by default examples ?>
						
							
						<section class="content-panel">
							<?php // remaining treatment notes are collapsed by default ?>
							<div class="detail-section treatment-note">	
								<div class="note-header collapsed" data-toggle="collapse" data-target="#tnote03" aria-expanded="false" aria-controls="tnote03">
									<h3>Another treatment note</h3>
									<div class="date-created">25 Feb 2017</div>
								</div>
								
								<div class="collapse" id="tnote03">
									<div class="note-body">
										<h5>Field title</h5>
										<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer gravida tristique aliquam. Proin sollicitudin rhoncus sem. Nullam maximus laoreet urna, eu vehicula nunc tincidunt ut.</p>
										
										<h5>Field title</h5>
										<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer gravida tristique aliquam. Proin sollicitudin rhoncus sem. Nullam maximus laoreet urna, eu vehicula nunc tincidunt ut.</p>
									</div>
									
									<div class="note-footer">
										<a href="#" class="btn btn-default">Edit</a>
										<a href="#" class="btn btn-default">Export PDF</a>
										<a href="#" class="btn btn-default" data-toggle="modal" data-target=".modal-delete">Delete</a>
									</div>
								</div>
							</div>
						</section>
							
						<section class="content-panel">
							<div class="detail-section treatment-note">	
								<div class="note-header collapsed" data-toggle="collapse" data-target="#tnote03" aria-expanded="false" aria-controls="tnote03">
									<h3>Another treatment note</h3>
									<div class="date-created">25 Feb 2017</div>
								</div>
								
								<div class="collapse" id="tnote03">
									<div class="note-body">
										<h5>Field title</h5>
										<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer gravida tristique aliquam. Proin sollicitudin rhoncus sem. Nullam maximus laoreet urna, eu vehicula nunc tincidunt ut.</p>
										
										<h5>Field title</h5>
										<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer gravida tristique aliquam. Proin sollicitudin rhoncus sem. Nullam maximus laoreet urna, eu vehicula nunc tincidunt ut.</p>
									</div>
									
									<div class="note-footer">
										<a href="#" class="btn btn-default">Edit</a>
										<a href="#" class="btn btn-default">Export PDF</a>
										<a href="#" class="btn btn-default" data-toggle="modal" data-target=".modal-delete">Delete</a>
									</div>
								</div>
							</div>
							<?php // end collapsed by default examples ?>
							
							
							<?php // If more than 20 treatment notes use lazy loading to load others as user scrolls down page ?>
						
						</section>
						
					</div>
					
					<div class="col-md-4 col-lg-3">
						<?php include 'includes/patients-menu.php'; ?>
					</div>
					
				</div><!-- .row -->
				
				
			</div>
			
<?php include 'includes/footer.php'; ?>