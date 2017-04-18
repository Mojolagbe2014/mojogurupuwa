<?php
session_start();
define("CONST_FILE_PATH", "../includes/constants.php");
include ('../classes/WebPage.php'); //Set up page as a web page
$thisPage = new WebPage(); //Create new instance of webPage class

$dbObj = new Database();//Instantiate database
$quoteObj = new Quote($dbObj); // Create an object of Quote class
$errorArr = array(); //Array of errors
$oldMedia = ""; $newMedia =""; $quoteImg ="";

if(!isset($_SESSION['ITCLoggedInAdmin']) || !isset($_SESSION["ITCadminEmail"])){ 
    $json = array("status" => 0, "msg" => "You are not logged in."); 
    echo json_encode($json);
}
else{
    if(filter_input(INPUT_POST, "addNewQuote") != NULL && filter_input(INPUT_POST, "addNewQuote")=="addNewQuote"){
        $postVars = array('author', 'content', 'image'); // Form fields names
        //Validate the POST variables and add up to error message if empty
        foreach ($postVars as $postVar){
            switch($postVar){
                case 'image':   $quoteObj->$postVar = basename($_FILES["image"]["name"]) ? rand(100000, 1000000)."_".  time().".".pathinfo(basename($_FILES["image"]["name"]),PATHINFO_EXTENSION): ""; 
                                $quoteImg = $quoteObj->$postVar;
                                if($quoteObj->$postVar == "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
                default     :   $quoteObj->$postVar = filter_input(INPUT_POST, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_POST, $postVar)) :  ''; 
                                if($quoteObj->$postVar === "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
            }
        }
        //If validated and not empty submit it to database
        if(count($errorArr) < 1)   {
            
            $targetFile = MEDIA_FILES_PATH."quote/". $quoteImg;
            $uploadOk = 1; $msg = '';
            
            if (file_exists($targetFile) && $quoteImg!="") { $msg .= " Quote image already exists."; $uploadOk = 0; }
            
            if($uploadOk == 0){
                $msg = "Sorry, quote was not uploaded. " .msg;
                $json = array("status" => 0, "msg" => $msg); 
                $dbObj->close();//Close Database Connection
                header('Content-type: application/json');
                echo json_encode($json);
            }
            else{ 
                if ($quoteImg !='' &&  $_FILES["image"]["size"] > 8000000) { $msg .= " Quote image is too large."; $uploadOk = 0; }
                if(($quoteImg !='') && (Imaging::checkDimension($_FILES["image"]["tmp_name"], 100, 100, 'equ', 'both')!= 'true')){ $uploadOk = 0; $msg .= " Quote image dimension not match. ERROR: ".$msg.Imaging::checkDimension($_FILES["image"]["tmp_name"], 100, 100, 'equ', 'both');  }
                if($uploadOk == 1){
                    if($quoteImg !=''){ move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);}
                    echo $quoteObj->add(); 
                }
                else{
                    $msg = "Sorry, quote was not uploaded. " .$msg;
                    $json = array("status" => 0, "msg" => $msg); 
                    $dbObj->close();//Close Database Connection
                    header('Content-type: application/json');
                    echo json_encode($json);
                }
            }
        }
        //Else show error messages
        else{ 
            $json = array("status" => 0, "msg" => $errorArr); 
            $dbObj->close();//Close Database Connection
            header('Content-type: application/json');
            echo json_encode($json);
        }
    } 
    
    if(filter_input(INPUT_POST, "fetchQuotes") != NULL){
        $requestData= $_REQUEST;
        $columns = array( 0 =>'id', 1 =>'id', 2 => 'content', 3 => 'author', 4 => 'image');

        // getting total number records without any search
        $query = $dbObj->query("SELECT * FROM quote ");
        $totalData = mysqli_num_rows($query);
        $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

        $sql = "SELECT * FROM quote WHERE 1=1 ";
        if(!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
                $sql.=" AND ( author LIKE '%".$requestData['search']['value']."%' ";    
                $sql.=" OR content LIKE '".$requestData['search']['value']."%' ) ";
        }
        $query = $dbObj->query($sql);
        $totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
        $sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
        /* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	

        echo $quoteObj->fetchForJQDT($requestData['draw'], $totalData, $totalFiltered, $sql);
    }
    
    if(filter_input(INPUT_POST, "deleteThisQuote")!=NULL){
        $postVars = array('id','image'); // Form fields names
        //Validate the POST variables and add up to error message if empty
        foreach ($postVars as $postVar){
            switch($postVar){
                case 'image':   $quoteObj->$postVar = filter_input(INPUT_POST, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_POST, $postVar)) :  ''; 
                                $quoteImg = $quoteObj->$postVar;
                                //if($quoteObj->$postVar === "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
                default     :   $quoteObj->$postVar = filter_input(INPUT_POST, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_POST, $postVar)) :  ''; 
                                if($quoteObj->$postVar === "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
            }
        }
        //If validated and not empty submit it to database
        if(count($errorArr) < 1)   {
            if($quoteImg!='' && file_exists(MEDIA_FILES_PATH."quote/".$quoteImg)){ unlink(MEDIA_FILES_PATH."quote/".$quoteImg); }
            echo $quoteObj->delete();
        }
        else{ 
            $json = array("status" => 0, "msg" => $errorArr); 
            $dbObj->close();//Close Database Connection
            header('Content-type: application/json');
            echo json_encode($json);
        }

    } 
    
    if(filter_input(INPUT_POST, "addNewQuote") != NULL && filter_input(INPUT_POST, "addNewQuote")=="editQuote"){
        $postVars = array('id', 'author', 'content', 'image'); // Form fields names
        $oldMedia = $_REQUEST['oldFile'];
        foreach ($postVars as $postVar){
            switch($postVar){
                case 'image':   $newMedia = basename($_FILES["image"]["name"]) ? rand(100000, 1000000)."_". time().".".pathinfo(basename($_FILES["image"]["name"]),PATHINFO_EXTENSION): ""; 
                                $quoteObj->$postVar = $newMedia;
                                if($quoteObj->$postVar == "") { $quoteObj->$postVar = $oldMedia;}
                                $quoteImg = $newMedia;
                                break;
                default     :   $quoteObj->$postVar = filter_input(INPUT_POST, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_POST, $postVar)) :  ''; 
                                if($quoteObj->$postVar === "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
            }
        }
        if(count($errorArr) < 1)   {
            $target_file = MEDIA_FILES_PATH."quote/". $quoteImg;
            $uploadOk = 1; $msg = '';
            
            if($newMedia !=""){
                if(Imaging::checkDimension($_FILES["image"]["tmp_name"], 100, 100, 'equ', 'both') != 'true'){$uploadOk = 0; $msg = Imaging::checkDimension($_FILES["image"]["tmp_name"], 100, 100, 'equ', 'both');}
                if ($uploadOk == 1 && move_uploaded_file($_FILES["image"]["tmp_name"], MEDIA_FILES_PATH."quote/".$quoteImg)) {
                    $msg .= "The file ". basename( $_FILES["image"]["name"]). " has been uploaded.";
                    $status = 'ok'; if($oldMedia!='' && file_exists(MEDIA_FILES_PATH."quote/".$oldMedia)) unlink(MEDIA_FILES_PATH."quote/".$oldMedia);
                } else { $uploadOk = 0; }
            }
            if($uploadOk == 1){ echo $quoteObj->update(); }
            else {
                    $msg = " Sorry, there was an error uploading your quote image. ERROR: ".$msg;
                    $json = array("status" => 0, "msg" => $msg); 
                    $dbObj->close();//Close Database Connection
                    header('Content-type: application/json');
                    echo json_encode($json);
            }
        }
        else{ 
            $json = array("status" => 0, "msg" => $errorArr); 
            $dbObj->close();//Close Database Connection
            header('Content-type: application/json');
            echo json_encode($json);
        }
    } 
}