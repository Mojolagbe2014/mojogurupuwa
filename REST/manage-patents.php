<?php
session_start();
define("CONST_FILE_PATH", "../includes/constants.php");
include ('../classes/WebPage.php'); //Set up page as a web page
$thisPage = new WebPage(); //Create new instance of webPage class

$dbObj = new Database();//Instantiate database
$pubCatObj = new Patent($dbObj); // Create an object of Patent class
$errorArr = array(); //Array of errors
$oldMedia = ""; $newMedia =""; $pubCatMedFil ="";

if(!isset($_SESSION['ITCLoggedInAdmin']) || !isset($_SESSION["ITCadminEmail"])){ 
    $json = array("status" => 0, "msg" => "You are not logged in."); 
    echo json_encode($json);
}
else{
    if(filter_input(INPUT_POST, "addNewPatent") != NULL && filter_input(INPUT_POST, "addNewPatent")=="addNewPatent"){
        $postVars = array('name', 'description', 'issuanceDate', 'image'); // Form fields names
        //Validate the POST variables and add up to error message if empty
        foreach ($postVars as $postVar){
            switch($postVar){
                case 'image':   $pubCatObj->$postVar = basename($_FILES["image"]["name"]) ? rand(100000, 1000000)."_".".".pathinfo(basename($_FILES["image"]["name"]),PATHINFO_EXTENSION): ""; 
                                $pubCatMedFil = $pubCatObj->$postVar;
                                //if($pubCatObj->$postVar == "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
                default     :   $pubCatObj->$postVar = filter_input(INPUT_POST, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_POST, $postVar)) :  ''; 
                                if($pubCatObj->$postVar === "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
            }
        }
        //If validated and not empty submit it to database
        if(count($errorArr) < 1)   {
            
            $target_file = MEDIA_FILES_PATH."patent/". $pubCatMedFil;
            $uploadOk = 1; $msg = '';
            $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
            
            if (file_exists($target_file) && $pubCatMedFil != "") { $msg .= " Publication patent image already exists."; $uploadOk = 0; }
            if ($_FILES["image"]["size"] > 800000000) { $msg .= " Publication patent image is too large."; $uploadOk = 0; }
            if($uploadOk == 1){ 
                if($pubCatMedFil !=''){ 
                    move_uploaded_file($_FILES["image"]["tmp_name"],  MEDIA_FILES_PATH."patent/".$pubCatMedFil);
                    $msg .= "The file ". basename( $_FILES["image"]["name"]). " has been uploaded.";
                }
                $status = 'ok';
                echo $pubCatObj->add();
            }
            else {
                $msg = " Sorry, there was an error uploading your  patent image. ERROR: ".$msg;
                $json = array("status" => 0, "msg" => $msg); 
                $dbObj->close();//Close Database Connection
                header('Content-type: application/json');
                echo json_encode($json);
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
    
    if(filter_input(INPUT_POST, "fetchPatents") != NULL){
        $requestData= $_REQUEST;
        $columns = array( 0 =>'id', 1 =>'id', 2 => 'name', 3 => 'description', 4 => 'issuance_date', 5 => 'image');

        // getting total number records without any search
        $query = $dbObj->query("SELECT * FROM patent ");
        $totalData = mysqli_num_rows($query);
        $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

        $sql = "SELECT * FROM patent WHERE 1=1 ";
        if(!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
                $sql.=" AND ( name LIKE '%".$requestData['search']['value']."%' ";    
                $sql.=" OR description LIKE '".$requestData['search']['value']."%' ";
                $sql.=" OR image LIKE '".$requestData['search']['value']."%' ) ";
        }
        $query = $dbObj->query($sql);
        $totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
        $sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
        /* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	

        echo $pubCatObj->fetchForJQDT($requestData['draw'], $totalData, $totalFiltered, $sql);
    }
    
    if(filter_input(INPUT_POST, "deleteThisPatent")!=NULL){
        $postVars = array('id','image'); // Form fields names
        //Validate the POST variables and add up to error message if empty
        foreach ($postVars as $postVar){
            switch($postVar){
                case 'image':   $pubCatObj->$postVar = filter_input(INPUT_POST, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_POST, $postVar)) :  ''; 
                                $pubCatMedFil = $pubCatObj->$postVar;
                                if($pubCatObj->$postVar === "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
                default     :   $pubCatObj->$postVar = filter_input(INPUT_POST, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_POST, $postVar)) :  ''; 
                                if($pubCatObj->$postVar === "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
            }
        }
        //If validated and not empty submit it to database
        if(count($errorArr) < 1)   {
            $imageDelParam = true;
            if($courseImage!='' && file_exists(MEDIA_FILES_PATH."patent/".$pubCatMedFil)){
                if(unlink(MEDIA_FILES_PATH."patent/".$pubCatMedFil)){ $imageDelParam = true;}
                else $imageDelParam = false;
            }
            if($imageDelParam ==true){ echo $pubCatObj->delete(); }
            else{ 
                $json = array("status" => 0, "msg" => $errorArr); 
                $dbObj->close();//Close Database Connection
                header('Content-type: application/json');
                echo json_encode($json);
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
    
    if(filter_input(INPUT_POST, "addNewPatent") != NULL && filter_input(INPUT_POST, "addNewPatent")=="editPatent"){
        $postVars = array('id', 'name', 'description', 'issuanceDate', 'image'); // Form fields names
        $oldMedia = $_REQUEST['oldFile'];
        //Validate the POST variables and add up to error message if empty
        foreach ($postVars as $postVar){
            switch($postVar){
                case 'image':   $newMedia = basename($_FILES["image"]["name"]) ? rand(100000, 1000000)."_".".".pathinfo(basename($_FILES["image"]["name"]),PATHINFO_EXTENSION): ""; 
                                $pubCatObj->$postVar = $newMedia;
                                if($pubCatObj->$postVar == "") { $pubCatObj->$postVar = $oldMedia;}
                                $pubCatMedFil = $newMedia;
                                break;
                default     :   $pubCatObj->$postVar = filter_input(INPUT_POST, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_POST, $postVar)) :  ''; 
                                if($pubCatObj->$postVar === "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
            }
        }
        //If validated and not empty submit it to database
        if(count($errorArr) < 1)   {
            //$target_dir = "../project-files/";
            $target_file = MEDIA_FILES_PATH."patent/". $pubCatMedFil;
            $uploadOk = 1; $msg = '';
            $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
           
            if($newMedia !=""){
                if (move_uploaded_file($_FILES["image"]["tmp_name"], MEDIA_FILES_PATH."patent/".$pubCatMedFil)) {
                    $msg .= "The file ". basename( $_FILES["image"]["name"]). " has been uploaded.";
                    $status = 'ok';
                    if(file_exists($oldMedia) && $oldMedia!="") unlink(MEDIA_FILES_PATH."patent/".$oldMedia);
                    echo $pubCatObj->update();
                } else {
                    $msg = " Sorry, there was an error uploading your  media. ERROR: ".$msg;
                    $json = array("status" => 0, "msg" => $msg); 
                    $dbObj->close();//Close Database Connection
                    header('Content-type: application/json');
                    echo json_encode($json);
                }
            } 
            else{
                echo $pubCatObj->update();
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
}