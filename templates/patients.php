<?php include 'includes/header.php'; ?>
				
			<div class="content">
				
				<h1 class="page-title">Patients</h1>
				
				<div class="page-actions">
					<a class="btn btn-labeled btn-primary"><span class="btn-label"><i class="glyphicon glyphicon-plus"></i></span>Add patient</a>
				</div>
				
				<form class="list-filter">
					<input type="text" class="form-control" id="" placeholder="Filter patients by name, phone number, email address...">
				</form>
				
				<div class="table patient-list">
				
					<div class="table-row table-header">
						<div class="table-cell">First name</div>
						<div class="table-cell">Last name</div>
						<div class="table-cell">Email</div>
						<div class="table-cell">Phone</div>
						<div class="table-cell">Next appointment</div>
					</div>
					
					<div class="table-row" onclick="location.href = 'patient-details.php';">
						<div class="table-cell">Jim</div>
						<div class="table-cell">Smith</div>
						<div class="table-cell">jim@email.com</div>
						<div class="table-cell">0415 476896</div>
						<a class="table-cell" href="http://google.com">25 May 2017</a>
					</div>
					
					<div class="table-row" onclick="location.href = 'patient-details.php';">
						<div class="table-cell">Jim</div>
						<div class="table-cell">Smith</div>
						<div class="table-cell">jim@email.com</div>
						<div class="table-cell">0415 476896</div>
						<div class="table-cell"><!-- No appointment booked --></div>
					</div>
					
				</div>
				
			</div>
			
<?php include 'includes/footer.php'; ?>