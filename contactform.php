<!DOCTYPE html>
<html>
<head>
<title>
Qwurks! - Contact Form
</title>
<link rel="stylesheet" type="text/css" href="css/main.css">
<link rel="stylesheet" type="text/css" href="css/dae.css">

</head>

<body>
<?php include_once 'includes/analyticstracking.php'; ?>
<div id="wrapper">

<?php include 'includes/header.php'; ?>
<?php include 'includes/lcolumn.php'; ?>
<?php include 'includes/rcolumn.php'; ?>

<div id="content">
<?php// include 'includes/adbanner1.php'; ?>
<!-- **************   Don't change anything above this line EXCEPT the title!     ************** -->

<form action="contact.php" method="post" id="contactform">
	<label for="name">Name:</label><br>
	<input type="text" id="name" name="name" required></input><br>
	<label for="email">Email address:</label><br>
	<input type="email" id="email" name="email" required></input><br>
	<label for="contactform">Message:</label><br>
	<textarea id="contactform" rows="20" cols="50" name="message" required></textarea><br>
	<label for="humantest">Are you human? Enter the word "english" in reverse: </label>
	<input type="text" id="humantest" name="humantest" required></input><br>
	<input type="submit" value="send"></input>
</form>


<!-- **************   Don't change anything below this line!     ************** -->
<?php// include 'includes/adbanner2.php'; ?>
</div>

<?php include 'includes/footer.php'; ?>
</div>
</body>
</html>