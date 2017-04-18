<?php
session_start();
define("CONST_FILE_PATH", "../includes/constants.php");
include ('../classes/WebPage.php'); //Set up page as a web page
$thisPage = new WebPage(); //Create new instance of webPage class

$dbObj = new Database();//Instantiate database
$projectObj = new Project($dbObj); // Create an object of Project class
$errorArr = array(); //Array of errors
$oldMedia = ""; $newMedia =""; $oldImage=""; $newImage =""; $projectImageFil="";

if(!isset($_SESSION['ITCLoggedInAdmin']) || !isset($_SESSION["ITCadminEmail"])){ 
    $json = array("status" => 0, "msg" => "You are not logged in."); 
    echo json_encode($json);
}
else{
    if(filter_input(INPUT_POST, "fetchProjects") != NULL){
        $requestData= $_REQUEST;
        $columns = array( 0 =>'id', 1 =>'id', 2 =>'id', 3 => 'name', 4 => 'is_completed', 5 => 'sponsor', 6 => 'start_date', 7 => 'end_date', 8 => 'description', 9 => 'media', 10 => 'status', 11 => 'date_registered');

        // getting total number records without any search
        $query = $dbObj->query("SELECT * FROM project ");
        $totalData = mysqli_num_rows($query);
        $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

        $sql = "SELECT * FROM project WHERE 1=1 "; //id, name, short_name, category, start_date, code, description, media, amount, date_registered
        if(!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
                $sql.=" AND ( name LIKE '%".$requestData['search']['value']."%' ";  
                $sql.=" OR description LIKE '%".$requestData['search']['value']."%' ";
                $sql.=" OR media LIKE '%".$requestData['search']['value']."%' ";
                $sql.=" OR start_date LIKE '%".$requestData['search']['value']."%' ";
                $sql.=" OR date_registered LIKE '%".$requestData['search']['value']."%' ) ";
        }
        $query = $dbObj->query($sql);
        $totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
        $sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
        /* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	

        echo $projectObj->fetchForJQDT($requestData['draw'], $totalData, $totalFiltered, $sql);
    }
    
    if(filter_input(INPUT_POST, "deleteThisProject")!=NULL){
        $postVars = array('id', 'media', 'image'); // Form fields names
        $projectMedia = "";
        //Validate the POST variables and add up to error message if empty
        foreach ($postVars as $postVar){
            switch($postVar){
                case 'media':   $projectObj->$postVar = filter_input(INPUT_POST, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_POST, $postVar)) :  ''; 
                                $projectMedia = $projectObj->$postVar;
                                //if($projectObj->$postVar === "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
                case 'image':   $projectObj->$postVar = filter_input(INPUT_POST, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_POST, $postVar)) :  ''; 
                                $projectImage = $projectObj->$postVar;
                                if($projectObj->$postVar === "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
                default     :   $projectObj->$postVar = filter_input(INPUT_POST, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_POST, $postVar)) :  ''; 
                                if($projectObj->$postVar === "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
            }
        }
        //If validated and not empty submit it to database
        if(count($errorArr) < 1)   {
            $fileDelParam = true; $imageDelParam = true;
            if($projectMedia!='' && file_exists(MEDIA_FILES_PATH."project/".$projectMedia)){
                unlink(MEDIA_FILES_PATH."project/".$projectMedia);
            }
            if($projectImage!='' && file_exists(MEDIA_FILES_PATH."project-image/".$projectImage)){
                unlink(MEDIA_FILES_PATH."project-image/".$projectImage);
            }
            if($fileDelParam == true && $imageDelParam ==true){ echo $projectObj->delete(); }
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
    
    if(filter_input(INPUT_GET, "activeProject")!=NULL){
        $postVars = array('id', 'status'); // Form fields names
        //Validate the POST variables and add up to error message if empty
        foreach ($postVars as $postVar){
            switch($postVar){
                case 'status':  $projectObj->$postVar = filter_input(INPUT_GET, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_GET, $postVar, FILTER_VALIDATE_INT)) :  0; 
                                if($projectObj->$postVar == 1) {$projectObj->$postVar = 0;} 
                                elseif($projectObj->$postVar == 0) {$projectObj->$postVar = 1;}
//                                if($projectObj->$postVar === "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
                default     :   $projectObj->$postVar = filter_input(INPUT_GET, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_GET, $postVar)) :  ''; 
                                if($projectObj->$postVar === "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
            }
        }
        //If validated and not empty submit it to database
        if(count($errorArr) < 1)   {
            echo Project::updateSingle($dbObj, ' status ',  $projectObj->status, $projectObj->id); 
        }
        //Else show error messages
        else{ 
            $json = array("status" => 0, "msg" => $errorArr); 
            $dbObj->close();//Close Database Connection
            header('Content-type: application/json');
            echo json_encode($json);
        }

    }
    
    if(filter_input(INPUT_POST, "updateThisProject") != NULL){
        $postVars = array('id','name','isCompleted','sponsor','startDate','endDate','description','media','image'); // Form fields names
        $oldMedia = $_REQUEST['oldFile']; $oldImage = $_REQUEST['oldImage'];
        //Validate the POST variables and add up to error message if empty
        foreach ($postVars as $postVar){
            switch($postVar){
                case 'media':   $newMedia = basename($_FILES["file"]["name"]) ? rand(100000, 1000000)."_".  strtolower(str_replace(" ", "_", filter_input(INPUT_POST, 'isCompleted'))).".".pathinfo(basename($_FILES["file"]["name"]),PATHINFO_EXTENSION): ""; 
                                $projectObj->$postVar = $newMedia;
                                if($projectObj->$postVar == ''){$projectObj->$postVar = $oldMedia;}
                                $projectMedFil = $newMedia;
                                break;
                case 'image':   $newImage = basename($_FILES["image"]["name"]) ? rand(100000, 1000000)."_".  strtolower(str_replace(" ", "_", filter_input(INPUT_POST, 'isCompleted'))).".".pathinfo(basename($_FILES["image"]["name"]),PATHINFO_EXTENSION): ""; 
                                $projectObj->$postVar = $newImage;
                                if($projectObj->$postVar == "") { $projectObj->$postVar = $oldImage;}
                                $projectImageFil = $newImage;
                                break;
                default     :   $projectObj->$postVar = filter_input(INPUT_POST, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_POST, $postVar)) :  ''; 
                                if($projectObj->$postVar == "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
                
            }
        }
        //If validated and not empty submit it to database
        if(count($errorArr) < 1)   {
            //$target_dir = "../project-files/";
            $target_file = MEDIA_FILES_PATH."project/". $projectMedFil;
            $target_Image = MEDIA_FILES_PATH."project-image/". $projectImageFil;
            $uploadOk = 1; $msg = '';
            $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
            
            if($newMedia !=""){
                if (move_uploaded_file($_FILES["file"]["tmp_name"], MEDIA_FILES_PATH."project/".$projectMedFil)) {
                    $msg .= "The file ". basename( $_FILES["file"]["name"]). " has been uploaded.";
                    $status = 'ok'; if($oldMedia!='' && file_exists(MEDIA_FILES_PATH."project/".$oldMedia)) unlink(MEDIA_FILES_PATH."project/".$oldMedia);
                } else {
                    $uploadOk = 0;
                }
            }
            if($newImage !=""){
                if(Imaging::checkDimension($_FILES["image"]["tmp_name"], 790, 420, 'equ', 'both') != 'true'){$uploadOk = 0; $msg = Imaging::checkDimension($_FILES["image"]["tmp_name"], 790, 420, 'equ', 'both');}
                if ($uploadOk == 1 && move_uploaded_file($_FILES["image"]["tmp_name"], MEDIA_FILES_PATH."project-image/".$projectImageFil)) {
                    $msg .= "The file ". basename( $_FILES["image"]["name"]). " has been uploaded.";
                    $status = 'ok'; if($oldImage!='' && file_exists(MEDIA_FILES_PATH."project-image/".$oldImage))unlink(MEDIA_FILES_PATH."project-image/".$oldImage);
                } else { $uploadOk = 0; }
            }
            
            if($uploadOk == 1){  echo $projectObj->update();  }
            else {
                $msg = " Sorry, there was an error uploading your project media. ERROR: ".$msg;
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
    
    if(filter_input(INPUT_GET, "makeFeaturedProject")!=NULL){
        $postVars = array('id', 'featured'); // Form fields names
        //Validate the POST variables and add up to error message if empty
        foreach ($postVars as $postVar){
            switch($postVar){
                case 'featured':  $projectObj->$postVar = filter_input(INPUT_GET, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_GET, $postVar, FILTER_VALIDATE_INT)) :  0; 
                                if($projectObj->$postVar == 1) {$projectObj->$postVar = 0;} 
                                elseif($projectObj->$postVar == 0) {$projectObj->$postVar = 1;}
                                break;
                default     :   $projectObj->$postVar = filter_input(INPUT_GET, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_GET, $postVar)) :  ''; 
                                if($projectObj->$postVar === "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
            }
        }
        //If validated and not empty submit it to database
        if(count($errorArr) < 1)   {
            echo Project::updateSingle($dbObj, ' featured ',  $projectObj->featured, $projectObj->id); 
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