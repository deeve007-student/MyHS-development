<?php include 'includes/header.php'; ?>
				
			<div class="content">
			
				<?php include 'includes/header-patient.php'; // common header for all patient info pages ?>
				
				<div class="row">
					
					<div class="col-sm-3">
						<?php include 'includes/patients-menu.php'; ?>
					</div>
					
					<div class="col-sm-9">
						
						<h2 class="subpage-title">Treatment notes</h2>
						
						<div class="subpage-actions">
							<a class="btn btn-labeled btn-default"><span class="btn-label"><i class="glyphicon glyphicon-plus"></i></span>Add treatment note</a>
						</div>
						
						
						<?php // first two treatment notes are expanded by default ?>
						<div class="treatment-note">	
							<div class="note-header" data-toggle="collapse" data-target="#tnote01" aria-expanded="false" aria-controls="tnote01">
								<h4>Another treatment type</h4>
								<div class="date-created">Created: 25 Feb 2017</div>
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
						
						<div class="treatment-note">	
							<div class="note-header"  data-toggle="collapse" data-target="#tnote02" aria-expanded="false" aria-controls="tnote02">
								<h4>Another treatment type</h4>
								<div class="date-created">Created: 25 Feb 2017</div>
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
						<?php // end expanded by default examples ?>
						
						
						<?php // remaining treatment notes are collapsed by default ?>
						<div class="treatment-note">	
							<div class="note-header collapsed" data-toggle="collapse" data-target="#tnote03" aria-expanded="false" aria-controls="tnote03">
								<h4>Another treatment type</h4>
								<div class="date-created">Created: 25 Feb 2017</div>
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
						
						<div class="treatment-note">	
							<div class="note-header collapsed" data-toggle="collapse" data-target="#tnote03" aria-expanded="false" aria-controls="tnote03">
								<h4>Another treatment type</h4>
								<div class="date-created">Created: 25 Feb 2017</div>
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
						
						
					</div>
					
				</div>
				
				
			</div>
			
<?php include 'includes/footer.php'; ?>