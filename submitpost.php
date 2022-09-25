<!DOCTYPE html>
<html>
<head>
<title>
Qwurks! - Question submission
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
<h1>Ask your own Qwurks! question</h1>
<p>Please begin your question with one of the following variations:</p>
<ul id="examples">
	<li>Can anybody/anyone else...</li>
	<li>Could anybody/anyone else...</li>
	<li>Does anybody/anyone else...</li>
	<li>Did anybody/anyone else...</li>
	<li>Has anybody/anyone else...</li>
	<li>Is anybody/anyone else...</li>
	<li>Was anybody/anyone else...</li>
	<li>Will anybody/anyone else...</li>
	<li>Would anybody/anyone else...</li>
</ul>

<form action="savepost.php" method="post" enctype="application/json">
	<label class="label" for="questioninput">Question: </label><br>
	<textarea maxlength="300" id="questioninput" name="questioninput" required></textarea><br>
	<label class="label" for="explanationinput">Add details or comments, if desired: </label><br>
	<textarea maxlength="300" id="explanationinput" name="explanationinput"></textarea><br>
	<label class="label" for="nicknameinput">Name or nickname:</label><br>
	<input type="text" id="nicknameinput" name="nicknameinput" required></input><br>
	<input type="submit" id="submit" value="submit"></input>
</form>

<h3>Rules</h3>
<ul id="rules">
	<li>The main point of the site is to have fun sharing your quirks and finding others who have them too. Please don't post simply opinions or facts. Ex: "Does anyone else hate Obama?"</li>
	<li>Hate speech of any sort will not be tolerated</li>
	<li>Please don't use offensive language</li>
	<li>Don't bully or insult others</li>
	<li>Basically, just be a decent person and enjoy the site for what it is. Have fun and let others have fun too!</li>
	<li>If you have any questions or comments for me personally, send me a message using the contact form and I'll try to reply in a timely manner</li>
</ul>
<!-- **************   Don't change anything below this line!     ************** -->
<?php// include 'includes/adbanner2.php'; ?>
</div>

<?php include 'includes/footer.php'; ?>
</div>
</body>
</html>