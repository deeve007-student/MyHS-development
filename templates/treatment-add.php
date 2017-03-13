<?php include 'includes/header.php'; ?>
				
			<h1 class="page-title title-treatments">Treatments</h1>
			
			<section class="content-panel treatment-edit">
				<h2 class="subpage-title">Add treatment</h2>
				
				<form>
					
					<div class="form-group">
						<label>Treatment name</label>
						<input type="text" class="form-control" placeholder="Enter treatment name">
					</div>
					
					<div class="form-group">
						<label>Treatment duration (in minutes)</label>
						<input type="text" class="form-control" placeholder="Enter treatment duration">
					</div>
					
					<label>Price</label>
					
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon">$</span>
							<input type="text" class="form-control" placeholder="Standard price">
						</div>
					</div>
					
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon">$</span>
							<input type="text" class="form-control" placeholder="Student price">
						</div>
					</div>
					
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon">$</span>
							<input type="text" class="form-control" placeholder="Pensioner price">
						</div>
					</div>
					
					<div class="footer-actions">
						<button type="button" class="btn btn-labeled btn-success"><span class="btn-label"><i class="glyphicon glyphicon-ok"></i></span>Save</button>
						<a class="btn btn-labeled btn-danger" href="treatments.php"><span class="btn-label"><i class="glyphicon glyphicon-remove"></i></span>Cancel</a>
					</div>
					
				</form>
			</section>
			
<?php include 'includes/footer.php'; ?>