<html>
<style>
	   body {
	   	background-image: url("Employer.jpg");
		background-repeat: no-repeat;
  		background-attachment: fixed;
 	 	background-size: cover;
	   }
	   </style>
<body>

<h1>Use this form to add a new employer.</h1>

<form action="addEmployer.php" method="post">

Employer ID: <input type="text" name="EmployerID" /><br><br>

Employer Name: <input type="text" name="EmployerName" /><br><br>

 

<input type="submit" />

</form>



<?php

$servername = "localhost";
$username = "root";
$password = "#SethsMySQLPassword900";
$dbname = "semester_project"; //information_schema vs semester_project

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!$conn)

  {

  die('Could not connect: ' . mysql_error());

  }

 

mysqli_select_db($conn, $dbname);
 
if (isset($_POST["EmployerID"])){
$sql="INSERT INTO EmployedBy (TaxPayerID, EmployerID, EmployerName)

VALUES

(201920392092039, '$_POST[EmployerID]','$_POST[EmployerName]')";

 

if (!$conn->query($sql))

  {

  die('Error: ' . mysql_error());

  }

echo "Employer added";

 
}
//mysql_close($con)

?>

		<section class = "block-of-text">
        <a href="mainPage.php"><input type = "submit" name = "reset" value = "Back"/></a>
      </section>

</body>

</html>