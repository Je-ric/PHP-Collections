<?php
include 'session.php';
$username = $_SESSION['username'];
$userID = $_SESSION['userID'];
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="mycss.css">
		<title>
			This is Sample
		</title>
		</head>
	<body>
		<div id="body">
			<div id="menu">
			<ul>
				<li><a href="home.php">Home</a></li>
				<li><a href="maintenance.php">Maintenance</a></li>
				<li><a href="logout.php">Logout</a></li>
			</ul>
			</div>

			<div id="content">
			
			<h1>Welcome <?php echo $username;?></h1>
				<h3 style="color:blue;"><?php echo "Today is".  "  ". date("M/d/y") . " - " . date("l") . ""?>&nbsp; <?php echo "" . date("h:i:sa") . ""?></h3>
				
				<hr style="border:1px solid red;" >
				
				

			</div>

		
		</div>
		</body>

</html>