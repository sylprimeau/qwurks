<?php 
  $comment = isset($_POST["commenttext"]) ? htmlspecialchars(trim($_POST["commenttext"])) : "no comment";
  $id = isset($_POST["id"]) ? htmlspecialchars(trim($_POST["id"])) : "no id";
  $nickname = isset($_POST["nicknametext"]) ? htmlspecialchars(trim($_POST["nicknametext"])) : "no nickname";

  $json = json_decode(file_get_contents('data.json'), true); // read existing file

//echo 'You clicked '.$vote.' to the question "Does anybody else '.$json[$id]['question'].'"';
//echo var_dump($json);


date_default_timezone_set("UTC");
$date = date("Y-m-d H:i:s", time()).' GMT'; 
//$date = new DateTime(); //create datestamp
//$date = gmdate($date->format('Y-m-d H:i:s'));

$data = array(
	'date'=> $date,
	'comment'=> $comment,
	'user'=> $nickname,
	);

isset($json[$id]['comments']) ? array_push($json[$id]['comments'], $data) : null;

$encfile = json_encode($json, JSON_PRETTY_PRINT);

echo $encfile;

file_put_contents('data.json', $encfile, LOCK_EX); //existing file is overwritten (not appended)

//// mail myself notification
//$msg = "Comment: \n".$comment."\n\nNickname: ".$nickname;
//// use wordwrap() if lines are longer than 70 characters
//$msg = wordwrap($msg,70);
//// send email
//$to = 'adminmail@qwurks.com';
//$subject = 'Qwurks comment submitted';
//$headers = 'From: adminmail@qwurks.com'."\r\n"; 
//// send email
//mail($to,$subject,$msg,$headers);


/* just a sample to look at!
$formdata = array(
	'question' => $_POST['questionSubmit'],
	'explanation' => $_POST['explanationSubmit'],
	'yesnum'=> 0,
	'nonum'=> 0,
	'totalvotes'=> 0,
	'submituser'=> $_POST['nicknameSubmit'],
	'comments'=> [],
	'index'=> $arrLen
	);


array_push($json, $formdata);
*/
?>


