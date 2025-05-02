<?php
include 'conn.php';
include 'session.php';

$id = $_GET['infoID'];
$view = "SELECT * from info_tbl where md5(infoID) = '$id'";
$result = $conn->query($view);
$row = $result->fetch_assoc();

if(isset($_POST['update'])){

	$ID = $_GET['infoID'];

	$fn = $_POST['fname'];
	$ln = $_POST['lname'];

	$insert = "UPDATE info_tbl set firstName = '$fn', lastName = '$ln' where md5(infoID) = '$ID'";
	
	if($conn->query($insert)== TRUE){

			echo "Sucessfully update data";
			header('location:maintenance.php');			
	}else{

		echo "Ooppss cannot add data" . $conn->error;
		header('location:maintenance.php');
	}
	$conn->close();
}
?>

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="mycss.css">
		<title>
			This is Sample
		</title>
		</head>
	<body bgcolor="green">
		<div id="body">
			<div id="menu">
			<ul>
				<li><a href="home.php">Home</a></li>
				<li><a href="maintenance.php">Maintenance</a></li>
				<li><a href="logout.php">Logout</a></li>
			</ul>
			</div>
			<div id="content">
				<form action="" method="POST">
				<table align="center">
					<tr>
						<td>Fisrt Name: <input type="text" name="fname" value="<?php echo $row['firstName'];?>" placeholder="Type Firstname here" required></td>
						</tr>
						<tr>
							<td>Last Name: <input type="text" name="lname"  value="<?php echo $row['lastName'];?>" placeholder="Type Last Name here.." required></td>
						</tr>
						<tr>
							<td><input type="submit" name="update" value="Update"></td>
						</tr>
				</table>
			</form>
				<br>
			
			</div>
		</div>
		</body>

</html>