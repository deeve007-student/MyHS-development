<?php include 'includes/header.php'; ?>
				
				<h1 class="page-title title-invoices">Invoices</h1>
				
				<div class="row">
			
					<?php // invoice menu for small screen sizes ?>
					<div class="col-md-3 hidden-md hidden-lg">
						<section class="content-panel">
							<form>
								<select class="selectpicker" title="Invoice options" onChange="window.document.location.href=this.options[this.selectedIndex].value;">
									<option value="#">Edit</option>
									<option value="#">Delete</option>
								</select>
							</form>
						</section>					
					</div>
					<?php // end invoice menu for small screen sizes ?>
					
					<div class="col-md-9">
				
						<section class="content-panel">
							
							Invoice display here
								
						</section>
						
					</div>
					
					<?php // invoice menu for larger screen sizes ?>
					<div class="col-md-3 hidden-xs hidden-sm">
						<section class="content-panel">
							Desktop menu
						</section>					
					</div>
					<?php // end invoice menu for larger screen sizes ?>
			
			
<?php include 'includes/footer.php'; ?>