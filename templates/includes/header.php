<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<title>MyHS</title>

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/css/jasny-bootstrap.min.css">
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">
	
	<!-- Google fonts & font awesome -->
	<link href="//fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet">
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	
	<!-- MyHS css -->
	<link rel="stylesheet" href="css/clinicspace.css">

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>

	<header class="header" role="banner">
		
		<div class="branding">
			<h1>Clinic Space</h1>
		</div>
		
		<div class="top-right">
		
			<div class="time-date">
				<div class="time"><?php echo date("H:i"); ?></div>
				<div class="date"><?php echo(date("D d M")); ?></div>
			</div>
			
			<div class="toggle-showhide01" data-target="#showhide01" data-toggle="collapse">Dr Jim Smith</div>
			
			<div id="showhide01" class="menu-utility collapse">
				<a href="#" class="link-settings">Settings</a>
				<a href="#" class="link-logout">Logout</a>
			</div>
			
		</div>
		
		<div class="menu-toggle" data-toggle="offcanvas" data-target=".navmenu" data-canvas="body"></div>
		
	</header><!-- .header -->
	
	<?php // Start mobile menu ?>
	<nav class="menu-mobile navmenu navmenu-default navmenu-fixed-left offcanvas-sm" role="navigation">
		<ul class="nav navmenu-nav">
			<li class="menu-dashboard"><a href="index.php">Dashboard</a></li>
			<li class="menu-calendar"><a href="calendar.php">Calendar</a></li>
			<li class="menu-patients active"><a href="patients.php">Patients</a></li>
			<li class="menu-invoices"><a href="invoices.php">Invoices</a></li>
			<li class="menu-treatments"><a href="treatments.php">Treatments</a></li>
			<li class="menu-products"><a href="products.php">Products</a></li>
			<li class="menu-messages"><a href="messages.php">Messages</a></li>
			<li class="menu-reports"><a href="#" class="link-reports">Reports</a></li>
		</ul>
	</nav>
	<?php // End mobile menu ?>
	
	<main class="main-content" role="main">