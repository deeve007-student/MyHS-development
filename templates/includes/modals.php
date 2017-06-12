
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
			[ Treatment type name ]
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
        <button type="button" class="btn btn-default">Edit</button>
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		<button type="button" class="btn btn-default">Reschedule</button>
		<button type="button" class="btn btn-default">Book again</button>
        <button type="button" class="btn btn-danger">Delete</button>
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

