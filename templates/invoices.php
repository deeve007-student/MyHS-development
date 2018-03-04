<?php include 'includes/header.php'; ?>
				
				<h1 class="page-title title-invoices">Invoices</h1>
				
				<div class="page-actions">
					<a href="#" class="btn btn-labeled btn-default" data-toggle="modal" data-target=".modal-noninvoice-refund"><span class="btn-label"><i class="glyphicon glyphicon-plus"></i></span>Non-invoice refund</a>
					
					<a href="#" class="btn btn-labeled btn-primary"><span class="btn-label"><i class="glyphicon glyphicon-plus"></i></span>Create new invoice</a>
				</div>
				
				<section class="content-panel">
				
					<form class="list-filter gray-box">
						<input type="text" class="form-control" id="" placeholder="Filter by patient name...">
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
							<div class="table-cell">Name</div>
							<div class="table-cell right-align">Amount</div>
							<div class="table-cell right-align">Status</div>
						</div>
						
						<div class="table-row" onclick="location.href = 'invoice-details.php';">
							<div class="table-cell">00056</div>
							<div class="table-cell">15 Feb 2017</div>
							<div class="table-cell">Bill Smith</div>
							<div class="table-cell right-align">$456.95</div>
							<div class="table-cell right-align cell-status draft"><span>Draft</span></div>
						</div>
						
						<div class="table-row" onclick="location.href = 'invoice-details.php';">
							<div class="table-cell">00078</div>
							<div class="table-cell">25 Jan 2017</div>
							<div class="table-cell">John Bull</div>
							<div class="table-cell right-align">$456.95</div>
							<div class="table-cell right-align cell-status pending"><span>Pending</span></div>
						</div>
						
						<div class="table-row" onclick="location.href = 'invoice-details.php';">
							<div class="table-cell">00078</div>
							<div class="table-cell">25 Jan 2017</div>
							<div class="table-cell">John Bull</div>
							<div class="table-cell right-align">$456.95</div>
							<div class="table-cell right-align cell-status overdue"><span>Overdue</span></div>
						</div>
						
						<div class="table-row" onclick="location.href = 'invoice-details.php';">
							<div class="table-cell">00078</div>
							<div class="table-cell">25 Jan 2017</div>
							<div class="table-cell">John Bull</div>
							<div class="table-cell right-align">$456.95</div>
							<div class="table-cell right-align cell-status paid"><span>Paid</span></div>
						</div>
						
						<div class="table-row" onclick="location.href = 'invoice-details.php';">
							<div class="table-cell"><em>Non-invoice refund</em></div>
							<div class="table-cell"><em>20 Feb 2018</em></div>
							<div class="table-cell"><em>Non-invoice refund: This would be the reason for this refund, as entered when adding non-invoice refund</em></div>
							<div class="table-cell right-align"><em>-$456.95</em></div>
							<div class="table-cell">&nbsp;</div>
						</div>
					
					</div>
					
				</section>
			
			
<?php include 'includes/footer.php'; ?>