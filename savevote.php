<?php 
  $vote = isset($_POST["vote"]) ? htmlspecialchars(trim($_POST["vote"])) : "";
  $id = isset($_POST["id"]) ? htmlspecialchars(trim($_POST["id"])) : "";
  
//  echo 'this worked if you can see the vote: '.$vote.' and the id: '.$id;

$json = json_decode(file_get_contents('data.json'), true); // read existing file

//echo 'You clicked '.$vote.' to the question "Does anybody else '.$json[$id]['question'].'"';
//echo var_dump($json);
//echo $json[$id]['question'];


if ($vote=="yesbutton") {
	$json[$id]['yesnum'] = $json[$id]['yesnum'] + 1;
	$json[$id]['totalvotes'] = $json[$id]['totalvotes'] + 1;
	echo "You just added one to the yes's so there are now ".$json[$id]['yesnum']." yesses";
}
if ($vote=="nobutton") {
	$json[$id]['nonum'] = $json[$id]['nonum'] + 1;
	$json[$id]['totalvotes'] = $json[$id]['totalvotes'] + 1;
	echo "You just added one to the no's so there are now ".$json[$id]['yesnum']." yesses";
}

// mail myself notification
//$msg = "Vote: \n".$vote."\n\nQuestion: \n".$json[$id]['question']."\n\nIndex: ".$id;
//// use wordwrap() if lines are longer than 70 characters
//$msg = wordwrap($msg,70);
//// send email
//$to = 'adminmail@qwurks.com';
//$subject = 'Someone voted on Qwurks';
//$headers = 'From: adminmail@qwurks.com'."\r\n";
//// send email
//mail($to,$subject,$msg,$headers);

$encfile = json_encode($json, JSON_PRETTY_PRINT);
//echo $encfile;

file_put_contents('data.json', $encfile, LOCK_EX); //existing file is overwritten (not appended)

?>
