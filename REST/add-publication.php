<?php
session_start();
define("CONST_FILE_PATH", "../includes/constants.php");
include ('../classes/WebPage.php'); //Set up page as a web page
$thisPage = new WebPage(); //Create new instance of webPage class

$dbObj = new Database();//Instantiate database
$publicationObj = new Publication($dbObj); // Create an object of Publication class
$errorArr = array(); //Array of errors
$publicationMedFil =""; $publicationImgFil ="";
if(!isset($_SESSION['ITCLoggedInAdmin']) || !isset($_SESSION["ITCadminEmail"])){ 
    $json = array("status" => 0, "msg" => "You are not logged in."); 
    echo json_encode($json);
}
else{
    if(filter_input(INPUT_POST, "addNewPublication") != NULL){
        $postVars = array('name','image','category','datePublished','description','media', 'featured'); // Form fields names
        //Validate the POST variables and add up to error message if empty
        foreach ($postVars as $postVar){
            switch($postVar){
                case 'image':   $publicationObj->$postVar = basename($_FILES["image"]["name"]) ? rand(100000, 1000000)."_".  strtolower(str_replace("-", "_", filter_input(INPUT_POST, 'datePublished'))).".".pathinfo(basename($_FILES["image"]["name"]),PATHINFO_EXTENSION): ""; 
                                $publicationImgFil = $publicationObj->$postVar;
                                //if($publicationObj->$postVar == "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
                case 'media':   $publicationObj->$postVar = basename($_FILES["file"]["name"]) ? rand(100000, 1000000)."_".  strtolower(str_replace("-", "_", filter_input(INPUT_POST, 'datePublished'))).".".pathinfo(basename($_FILES["file"]["name"]),PATHINFO_EXTENSION): ""; 
                                $publicationMedFil = $publicationObj->$postVar;
                                break;
                case 'featured':    $publicationObj->$postVar = filter_input(INPUT_POST, $postVar, FILTER_VALIDATE_INT) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_POST, $postVar, FILTER_VALIDATE_INT)) :  0; 
                                break;
                default     :   $publicationObj->$postVar = filter_input(INPUT_POST, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_POST, $postVar)) :  ''; 
                                if($publicationObj->$postVar == "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
            }
        }
        //If validated and not empty submit it to database
        if(count($errorArr) < 1)   {
            $targetFile = MEDIA_FILES_PATH."publication/". $publicationMedFil;
            $targetImage = MEDIA_FILES_PATH."publication-image/". $publicationImgFil;
            $uploadOk = 1; $msg = ''; //$normalSize = true; $isImage = true;
            $imageFileType = pathinfo($targetFile,PATHINFO_EXTENSION);
            
            if (file_exists($targetImage) && $publicationImgFil!="") { $msg .= " Publication image already exists."; $uploadOk = 0; }
            if (file_exists($targetFile) && $publicationMedFil!="") { $msg .= " Publication media/file already exists."; $uploadOk = 0; }
            
            if($uploadOk == 0){
                $msg = "Sorry, your publication was not uploaded. " .msg;
                $json = array("status" => 0, "msg" => $msg); 
                $dbObj->close();//Close Database Connection
                header('Content-type: application/json');
                echo json_encode($json);
            }
            else{ 
                if (($publicationMedFil !='' || $publicationImgFil !='') && ($_FILES["file"]["size"] > 800000000 || $_FILES["image"]["size"] > 8000000)) { $msg .= " Publication image is too large."; $uploadOk = 0; }
                if(($publicationImgFil !='') && (Imaging::checkDimension($_FILES["image"]["tmp_name"], 420, 305, 'equ', 'both')!= 'true')){ $uploadOk = 0; $msg .= " Publication image dimension not match. ERROR: ".$msg.Imaging::checkDimension($_FILES["image"]["tmp_name"], 420, 305, 'equ', 'both');  }
                if($uploadOk == 1){
                    if($publicationMedFil !=''){ move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile);}
                    if($publicationImgFil !=''){ move_uploaded_file($_FILES["image"]["tmp_name"], $targetImage);}
                    echo $publicationObj->add(); 
                }
                else{
                    $msg = "Sorry, your publication was not uploaded. " .$msg;
                    $json = array("status" => 0, "msg" => $msg); 
                    $dbObj->close();//Close Database Connection
                    header('Content-type: application/json');
                    echo json_encode($json);
                }
            }

        }else{ 
            $json = array("status" => 0, "msg" => $errorArr); 
            $dbObj->close();//Close Database Connection
            header('Content-type: application/json');
            echo json_encode($json);
        }
    } 
}