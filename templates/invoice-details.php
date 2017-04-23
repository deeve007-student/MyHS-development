<?php include 'includes/header.php'; ?>
				
				<h1 class="page-title title-invoices">Invoices</h1>
		
				
				<div class="subpage-titlebar">
				
					<h2 class="subpage-title">Invoice #00467</h2>
	
					<div class="subpage-actions">
						<a href="patient-details-edit.php" class="btn btn-labeled btn-default"><span class="btn-label"><i class="glyphicon glyphicon-pencil"></i></span>Edit invoice</a>
					</div>
				
				</div>
				
				
				<div class="row">
			
					<?php // invoice menu for small screen sizes ?>
					<div class="col-md-3 hidden-md hidden-lg">
						<section class="content-panel">
							<form>
								<select class="selectpicker" title="Invoice options" onChange="window.document.location.href=this.options[this.selectedIndex].value;">
									<option value="#">Make Pending</option>
									<option value="#">Duplicate</option>
									<option value="#">PDF</option>
									<option value="#">Email</option>
									<option value="#">Delete</option>
								</select>
							</form>
						</section>					
					</div>
					<?php // end invoice menu for small screen sizes ?>
					
					<div class="col-md-9">
				
						<section class="content-panel invoice-detail">
							
							<h2>Invoice</h2>
							
							<div class="invoice-status-box">
								Draft
							</div>
							
							<div class="inv-from">
								<span>ACME Medical Clinic</span><br>
								65 Main St, Sydney NSW 2000
							</div>
							
							<div class="inv-details">
								<span>Number:</span>  00462<br>
								<span>Date:</span>  Mar 20, 2017<br>
								<span>Due date:</span>  Apr 04, 2017<br>
								<span>Reminder:</span>  Every 7 Days
							</div>
							
							<div class="inv-client">
								<span>John Smith</span><br>
								5 Smith St<br>
								Sydney, NSW 2056<br>
								Australia
							</div>
							
							<div class="table inv-items">
					
								<div class="table-row table-header">
									<div class="table-cell">Item</div>
									<div class="table-cell right-align">Price</div>
									<div class="table-cell right-align">Qty</div>
									<div class="table-cell right-align">Total</div>
								</div>
								
								<div class="table-row">
									<div class="table-cell">Sports Massage - 30 minutes</div>
									<div class="table-cell right-align">$65.00</div>
									<div class="table-cell right-align">1</div>
									<div class="table-cell right-align">$65.00</div>
								</div>
								
								<div class="table-row">
									<div class="table-cell">Yoga mat</div>
									<div class="table-cell right-align">$35.00</div>
									<div class="table-cell right-align">1</div>
									<div class="table-cell right-align">$35.00</div>
								</div>
								
							</div>
							
							<div class="inv-total">
								<span class="total-text">Amount due:</span>
								<span class="total-amount">$100.00</span>
							</div>
							
							<div class="inv-notes">
								<span>Invoice notes</span>
								This may include terms & conditions, payment methods, or any other item. A default invoice note for invoices can be created in the "Settings" section. If no notes have been added this section doesn't appear at all.
							</div>
							
						</section>
						
					</div>
					
					<?php // invoice menu for larger screen sizes ?>
					<div class="col-md-3 hidden-xs hidden-sm">
						<section class="content-panel invoice-actions">
							
							<a class="btn btn-default" href="#" role="button">Make Draft</a>
							<a class="btn btn-info" href="#" role="button">Make Pending</a>
							<a class="btn btn-success" href="#" role="button">Make Paid</a>
							<a class="btn btn-default" href="#" role="button">Duplicate</a>
							<a class="btn btn-default" href="#" role="button">PDF</a>
							<a class="btn btn-default" href="#" role="button">Email</a>
							<a class="btn btn-danger" href="#" role="button">Delete</a>
							
						</section>					
					</div>
					<?php // end invoice menu for larger screen sizes ?>
			
			
<?php include 'includes/footer.php'; ?>