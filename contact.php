<!DOCTYPE html>
<html>
<head>
<title>
Qwurks!
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
<?php 
	$name = isset($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : 'No name';
	$email = isset($_POST['email']) ? htmlspecialchars(trim($_POST["email"])) : 'No email';
	$message = isset($_POST['message']) ? htmlspecialchars(trim($_POST["message"])) : 'No message';
	$human = isset($_POST['humantest']) ? htmlspecialchars(trim($_POST["humantest"])) : 'Not human';

if ($human == "hsilgne") {
	$msg = "Name: \n".$name."\n\nEmail: \n".$email."\n\nMessage: \n".$message;
	// use wordwrap() if lines are longer than 70 characters
	$msg = wordwrap($msg,70);
	// send email
	mail("adminmail@qwurks.com","Quirks Contact Form",$msg);
	echo '<br>';
	echo "Thank you. Your message has been sent!";
} else {
	echo "Sorry. You didn't pass the human test.";
	echo "You failed the human test because you entered the word: ".$human;
}
?>
<!-- **************   Don't change anything below this line!     ************** -->
<?php// include 'includes/adbanner2.php'; ?>
</div>

<?php include 'includes/footer.php'; ?>
</div>
</body>
</html>