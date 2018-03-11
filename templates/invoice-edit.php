<?php include 'includes/header.php'; ?>
				
				<h1 class="page-title title-invoices">Invoices</h1>
		
				
				<div class="subpage-titlebar">
				
					<h2 class="subpage-title">Invoice #00467</h2>
	
					<div class="subpage-actions">
						<button type="submit" class="btn btn-labeled btn-success"><span class="btn-label"><i class="glyphicon glyphicon-ok"></i></span>Save</button>
						<a href="#" class="btn btn-labeled btn-danger"><span class="btn-label"><i class="glyphicon glyphicon-remove"></i></span>Cancel</a>
					</div>
				
				</div>
				
				
				<div class="row">
					
					<div class="col-md-12">
				
						<section class="content-panel invoice-edit">
							
							
							<section class="detail-section">
								<h3>Invoice details</h3>
								
								<div class="row">
									<div class="col-sm-5 col-md-4 date-selectors">
										<div class="form-group">
											<label class="control-label">Invoice date</label>
											<div class='input-group date' id='datetimepicker1'>
												<input type='text' class="form-control" />
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
										</div>
									</div>
									
									<div class="col-sm-3 col-md-4">
										<div class="form-group">
											<label class="control-label required">Due within</label>
											<div>
												<select class="form-control">
												  <option></option>
												  <option>Option 1</option>
												  <option>Option 2</option>
												  <option>Option 3</option>
												  <option>Option 4</option>
												</select>
											</div>
										</div>
									</div>
									
									<div class="col-sm-4">
										<div class="form-group">
											<label class="control-label required">Reminder frequency</label>
											<div>
												<select class="form-control">
												  <option></option>
												  <option>Option 1</option>
												  <option>Option 2</option>
												  <option>Option 3</option>
												  <option>Option 4</option>
												</select>
											</div>
										</div>
									</div>
								</div>
								
							</section>
							
							
							<section class="detail-section">
								<h3>Patient details</h3>
								
								<div class="row">
									<div class="col-sm-5 col-md-4">
										<div class="form-group">
											<label class="control-label">Patient</label>
											<div>
												<select class="form-control">
												  <option></option>
												  <option>Option 1</option>
												  <option>Option 2</option>
												  <option>Option 3</option>
												  <option>Option 4</option>
												</select>
											</div>
										</div>
									</div>
									
									<div class="col-sm-7 col-md-8">
										<div class="form-group">
											<label class="control-label required">Patient address</label>
											<div><textarea class="form-control" rows="3"></textarea></div>
										</div>
									</div>
								</div>
							</section>
							
							
							<section class="detail-section">
								<h3>Treatments</h3>
								
								<div class="repeater-rows">
								
									<div class="col-labels hidden-xs">
										<div class="row">
											<div class="col-sm-4">Treatment</div>
											<div class="col-sm-3">Price</div>
											<div class="col-sm-1">Qty</div>
											<div class="col-sm-3">Sub total</div>
											<div class="col-sm-1">&nbsp;</div>
										</div>
									</div>
									
									<!-- .previous-invoice -->
									<div class="previous-invoice">
										<h4>From previous draft invoices</h4>
										
										<div class="row">
											<div class="col-sm-4">
												<div class="form-group">
													<label class="control-label">Treatment</label>
													<select class="form-control">
													  <option></option>
													  <option>Option 1</option>
													  <option>Option 2</option>
													  <option>Option 3</option>
													  <option>Option 4</option>
													</select>
												</div>
											</div>
											
											<div class="col-sm-3">
												<div class="form-group">
													<label class="control-label">Price</label>
													<div><input type="text" class="form-control"></div>
												</div>
											</div>
											
											<div class="col-sm-1">
												<div class="form-group">
													<label class="control-label">Qty</label>
													<div><input type="text" class="form-control"></div>
												</div>
											</div>
											
											<div class="col-sm-2">
												<div class="form-group">
													<label class="control-label">Sub total</label>
													<div><input type="text" class="form-control" readonly="readonly"></div>
												</div>
											</div>
											
											<div class="col-sm-2 delete">
												<div class="form-group">
													<label class="control-label">&nbsp;</label>
													<div><a href="#" class="btn btn-default">Delete</a></div>
												</div>
											</div>
										</div>
										
										<div class="row">
											<div class="col-sm-4">
												<div class="form-group">
													<label class="control-label">Treatment</label>
													<select class="form-control">
													  <option></option>
													  <option>Option 1</option>
													  <option>Option 2</option>
													  <option>Option 3</option>
													  <option>Option 4</option>
													</select>
												</div>
											</div>
											
											<div class="col-sm-3">
												<div class="form-group">
													<label class="control-label">Price</label>
													<div><input type="text" class="form-control"></div>
												</div>
											</div>
											
											<div class="col-sm-1">
												<div class="form-group">
													<label class="control-label">Qty</label>
													<div><input type="text" class="form-control"></div>
												</div>
											</div>
											
											<div class="col-sm-2">
												<div class="form-group">
													<label class="control-label">Sub total</label>
													<div><input type="text" class="form-control" readonly="readonly"></div>
												</div>
											</div>
											
											<div class="col-sm-2 delete">
												<div class="form-group">
													<label class="control-label">&nbsp;</label>
													<div><a href="#" class="btn btn-default">Delete</a></div>
												</div>
											</div>
										</div>
										
									</div>
									<!-- /.previous-invoice -->
									
									<div class="row">
										<div class="col-sm-4">
											<div class="form-group">
												<label class="control-label">Treatment</label>
												<select class="form-control">
												  <option></option>
												  <option>Option 1</option>
												  <option>Option 2</option>
												  <option>Option 3</option>
												  <option>Option 4</option>
												</select>
											</div>
										</div>
										
										<div class="col-sm-3">
											<div class="form-group">
												<label class="control-label">Price</label>
												<div><input type="text" class="form-control"></div>
											</div>
										</div>
										
										<div class="col-sm-1">
											<div class="form-group">
												<label class="control-label">Qty</label>
												<div><input type="text" class="form-control"></div>
											</div>
										</div>
										
										<div class="col-sm-2">
											<div class="form-group">
												<label class="control-label">Sub total</label>
												<div><input type="text" class="form-control" readonly="readonly"></div>
											</div>
										</div>
										
										<div class="col-sm-2 delete">
											<div class="form-group">
												<label class="control-label">&nbsp;</label>
												<div><a href="#" class="btn btn-default">Delete</a></div>
											</div>
										</div>
									</div>
								
								</div> <!-- /.repeater-rows -->
								
								<a href="#" class="btn btn-default">Add treatment</a>
								
							</section>
							
							
							<section class="detail-section">
								<h3>Products</h3>
								<a href="#" class="btn btn-default">Add product</a>
							</section>
							
							
							<section class="detail-section">
								<h3>Invoice notes</h3>
								<textarea class="form-control" rows="3"></textarea>
							</section>
							
							
							<section class="detail-section patient-mandatory">
								<h3>Payments</h3>
								
								<div class="repeater-rows">
								
									<div class="row">
										<div class="col-sm-4">
											<label class="control-label">Date</label>
											<div class='input-group date' id='datetimepicker1'>
												<input type='text' class="form-control" />
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
										</div>
										
										<div class="col-sm-4">
											<div class="form-group">
												<label class="control-label">Payment method</label>
												<select class="form-control">
												  <option></option>
												  <option selected>Credit card</option>
												  <option>Cash</option>
												  <option>Cheque</option>
												  <option>Bank transfer</option>
												  <option>Hicaps</option>
												</select>
											</div>
										</div>
										
										<div class="col-sm-2">
											<div class="form-group">
												<label class="control-label">Amount</label>
												<div><input type="text" class="form-control"  value="$200.00"></div>
											</div>
										</div>
										
										<div class="col-sm-2 delete">
											<div class="form-group">
												<label class="control-label">&nbsp;</label>
												<div><a href="#" class="btn btn-default">Delete</a></div>
											</div>
										</div>
									</div>
									
									<div class="row">
										<div class="col-sm-4">
											<label class="control-label">Date</label>
											<div class='input-group date' id='datetimepicker1'>
												<input type='text' class="form-control" />
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
										</div>
										
										<div class="col-sm-4">
											<div class="form-group">
												<label class="control-label">Payment method</label>
												<select class="form-control">
												  <option></option>
												  <option>Credit card</option>
												  <option selected>Cash</option>
												  <option>Cheque</option>
												  <option>Bank transfer</option>
												  <option>Hicaps</option>
												</select>
											</div>
										</div>
										
										<div class="col-sm-2">
											<div class="form-group">
												<label class="control-label">Amount</label>
												<div><input type="text" class="form-control"></div>
											</div>
										</div>
										
										<div class="col-sm-2 delete">
											<div class="form-group">
												<label class="control-label">&nbsp;</label>
												<div><a href="#" class="btn btn-default">Delete</a></div>
											</div>
										</div>
									</div>
									
									<div class="row">
										<div class="col-sm-4">
											<label class="control-label">Date</label>
											<div class='input-group date' id='datetimepicker1'>
												<input type='text' class="form-control" />
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
										</div>
										
										<div class="col-sm-4">
											<div class="form-group">
												<label class="control-label">Payment method</label>
												<select class="form-control">
												  <option></option>
												  <option>Credit card</option>
												  <option>Cash</option>
												  <option selected>Cheque</option>
												  <option>Bank transfer</option>
												  <option>Hicaps</option>
												</select>
											</div>
										</div>
										
										<div class="col-sm-2">
											<div class="form-group">
												<label class="control-label">Amount</label>
												<div><input type="text" class="form-control" value="$200.00"></div>
											</div>
										</div>
										
										<div class="col-sm-2 delete">
											<div class="form-group">
												<label class="control-label">&nbsp;</label>
												<div><a href="#" class="btn btn-default">Delete</a></div>
											</div>
										</div>
									</div>
								
								</div> <!-- /.repeater-rows -->
								
								
								<div class="payment-totals">
									
									<div class="row">
										<div class="col-sm-4 col-sm-offset-4">
											<label class="control-label">Current payments total</label>
										</div>
										<div class="col-sm-2">
											<div class="form-group">
												<input type="text" class="form-control" readonly="readonly" value="$400.00">
											</div>
										</div>
									</div>
									
									<div class="current-invoice-total-divider"></div>
									
									<div class="row">
										<div class="col-sm-4 col-sm-offset-4">
											<label class="control-label">Current invoice total</label>
										</div>
										<div class="col-sm-2">
											<div class="form-group">
												<input type="text" class="form-control" readonly="readonly" value="$500.00">
											</div>
										</div>
									</div>
									
									<div class="current-invoice-total-divider"></div>
									
									<div class="row">
										<div class="col-sm-4 col-sm-offset-4">
											<label class="control-label">Outstanding balance</label>
										</div>
										<div class="col-sm-2">
											<div class="form-group">
												<input type="text" class="form-control" readonly="readonly" value="$100.00">
											</div>
										</div>
									</div>
									
									<div class="current-invoice-total-divider"></div>
									
								</div>
								
								
								<a href="#" class="btn btn-default">Add payment</a>
							</section>
						
						</section>
						
					</div>
			
			
<?php include 'includes/footer.php'; ?>