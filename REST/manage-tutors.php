<?php
session_start();
define("CONST_FILE_PATH", "../includes/constants.php");
include ('../classes/WebPage.php'); //Set up page as a web page
$thisPage = new WebPage(); //Create new instance of webPage class

$dbObj = new Database();//Instantiate database
$tutorObj = new Tutor($dbObj); // Create an object of Tutor class
$errorArr = array(); //Array of errors
$oldPicture=""; $newPicture =""; $tutorPictureFil="";

if(!isset($_SESSION['ITCLoggedInAdmin']) || !isset($_SESSION["ITCadminEmail"])){ 
    $json = array("status" => 0, "msg" => "You are not logged in."); 
    header('Content-type: application/json');
    echo json_encode($json);
}
else{
    if(filter_input(INPUT_POST, "fetchTutors") != NULL){
        $requestData= $_REQUEST;
        $columns = array( 0 =>'id', 1 =>'id', 2 => 'visible',  3 => 'picture', 4 => 'name', 5 => 'qualification', 6 => 'field', 7 => 'bio', 8 => 'email', 9 => 'website');

        // getting total number records without any search
        $query = $dbObj->query("SELECT * FROM tutor ");
        $totalData = mysqli_num_rows($query);
        $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

        $sql = "SELECT * FROM tutor WHERE 1=1 "; //id, name, short_name, category, start_date, code, description, media, amount, date_registered
        if(!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
                $sql.=" AND ( name LIKE '%".$requestData['search']['value']."%' ";    
                $sql.=" OR qualification LIKE '%".$requestData['search']['value']."%' ";
                $sql.=" OR field LIKE '%".$requestData['search']['value']."%' ";
                $sql.=" OR bio LIKE '%".$requestData['search']['value']."%' ";
                $sql.=" OR email LIKE '%".$requestData['search']['value']."%' ";
                $sql.=" OR username LIKE '%".$requestData['search']['value']."%' ";
                $sql.=" OR website LIKE '%".$requestData['search']['value']."%' ) ";
        }
        $query = $dbObj->query($sql);
        $totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
        $sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
        /* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	

        echo $tutorObj->fetchForJQDT($requestData['draw'], $totalData, $totalFiltered, $sql);
    }
    
    if(filter_input(INPUT_POST, "deleteThisTutor")!=NULL){
        $postVars = array('id',  'picture'); // Form fields names
        $tutorImage = "";
        //Validate the POST variables and add up to error message if empty
        foreach ($postVars as $postVar){
            switch($postVar){
                case 'picture':   $tutorObj->$postVar = filter_input(INPUT_POST, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_POST, $postVar)) :  ''; 
                                $tutorImage = $tutorObj->$postVar;
                                if($tutorObj->$postVar === "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
                default     :   $tutorObj->$postVar = filter_input(INPUT_POST, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_POST, $postVar)) :  ''; 
                                if($tutorObj->$postVar === "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
            }
        }
        //If validated and not empty submit it to database
        if(count($errorArr) < 1)   {
            $pictureDelParam = true;
            if($tutorImage!='' && file_exists(MEDIA_FILES_PATH."tutor/".$tutorImage)){
                if(unlink(MEDIA_FILES_PATH."tutor/".$tutorImage)){ $pictureDelParam = true;}
                else { $pictureDelParam = false; }
            }
            if($pictureDelParam == true){ echo $tutorObj->delete(); }
            else{ 
                $json = array("status" => 0, "msg" => $errorArr); 
                $dbObj->close();//Close Database Connection
                header('Content-type: application/json');
                echo json_encode($json);
            }
        }
        else{ //Else show error messages
            $json = array("status" => 0, "msg" => $errorArr); 
            $dbObj->close();//Close Database Connection
            header('Content-type: application/json');
            echo json_encode($json);
        }

    } 
    
    if(filter_input(INPUT_GET, "activateTutor")!=NULL){
        $postVars = array('id', 'visible'); // Form fields names
        //Validate the POST variables and add up to error message if empty
        foreach ($postVars as $postVar){
            switch($postVar){
                case 'visible':  $tutorObj->$postVar = filter_input(INPUT_GET, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_GET, $postVar, FILTER_VALIDATE_INT)) :  0; 
                                if($tutorObj->$postVar == 1) {$tutorObj->$postVar = 0;} 
                                elseif($tutorObj->$postVar == 0) {$tutorObj->$postVar = 1;}
                                break;
                default     :   $tutorObj->$postVar = filter_input(INPUT_GET, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_GET, $postVar)) :  ''; 
                                if($tutorObj->$postVar === "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
            }
        }
        //If validated and not empty submit it to database
        if(count($errorArr) < 1)   {
            echo Tutor::updateSingle($dbObj, ' visible ',  $tutorObj->visible, $tutorObj->id); 
        }
        //Else show error messages
        else{ 
            $json = array("status" => 0, "msg" => $errorArr); 
            $dbObj->close();//Close Database Connection
            header('Content-type: application/json');
            echo json_encode($json);
        }

    }
    
    if(filter_input(INPUT_POST, "updateThisTutor") != NULL){
        $postVars = array('id', 'name','qualification','field','bio','email','website','picture');  // Form fields names
        $oldPicture = $_REQUEST['oldPicture'];
        //Validate the POST variables and add up to error message if empty
        foreach ($postVars as $postVar){
            switch($postVar){
                case 'picture':   $newPicture = basename($_FILES["picture"]["name"]) ? rand(100000, 1000000)."_".  strtolower(str_replace(" ", "_", filter_input(INPUT_POST, 'name'))).".".pathinfo(basename($_FILES["picture"]["name"]),PATHINFO_EXTENSION): ""; 
                                $tutorObj->$postVar = $newPicture;
                                if($tutorObj->$postVar == "") { $tutorObj->$postVar = $oldPicture;}
                                $tutorPictureFil = $newPicture;
                                break;
                default     :   $tutorObj->$postVar = filter_input(INPUT_POST, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_POST, $postVar)) :  ''; 
                                if($tutorObj->$postVar == "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
                
            }
        }
        //If validated and not empty submit it to database
        if(count($errorArr) < 1)   {
            $targetPicture = MEDIA_FILES_PATH."tutor/". $tutorPictureFil;
            $uploadOk = 1; $msg = '';
            
            if($newPicture !=""){
                if (move_uploaded_file($_FILES["picture"]["tmp_name"], $targetPicture)) {
                    $msg .= "The file ". basename( $_FILES["picture"]["name"]). " has been uploaded.";
                    $status = 'ok'; if(file_exists(MEDIA_FILES_PATH."tutor/".$oldPicture)) unlink(MEDIA_FILES_PATH."tutor/".$oldPicture); $uploadOk = 1;
                } else {
                    $uploadOk = 0;
                }
            }
            if($uploadOk == 1){
                echo $tutorObj->update();
            }
            else {
                    $msg = " Sorry, there was an error uploading your tutor picture. ERROR: ".$msg;
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
}