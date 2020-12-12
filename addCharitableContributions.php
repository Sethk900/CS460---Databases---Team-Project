<html>
<style>
	   body {
	   	background-image: url("charity.jpg");
		background-repeat: no-repeat;
  		background-attachment: fixed;
 	 	background-size: cover;
	   }
	   </style>
<body>

<h1>If you gave money to a charity this year, note your contributions here to get a bigger tax deduction.</h1>

<form action="addCharitableContributions.php" method="post">

Description of Charity: <input type="text" name="Description" /><br><br>

Donation Amount: <input type="text" name="Amount" /><br><br>

 

<input type="submit" />

</form>



<?php

$servername = ""; //Add your credentials here
$username = "";
$password = "";
$dbname = "";

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
$sql="INSERT INTO CharitableContributions (TaxPayerID, Description, Amount)

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
