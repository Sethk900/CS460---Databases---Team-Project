<html>
<style>
	   body {
	   	background-image: url("taxes.jpg");
		background-repeat: no-repeat;
  		background-attachment: fixed;
 	 	background-size: cover;
	   }
	   </style>
<?php
// List the pdf files currently in the directory
echo nl2br("\n");
$i=1;
echo nl2br("PDF files currently available:\n");
foreach(glob("*.pdf") as $file) {
    //echo nl2br($file."\n");
	$file='"'.$file.'"';
	$filelist[$i]=$file;
	$i++;
}
$childForm = '';
$i=0;
foreach ($filelist as $file) {
			   $childForm.='<input type="submit" name="'.$i.'" class="button" value="'.$file.'"';
			   $i++;
        }
$fileListImplode = implode(', ', $filelist);
$fileListImplode = '['.$fileListImplode.']';

?>

<script>
    var arrayEntries = <?php echo $fileListImplode; ?>;
    function CreateButtons(element, index, array) {
        var button = document.createElement("input");
        button.type = "button";
        button.value = element; //'element' is the name of the file we're handling
        button.onclick = function() {
			window.location.href = element;
        }
        document.body.appendChild(button);
    }
    arrayEntries.forEach(CreateButtons);
</script>
</html>
