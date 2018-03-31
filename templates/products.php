<?php include 'includes/header.php'; ?>
				
			<h1 class="page-title title-products">Products</h1>
				
				<div class="page-actions">
					<a href="product-add.php" class="btn btn-labeled btn-primary"><span class="btn-label"><i class="glyphicon glyphicon-plus"></i></span>Add product</a>
				</div>
				
				<section class="content-panel">
				
					<form class="list-filter gray-box">
						<input type="text" class="form-control" id="" placeholder="Filter by product name...">
					</form>
					
					<div class="table treatment-list">
					
						<div class="table-row table-header">
							<div class="table-cell">Product</div>
							<div class="table-cell">Supplier</div>
							<div class="table-cell">Stock level</div>
							<div class="table-cell">Price</div>
							<div class="table-cell"><!-- Edit options --></div>
						</div>
						
						<div class="table-row">
							<div class="table-cell">Yoga Mat</div>
							<div class="table-cell">ACME Inc.</div>
							<div class="table-cell">22</div>
							<div class="table-cell">$45.00</div>
							<div class="table-cell">
								<a href="product-add.php" class=""><span class="glyphicon glyphicon-pencil"></span></a>
								<a href="#" data-toggle="modal" data-target=".modal-delete"><span class="glyphicon glyphicon-remove"></span></a>
							</div>
						</div>
						
						<div class="table-row">
							<div class="table-cell">Vitamins</div>
							<div class="table-cell">ACME Inc.</div>
							<div class="table-cell">22</div>
							<div class="table-cell">
								$45.00
								<br>Student: $35.00
								<br>Pensioner: $25:00
							</div>
							<div class="table-cell">
								<a href="product-add.php" class=""><span class="glyphicon glyphicon-pencil"></span></a>
								<a href="#" data-toggle="modal" data-target=".modal-delete"><span class="glyphicon glyphicon-remove"></span></a>
							</div>
						</div>
						
						<div class="table-row">
							<div class="table-cell">Treatment pack: Neck massage (P3645)</div>
							<div class="table-cell"><!-- Not applicable --></div>
							<div class="table-cell"><!-- Not applicable --></div>
							<div class="table-cell">$45.00</div>
							<div class="table-cell">
								<a href="product-add.php" class=""><span class="glyphicon glyphicon-pencil"></span></a>
								<a href="#" data-toggle="modal" data-target=".modal-delete"><span class="glyphicon glyphicon-remove"></span></a>
							</div>
						</div>
						
					</div>
				
				</section>

			
<?php include 'includes/footer.php'; ?>