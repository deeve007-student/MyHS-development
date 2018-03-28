<?php include 'includes/header.php'; ?>
				
			<h1 class="page-title title-products">Add product</h1>
			
			<section class="content-panel treatment-edit">
				
				
				<form>
					
					<section class="detail-section contact-information">
					
						<h3>General information</h3>
						
						<div class="form-group">
							<label>Product type</label>
							<select class="form-control">
								<option>Standard product</option>
								<option>Treatment pack</option>
							</select>
						</div>
						
						<div class="form-group">
							<label>Product name</label>
							<input type="text" class="form-control" placeholder="Enter product name">
						</div>
						
						<div class="form-group">
							<label>Product code</label>
							<input type="text" class="form-control" placeholder="Enter product code">
						</div>
						
						<div class="form-group">
							<label>Supplier</label>
							<input type="text" class="form-control" placeholder="Enter supplier">
						</div>
						
						<div class="form-group">
							<label>Stock level</label>
							<input type="text" class="form-control" value="0">
						</div>
						
						<div class="form-group">
							<label>Cost price</label>
							<div class="input-group">
								<span class="input-group-addon">$</span>
								<input type="text" class="form-control" placeholder="Enter cost price">
							</div>
						</div>
					
					</section>
					
					<section class="detail-section contact-information">
					
						<h3>Pricing</h3>
					
						<div class="form-group">
							<label>Standard price</label>
							<div class="input-group">
								<span class="input-group-addon">$</span>
								<input type="text" class="form-control" placeholder="Enter standard price">
							</div>
						</div>
						
						<div class="form-group">
							<label>Student price</label>
							<div class="input-group">
								<span class="input-group-addon">$</span>
								<input type="text" class="form-control" placeholder="Same as standard price">
							</div>
						</div>
						
						<div class="form-group">
							<label>Pensioner price</label>
							<div class="input-group">
								<span class="input-group-addon">$</span>
								<input type="text" class="form-control" placeholder="Same as standard price">
							</div>
						</div>
						
					</section>
					
					<div class="footer-actions">
						<button type="button" class="btn btn-labeled btn-success"><span class="btn-label"><i class="glyphicon glyphicon-ok"></i></span>Save</button>
						<a class="btn btn-labeled btn-danger" href="treatments.php"><span class="btn-label"><i class="glyphicon glyphicon-remove"></i></span>Cancel</a>
					</div>
					
				</form>
			</section>
			
<?php include 'includes/footer.php'; ?>