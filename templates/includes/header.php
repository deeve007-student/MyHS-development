<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<title>MyHS</title>

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/css/jasny-bootstrap.min.css">
	
	<!-- Google fonts & font awesome -->
	<link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:400,700" rel="stylesheet">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	
	<!-- MyHS css -->
	<link rel="stylesheet" href="css/myhs.css">

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>

	<header class="header" role="banner">
		
		<div class="branding">
			<h1>MyHS</h1>
		</div>
		
		<div class="top-right">
		
			<div class="menu-utility">
				<a href="#" class="link-reports">Reports</a>
				<a href="#" class="link-settings">Settings</a>
				<a href="#" class="link-logout">Logout</a>
			</div>
			
			<div class="menu-toggle" data-toggle="offcanvas" data-target=".navmenu" data-canvas="body"></div>
			
		</div>
		
	</header><!-- .header -->
	
	<?php // Start mobile menu - This menu only appears below 768px, can it be removed from the HTML from 768px or only possible to hide via CSS? ?>
	<nav class="navmenu navmenu-default navmenu-fixed-left offcanvas" role="navigation">
		<div class="navmenu-brand">Menu</div>
		
		<ul class="menu-mobile nav navmenu-nav">
			<li class="menu-dashboard"><a href="index.php">Dashboard</a></li>
			<li class="menu-calendar"><a href="#">Calendar</a></li>
			<li class="menu-patients active"><a href="patients.php">Patients</a></li>
			<li class="menu-invoices"><a href="#">Invoices</a></li>
			<li class="menu-treatment-types"><a href="#">Treatment types</a></li>
			<li class="menu-products"><a href="#">Products</a></li>
			<li class="menu-messages"><a href="#">Messages</a></li>
			<li class="menu-reports"><a href="#" class="link-reports">Reports</a></li>
			<li class="menu-settings"><a href="#" class="link-settings">Settings</a></li>
			<li class="menu-logout"><a href="#" class="link-logout">Logout</a></li>
		</ul>
	</nav>
	<?php // End mobile menu ?>
	
	<div class="wrapper-mainmenu">
		
		<?php // Start tablet/desktop primary menu - This menu only appears at 768px and wider, can it be removed from the HTML below 768px or only possible to hide via CSS? ?>
		<ul class="menu-primary" role="navigation">
			<li class="menu-dashboard"><a href="index.php">Dashboard</a></li>
			<li class="menu-calendar"><a href="#">Calendar</a></li>
			<li class="menu-patients current"><a href="patients.php">Patients</a></li>
			<li class="menu-invoices"><a href="#">Invoices</a></li>
			<li class="menu-treatment-types"><a href="#">Treatment types</a></li>
			<li class="menu-products"><a href="#">Products</a></li>
			<li class="menu-messages"><a href="#">Messages</a></li>
		</ul>
		<?php // End tablet/desktop primary menu ?>
		
	</div>
	
	<div class="container main-content">
		
		<main class="row" role="main">