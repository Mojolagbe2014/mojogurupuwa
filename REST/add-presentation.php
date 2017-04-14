<?php
session_start();
define("CONST_FILE_PATH", "../includes/constants.php");
include ('../classes/WebPage.php'); //Set up page as a web page
$thisPage = new WebPage(); //Create new instance of webPage class

$dbObj = new Database();//Instantiate database
$presentationObj = new Presentation($dbObj); // Create an object of Presentation class
$errorArr = array(); //Array of errors
$presentationMedFil =""; $presentationImgFil ="";
if(!isset($_SESSION['ITCLoggedInAdmin']) || !isset($_SESSION["ITCadminEmail"])){ 
    $json = array("status" => 0, "msg" => "You are not logged in."); 
    echo json_encode($json);
}
else{
    if(filter_input(INPUT_POST, "addNewPresentation") != NULL){
        $postVars = array('name','image','organizer','location','datePresented','description','media', 'featured'); // Form fields names
        //Validate the POST variables and add up to error message if empty
        foreach ($postVars as $postVar){
            switch($postVar){
                case 'image':   $presentationObj->$postVar = basename($_FILES["image"]["name"]) ? rand(100000, 1000000)."_".  strtolower(str_replace("-", "_", filter_input(INPUT_POST, 'datePresented'))).".".pathinfo(basename($_FILES["image"]["name"]),PATHINFO_EXTENSION): ""; 
                                $presentationImgFil = $presentationObj->$postVar;
                                if($presentationObj->$postVar == "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
                case 'media':   $presentationObj->$postVar = basename($_FILES["file"]["name"]) ? rand(100000, 1000000)."_".  strtolower(str_replace("-", "_", filter_input(INPUT_POST, 'datePresented'))).".".pathinfo(basename($_FILES["file"]["name"]),PATHINFO_EXTENSION): ""; 
                                $presentationMedFil = $presentationObj->$postVar;
                                break;
                case 'featured':    $presentationObj->$postVar = filter_input(INPUT_POST, $postVar, FILTER_VALIDATE_INT) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_POST, $postVar, FILTER_VALIDATE_INT)) :  0; 
                                break;
                default     :   $presentationObj->$postVar = filter_input(INPUT_POST, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_POST, $postVar)) :  ''; 
                                if($presentationObj->$postVar == "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
            }
        }
        //If validated and not empty submit it to database
        if(count($errorArr) < 1)   {
            $targetFile = MEDIA_FILES_PATH."presentation/". $presentationMedFil;
            $targetImage = MEDIA_FILES_PATH."presentation-image/". $presentationImgFil;
            $uploadOk = 1; $msg = ''; //$normalSize = true; $isImage = true;
            $imageFileType = pathinfo($targetFile,PATHINFO_EXTENSION);
            
            if (file_exists($targetImage)) { $msg .= " Presentation image already exists."; $uploadOk = 0; }
            //if ($_FILES["file"]["size"] > 800000000 || $_FILES["image"]["size"] > 8000000) { $msg .= " Presentation media is too large."; $normalSize = false; }
            if($uploadOk == 1 && Imaging::checkDimension($_FILES["image"]["tmp_name"], 400, 400, 'min', 'both')== 'true'){ 
                if($presentationMedFil !=''){ move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile);}
                if($presentationImgFil !=''){ move_uploaded_file($_FILES["image"]["tmp_name"], $targetImage);}
                echo $presentationObj->add(); 
            }
            else {
                $msg = "Sorry, your presentation was not uploaded. ERROR: ".$msg.Imaging::checkDimension($_FILES["image"]["tmp_name"], 400, 400, 'equ', 'both');
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