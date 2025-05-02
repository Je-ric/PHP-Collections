<?php
include 'conn.php';
include 'session.php';
if(isset($_POST['add'])){

	$fname = $_POST['fname'];
	$lname = $_POST['lname'];

	$insert = "insert into info_tbl (firstName,lastName) values ('$fname','$lname')";
	
	if($conn->query($insert)== TRUE){

			echo "Sucessfully add data";
			header('location:maintenance.php');
	}else{

		echo "Ooppss cannot add data" . $conn->connect_error;
		header('location:maintenance.php');
	}
	$insert->close();
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
				<form action="result.php" method="get" ecntype="multipart/data-form">
						<table align="center">
							<tr>
								<td>Search: <input type="text" name="query"><input type="submit" value="Search" name="search"></td>
							</tr>
						</table>
				</form>
				<form action="maintenance.php" method="POST">
				<table align="center">
					<tr>
						<td>Fisrt Name: <input type="text" name="fname" value="" placeholder="Type Firstname here" required></td>
						</tr>
						<tr>
							<td>Last Name: <input type="text" name="lname" placeholder="Type Last Name here.." required></td>
						</tr>
						<tr>
							<td><input type="submit" name="add" value="Add"></td>
						</tr>
				</table>
			</form>
				<br>
				<table align="center" border="1" cellspacing="0" width="500">
					<tr>
					<th>First Name</th>
					<th>Last Name</th>
					<th>Action</th>
					</tr>
					<?php
					
					if(isset($_GET['search'])){
						$query = $_GET['query'];

						$sql = "select * from info_tbl where firstName like '%$query%' or lastName like '%$query%'";

						$result = $conn->query($sql);
						if($result->num_rows > 0){
							while($row1 = $result->fetch_array()){
								$fname = $row1['firstName'];
								$lname = $row1['lastName'];
		
						?>
						<tr>
							<td align="center"><?php echo $fname;?></td>
							<td align="center"><?php echo $lname;?></td>
							<td align="center"><a href="edit.php?infoID=<?php echo md5($row1['infoID']);?>">Edit
							</a>/<a href="delete.php?infoID=<?php echo md5($row1['infoID']);?>">Delete</a></td>
						</tr>
						<?php
					
							}

						}else{
							echo "<center>No records</center>";
						}
					}
				$conn->close();
				?>
				</table>
			</div>
		</div>
		</body>

</html>