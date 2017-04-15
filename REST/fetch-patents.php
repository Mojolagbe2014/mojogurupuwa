<?php
session_start();
define("CONST_FILE_PATH", "../includes/constants.php");
include ('../classes/WebPage.php'); //Set up page as a web page
$thisPage = new WebPage(); //Create new instance of webPage class

$dbObj = new Database();//Instantiate database
$patentObj = new Patent($dbObj); // Create an object of Patent class
$errorArr = array(); //Array of errors


//fetch all patents
header('Content-type: application/json');
echo $patentObj->fetch();