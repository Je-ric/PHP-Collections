<?php 
 

 //database constants
 define('DB_HOST', 'localhost');
 define('DB_USER', 'khan');
 define('DB_PASS', 'khan');
 define('DB_NAME', 'crud');
 
 //connecting to database and getting the connection object
 $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
 
 //Checking if any error occured while connecting
 if (mysqli_connect_errno()) {
 echo "Failed to connect to MySQL: " . mysqli_connect_error();
 die();
 }
 
 //creating a query
 $stmt = $conn->prepare("SELECT student_id, student_name, email_address, contact, gender, country FROM students;");
 
 //executing the query 
 $stmt->execute();
 
 //binding results to the query 
 $stmt->bind_result($student_id, $student_name, $email_address, $contact, $gender, $country);
 
 $students = array(); 
 
 //traversing through all the result 
 while($stmt->fetch()){
 $temp = array();
 $temp['student_id'] = $student_id; 
 $temp['student_name'] = $student_name; 
 $temp['email_address'] = $email_address; 
 $temp['contact'] = $contact; 
 $temp['gender'] = $gender; 
 $temp['country'] = $country; 
 array_push($students, $temp);
 }
 
 //displaying the result in json format 
 echo json_encode($students);