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

<form action="addWorkExpenses.php" method="post">

Description of Expense: <input type="text" name="Description" /><br><br>

Expense Amount: <input type="text" name="Amount" /><br><br>

 

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
 
if (isset($_POST["Amount"])){
$sql="INSERT INTO WorkExpenses (TaxPayerID, Description, Amount)

VALUES

(201920392092039, '$_POST[Description]','$_POST[Amount]')";

 

if (!$conn->query($sql))

  {

  die('Error: ' . mysqli_error($conn));

  }

echo "Expense added";

 
}
//mysql_close($con)

?>

		<section class = "block-of-text">
        <a href="mainPage.php"><input type = "submit" name = "reset" value = "Back"/></a>
      </section>

</body>

</html>