<?php
//	// force refresh!
//	$_SESSION['form_submitted'] = true;
?>

<!DOCTYPE html>
<html>
<head>
<title>
Qwurks!
</title>
<link rel="stylesheet" type="text/css" href="css/main.css">
<link rel="stylesheet" type="text/css" href="css/dae.css">
<meta charset="UTF-8"> 
</head>

<body>
<?php 
//	include_once 'includes/analyticstracking.php'; 
?>

<div id="wrapper">

<?php include 'includes/header.php'; ?>
<?php include 'includes/lcolumn.php'; ?>
<?php include 'includes/rcolumn.php'; ?>

<div id="content">
<?
//	php include 'includes/adbanner1.php';
?>
<!-- **************   Don't change anything above this line EXCEPT the title!     ************** -->

<?php

// open and read existing file
$json = json_decode(file_get_contents('data.json'), true);
$arrLen = count($json);

$question = isset($_POST['questioninput']) ? trim($_POST['questioninput']) : "";
$explanation = isset($_POST['explanationinput']) ? trim($_POST['explanationinput']) : "";
$submituser = isset($_POST['nicknameinput']) ? trim($_POST['nicknameinput']) : "anonymous";

if ($question != "" && $question != null && $submituser != "" && $submituser != null) {
	$formdata = array(
		'question' => htmlspecialchars($question),
		'explanation' => htmlspecialchars($explanation),
		'yesnum'=> 0,
		'nonum'=> 0,
		'totalvotes'=> 0,
		'submituser'=> htmlspecialchars($submituser),
		'comments'=> [],
		'index'=> $arrLen
		);
	array_push($json, $formdata);
	$encfile = json_encode($json, JSON_PRETTY_PRINT);
	//echo $encfile;
	echo '<p>Thank you. Your question has been submitted!</p>';
	file_put_contents('data.json', $encfile, LOCK_EX); //existing file is overwritten (not appended)
	
	//mail myself notification
//	$msg = "Question: \n".$question."\n\nExplanation: \n".$explanation."\n\nsubmituser: ".$submituser;
//	$msg = wordwrap($msg,70); // use wordwrap() if lines are longer than 70 characters
//	$to = 'adminmail@qwurks.com';
//	$subject = 'Quirks Question Submitted'; 
//	$headers = 'From: adminmail@qwurks.com'."\r\n";
//	// send email
//	mail($to,$subject,$msg,$headers);
} else {
	echo '<br>Sorry. It seems that there is an error. Please make sure that you entered a question and a name or nickname';
}
?>
<!-- **************   Don't change anything below this line!     ************** -->
<?php
//	include 'includes/adbanner2.php'; 
?>
</div>

<!-- redirect to top after form submission after 2 seconds -->
<script>
	setTimeout("window.location = 'index.php?sort=newest'", 2000);
</script>

<?php include 'includes/footer.php'; ?>

</div>
</body>
</html>