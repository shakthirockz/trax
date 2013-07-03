<?php 
// Script written by Chris Bryson.
// http://www.chrisbryson.co.uk
$name = @$_POST["name"];
$comments = @$_POST["comments"];
$comments = stripslashes($comments);
$values = "<b><font color=\"red\">< $name > </b></font>";
$values .= "<font color=\"black\">$comments</font><hr>";

$fp = @fopen("data.txt", "a+") or die("Couldn't open data file for writing!");
$numBytes = @fwrite($fp, $values) or die("Couldn't write values to file!");

@fclose($fp);
header("Location: index.php") 

?>  
