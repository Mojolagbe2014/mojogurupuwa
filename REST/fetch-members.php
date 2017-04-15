<?php
define("CONST_FILE_PATH", "../includes/constants.php");
include ('../classes/WebPage.php'); //Set up page as a web page
$thisPage = new WebPage(); //Create new instance of webPage class

$dbObj = new Database();//Instantiate database
$memberObj = new Member($dbObj); // Create an object of Member class

$totalNo = filter_input(INPUT_GET, "totalNo", FILTER_VALIDATE_INT) 
        ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_GET, "totalNo", FILTER_VALIDATE_INT)) :  100;
$offset = filter_input(INPUT_GET, "offset", FILTER_VALIDATE_INT) 
        ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_GET, "offset", FILTER_VALIDATE_INT)) :  0;

echo $memberObj->fetch("*", " 1=1 ", " id ASC LIMIT $totalNo OFFSET $offset "); 