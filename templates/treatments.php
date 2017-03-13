<?php include 'includes/header.php'; ?>
				
			<h1 class="page-title title-treatments">Treatments</h1>
				
				<div class="page-actions">
					<a href="treatment-add.php" class="btn btn-labeled btn-primary"><span class="btn-label"><i class="glyphicon glyphicon-plus"></i></span>Add treatment</a>
				</div>
				
				<section class="content-panel">
				
					<form class="list-filter gray-box">
						<input type="text" class="form-control" id="" placeholder="Filter by treatment name...">
					</form>
					
					<div class="table treatment-list">
					
						<div class="table-row table-header">
							<div class="table-cell">Treatment</div>
							<div class="table-cell">Duration</div>
							<div class="table-cell">Price</div>
							<div class="table-cell"><!-- Edit options --></div>
						</div>
						
						<div class="table-row">
							<div class="table-cell">Deep Tissue Massage</div>
							<div class="table-cell">30 min</div>
							<div class="table-cell">$45.00</div>
							<div class="table-cell">
								<a href="treatment-add.php" class="edit-item"></a>
								<a href="#" class="delete-item"></a>
							</div>
						</div>
						
						<div class="table-row">
							<div class="table-cell">Sports Massage</div>
							<div class="table-cell">30 min</div>
							<div class="table-cell">
								$45.00
								<br>Student: $35.00
								<br>Pensioner: $25:00
							</div>
							<div class="table-cell">
								<a href="treatment-add.php" class="edit-item"></a>
								<a href="#" class="delete-item"></a>
							</div>
						</div>
						
					</div>
				
				</section>

			
<?php include 'includes/footer.php'; ?>