<?php include 'includes/header.php'; ?>
				
			<h1 class="page-title title-messages">Communications</h1>
				
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
							<td class="table-cell"><i class="fa fa-envelope-o" aria-hidden="true"></i></td>
							<td class="table-cell">24 Aug 2017</td>
							<td class="table-cell">Bill Smith</td>
							<td class="table-cell">Invoice</td>
						</tr>
						
						<tr class="table-row">
							<td class="table-cell"><i class="fa fa-mobile" aria-hidden="true"></i></td>
							<td class="table-cell">12 Aug 2017</td>
							<td class="table-cell">John Jones</td>
							<td class="table-cell">Appointment reminder</td>
						</tr>
						
						<tr class="table-row sms-reply">
							<td class="table-cell"><i class="fa fa-share" aria-hidden="true"></i></td>
							<td class="table-cell">12 Aug 2017</td>
							<td class="table-cell sms-message" colspan="2">Sorry I can't make this, can I rebook for August 20? Thanks</td>
						</tr>
						
						<tr class="table-row">
							<td class="table-cell"><i class="fa fa-phone" aria-hidden="true"></i></td>
							<td class="table-cell">24 Jul 2017</td>
							<td class="table-cell">Billy Joel</td>
							<td class="table-cell">Recall</td>
						</tr>
						
						<tr class="table-row">
							<td class="table-cell"><i class="fa fa-envelope-o" aria-hidden="true"></i></td>
							<td class="table-cell">24 Jun 2017</td>
							<td class="table-cell">Bill Smith</td>
							<td class="table-cell">Invoice</td>
						</tr>
						
						<tr class="table-row">
							<td class="table-cell"><i class="fa fa-mobile" aria-hidden="true"></i></td>
							<td class="table-cell">12 Jun 2017</td>
							<td class="table-cell">John Jones</td>
							<td class="table-cell">Appointment creation</td>
						</tr>
						
						<tr class="table-row">
							<td class="table-cell"><i class="fa fa-phone" aria-hidden="true"></i></td>
							<td class="table-cell">24 May 2017</td>
							<td class="table-cell">Billy Joel</td>
							<td class="table-cell">Recall</td>
						</tr>
						
					</table>
				
				</section>

			
<?php include 'includes/footer.php'; ?>