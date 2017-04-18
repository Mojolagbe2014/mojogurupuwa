<?php
session_start();
define("CONST_FILE_PATH", "../includes/constants.php");
include ('../classes/WebPage.php'); //Set up page as a web page
$thisPage = new WebPage(); //Create new instance of webPage class

$dbObj = new Database();//Instantiate database
$memberObj = new Member($dbObj); // Create an object of Member class
$errorArr = array(); //Array of errors
$oldPicture=""; $newPicture =""; $memberPictureFil="";

if(!isset($_SESSION['ITCLoggedInAdmin']) || !isset($_SESSION["ITCadminEmail"])){ 
    $json = array("status" => 0, "msg" => "You are not logged in."); 
    header('Content-type: application/json');
    echo json_encode($json);
}
else{
    if(filter_input(INPUT_POST, "fetchMembers") != NULL){
        $requestData= $_REQUEST;
        $columns = array( 0 =>'id', 1 =>'id', 2 => 'visible',  3 => 'picture', 4 => 'name', 5 => 'program', 6 => 'field', 7 => 'bio', 8 => 'email', 9 => 'website', 10 => 'twitter', 11 => 'facebook', 12 => 'linkedin');

        // getting total number records without any search
        $query = $dbObj->query("SELECT * FROM member ");
        $totalData = mysqli_num_rows($query);
        $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

        $sql = "SELECT * FROM member WHERE 1=1 "; //id, name, short_name, category, start_date, code, description, media, amount, date_registered
        if(!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
                $sql.=" AND ( name LIKE '%".$requestData['search']['value']."%' ";    
                $sql.=" OR program LIKE '%".$requestData['search']['value']."%' ";
                $sql.=" OR field LIKE '%".$requestData['search']['value']."%' ";
                $sql.=" OR bio LIKE '%".$requestData['search']['value']."%' ";
                $sql.=" OR email LIKE '%".$requestData['search']['value']."%' ";
                $sql.=" OR twitter LIKE '%".$requestData['search']['value']."%' ";
                $sql.=" OR facebook LIKE '%".$requestData['search']['value']."%' ";
                $sql.=" OR linkedin LIKE '%".$requestData['search']['value']."%' ";
                $sql.=" OR website LIKE '%".$requestData['search']['value']."%' ) ";
        }
        $query = $dbObj->query($sql);
        $totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
        $sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
        /* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	

        echo $memberObj->fetchForJQDT($requestData['draw'], $totalData, $totalFiltered, $sql);
    }
    
    if(filter_input(INPUT_POST, "deleteThisMember")!=NULL){
        $postVars = array('id',  'picture'); // Form fields names
        $memberImage = "";
        //Validate the POST variables and add up to error message if empty
        foreach ($postVars as $postVar){
            switch($postVar){
                case 'picture':   $memberObj->$postVar = filter_input(INPUT_POST, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_POST, $postVar)) :  ''; 
                                $memberImage = $memberObj->$postVar;
                                //if($memberObj->$postVar === "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
                default     :   $memberObj->$postVar = filter_input(INPUT_POST, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_POST, $postVar)) :  ''; 
                                if($memberObj->$postVar === "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
            }
        }
        //If validated and not empty submit it to database
        if(count($errorArr) < 1)   {
            $pictureDelParam = true;
            if($memberImage!='' && file_exists(MEDIA_FILES_PATH."member/".$memberImage)){
                unlink(MEDIA_FILES_PATH."member/".$memberImage);
            }
            if($pictureDelParam == true){ echo $memberObj->delete(); }
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
    
    if(filter_input(INPUT_GET, "activateMember")!=NULL){
        $postVars = array('id', 'visible'); // Form fields names
        //Validate the POST variables and add up to error message if empty
        foreach ($postVars as $postVar){
            switch($postVar){
                case 'visible':  $memberObj->$postVar = filter_input(INPUT_GET, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_GET, $postVar, FILTER_VALIDATE_INT)) :  0; 
                                if($memberObj->$postVar == 1) {$memberObj->$postVar = 0;} 
                                elseif($memberObj->$postVar == 0) {$memberObj->$postVar = 1;}
                                break;
                default     :   $memberObj->$postVar = filter_input(INPUT_GET, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_GET, $postVar)) :  ''; 
                                if($memberObj->$postVar === "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
            }
        }
        //If validated and not empty submit it to database
        if(count($errorArr) < 1)   {
            echo Member::updateSingle($dbObj, ' visible ',  $memberObj->visible, $memberObj->id); 
        }
        //Else show error messages
        else{ 
            $json = array("status" => 0, "msg" => $errorArr); 
            $dbObj->close();//Close Database Connection
            header('Content-type: application/json');
            echo json_encode($json);
        }

    }
    
    if(filter_input(INPUT_POST, "updateThisMember") != NULL){
        $postVars = array('id', 'name','program','field','bio','email','website','picture','twitter', 'facebook', 'linkedin');  // Form fields names
        $oldPicture = $_REQUEST['oldPicture'];
        //Validate the POST variables and add up to error message if empty
        foreach ($postVars as $postVar){
            switch($postVar){
                case 'picture':   $newPicture = basename($_FILES["picture"]["name"]) ? rand(100000, 1000000)."_".  strtolower(str_replace(" ", "_", filter_input(INPUT_POST, 'name'))).".".pathinfo(basename($_FILES["picture"]["name"]),PATHINFO_EXTENSION): ""; 
                                $memberObj->$postVar = $newPicture;
                                if($memberObj->$postVar == "") { $memberObj->$postVar = $oldPicture;}
                                $memberPictureFil = $newPicture;
                                break;
                case 'website': $memberObj->$postVar = filter_input(INPUT_POST, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_POST, $postVar)) :  ''; 
                                break;
                case 'twitter': $memberObj->$postVar = filter_input(INPUT_POST, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_POST, $postVar)) :  ''; 
                                break;
                case 'facebook': $memberObj->$postVar = filter_input(INPUT_POST, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_POST, $postVar)) :  ''; 
                                break;
                case 'linkedin': $memberObj->$postVar = filter_input(INPUT_POST, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_POST, $postVar)) :  ''; 
                                break;

                default     :   $memberObj->$postVar = filter_input(INPUT_POST, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_POST, $postVar)) :  ''; 
                                if($memberObj->$postVar == "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
                
            }
        }
        //If validated and not empty submit it to database
        if(count($errorArr) < 1)   {
            $targetPicture = MEDIA_FILES_PATH."member/". $memberPictureFil;
            $uploadOk = 1; $msg = '';
            
            if($newPicture !=""){
                if(Imaging::checkDimension($_FILES["picture"]["tmp_name"], 270, 220, 'equ', 'both') != 'true'){$uploadOk = 0; $msg = Imaging::checkDimension($_FILES["picture"]["tmp_name"], 270, 220, 'equ', 'both');}
                if ($uploadOk == 1 && move_uploaded_file($_FILES["picture"]["tmp_name"], MEDIA_FILES_PATH."member/".$memberPictureFil)) {
                    $msg .= "The file ". basename( $_FILES["picture"]["name"]). " has been uploaded.";
                    $status = 'ok'; if($oldPicture!='' && file_exists(MEDIA_FILES_PATH."member/".$oldPicture))unlink(MEDIA_FILES_PATH."member/".$oldPicture);
                } else { $uploadOk = 0; }
            }
            if($uploadOk == 1){ echo $memberObj->update(); }
            else {
                    $msg = " Sorry, there was an error uploading your member picture. ERROR: ".$msg;
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
    
    if(filter_input(INPUT_GET, "setGraduationStatus")!=NULL){
        $postVars = array('id', 'graduated'); // Form fields names
        //Validate the POST variables and add up to error message if empty
        foreach ($postVars as $postVar){
            switch($postVar){
                case 'graduated':  $memberObj->$postVar = filter_input(INPUT_GET, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_GET, $postVar, FILTER_VALIDATE_INT)) :  0; 
                                if($memberObj->$postVar == 1) {$memberObj->$postVar = 0;} 
                                elseif($memberObj->$postVar == 0) {$memberObj->$postVar = 1;}
                                break;
                default     :   $memberObj->$postVar = filter_input(INPUT_GET, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_GET, $postVar)) :  ''; 
                                if($memberObj->$postVar === "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
            }
        }
        //If validated and not empty submit it to database
        if(count($errorArr) < 1)   {
            echo Member::updateSingle($dbObj, ' graduated ',  $memberObj->graduated, $memberObj->id); 
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