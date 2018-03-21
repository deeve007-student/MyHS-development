
<!-- Bootstrap modal examples for various common functions across site -->


<!-- Confirm deletion modal -->
<div class="modal-delete modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Confirm deletion</h4>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete [item being deleted]?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger">Delete</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- end confirm deletion modal -->


<!-- View appointment modal -->
<div class="modal-appointment-view modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">
			Treatment type name (code)
		</h4>
		<div class="appt-time">
			Wednesday, 14 June 2017 <span>at</span> 1:00pm <span>for</span> 30 minutes
		</div>
      </div>
      <div class="modal-body">
		
		<div class="appt-info">
			<strong>Mary Jones</strong><br>
			0412 375 809<br>
			<a href="mailto:mary@email.com">mary@email.com</a>
			
			<div class="appt-next">
				<strong>Next appointment:</strong><br>
				<a href="#">Thu, 25 May 2017</a>
			</div>
			
			<div class="appt-alert">This is a patient alert, it will show information added to patient profile.</div>
			<div class="appt-alert">This is another patient alert, if they have multiple alerts.</div>
			
			<div class="appt-note">
				This is freeform text that can be entered against any appointment for some additional information.
			</div>
		</div>
		
		<div class="appt-actions">
		
			<button type="button" class="btn btn-default">Arrived</button>
			<button type="button" class="btn btn-default">Create invoice</button>
			<button type="button" class="btn btn-default">Create treatement note</button>
		
		</div>
		
      </div>
      <div class="modal-footer">
        <a href="#" class="delete-apt">Delete</a>
		<button type="button" class="btn btn-default">Edit</button>
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		<button type="button" class="btn btn-default">Reschedule</button>
		<button type="button" class="btn btn-default">Book again</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End view appointment modal  -->


<!-- Create appointment modal -->
<div class="modal-appointment modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title">Create appointment</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal">
			
			<div class="form-group">
				<label class="col-sm-3 control-label">Treatment</label>
				<div class="col-sm-9">
					<select class="form-control">
						<option></option>
						<option>Treatment one</option>
						<option>Treatment twp</option>
						<option>Treatment three</option>
						<option>Treatment four</option>
					</select>
				</div>
			</div>
			
			
			<?php // If user wants to enter new patient, this section should disappear ?>
			<div class="form-group">
				<label class="col-sm-3 control-label">Patient</label>
				<div class="col-sm-9">
					<input type="text" class="form-control" placeholder="Start typing to search patients">
					<p class="text-muted small add-new-patient link-like" data-toggle="collapse" data-target=".patient-new">Add new patient</p>
				</div>
			</div>
			<?php // If user wants to enter new patient, the above section should disappear ?>
			

			<?php // Only appears if user has selected to add new patient at same time as creating appointment ?>
			<div class="patient-new collapse"><div class="patient-new-inner">
				
				<h3>Add new patient</h3>
				
				<div class="form-group">
					<label class="col-sm-3 control-label">First name</label>
					<div class="col-sm-9">
						<input type="text" class="form-control">
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label">Last name</label>
					<div class="col-sm-9">
						<input type="text" class="form-control">
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label">Mobile</label>
					<div class="col-sm-9">
						<input type="text" class="form-control">
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label">Email</label>
					<div class="col-sm-9">
						<input type="email" class="form-control">
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label">Date of birth</label>
					<div class="col-sm-9">
						<div class='input-group date' id='datepicker1'>
							<input type='text' class="form-control" />
							<span class="input-group-addon">
								<span class="glyphicon glyphicon-calendar"></span>
							</span>
						</div>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label">Referrer</label>
					<div class="col-sm-9">
						<input type="text" class="form-control">
						<p class="text-muted small">To enter name of patient in system start typing their name</p>
					</div>
				</div>
				
				<div class="form-group">
					<div class="text-muted small link-like col-sm-offset-3 col-sm-9" data-toggle="collapse" data-target=".patient-new">Choose existing patient</div>
				</div>
				
			</div></div>
			<?php // End new patient part of form ?>
			
			<div class="form-group">
				<label class="col-sm-3 control-label">Date</label>
				<div class="col-sm-9">
					<div class='input-group date' id='datepicker1'>
						<input type='text' class="form-control" />
						<span class="input-group-addon">
							<span class="glyphicon glyphicon-calendar"></span>
						</span>
					</div>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label">Time</label>
				<div class="col-sm-9">[ Start time selector] to [ end time selector ]</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label">Note</label>
				<div class="col-sm-9"><textarea class="form-control"></textarea></div>
			</div>
			
		</form>
      </div>
      <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success">Create appointment</button>
		<a href="#" class="pull-left switch-modals">Create unavailable block</a>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End create appointment modal -->

