<html>
<style>
	   body {
	   	background-image: url("medicine.jpg");
		background-repeat: no-repeat;
  		background-attachment: fixed;
 	 	background-size: cover;
	   }
	   </style>
<body>

<h1>If you spent money out of pocket on healthcare this year, note your expenses on this form to get a larger tax deduction.</h1>

<form action="addMedicalExpenses.php" method="post">

Description of Expense: <input type="text" name="Description" /><br><br>

Expense Amount: <input type="text" name="Amount" /><br><br>

 

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
 
if (isset($_POST["Amount"])){
$sql="INSERT INTO MedicalExpenses (TaxPayerID, Description, Amount)

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