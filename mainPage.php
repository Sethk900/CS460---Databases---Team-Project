<?php

//========== Global Parameters ==========

$msgIndex = 0;

$targetDB = '';
$querytype = 'sql';
$inputQuery = '';

$tableName = '';
$selection = '';

$errorMsg = array('');
$successMsg = array('');
$defaultTables = ['information_schema', 'mysql', 'performance_schema', 'sakila', 'sys', 'world'];

$search_result = null;

//========== Database Connection ==========

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "semester_project"; //information_schema vs semester_project

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//========== Button Actions ==========

if(isset($_POST['submit']))
{
  # This variable should be set to the DB name
  $selection = "semester_project"; //$_POST['sqldblist'];

  if($selection !== 'Select Database') {$targetDB = $selection;}

  // Create connection
  $conn = new mysqli($servername, $username, $password, $targetDB);
  // Check connection
  if ($conn->connect_error) {
      die($conn->connect_error);
  }

  $search_result = null;

  $inputQuery = trim($_POST['inputQuery']);

  if (strpos(strtolower('###'.$inputQuery), 'create database'))
  {
      //updateMessages('error', 'Database creation not allowed on this platform.');
  }
  else if (strpos(strtolower('###'.$inputQuery), 'drop database')) // prefixing with ### 
  {
      //updateMessages('error', 'Database deletion not allowed on this platform.');
  }
  // else
  {
      if (0)//(mysqli_multi_query($conn, str_replace('<br>', '', $inputQuery)))
    {
        do
        {   
            //check first result
            if ($result = mysqli_store_result())
            {
                $search_result = $result; echo $inputQuery;
                //free the result and move on to next query
                mysqli_free_result($result);
            }
            else
            {
                updateMessages('error', $conn->error);
            }

            $success = mysqli_next_result($conn); echo $success;
            if (!$success)
            {
                updateMessages('error', $conn->error);
            }
            else 
            {
                $search_result = mysqli_store_result($conn);
            }
        }
        while ($success);
    }

      //$search_result = mysqli_store_result($conn);
      $search_result = $conn->query($inputQuery);
      if(is_bool($search_result) and $search_result)
      {
        $operation = substr($inputQuery, 0, strpos($inputQuery, ' '));
        updateMessages('success', ucfirst($operation).' operation successfully executed.');
      }
  }

  //retrieve column names to display in output table
  $col_names = '';
  if (strpos(strtolower('###'.substr(trim($inputQuery), 0, 7)), 'select'))
  {
      preg_match('/(?<=select )(.*)(?= from)/', $inputQuery, $regexResults);
      $col_names = $regexResults[0];
  }
  if (strpos(strtolower('###'.substr(trim($inputQuery), 0, 7)), 'show'))
  {
      $col_names = 'show';
  }

  if($col_names == '*' or strtolower($col_names) == 'show')
  {

      if (strtolower($col_names) == 'show'){$q = rtrim($inputQuery, ';');}
      else
      {
          $q = $inputQuery;
          if (strpos($q, 'limit')) # remove any occurence of 'limit'
          {
              $q = substr($q, 0, strpos($q, 'limit'));
          }
          
          $q = rtrim($q, ';').' limit 1';
      }

      $col_names = '';
      if ($result = mysqli_query($conn, $q))
      {
        // Get field information for all fields
          while ($fieldinfo = mysqli_fetch_field($result))
          {
              $col_names .= $fieldinfo->name.' ';
          }
          // Free result set
          mysqli_free_result($result);
      }
      else
      {
          updateMessages('error', $conn->error);
      }
  }

  $columns = explode(" ", trim($col_names));
}

//========== Functions ==========

function updateMessages($msgStatus, $msg)
{
    GLOBAL $msgIndex;
    GLOBAL $successMsg;
    GLOBAL $errorMsg;

    if($msg != '')
    {
        $msgIndex += 1;
        if ($msgStatus == 'success') {array_push($successMsg, $msgIndex.'. '.$msg);}
        else {array_push($errorMsg, $msgIndex.'. '.$msg);}
    }
}

?>

<!------------- HTML ------------->

