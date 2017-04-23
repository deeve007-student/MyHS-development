<?php include 'includes/header.php'; ?>
				
			<div class="content">
			
				<?php include 'includes/header-patient.php'; // common header for all patient info pages ?>
				
				<div class="subpage-titlebar">
				
					<h2 class="subpage-title hidden-xs hidden-sm">Invoices</h2>
					
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
								<div class="filter-checkboxes">
									<span>Filter by:</span>
									<label class="checkbox-inline" value=""><input type="checkbox">Draft</label>
									<label class="checkbox-inline" value=""><input type="checkbox">Pending</label>
									<label class="checkbox-inline" value=""><input type="checkbox">Overdue</label>
									<label class="checkbox-inline" value=""><input type="checkbox">Paid</label>
								</div>
							</form>
							
							<div class="table patient-list invoice-list">
					
						<div class="table-row table-header">
							<div class="table-cell">Invoice</div>
							<div class="table-cell">Date</div>
							<div class="table-cell right-align">Amount</div>
							<div class="table-cell right-align">Status</div>
						</div>
						
						<div class="table-row" onclick="location.href = 'invoice-details.php';">
							<div class="table-cell">00056</div>
							<div class="table-cell">15 Feb 2017</div>
							<div class="table-cell right-align">$456.95</div>
							<div class="table-cell right-align cell-status draft"><span>Draft</span></div>
						</div>
						
						<div class="table-row" onclick="location.href = 'invoice-details.php';">
							<div class="table-cell">00078</div>
							<div class="table-cell">25 Jan 2017</div>
							<div class="table-cell right-align">$456.95</div>
							<div class="table-cell right-align cell-status pending"><span>Pending</span></div>
						</div>
					
					</div>
						
						</section>
						
						
					</div>
					
					<?php include 'includes/patients-menu.php'; ?>
					
				</div><!-- .row -->
				
			</div>
			
<?php include 'includes/footer.php'; ?>