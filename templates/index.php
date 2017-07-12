<?php include 'includes/header.php'; ?>
				
			<h1 class="page-title title-dashboard">Dashboard</h1>
			
			<div class="row">
			
			
				<div class="col-lg-6 appointment-list">
				
					<?php // start appointment list ?>
					<section class="content-panel">
						
						<div class="date-scroll">
							<h2>Tuesday, 13 May</h2>
							<a href="#" class="scroll-prev"></a>
							<a href="#" class="scroll-next"></a>
						</div>
						
						<table class="appt-list">
						
							<tr>
								<td class="appt-item">
									<div class="appt-info">
										<span class="time">2:00<span>pm</span></span>
										<span class="name">Jim Smith</span>
									</div>
									<div class="appt-meta">
										Chiropractic Standard
										<span class="duration">15 min</span>
									</div>
								</td>
								<td class="appt-item">
									<div class="appt-info">
										<span class="time">2:00<span>pm</span></span>
										<span class="name">Bob Jones</span>
									</div>
									<div class="appt-meta">
										Chiropractic Standard
										<span class="duration">15 min</span>
									</div>
								</td>
							</tr>
							<tr>
								<td class="appt-item" rowspan="2">
									<div class="appt-info">
										<span class="time">2:15<span>pm</span></span>
										<span class="name">Patient name</span>
									</div>
									<div class="appt-meta">
										Chiropractic Standard
										<span class="duration">30 min</span>
										<a class="appt-alert">This is a patient alert, if you click on it the alert edit modal will open.</a>
									</div>
								</td>
								<td class="appt-item">
									<div class="appt-info">
										<span class="time">2:15<span>pm</span></span>
										<span class="name">Patient name</span>
									</div>
									<div class="appt-meta">
										Chiropractic Standard
										<span class="duration">15 min</span>
										<a class="appt-alert">This is a patient alert, if you click on it the alert edit modal will open.</a>
									</div>
								</td>
							</tr>
							<tr>
								<td class="appt-item" rowspan="2">
									<div class="appt-info">
										<span class="time">2:30<span>pm</span></span>
										<span class="name">Patient name</span>
									</div>
									<div class="appt-meta">
										Chiropractic Standard
										<span class="duration">30 min</span>
									</div>
								</td>
							</tr>
							<tr>
								<td class="appt-item">
									<div class="appt-info">
										<span class="time">2:45<span>pm</span></span>
										<span class="name">Patient name</span>
									</div>
									<div class="appt-meta">
										Chiropractic Standard
										<span class="duration">15 min</span>
									</div>
								</td>
							</tr>
							
							<tr>
								<td class="appt-item">
									<div class="appt-info">
										<span class="time">3:00<span>pm</span></span>
										<span class="name">Jim Smith</span>
									</div>
									<div class="appt-meta">
										Chiropractic Standard
										<span class="duration">15 min</span>
									</div>
								</td>
								<td class="appt-item" rowspan="3">
									<div class="appt-info">
										<span class="time">3:00<span>pm</span></span>
										<span class="name">Bob Jones</span>
									</div>
									<div class="appt-meta">
										Chiropractic Standard
										<span class="duration">45 min</span>
									</div>
								</td>
							</tr>
							
							<tr>
								<td class="appt-item">
									<div class="appt-info">
										<span class="time">3:15<span>pm</span></span>
										<span class="name">Jim Smith</span>
									</div>
									<div class="appt-meta">
										Chiropractic Standard
										<span class="duration">15 min</span>
									</div>
								</td>
							</tr>
							
							<tr>
								<td class="appt-item" rowspan="2">
									<div class="appt-info">
										<span class="time">3:30<span>pm</span></span>
										<span class="name">Jim Smith</span>
									</div>
									<div class="appt-meta">
										Chiropractic Standard
										<span class="duration">30 min</span>
									</div>
								</td>
							</tr>
							
							<tr>
								<td class="appt-item">
									<div class="appt-info">
										<span class="time">3:30<span>pm</span></span>
										<span class="name">Jim Smith</span>
									</div>
									<div class="appt-meta">
										Chiropractic Standard
										<span class="duration">15 min</span>
									</div>
								</td>
							</tr>
							
						</table>
						<?php // end appointment list ?>
						
					</section>
					
				</div>
				
	
				
				<div class="col-lg-6 dash-col2">
				
					<?php // start communication list ?>
					<section class="content-panel dashboard-panel communications-list">
						<h2 class="panel-title">Latest communications</h2>
						
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
						
						<a href="communications.php" class="view-all">View all communications</a>
						
					</section>
					<?php // end communication list ?>
					
					
					
					
					<?php // start task list ?>
					<section class="content-panel dashboard-panel task-list">
						<h2 class="panel-title">Task list</h2>
						
						<h3>Today
							<a href="#" class="add-task" data-toggle="modal" data-target=".modal-task">Add task</a>
						</h3>
						
						<div class="table">
							<div class="table-row">
								<div class="table-cell">
									<a href="#" data-toggle="modal" data-target=".modal-task">This is a standard task created today.</a>
								</div>
								<div class="table-cell right-align">
									<input type="checkbox">
								</div>
							</div>
							<div class="table-row task-recurring">
								<div class="table-cell">
									<a href="#" data-toggle="modal" data-target=".modal-task">This is a recurring task that will appear each day it's been assigned to recur.</a>
								</div>
								<div class="table-cell right-align">
									<input type="checkbox">
								</div>
							</div>
						</div>
						
						<h3>Previous</h3>
						
						<div class="table">
							<div class="table-row">
								<div class="table-cell">
									<span class="task-date">Mar 20</span>
									<a href="#" data-toggle="modal" data-target=".modal-task">This is a standard task that was created prior to today but hasn't been checked off yet.</a>
								</div>
								<div class="table-cell right-align">
									<input type="checkbox">
								</div>
							</div>
							<div class="table-row task-recurring">
								<div class="table-cell">
									<span class="task-date">Mar 26</span>
									<a href="#" data-toggle="modal" data-target=".modal-task">This is a recurring task that was not checked off on a previous day it was due.</a>
								</div>
								<div class="table-cell right-align">
									<input type="checkbox">
								</div>
							</div>
						</div>
						
					</section>
					<?php // end task list ?>
					
					
					<?php // start recall list ?>
					<section class="content-panel dashboard-panel recall-list">
						<h2 class="panel-title">Patient recalls</h2>
						
						<h3>Today</h3>
						
						<div class="table patient-list invoice-list">
							<div class="table-row table-header">
								<div class="table-cell">Patient</div>
								<div class="table-cell">Phone</div>
								<div class="table-cell">Recall for</div>
							</div>
							
							<div class="table-row" onclick="location.href = '#';">
								<div class="table-cell">Bill Smith</div>
								<div class="table-cell">02 4856 4584</div>
								<div class="table-cell">Blood pressure checkup</div>
							</div>
							
							<div class="table-row" onclick="location.href = '#';">
								<div class="table-cell">Bill Smith</div>
								<div class="table-cell">02 4856 4584</div>
								<div class="table-cell">knee operation follow up</div>
							</div>
							
							<div class="table-row" onclick="location.href = '#';">
								<div class="table-cell">George Graham</div>
								<div class="table-cell">02 4856 4584</div>
								<div class="table-cell">Monthly massage</div>
							</div>
						</div>
						
						<h3>Previous</h3>
						
						<div class="table patient-list invoice-list">
							<div class="table-row table-header">
								<div class="table-cell">Patient</div>
								<div class="table-cell">Phone</div>
								<div class="table-cell">Recall for</div>
							</div>
							
							<div class="table-row" onclick="location.href = '#';">
								<div class="table-cell">Bill Smith</div>
								<div class="table-cell">02 4856 4584</div>
								<div class="table-cell">Blood pressure checkup</div>
							</div>
							
							<div class="table-row" onclick="location.href = '#';">
								<div class="table-cell">Bill Smith</div>
								<div class="table-cell">02 4856 4584</div>
								<div class="table-cell">knee operation follow up</div>
							</div>
							
							<div class="table-row" onclick="location.href = '#';">
								<div class="table-cell">George Graham</div>
								<div class="table-cell">02 4856 4584</div>
								<div class="table-cell">Monthly massage</div>
							</div>
						</div>
						
					</section>
					<?php // end recall list ?>

				
					<section class="content-panel dashboard-panel pending-invoices">
						<h2 class="panel-title">Pending invoices</h2>
						
						<div class="table patient-list invoice-list">
					
							<div class="table-row table-header">
								<div class="table-cell">Invoice</div>
								<div class="table-cell">Name</div>
								<div class="table-cell right-align">Amount</div>
								<div class="table-cell right-align">Status</div>
							</div>
							
							<div class="table-row" onclick="location.href = 'invoice-details.php';">
								<div class="table-cell">00056</div>
								<div class="table-cell">Bill Smith</div>
								<div class="table-cell right-align">$456.95</div>
								<div class="table-cell right-align cell-status overdue"><span>Overdue</span></div>
							</div>
							
							<div class="table-row" onclick="location.href = 'invoice-details.php';">
								<div class="table-cell">00056</div>
								<div class="table-cell">Bill Smith</div>
								<div class="table-cell right-align">$456.95</div>
								<div class="table-cell right-align cell-status pending"><span>Pending</span></div>
							</div>
							
							<div class="table-row" onclick="location.href = 'invoice-details.php';">
								<div class="table-cell">00056</div>
								<div class="table-cell">Bill Smith</div>
								<div class="table-cell right-align">$456.95</div>
								<div class="table-cell right-align cell-status pending"><span>Pending</span></div>
							</div>
						
						</div>
						
					</section>
					
					
					<section class="content-panel dashboard-panel draft-treatments">
						<h2 class="panel-title">Draft treatment notes</h2>
						
						<div class="table patient-list invoice-list">
							<div class="table-row table-header">
								<div class="table-cell">Patient</div>
								<div class="table-cell">Treatment note</div>
							</div>
							
							<div class="table-row" onclick="location.href = 'patient-treatment-notes.php';">
								<div class="table-cell">Bill Smith</div>
								<div class="table-cell">General checkup</div>
							</div>
							
							<div class="table-row" onclick="location.href = 'patient-treatment-notes.php';">
								<div class="table-cell">Bill Smith</div>
								<div class="table-cell">Body massage</div>
							</div>
							
							<div class="table-row" onclick="location.href = 'patient-treatment-notes.php';">
								<div class="table-cell">George Graham</div>
								<div class="table-cell">Blood pressure</div>
							</div>
						</div>
						
					</section>
					
				</div>
				
			
			</div>
			
<?php include 'includes/footer.php'; ?>