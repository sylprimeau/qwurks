<?php
/* I need this file to act as intermediary between AJAX and the file on the server because the file is in a non-public directory. AJAX can only access public dirs because it is client-side. Wouldn't need to do this if I was using a PHP function but AJAX functions need PHP to get the data for them.  */

$json = json_decode(file_get_contents('data.json'), true); // read existing file
$encfile = json_encode($json, JSON_PRETTY_PRINT);
echo $encfile;
?>