<!-- Create unavailable block modal -->
<div class="modal-unavailable modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title">Create unavailable block</h4>
      </div>
      <div class="modal-body">
        
		<p>Create a block of time when you will be unavailable for appointments.</p>
		
		<form class="form-horizontal">
			
			<div class="form-group">
				<label class="col-sm-3 control-label">Date</label>
				<div class="col-sm-9">
					<div class='input-group date' id='datepicker1'>
						<input type='text' class="form-control" />
						<span class="input-group-addon">
							<span class="glyphicon glyphicon-calendar"></span>
						</span>
					</div>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label">Time</label>
				<div class="col-sm-9">[ Start time selector] to [ end time selector ]</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label">Reason</label>
				<div class="col-sm-9">
					<input type="text" class="form-control">
				</div>
			</div>
			
		</form>
      </div>
      <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success">Create unavailable block</button>
		<a href="#" class="pull-left switch-modals">Create appointment</a>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End create unavailable block modal -->


<!-- Add invoice payment modal -->
<div class="modal-payment modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title">Add payment</h4>
      </div>
      <div class="modal-body">
		
		<form class="form-horizontal">
			
			<div class="form-group">
				<label class="col-sm-3 control-label">Amount</label>
				<div class="col-sm-9">
					<input type="text" class="form-control" value="55.00">
				</div>
			</div>
			
			<div class="form-group">
                <label class="col-sm-3 control-label">Date</label>
				<div class="col-sm-9">
					<div class='input-group date' id='datetimepicker1'>
						<input type='text' class="form-control" />
						<span class="input-group-addon">
							<span class="glyphicon glyphicon-calendar"></span>
						</span>
					</div>
				</div>
            </div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label">Payment method</label>
				<div class="col-sm-9">
					<select class="form-control">
						<option></option>
						<option>Credit card</option>
						<option>Cash</option>
						<option>Cheque</option>
						<option>Bank transfer</option>
						<option>Hicaps</option>
					</select>
				</div>
			</div>
			
		</form>
      </div>
      <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success">Add payment</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End invoice payment modal -->



