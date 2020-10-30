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
	$pdf->Output();
	ob_end_flush();

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
