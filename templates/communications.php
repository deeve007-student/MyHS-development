<?php include 'includes/header.php'; ?>
				
			<h1 class="page-title title-messages">Communications</h1>
				
				<div class="page-actions">
					<a href="#" class="btn btn-labeled btn-default" data-toggle="modal" data-target=".modal-event"><span class="btn-label"><i class="glyphicon glyphicon-plus"></i></span>Add event</a>
					<a href="#" class="btn btn-labeled btn-default" data-toggle="modal" data-target=".modal-communication"><span class="btn-label"><i class="glyphicon glyphicon-plus"></i></span>Manual communication</a>
				</div>
				
				
				<section class="content-panel">
				
					<form class="list-filter gray-box">
						<input type="text" class="form-control" placeholder="Filter patients by name, phone number, email address...">
					</form>
					
					<table class="table communications-list">
					
						<tr class="table-row table-header">
							<td class="table-cell"><!-- channel --></td>
							<td class="table-cell">Date</td>
							<td class="table-cell">Name</td>
							<td class="table-cell">Category</td>
						</tr>
						
						<tr class="table-row">
							<td class="table-cell"><i class="fa fa-envelope-o" aria-hidden="true"></i> <i class="fa fa-users" aria-hidden="true"></i></td>
							<td class="table-cell">16 Feb 2018</td>
							<td class="table-cell"><a href="#">Filtered patient list</a></td>
							<td class="table-cell"><a href="#">Bulk communication</a></td>
						</tr>
						
						<tr class="table-row">
							<td class="table-cell"><i class="fa fa-envelope-o" aria-hidden="true"></i></td>
							<td class="table-cell">24 Aug 2017</td>
							<td class="table-cell"><a href="#">Bill Smith</a></td>
							<td class="table-cell"><a href="#">Invoice</a></td>
						</tr>
						
						<tr class="table-row">
							<td class="table-cell"><i class="fa fa-mobile" aria-hidden="true"></i></td>
							<td class="table-cell">12 Aug 2017</td>
							<td class="table-cell"><a href="#">John Jones</a></td>
							<td class="table-cell"><a href="#">Appointment reminder</a></td>
						</tr>
						
						<tr class="table-row sms-reply">
							<td class="table-cell"><i class="fa fa-share" aria-hidden="true"></i></td>
							<td class="table-cell">12 Aug 2017</td>
							<td class="table-cell sms-message" colspan="2">Sorry I can't make this, can I rebook for August 20? Thanks</td>
						</tr>
						
						<tr class="table-row">
							<td class="table-cell"><i class="fa fa-phone" aria-hidden="true"></i></td>
							<td class="table-cell">24 Jul 2017</td>
							<td class="table-cell"><a href="#">Billy Joel</a></td>
							<td class="table-cell"><a href="#">Recall</a></td>
						</tr>
						
						<tr class="table-row">
							<td class="table-cell"><i class="fa fa-envelope-o" aria-hidden="true"></i></td>
							<td class="table-cell">24 Jun 2017</td>
							<td class="table-cell"><a href="#">Bill Smith</a></td>
							<td class="table-cell"><a href="#">Invoice</a></td>
						</tr>
						
						<tr class="table-row">
							<td class="table-cell"><i class="fa fa-mobile" aria-hidden="true"></i></td>
							<td class="table-cell">12 Jun 2017</td>
							<td class="table-cell"><a href="#">John Jones</a></td>
							<td class="table-cell"><a href="#">Appointment creation</a></td>
						</tr>
						
						<tr class="table-row">
							<td class="table-cell"><i class="fa fa-phone" aria-hidden="true"></i></td>
							<td class="table-cell">24 May 2017</td>
							<td class="table-cell"><a href="#">Billy Joel</a></td>
							<td class="table-cell"><a href="#">Recall</a></td>
						</tr>
						
					</table>
				
				</section>

			
<?php include 'includes/footer.php'; ?>