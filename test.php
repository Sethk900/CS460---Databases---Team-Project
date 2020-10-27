<?php


//========== Database Connection ==========

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


?>

<!------------- HTML ------------->

<!DOCTYPE html>
<html>

    <head>
        <title>MyTaxes Portal</title>
        <link href = 'style.css' rel = 'stylesheet'>
    </head>

    <body>
	<img src="MyTaxesPhoto.jpg" style="width:1500px;height:600px;">
      <h2>MyTaxes Portal</h2>
	  <?php
        if(array_key_exists('button1', $_POST)) { 
            button1(); 
        } 
        function button1() { 
		global $conn;
			//Update the taxpayer's tax statement
			$query = $conn->query("set @test = generateTaxReturnStatement(201920392092039)");
			$query = $conn->query("SELECT * FROM TaxReturnStatement");
			$query = "SELECT * FROM TaxReturnStatement";

			if ($result = $conn->query($query)) {

   			 /* fetch associative array */
   			 while ($row = $result->fetch_assoc()) {
       		 $field1name = $row["TaxPayerID"];
       		 $field2name = $row["AmountOwed"];
      		  $field3name = $row["RefundDue"];
			
			echo "TaxPayerID:", $field1name.'<br />';
			echo "Taxes Owed: ", $field2name.'<br />';
        	echo "Refund Due: ", $field3name.'<br />';
  			  }

    		/* free result set */
   			 $result->free();
}
            //echo "This is Button1 that is selected"; 

        } 
    ?> 
	<form method="post">
	<input type="submit" name="button1" class="button" value="Generate Tax Return"/>
	</form>
     

      <?php $conn->close(); ?>

      <script src = "effects.js"></script>

    </body>
</html>
