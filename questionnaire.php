<html>
<style>
	   body {
	   	background-image: url("refund.jpg");
		background-repeat: no-repeat;
  		background-attachment: fixed;
 	 	background-size: cover;
	   }
	   </style>
<body>

<h1>Fill out the following questionnaire to get the maximum possible deduction.</h1>

<form action="questionnaire.php" method="post">

What is your gender? (Male/Female): <input type="text" name="Gender" /><br><br>

Are you a wounded veteran? (Yes/No): <input type="text" name="WoundedVet" /><br><br>

Are you legally handicapped? (Yes/No): <input type="text" name="Handicapped" /><br><br>
 
Are you over 65 years of age? (Yes/No): <input type="text" name="Elderly" /><br><br>

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
 
if (isset($_POST["Gender"])){
$sql="UPDATE TaxPayers SET Gender='$_POST[Gender]', IsElderly='$_POST[Elderly]', IsHandicapped='$_POST[Handicapped]', IsWoundedVet='$_POST[WoundedVet]' WHERE TaxPayerID=201920392092039";

if (!$conn->query($sql))

  {

  die('Error: ' . mysqli_error($conn));

  }

echo "Updated taxpayer information.";

 
}
//mysql_close($con)

?>

		<section class = "block-of-text">
        <a href="mainPage.php"><input type = "submit" name = "reset" value = "Back"/></a>
      </section>

</body>

</html>