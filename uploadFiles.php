<html>
<title>Upload Income Statements</title>
<style>
	   body {
	   	background-image: url("business.jpg");
		background-repeat: no-repeat;
  		background-attachment: fixed;
 	 	background-size: cover;
	   }
	   </style>
<body>
<?php echo nl2br("\nPlease upload files in .pdf format\n\n"); ?>
<form action="upload.php" method="post" enctype="multipart/form-data">
        Select File to Upload:
        <input type="file" name="file">
        <input type="submit" name="submit" value="Upload">
   	 </form>
	 <section class = "block-of-text">
        <a href="mainPage.php"><input type = "submit" name = "reset" value = "Back"/></a>
      </section>
	 </body>
	 </html>