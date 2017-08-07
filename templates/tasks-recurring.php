<?php include 'includes/header.php'; ?>
				
			<h1 class="page-title title-tasks">Recurring tasks</h1>
				
				<div class="page-actions">
					<a href="#" class="btn btn-labeled btn-primary"><span class="btn-label"><i class="glyphicon glyphicon-plus"></i></span>Add recurring task</a>
				</div>
				
				<section class="content-panel">
				
					<form class="list-filter gray-box">
						<input type="text" class="form-control" id="" placeholder="Filter by task...">
					</form>
					
					<div class="table treatment-list">
					
						<div class="table-row table-header">
							<div class="table-cell">Task</div>
							<div class="table-cell">Next occurance</div>
							<div class="table-cell"><!-- Edit options --></div>
						</div>
						
						<div class="table-row">
							<div class="table-cell">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam condimentum a neque id tincidunt.</div>
							<div class="table-cell">October 25, 2017</div>
							<div class="table-cell">
								<a href="#" class="edit-item"></a>
								<a href="#" class="delete-item" data-toggle="modal" data-target=".modal-delete"></a>
							</div>
						</div>
						
						<div class="table-row">
							<div class="table-cell">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam condimentum a neque id tincidunt.</div>
							<div class="table-cell">November 25, 2017</div>
							<div class="table-cell">
								<a href="#" class="edit-item"></a>
								<a href="#" class="delete-item" data-toggle="modal" data-target=".modal-delete"></a>
							</div>
						</div>
						
					</div>
				
				</section>

			
<?php include 'includes/footer.php'; ?>