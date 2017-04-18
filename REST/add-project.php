<?php
session_start();
define("CONST_FILE_PATH", "../includes/constants.php");
include ('../classes/WebPage.php'); //Set up page as a web page
$thisPage = new WebPage(); //Create new instance of webPage class

$dbObj = new Database();//Instantiate database
$projectObj = new Project($dbObj); // Create an object of Project class
$errorArr = array(); //Array of errors
$projectMedFil =""; $projectImgFil ="";
if(!isset($_SESSION['ITCLoggedInAdmin']) || !isset($_SESSION["ITCadminEmail"])){ 
    $json = array("status" => 0, "msg" => "You are not logged in."); 
    echo json_encode($json);
}
else{
    if(filter_input(INPUT_POST, "addNewProject") != NULL){
        $postVars = array('name','image','isCompleted','sponsor','startDate','description','media', 'endDate', 'featured'); // Form fields names
        //Validate the POST variables and add up to error message if empty
        foreach ($postVars as $postVar){
            switch($postVar){
                case 'image':   $projectObj->$postVar = basename($_FILES["image"]["name"]) ? rand(100000, 1000000)."_".  strtolower(str_replace(" ", "_", filter_input(INPUT_POST, 'isCompleted'))).".".pathinfo(basename($_FILES["image"]["name"]),PATHINFO_EXTENSION): ""; 
                                $projectImgFil = $projectObj->$postVar;
                                if($projectObj->$postVar == "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
                case 'media':   $projectObj->$postVar = basename($_FILES["file"]["name"]) ? rand(100000, 1000000)."_".  strtolower(str_replace(" ", "_", filter_input(INPUT_POST, 'isCompleted'))).".".pathinfo(basename($_FILES["file"]["name"]),PATHINFO_EXTENSION): ""; 
                                $projectMedFil = $projectObj->$postVar;
                                break;
                case 'featured':    $projectObj->$postVar = filter_input(INPUT_POST, $postVar, FILTER_VALIDATE_INT) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_POST, $postVar, FILTER_VALIDATE_INT)) :  0; 
                                break;
                default     :   $projectObj->$postVar = filter_input(INPUT_POST, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_POST, $postVar)) :  ''; 
                                if($projectObj->$postVar == "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
            }
        }
        //If validated and not empty submit it to database
        if(count($errorArr) < 1)   {
            $targetFile = MEDIA_FILES_PATH."project/". $projectMedFil;
            $targetImage = MEDIA_FILES_PATH."project-image/". $projectImgFil;
            $uploadOk = 1; $msg = ''; //$normalSize = true; $isImage = true;
            $imageFileType = pathinfo($targetFile,PATHINFO_EXTENSION);
            
            if ($projectMedFil!="" && file_exists($targetImage)) { $msg .= " Project image already exists."; $uploadOk = 0; }
            //if ($_FILES["file"]["size"] > 800000000 || $_FILES["image"]["size"] > 8000000) { $msg .= " Project media is too large."; $normalSize = false; }
            if($uploadOk == 1 && Imaging::checkDimension($_FILES["image"]["tmp_name"], 790, 420, 'equ', 'both')== 'true'){ 
                if($projectMedFil !=''){ move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile);}
                if($projectImgFil !=''){ move_uploaded_file($_FILES["image"]["tmp_name"], $targetImage);}
                echo $projectObj->add(); 
            }
            else {
                $msg = "Sorry, your project was not uploaded. ERROR: ".$msg.Imaging::checkDimension($_FILES["image"]["tmp_name"], 790, 420, 'equ', 'both');
                $json = array("status" => 0, "msg" => $msg); 
                $dbObj->close();//Close Database Connection
                header('Content-type: application/json');
                echo json_encode($json);
            } 

        }else{ 
            $json = array("status" => 0, "msg" => $errorArr); 
            $dbObj->close();//Close Database Connection
            header('Content-type: application/json');
            echo json_encode($json);
        }
    } 
}