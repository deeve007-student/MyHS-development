<?php include 'includes/header.php'; ?>
				
				<h1 class="page-title title-invoices">Invoices</h1>
		
				
				<div class="subpage-titlebar">
				
					<h2 class="subpage-title">Invoice #00467</h2>
	
					<div class="subpage-actions">
						<a href="invoice-edit.php" class="btn btn-labeled btn-default"><span class="btn-label"><i class="glyphicon glyphicon-pencil"></i></span>Edit invoice</a>
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
									<option value="#">Issue refund</option>
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
								<span>Dr Jim Clarke</span><br>
								Provider no: 725239<br>
								ACME Medical Clinic<br>
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
								<span class="total-text">Invoice total:</span>
								<span class="total-amount">$100.00</span>
							</div>
							
							<div class="inv-total amount-due">
								<span class="total-text">Amount due:</span>
								<span class="total-amount">$55.00</span>
							</div>
							
							<div class="inv-notes">
								<span>Invoice notes</span>
								This may include terms & conditions, payment methods, or any other item. A default invoice note for invoices can be created in the "Settings" section. If no notes have been added this section doesn't appear at all.
							</div>
							
							
							<?php // invoice payments table - only displays if payment has been made on invoice ?>
							<div class="invoice-payments">
								<h3>Payment</h3>
								<div class="table inv-payments">
						
									<div class="table-row table-header">
										<div class="table-cell">Date</div>
										<div class="table-cell">Payment method</div>
										<div class="table-cell right-align">Amount</div>
										<div class="table-cell right-align">&nbsp;</div>
									</div>
									
									<div class="table-row">
										<div class="table-cell">26 Jun 2017</div>
										<div class="table-cell">Bank transfer</div>
										<div class="table-cell right-align">$45.00</div>
										<div class="table-cell right-align row-delete"><i class="fa fa-times" aria-hidden="true" data-toggle="modal" data-target=".modal-delete"></i></div>
									</div>
									
								</div>
							</div>
							<?php // end invoice payments table ?>
							
							<?php // invoice refunds table - only displays if a refund has been made on invoice ?>
							<div class="invoice-payments">
								<h3>Refunds</h3>
								<div class="table inv-payments">
						
									<div class="table-row table-header">
										<div class="table-cell">Date</div>
										<div class="table-cell">Item</div>
										<div class="table-cell">Payment method</div>
										<div class="table-cell right-align">Refund amount</div>
										<div class="table-cell">&nbsp;</div>
									</div>
									
									<div class="table-row">
										<div class="table-cell">26 Jun 2017</div>
										<div class="table-cell">60min Neck Massage (HFD34)</div>
										<div class="table-cell">Bank transfer</div>
										<div class="table-cell right-align">$45.00</div>
										<div class="table-cell right-align row-delete"><i class="fa fa-times" aria-hidden="true" data-toggle="modal" data-target=".modal-delete"></i></div>
									</div>
									
								</div>
							</div>
							<?php // end invoice refunds table ?>
							
							
						</section>
						
					</div>
					
					<?php // invoice menu for larger screen sizes ?>
					<div class="col-md-3 hidden-xs hidden-sm">
						<section class="content-panel invoice-actions">
							
							<a class="btn btn-default" href="#" role="button">Make Draft</a>
							<a class="btn btn-info" href="#" role="button">Make Pending</a>
							<a class="btn btn-success" href="#" data-toggle="modal" data-target=".modal-payment" role="button">Add Payment</a>
							<a class="btn btn-default" href="#" role="button">Duplicate</a>
							<a class="btn btn-default" href="#" role="button">PDF</a>
							<a class="btn btn-default" href="#" role="button">Email</a>
							<a class="btn btn-default" href="#" role="button" data-toggle="modal" data-target=".modal-refund-invoice">Issue refund</a>
							<a class="btn btn-danger" href="#" role="button">Delete</a>
							
						</section>					
					</div>
					<?php // end invoice menu for larger screen sizes ?>
			
			
<?php include 'includes/footer.php'; ?>