<!-- Add/edit task modal -->
<div class="modal-task modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title">Add/edit task</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal">
			
			<div class="form-group">
				<label class="col-sm-2 control-label">Task</label>
				<div class="col-sm-10">
					<input type="text" class="form-control">
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-2 control-label">Task type</label>
				<div class="col-sm-10">
					<select class="form-control">
						<option>Standard</option>
						<option>Recurring</option>
					</select>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-2 control-label">Date</label>
				<div class="col-sm-10">
					<div class='input-group date' id='datepicker1'>
						<input type='text' class="form-control" />
						<span class="input-group-addon">
							<span class="glyphicon glyphicon-calendar"></span>
						</span>
					</div>
					
					<div class="text-muted small">Tech note: This field disappears if "Recurring" chosen above</div>
				</div>
			</div>
			
			<p class="text-muted small add-new-patient link-like" data-toggle="collapse" data-target=".patient-new">Recurring (this link just demo, above dropdown selects recurring task)</p>
			

			<?php // Only appears if user has selected recurring task ?>
			<div class="patient-new collapse"><div class="patient-new-inner">
				
				<h3>Add recurring task</h3>
				
				<div class="form-group">
					<label class="col-sm-3 control-label">Repeats</label>
					<div class="col-sm-9">
						<select class="form-control">
							<option>Weekly</option>
							<option>Monthly</option>
							<option>Yearly</option>
						</select>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label">Repeat every</label>
					<div class="col-sm-9">
						<select class="form-control">
							<option>1 week</option>
							<option>2 weeks</option>
							<option>3 weeks</option>
							<option>4 weeks</option>
							<option>5 weeks</option>
							<option>6 weeks</option>
							<option>7 weeks</option>
							<option>8 weeks</option>
							<option>9 weeks</option>
							<option>10 weeks</option>
						</select>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label">Repeat on</label>
					<div class="col-sm-9">
						<label class="checkbox-inline">
						  <input type="checkbox" id="inlineCheckbox1" value="option1"> M
						</label>
						<label class="checkbox-inline">
						  <input type="checkbox" id="inlineCheckbox2" value="option2"> T
						</label>
						<label class="checkbox-inline">
						  <input type="checkbox" id="inlineCheckbox3" value="option3"> W
						</label>
						<label class="checkbox-inline">
						  <input type="checkbox" id="inlineCheckbox1" value="option1"> T
						</label>
						<label class="checkbox-inline">
						  <input type="checkbox" id="inlineCheckbox2" value="option2"> F
						</label>
						<label class="checkbox-inline">
						  <input type="checkbox" id="inlineCheckbox3" value="option3"> S
						</label>
						<label class="checkbox-inline">
						  <input type="checkbox" id="inlineCheckbox3" value="option3"> S
						</label>
						
						<div class="text-muted small">Tech note: This field only appears if "weekly" chosen above</div>
						
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label">Repeat by</label>
					<div class="col-sm-9">
						
						<label class="radio-inline">
						  <input type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1"> Day of month
						</label>
						<label class="radio-inline">
						  <input type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2"> Day of week
						</label>

						<div class="text-muted small">Tech note: This field only appears if "monthly" chosen above</div>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label">Start date</label>
					<div class="col-sm-9">
						<div class='input-group date' id='datepicker1'>
							<input type='text' class="form-control" />
							<span class="input-group-addon">
								<span class="glyphicon glyphicon-calendar"></span>
							</span>
						</div>
					</div>
				</div>
				
			</div></div>
			<?php // End recurring task part of form ?>
			
		</form>
      </div>
      <div class="modal-footer">
		<a href="#" class="delete-apt">Delete</a>
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success">Save</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End add/edit task modal -->


<!-- Add/edit goal modal -->
<div class="modal-goal modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title">Add/edit goal</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal">
			
			<div class="form-group">
				<label class="col-sm-2 control-label">Goal</label>
				<div class="col-sm-10">
					<input type="text" class="form-control">
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-2 control-label">Action step</label>
				<div class="col-sm-10">
					<textarea class="form-control" rows="5"></textarea>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-2 control-label">When</label>
				<div class="col-sm-10">
					<select class="form-control">
						<option>This month</option>
						<option>This quarter</option>
						<option>This year</option>
					</select>
				</div>
			</div>
			
		</form>
      </div>
      <div class="modal-footer">
		<a href="#" class="delete-apt">Delete</a>
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success">Save</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End add/edit goal modal -->


<!-- Create or edit patient recall modal -->
<div class="modal-recall modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title">Create/edit patient recall</h4>
      </div>
      <div class="modal-body">
		
		<form class="form-horizontal">
			
			<div class="form-group">
				<label class="col-sm-3 control-label">Recall type</label>
				<div class="col-sm-9">
					<select class="form-control">
						<option>Email</option>
						<option>SMS</option>
						<option>Email & SMS</option>
						<option>Manual</option>
					</select>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label">Date</label>
				<div class="col-sm-9">
					<div class='input-group date' id='datepicker1'>
						<input type='text' class="form-control" />
						<span class="input-group-addon">
							<span class="glyphicon glyphicon-calendar"></span>
						</span>
					</div>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label">Recall for</label>
				<div class="col-sm-9">
					<select class="form-control">
						<option></option>
						<option>FTKA</option>
						<option>Did not reschedule </option>
						<option>Care Call</option>
						<option>Cancelled</option>
						<option>Check notes</option>
						<option>Asked us to call to reschedule</option>
						<option>Due for next appointment</option> 
					</select>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label">Other recall</label>
				<div class="col-sm-9">
					<input type="text" class="form-control">
				</div>
			</div>
			
		</form>
      </div>
      <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success">Save</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End create or edit patient recall modal -->


