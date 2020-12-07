<html>
<style>
	   body {
	   	background-image: url("happyFamily.jpg");
		background-repeat: no-repeat;
  		background-attachment: fixed;
 	 	background-size: cover;
	   }
	   </style>
<body>

<h1>Use this form to add any of your dependents who are not reflected in the database.</h1>

<form action="addDependent.php" method="post">

Dependent's Name: <input type="text" name="DependentName" /><br><br>

Dependent's SSN: <input type="text" name="DependentSSN" /><br><br>

Relationship Type: <input type="text" name="RelationshipType" /><br><br>
 

<input type="submit" />

</form>



<?php

$servername = "localhost";
$username = "mytax2";
$password = "&1E-epifQh,8";
$dbname = "mytax2"; //information_schema vs semester_project

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
 
if (isset($_POST["DependentName"])){
$sql="INSERT INTO HasDependent (TaxPayerID, DependentName, DependentSSN, RelationshipType)

VALUES

(201920392092039, '$_POST[DependentName]','$_POST[DependentSSN]', '$_POST[RelationshipType]')";

 

if (!$conn->query($sql))

  {

  die('Error: ' . mysql_error());

  }

echo "1 dependent added";

 
}
//mysql_close($con)

?>

		<section class = "block-of-text">
        <a href="mainPage.php"><input type = "submit" name = "reset" value = "Back"/></a>
      </section>

</body>

</html>