<?php include 'includes/header.php'; ?>
				
			<h1 class="page-title title-documents">Documents</h1>
				
				<div class="page-actions">
					<a href="#" class="btn btn-labeled btn-primary" data-toggle="modal" data-target=".modal-upload"><span class="btn-label"><i class="glyphicon glyphicon-plus"></i></span>Upload document</a>
				</div>
				
				<section class="content-panel">
					
					<form class="list-filter gray-box">
						<input type="text" class="form-control" id="" placeholder="Filter by document name...">
						<div class="filter-checkboxes">
							<label class="checkbox-inline" value=""><input type="checkbox">General</label>
							<label class="checkbox-inline" value=""><input type="checkbox">Custom category 1</label>
							<label class="checkbox-inline" value=""><input type="checkbox">Custom category 2</label>
							<label class="checkbox-inline" value=""><input type="checkbox">Custom category 3</label>
						</div>
					</form>
					
					<div class="table treatment-list">
					
						<div class="table-row table-header">
							<div class="table-cell">File name</div>
							<div class="table-cell">Category</div>
							<div class="table-cell">Size (kb)</div>
							<div class="table-cell">Date</div>
							<div class="table-cell"><!-- Edit options --></div>
						</div>
						
						<div class="table-row">
							<div class="table-cell"><a href="#" target="_blank">file-name.doc</a></div>
							<div class="table-cell">Custom category 1</div>
							<div class="table-cell">45</div>
							<div class="table-cell">25 Feb 2018</div>
							<div class="table-cell right-align">
								<a href="#" data-toggle="modal" data-target=".modal-delete"><span class="glyphicon glyphicon-remove"></span></a>
							</div>
						</div>
						
						<div class="table-row">
							<div class="table-cell"><a href="#" target="_blank">another-file-name.doc</a></div>
							<div class="table-cell">General</div>
							<div class="table-cell">75</div>
							<div class="table-cell">15 Mar 2018</div>
							<div class="table-cell right-align">
								<a href="#" data-toggle="modal" data-target=".modal-delete"><span class="glyphicon glyphicon-remove"></span></a>
							</div>
						</div>
						
					</div>
				
				</section>

			
<?php include 'includes/footer.php'; ?>