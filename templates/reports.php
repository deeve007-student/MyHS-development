<?php include 'includes/header.php'; ?>
			
				<h1 class="page-title title-reports">Reports</h1>
				
				<div class="subpage-titlebar">		
				
					<h2 class="subpage-title hidden-xs hidden-sm">Appointments</h2>
					
					<?php // start mobile sub-menu ?>
					<div class="btn-group visible-xs visible-sm">
						<h2 class="subpage-title" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Appointments <span class="caret"></span></h2>
						
						<?php include 'includes/reports-menu-mobile.php'; // mobile menu ?>
					</div>
					<?php // end mobile sub-menu ?>

				</div>
				
				<div class="row">
					
					<div class="col-md-8 col-lg-9">
						
						<section class="content-panel">

							Report form/output displays here...
						
						</section>
						
					</div>
					
					<?php include 'includes/reports-menu.php'; ?>
					
				</div><!-- .row -->
			
<?php include 'includes/footer.php'; ?>