<!DOCTYPE html>
<html>

    <head>
        <title>MyTaxes Portal</title>
       <!-- <link href = 'style.css' rel = 'stylesheet'> -->
	   <style>
	   body {
	   	background-image: url("MyTaxesPhoto.jpg");
		background-repeat: no-repeat;
  		background-attachment: fixed;
 	 	background-size: cover;
	   }
	   </style>
    </head>

    <body>
	<!-- <img src="MyTaxesPhoto.jpg" style="width:1500px;height:600px;"> -->
      <h2>MyTaxes Portal</h2>
	  <form method="post">
	<input type="submit" name="button1" class="button" value="Generate Tax Return"/>
	</form>
	  <?php
        if(array_key_exists('button1', $_POST)) { 
            button1(); 
        } 
        function button1() { 
		global $conn, $Line1, $Line2, $Line3, $Line4, $Line5, $Line6, $Line7, $Line8;
			//Update the taxpayer's tax statement
			$query = $conn->query("set @test = generateTaxReturnStatement(201920392092039)");
			//$query = $conn->query("SELECT * FROM TaxReturnStatement");
			$query = "SELECT * FROM TaxReturnStatement";
			$query = "SELECT * FROM CompleteTaxReturnStatement";
			if ($result = $conn->query($query)) {

   			 /* fetch associative array */
   			 while ($row = $result->fetch_assoc()) {
			 
			 $TaxPayerID = $row["TaxPayerID"];
			 $Name = $row["Name"];
			 $DOB = $row["DOB"];
			 $SSN = $row["SSN"];
			 $StreetAddress = $row["StreetAddress"];
			 $City = $row["City"];
			 $State = $row["State"];
			 $NumberOfDependents = $row["NumberOfDependents"];
			 $TotalIncome = $row["TotalIncome"];
			 $MaxDeduction = $row["MaxDeduction"];
			 $TaxableIncome = $row["TaxableIncome"];
			 $TaxesWitheld = $row["TaxesWitheld"];
			 $PaymentDue = $row["PaymentDue"];
			 $RefundDue = $row["RefundDue"];
			
  			  }

    		/* free result set */
   			 $result->free();
}

	
	//ob_start();
	


	
/* PDF Magic happens here  - Edits/Additions by Alex */


	/*	The base 1040 form is converted into a text-fillable file type '.fdf', and this is the base version to work from */
	$filename = getcwd() . "/unfilled.fdf";
	$outfilename = "filled.fdf";
		
	/* --- Helpful resource(s) to understand how this part works --- 
		- https://stackoverflow.com/a/3278051
		- The 'LEGEND.fdf' file is an edited version of 'unfilled.fdf', 
	with each fillable text slot written out to what it maps to in the 1040 form.
		- To check/uncheck a checkbox is more complicated, I will attempt to provide documentation.

		- Barebones approach at the moment, goal is to improve how this works, and have the entire form filled

		EDITED @ 4:15 AM - 11/16/2020
	*/


	$lines = file( $filename, FILE_IGNORE_NEW_LINES );

	$target_line1 = 561 - 1; 
	$target_line2 = 579 - 1;
	$target_line3 = 339 - 1;
	$target_line4 = 81 - 1;
	$target_line5 = 145 - 1;
	$target_line6 = 297 - 1;
	$target_line7 = 169 - 1;
	$target_line8 = 241 - 1;

	
	$lines[$target_line1] = "/V (".$Name.")";
	$lines[$target_line2] = "/V (".$SSN.")";
	$lines[$target_line3] = "/V (".$StreetAddress.")";
	$lines[$target_line4] = "/V (".$TotalIncome.")";
	$lines[$target_line5] = "/V (".$TaxableIncome.")";
	$lines[$target_line6] = "/V (".$TaxesWitheld.")";
	$lines[$target_line7] = "/V (".$PaymentDue.")";
	$lines[$target_line8] = "/V (".$RefundDue.")";
	


	file_put_contents( $outfilename , implode( "\n", $lines ) );


	/* Fills the 1040 form with our modified unfilled.fdf, outputs as 1040_filled
	Need to install pdtfk on the server, in the local path, to make this shell call.*/

	exec('pdftk 1040_Form.pdf fill_form filled.fdf output 1040_Filled.pdf');
	

	/* force downloads file */
	$file_url = getcwd() . "/1040_Filled.pdf";
	header('Content-Type: application/pdf');
	header("Content-Transfer-Encoding: Binary");
	header("Content-disposition: attachment; filename=1040_Filled.pdf");
	readfile($file_url);


/* End of current Edits/Additions by Alex */



	//ob_end_flush();	

            //echo "This is Button1 that is selected"; 

        } 
    ?> 

      <form action = "mainPage.php" method = "post" id = "options">

      

        <!-- INPUT SECTION -->

        <section class = "block-of-text">
          <fieldset>
            <legend>Input</legend>

                <textarea class = "FormElement" name = "inputQuery" id = "input" cols = "40" rows = "10" placeholder = "Type Query Here"><?php echo $inputQuery; ?></textarea>

                <br>

                <input type = "submit" id = "submit" name = "submit" value = "Submit" onclick = "return checkInput();">

          </fieldset>
        </section>
      </form>

      <!-- OUTPUT SECTION -->
      <form action = "mainPage.php" method = "post">

        <section class = "block-of-text">
          <fieldset>
            <legend>Output</legend>

              <?php $messages = array_merge($successMsg, $errorMsg); asort($messages); ?>
                <?php foreach ($messages as $msg):?>
                    <b><?php if ($msg !== '') { echo $msg.'<br>';} ?></b>
                <?php endforeach; ?>

                <br>

                <?php if($search_result and !is_bool($search_result)): ?>

                    <table>
                        <!-- table header -->
                        <tr>
                            <?php foreach ($columns as $col):?>
                                <th><?php echo trim($col, ",");?></th>
                            <?php endforeach; ?>
                        </tr>

                        <!-- populate table -->
                        <?php if ($search_result and $search_result != ''):?>
                            <?php while($row = mysqli_fetch_array($search_result)):?>
                                <tr>
                                    <?php foreach ($columns as $col):?>
                                        <td><?php echo $row[trim($col, ",")];?></td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endwhile;?>
                        <?php endif?>
                    </table>

                <?php endif?>
                
          </fieldset>
        </section>
      </form>

	  <form action="upload.php" method="post" enctype="multipart/form-data">
        Select File to Upload:
        <input type="file" name="file">
        <input type="submit" name="submit" value="Upload">
   	 </form>
	 
	  <section class = "block-of-text">
        <a href="mainPage.php"><input type = "submit" name = "reset" value = "Reset Page"/></a>
      </section>

      <?php $conn->close(); ?>

      <script src = "effects.js"></script>

    </body>
</html>
<?php
echo nl2br("\n");
echo nl2br("PDF files currently available:\n");
foreach(glob("*.pdf") as $file) {
    echo nl2br($file."\n");
	//nl2br(1);
}
?>
