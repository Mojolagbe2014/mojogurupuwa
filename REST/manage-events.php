<?php
session_start();
define("CONST_FILE_PATH", "../includes/constants.php");
include ('../classes/WebPage.php'); //Set up page as a web page
$thisPage = new WebPage(); //Create new instance of webPage class

$dbObj = new Database();//Instantiate database
$eventObj = new Event($dbObj); // Create an object of Event class
$errorArr = array(); //Array of errors
$eventImage ="";  $newImage = ""; 

if(!isset($_SESSION['ITCLoggedInAdmin']) || !isset($_SESSION["ITCadminEmail"])){ 
    $json = array("status" => 0, "msg" => "You are not logged in."); 
    header('Content-type: application/json');
    echo json_encode($json);
}
else{
    if(filter_input(INPUT_POST, "addNewEvent") != NULL && filter_input(INPUT_POST, "addNewEvent")=="addNewEvent"){
        $postVars = array('name', 'description', 'location', 'image', 'dateTime'); // Form fields names
        //Validate the POST variables and add up to error message if empty
        foreach ($postVars as $postVar){
            switch($postVar){
                case 'image':   $eventObj->$postVar = basename($_FILES["image"]["name"]) ? rand(100000, 1000000)."_".  strtolower(str_replace(" ", "_", filter_input(INPUT_POST, 'name'))).".".pathinfo(basename($_FILES["image"]["name"]),PATHINFO_EXTENSION): ""; 
                                $eventImage = $eventObj->$postVar;
                                if($eventObj->$postVar == "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
                default     :   $eventObj->$postVar = filter_input(INPUT_POST, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_POST, $postVar)) :  ''; 
                                if($eventObj->$postVar === "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
            }
        }
        //If validated and not empty submit it to database
        if(count($errorArr) < 1)   {
            $targetImage = MEDIA_FILES_PATH."event/". $eventImage;
            $uploadOk = 1; $msg = '';
            //$imageFileType = pathinfo($targetLogo, PATHINFO_EXTENSION);
            if ($eventImage!='' && file_exists($targetImage)) { $msg .= " Event image already exists."; $uploadOk = 0; }
            if ($_FILES["image"]["size"] > 80000000) { $msg .= " Event image is too large."; $uploadOk = 0; }
            if ($uploadOk == 0) {
                $msg = "Sorry, your event logo was not uploaded. ERROR: ".$msg;
                $json = array("status" => 0, "msg" => $msg); 
                header('Content-type: application/json');
                echo json_encode($json);
            } 
            else {
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetImage)) {
                    $msg .= "The image has been uploaded.";
                    $status = 'ok';
                    echo $eventObj->add();
                } else {
                    $msg = " Sorry, there was an error uploading your event image. ERROR: ".$msg;
                    $json = array("status" => 0, "msg" => $msg); 
                    $dbObj->close();//Close Database Connection
                    header('Content-type: application/json');
                    echo json_encode($json);
                }
            }
        }
        else{ 
            $json = array("status" => 0, "msg" => $errorArr); 
            $dbObj->close();//Close Database Connection
            header('Content-type: application/json');
            echo json_encode($json);
        }
    } 
    
    if(filter_input(INPUT_POST, "fetchEvents") != NULL){
        $requestData= $_REQUEST;
        $columns = array( 0 =>'id', 1 =>'id', 2 => 'status', 3 => 'name', 4 => 'image', 5 => 'description', 6 => 'date_time', 7 => 'location', 8 =>'date_added');

        // getting total number records without any search
        $query = $dbObj->query("SELECT * FROM event ");
        $totalData = mysqli_num_rows($query);
        $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

        $sql = "SELECT * FROM event WHERE 1=1 ";
        if(!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
                $sql.=" AND ( name LIKE '%".$requestData['search']['value']."%' ";    
                $sql.=" OR location LIKE '%".$requestData['search']['value']."%' ";
                $sql.=" OR date_time LIKE '%".$requestData['search']['value']."%' ";
                $sql.=" OR description LIKE '%".$requestData['search']['value']."%' ";
                $sql.=" OR date_added LIKE '%".$requestData['search']['value']."%' ) ";
        }
        $query = $dbObj->query($sql);
        $totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
        $sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
        /* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	

        echo $eventObj->fetchForJQDT($requestData['draw'], $totalData, $totalFiltered, $sql);
    }
    
    if(filter_input(INPUT_POST, "deleteThisEvent")!=NULL){
        $postVars = array('id', 'image'); // Form fields names
        $eventImage ="";  
        //Validate the POST variables and add up to error message if empty
        foreach ($postVars as $postVar){
            switch($postVar){
                case 'image': $eventObj->$postVar = filter_input(INPUT_POST, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_POST, $postVar)) :  ''; 
                                $eventImage = $eventObj->$postVar;
                                if($eventObj->$postVar === "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
                default     :   $eventObj->$postVar = filter_input(INPUT_POST, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_POST, $postVar)) :  ''; 
                                if($eventObj->$postVar === "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
            }
        }
        //If validated and not empty submit it to database
        if(count($errorArr) < 1)   {
            $logoDelParam = true;
            if(file_exists(MEDIA_FILES_PATH."event/".$eventImage) && !empty($eventImage)){
                if(unlink(MEDIA_FILES_PATH."event/".$eventImage)){ $logoDelParam = true;}
                else { $logoDelParam = false; }
            }
            if($logoDelParam == true){ echo $eventObj->delete(); }
            else{ 
                $json = array("status" => 0, "msg" => $errorArr); 
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
    
    if(filter_input(INPUT_POST, "addNewEvent") != NULL && filter_input(INPUT_POST, "addNewEvent")=="editEvent"){
        $postVars = array('id', 'name', 'description', 'location', 'image', 'dateTime'); // Form fields names 
        $oldImage = $_REQUEST['oldImage'];
        //Validate the POST variables and add up to error message if empty
        foreach ($postVars as $postVar){
            switch($postVar){
                case 'image':   $eventObj->$postVar = basename($_FILES["image"]["name"]) ? rand(100000, 1000000)."_".  strtolower(str_replace(" ", "_", filter_input(INPUT_POST, 'name'))).".".pathinfo(basename($_FILES["image"]["name"]),PATHINFO_EXTENSION): ""; 
                                $newImage = $eventObj->$postVar;
                                if($eventObj->$postVar == "") { $eventObj->$postVar = $oldImage;}
                                break;
                default     :   $eventObj->$postVar = filter_input(INPUT_POST, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_POST, $postVar)) :  ''; 
                                if($eventObj->$postVar === "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
            }
        }
        //If validated and not empty submit it to database
        if(count($errorArr) < 1)   {
            $targetImage = MEDIA_FILES_PATH."event/". $newImage;
            $uploadOk = 1; $msg = '';
            if($newImage !=""){
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetImage)) {
                    $msg .= "The file ". basename( $_FILES["image"]["name"]). " has been uploaded.";
                    $status = 'ok'; if(file_exists(MEDIA_FILES_PATH."event/".$oldImage)) unlink(MEDIA_FILES_PATH."event/".$oldImage); $uploadOk = 1;
                } else { $uploadOk = 0; }
            }
            if($uploadOk == 1){ echo $eventObj->update(); }
            else {
                    $msg = " Sorry, there was an error uploading your event logo. ERROR: ".$msg;
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

    if(filter_input(INPUT_GET, "activateEvent")!=NULL){
        $postVars = array('id', 'status'); // Form fields names
        //Validate the POST variables and add up to error message if empty
        foreach ($postVars as $postVar){
            switch($postVar){
                case 'status':  $eventObj->$postVar = filter_input(INPUT_GET, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_GET, $postVar, FILTER_VALIDATE_INT)) :  0; 
                                if($eventObj->$postVar == 1) {$eventObj->$postVar = 0;} 
                                elseif($eventObj->$postVar == 0) {$eventObj->$postVar = 1;}
                                break;
                default     :   $eventObj->$postVar = filter_input(INPUT_GET, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_GET, $postVar)) :  ''; 
                                if($eventObj->$postVar === "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
            }
        }
        //If validated and not empty submit it to database
        if(count($errorArr) < 1)   {
            echo Event::updateSingle($dbObj, ' status ',  $eventObj->status, $eventObj->id); 
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