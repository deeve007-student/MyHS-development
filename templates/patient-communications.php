<?php include 'includes/header.php'; ?>
				
			<div class="content">
			
				<?php include 'includes/header-patient.php'; // common header for all patient info pages ?>
				
				<div class="subpage-titlebar">
				
					<h2 class="subpage-title hidden-xs hidden-sm">Communications</h2>
					
					<?php // start mobile sub-menu ?>
					<div class="btn-group visible-xs visible-sm">
						<h2 class="subpage-title" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Attachments <span class="caret"></span></h2>
						
						<?php include 'includes/patients-menu-mobile.php'; // mobile menu ?>
					</div>
					<?php // end mobile sub-menu ?>
					
					<div class="subpage-actions">
						<a href="#" class="btn btn-labeled btn-default"><span class="btn-label"><i class="glyphicon glyphicon-pencil"></i></span>Manual communication</a>
					</div>
				
				</div>
				
				<div class="row">
					
					<div class="col-md-8 col-lg-9">
						
						<section class="content-panel">
							
							<table class="table communications-list">
					
								<tr class="table-row table-header">
									<td class="table-cell"><!-- channel --></td>
									<td class="table-cell">Date</td>
									<td class="table-cell">Category</td>
								</tr>
								
								<tr class="table-row">
									<td class="table-cell"><i class="fa fa-phone" aria-hidden="true"></i></td>
									<td class="table-cell">24 Jul 2017</td>
									<td class="table-cell">Recall</td>
								</tr>
								
								<tr class="table-row">
									<td class="table-cell"><i class="fa fa-envelope-o" aria-hidden="true"></i></td>
									<td class="table-cell">24 Jun 2017</td>
									<td class="table-cell">Invoice</td>
								</tr>
								
								<tr class="table-row">
									<td class="table-cell"><i class="fa fa-mobile" aria-hidden="true"></i></td>
									<td class="table-cell">12 Jun 2017</td>
									<td class="table-cell">Appointment reminder</td>
								</tr>
								
								<tr class="table-row sms-reply">
									<td class="table-cell"><i class="fa fa-share" aria-hidden="true"></i></td>
									<td class="table-cell">13 Jun 2017</td>
									<td class="table-cell sms-message">Sorry I can't make this, can I rebook for August 12? Thanks</td>
								</tr>
								
								<tr class="table-row">
									<td class="table-cell"><i class="fa fa-phone" aria-hidden="true"></i></td>
									<td class="table-cell">24 May 2017</td>
									<td class="table-cell">Recall</td>
								</tr>
								
							</table>
						
						</section>
						
						
					</div>
					
					<?php include 'includes/patients-menu.php'; ?>
					
				</div><!-- .row -->
				
			</div>
			
<?php include 'includes/footer.php'; ?>