<!-- Make recall - phone -->
<div class="modal-recall-phone modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title">Make recall - Phone</h4>
      </div>
      <div class="modal-body">
		
		<strong>Mary Jones</strong><br>
		0412 375 809 (preferred)<br>
		02 9456 3457<br>		
		<br>
		
		<div class="form-group">
			<label>Notes:</label>
			<textarea class="form-control"></textarea>
		</div>
			
      </div>
      <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success">Phone call made</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End make recall - phone -->


<!-- Make recall - email -->
<div class="modal-recall-email modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title">Make recall - Email</h4>
      </div>
      <div class="modal-body">
		
		<strong>Mary Jones</strong><br>
		mary@email.com<br>		
		<br>
		
		<div class="form-group">
			<label>Email subject:</label>
			<input type="text" class="form-control" value="Auto populated from settings section, but can be edited before sending">
		</div>
		
		<div class="form-group">
			<label>Email message:</label>
			<textarea class="form-control" rows="5">Auto populated from settings section, but can be edited before sending</textarea>
		</div>
			
      </div>
      <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success">Send email</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End make recall - email -->


<!-- Make recall - SMS -->
<div class="modal-recall-sms modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title">Make recall - SMS</h4>
      </div>
      <div class="modal-body">
		
		<strong>Mary Jones</strong><br>
		0412 346 794<br>		
		<br>
		
		<div class="form-group">
			<label>SMS message:</label>
			<textarea class="form-control" rows="2">Auto populated from settings section, but can be edited before sending</textarea>
		</div>
			
      </div>
      <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success">Send SMS</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End make recall - SMS -->


<!-- Make recall - SMS & email -->
<div class="modal-recall-smsemail modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title">Make recall - SMS &amp; email</h4>
      </div>
      <div class="modal-body">
		
		<strong>Mary Jones</strong><br>
		0412 346 794<br>
		mary@email.com<br>
		<br>
		
		<div class="form-group">
			<label>SMS message:</label>
			<textarea class="form-control" rows="2">Auto populated from settings section, but can be edited before sending</textarea>
		</div>
		
		<div class="form-group">
			<label>Email subject:</label>
			<input type="text" class="form-control" value="Auto populated from settings section, but can be edited before sending">
		</div>
		
		<div class="form-group">
			<label>Email message:</label>
			<textarea class="form-control" rows="5">Auto populated from settings section, but can be edited before sending</textarea>
		</div>
			
      </div>
      <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success">Send SMS &amp; email</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End make recall - SMS & email -->


<!-- Export treatment notes -->
<div class="modal-treatment-notes modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title">Export treatment notes</h4>
      </div>
      <div class="modal-body">
		
		<div class="filter-list">
			<select class="form-control">
				<option value="recent5" selected="selected">Most recent 5</option>
				<option value="month">Current month (February 2018)</option>
				<option value="prevMonth">Prev month (January 2018)</option>
				<option value="quarter">Current quarter (January 2018 - March 2018)</option>
				<option value="prevQuarter">Prev quarter (October 2017 - December 2017)</option>
				<option value="nextQuarter">Next quarter (April 2018 - June 2018)</option>
				<option value="year">Current year (January 2018 - December 2018)</option>
				<option value="range">Choose range</option>
			</select>
		</div>
		
		<table class="table">
			<tr>
			  <td><input type="checkbox" checked></td>
			  <td>25 Feb 2017 &middot; One hour massage &middot; Dr Bill Smith</td>
			</tr>
			<tr>
			  <td><input type="checkbox" checked></td>
			  <td>25 Jan 2017 &middot; One hour massage &middot; Dr Bill Smith</td>
			</tr>
			<tr>
			  <td><input type="checkbox" checked></td>
			  <td>5 Jan 2017 &middot; One hour massage &middot; Dr Bill Smith</td>
			</tr>
			<tr>
			  <td><input type="checkbox" checked></td>
			  <td>25 Jan 2017 &middot; One hour massage &middot; Dr Bill Smith</td>
			</tr>
			<tr>
			  <td><input type="checkbox" checked></td>
			  <td>5 Jan 2017 &middot; One hour massage &middot; Dr Bill Smith</td>
			</tr>
		</table>
			
      </div>
      <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success"><i class="glyphicon glyphicon glyphicon glyphicon-file"></i> Export PDF</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End export treatment notes -->


