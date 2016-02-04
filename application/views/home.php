<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en" data-ng-app="pollsApp">
<head>
	<meta charset="utf-8">
	<title>SENG365 Polls</title>
	<link rel="icon" type="image/x-icon" href="favicon.ico">
	<link rel="stylesheet" type="text/css" href="styles/polls-custom.css">
	
	<!-- Skeleton requires these -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="//fonts.googleapis.com/css?family=Raleway:400,300,600" 
		  rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="styles/normalize.css">
	<link rel="stylesheet" type="text/css" href="styles/skeleton.css">
	<!-- End Skeleton requirements -->
	
</head>
<body>
	<nav class="container"><ul>
		<li><a href="#/polls">Voter</a></li>
		<li><a href="#/admin">Admin</a></li>
		<li><a href="#/about">About</a></li>
	</ul></nav>

	<section class="container" data-ng-view data-autoscroll="true"></section>

	<footer class="container">&copy; 2015 Marcy Rattner</footer>
	
	<!-- Necessary for our Angular scripts to know where our site is located -->
	<script type="text/javascript">
	var baseUrl = '<?php echo base_url() ?>';
	</script>

	<script type="text/javascript" src="scripts/lib/angular1.3.15.min.js"></script>
	<script type="text/javascript" src="scripts/lib/angular-route.min.js"></script>
	<script type="text/javascript" src="scripts/app.js"></script>
	
	<!-- Controllers -->
	<script type="text/javascript" src="scripts/ctrl/PollList.js"></script>
	<script type="text/javascript" src="scripts/ctrl/PollVote.js"></script>
	<script type="text/javascript" src="scripts/ctrl/PollAdmin.js"></script>
	<script type="text/javascript" src="scripts/ctrl/PollCreate.js"></script>
</body>
</html>
