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
$password = "#SethsMySQLPassword900";
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
        <link href = 'style.css' rel = 'stylesheet'>
    </head>

    <body>
	 <img src="MyTaxesPhoto.jpg" style="width:1500px;height:600px;"> 
      <h2>MyTaxes Portal</h2>
	  <form method="post">
	<input type="submit" name="button1" class="button" value="Generate Tax Return"/>
	</form>
	  <?php
        if(array_key_exists('button1', $_POST)) { 
            button1(); 
        } 
        function button1() { 
		global $conn;
			//Update the taxpayer's tax statement
			$query = $conn->query("set @test = generateTaxReturnStatement(201920392092039)");
			//$query = $conn->query("SELECT * FROM TaxReturnStatement");
			$query = "SELECT * FROM TaxReturnStatement";
			$query = "SELECT * FROM CompleteTaxReturnStatement";
			if ($result = $conn->query($query)) {

   			 /* fetch associative array */
   			 while ($row = $result->fetch_assoc()) {
			 /*
       		 $field1name = $row["TaxPayerID"];
       		 $field2name = $row["AmountOwed"];
      		 $field3name = $row["RefundDue"];
			 */
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
			
			$Line1 = "TaxPayerID: ".$TaxPayerID."\nName: ".$Name."\nSSN: ".$SSN;
			$Line2 = "Address: ".$StreetAddress.", ".$City.", ".$State;
			$Line3 = "Total Income: $".$TotalIncome;
			$Line4 = "Maximum Deduction: $".$MaxDeduction;
			$Line5 = "Taxable Income: $".$TaxableIncome;
			$Line6 = "Total Taxes Witheld: $".$TaxesWitheld;
			$Line7 = "Tax Payment Due: $".$PaymentDue;
			$Line8 = "Tax Refund Due: $".$RefundDue;
			echo "TaxPayerID:", $TaxPayerID.'<br />';
			echo "TaxPayerID:", $TaxPayerID.'<br />';
			echo "Taxes Owed: ", $PaymentDue.'<br />';
        	echo "Refund Due: ", $RefundDue.'<br />';
  			  }

    		/* free result set */
   			 $result->free();
}

	ob_start();
	require('./fpdf.php');
	require('./fpdm.php');
	/*
	$fields = array( //for checkboxes, initialize with any string for it to be checked. Otherwise, initialize with false.
	'singleFilingStatus' => 'yes',
	'marriedFilingStatus' => false,
	'marriedFilingSeperately' => false,
	'headofHousehold' => 'yes',
	'qualifyingWidower' => 'yes',
	'SSN' => '782838271',
	'SSN1' => '827837898',
	'spouseFirstName' => '99',
	'spouseLastName' => '99',
	'isBlind' => 'yes',
	'isElderly' => 'yes',
	'isDependent' => false,
	'spouseSSN' =>'99',
	'spouseIsElderly' => false,
	'spouseIsDependent' => false,
	'spouseItemizesSeperately' => false,
	'spouseIsBlind' => false,
	'homeAddress' => '92038 farthington lane',
	'aptNo' => '6-308',
	'healthCareCovered' => 'yes',
	'presidentialCampaign' => false,
	'spousePresidentialCampaign' => false,
	'overFourDependents' => false,
	'cityAndZip' => 'Moscow ID 83843',
	'dependent1Name' => 'example dependent',
	'dependent1SSN' => '99',
	'dependent1TaxCredit' => false,
	'dependent1OtherCredit' => false,
	'dependent2Name' => 'example dependent',
	'dependent2SSN' => '99',
	'dependent2TaxCredit' => false,
	'dependent2OtherCredit' => false,
	'dependent3Name' => 'example dependent',
	'dependent3SSN' => '99',
	'dependent3TaxCredit' => false,
	'dependent3OtherCredit' => false,
	'dependent4Name' => 'example dependent',
	'dependent4SSN' => '99',
	'dependent4TaxCredit' => false,
	'dependent4OtherCredit' => false,
	'dependent1Relationship' => 'son',
	'dependent2Relationship' => 'son',
	'dependent3Relationship' => 'son',
	'dependent4Relationship' => 'son',
	'occupation' => 'carpenter',
	'spouseOccupation' => '99',
	'IPPin' => '99',
	'spouseIPPin' => '99',
	'preparerName' => 'JoeVandal',
	'PTIN' => '99',
	'EIN' => '99',
	'preparerPhone' => '911',
	'preparerFirmName' => 'Univ of Idaho',
	'preparerFirmAddress' => 'Moscow, ID',
	'preparerIsThirdParty' => 'yes',
	'preparerIsSelfEmployed' => false,
	'TaxExemptDollars' => '99',
	'taxExemptCents' => '99',
	'wagesDollars' => '99',
	'wagesCents' => '99',
	'interestDollars' => '99',
	'interestCents' => '99',
	'qualifiedDividendsDollars' => '99',
	'qualifiedDividendsCents' => '99',
	'ordinaryDividendsDollars' => '99',
	'ordinaryDividendsCents' => '99',
	'retirementDollars' => '1',
	'retirementCents' => '99',
	'retirementTaxDollars' => '1',
	'retirementTaxCents' => '99',
	'socialSecurityDollars' => '99',
	'socialSecurityCents' => '99',
	'socialSecurityTaxDollars' => '77',
	'socialSecurityTaxCents' => '99',
	'totalIncome' => '40000',
	'totalIncomeDollars' => '40000',
	'totalIncomeCents' => '99',
	'grossIncomeDollars' => '99',
	'grossIncomeCents' => '40',
	'standardDeductionDollars' => '99',
	'standardDeductionCents' => '40',
	'businessIncomeDeductionDollars' => '40',
	'businessIncomeDeductionCents' => '77',
	'dependentCredit' => '99',
	'otherTaxes' => '500',
	'fedTaxesWitheld' => '7000',
	'totalTaxesOwed' => '50',
	'totalTaxesPaid' => '7000',
	'amountOverpaid' => '6500',
	'refundDue' => '6500',
	'bankRoutingNumber' => '1234567',
	'isCheckingAccount' => 'yes',
	'isSavingsAccount' => false,
	'bankAccountNumber' => '1234567898',
	'refundAppliedToTax' => '50',
	'taxesOwed' => 'yes',
	'estimatedTaxPenalty' => 'yes',
	'taxableIncome' => '35000',
    'firstName'    => 'Seth',
    'lastName' => 'King'
);


$fields2 = array(
    'name'    => 'My name',
    'address' => 'My address',
    'city'    => 'My city',
    'phone'   => 'My phone number'
);
ob_start();
//$pdf = new FPDM('1040_Form_formatted.pdf');
$pdf = new FPDM('template.pdf');
//$pdf->useCheckboxParser = true;
$pdf->Load($fields2, false); // second parameter: false if field values are in ISO-8859-1, true if UTF-8
$pdf->Merge();
$pdf->Output();
ob_end_flush();
//set_time_limit(0);
//ob_end_flush();
*/
	$Header='IRS Form 1040';
	$Output2='This is a test';
	$SignatureLine="Signed:________________________";
	$pdf=new FPDF();
	$pdf->AddPage();
	$pdf->SetFont('Times','B',16);
	$pdf->Cell(40,10,$Header);
	$pdf->Ln(); $pdf->Ln();
	$pdf->SetFont('Times','',16);
	$pdf->Cell(40,10,$Line1);
	$pdf->Ln();
	$pdf->Cell(40,10,$Line2);
	$pdf->Ln();
	$pdf->Cell(40,10,$Line3);
	$pdf->Ln();
	$pdf->Cell(40,10,$Line4);
	$pdf->Ln();
	$pdf->Cell(40,10,$Line5);
	$pdf->Ln();
	$pdf->Cell(40,10,$Line6);
	$pdf->Ln();
	$pdf->Ln();
	$pdf->SetFont('Times','B',16);
	$pdf->Cell(40,10,$Line7);
	$pdf->Ln();
	$pdf->Cell(40,10,$Line8);
	$pdf->Ln();
	$pdf->Cell(40,10,$SignatureLine);
	//$pdf->Output();
	$pdf->Output("D", "TaxReturnStatement.pdf");
	ob_end_flush();

            //echo "This is Button1 that is selected"; 

        } 
    ?> 

      <form action = "test2.php" method = "post" id = "options">

      

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
      <form action = "test2.php" method = "post">

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

      <section class = "block-of-text">
        <a href="test2.php"><input type = "submit" name = "reset" value = "Reset Page"/></a>
      </section>

      <?php $conn->close(); ?>

      <script src = "effects.js"></script>

    </body>
</html>