<!-- Invoice refund -->
<div class="modal-refund-invoice modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title">Invoice refund</h4>
      </div>
      <div class="modal-body">
		
		<table class="table"><?php // payments table ?>
			<tr>
				<th>Payment method</th>
				<th>Paid</th>
				<th>Refund amount</th>
			</tr>
			
			<tr><?php // repeatable row ?>
				<td>Hicaps</td>
				<td>
					<div class="form-group">
						<div><input type="text" class="form-control" readonly value="80.00"></div>
					</div>
				</td>
				<td>
					<div class="form-group">
						<div><input type="text" class="form-control" value="45.00"></div>
					</div>
				</td>
			</tr><?php // end repeatable row ?>
			
			<tr><?php // repeatable row ?>
				<td>Cash</td>
				<td>
					<?php // example of additional refund method added from below ?>
				</td>
				<td>
					<div class="form-group">
						<div><input type="text" class="form-control" value="45.00"></div>
					</div>
				</td>
			</tr><?php // end repeatable row ?>
			
			<tr class="total-row">
				<td colspan="2">Payment refund total:</td>
				<td>
					<div class="form-group">
						<div><input type="text" class="form-control" readonly value="45.00"></div>
					</div>
				</td>
			</tr>
			
			
			<tr><?php // repeatable row ?>
				<td>Add refund method</td>
				<td>
					<div class="form-group">
						<select class="form-control refund-methods">
							<option>Cash</option>
							<option>Bank transfer</option>
							<option>Cheque</option>
						</select>
					</div>
				</td>
				<td>
					<div class="form-group">
						+
					</div>
				</td>
			</tr><?php // end repeatable row ?>
			
			
		</table><?php // end payments table ?>
		
			
      </div>
      <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success">Issue refund</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End invoice refund -->


<!-- None invoice refund -->
<div class="modal-noninvoice-refund modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title">None invoice refund</h4>
      </div>
      <div class="modal-body">
		
		<div class="form-group">
			<label>Refund reason:</label>
			<input type="text" class="form-control">
		</div>
		
		<div class="form-group">
			<label>Amount:</label>
			<input type="text" class="form-control">
		</div>
		
		<div class="form-group">
			<label>Refund method:</label>
			<select class="form-control">
				<option></option>
				<option>Cash</option>
				<option>Credit card</option>
				<option>HECS</option>
			</select>
		</div>
			
      </div>
      <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success">Process refund</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End none invoice refund -->


<!-- Pack refund -->
<div class="modal-refund-pack modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title">Treatment pack refund</h4>
      </div>
      <div class="modal-body">
		
		<table class="table"><?php // payments table ?>
			<tr>
				<th>Treatment name</th>
				<th>Number</th>
				<th>Refund amount</th>
			</tr>
			
			<tr>
				<td>90min Massage (MS455)</td>
				<td>
					<div class="form-group">
						<select class="form-control">
							<option>5</option>
							<option>4</option>
							<option>3</option>
							<option>2</option>
							<option>1</option>
						</select>
					</div>
				</td>
				<td>
					<div class="form-group">
						<div><input type="text" class="form-control" readonly value="45.00"></div>
					</div>
				</td>
			</tr>

		</table><?php // end payments table ?>
		
			
      </div>
      <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success">Issue refund</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End pack refund -->


<!-- Add communication event -->
<div class="modal-event modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title">Add communications event</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal">
			
			<div class="form-group">
				<label class="col-sm-3 control-label">Patient</label>
				<div class="col-sm-9">
					<input type="text" class="form-control" placeholder="Start typing to search patients">
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label">Description</label>
				<div class="col-sm-9">
					<input type="text" class="form-control">
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label">Event date</label>
				<div class="col-sm-9">
					[ Date selector - current date default ]
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label">Colour</label>
				<div class="col-sm-9">
					[ Colour picker - default #58585c ]
				</div>
			</div>
			
		</form>
      </div>
      <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success">Add event</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End communication event -->
