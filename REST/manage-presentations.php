<?php
session_start();
define("CONST_FILE_PATH", "../includes/constants.php");
include ('../classes/WebPage.php'); //Set up page as a web page
$thisPage = new WebPage(); //Create new instance of webPage class

$dbObj = new Database();//Instantiate database
$presentationObj = new Presentation($dbObj); // Create an object of Presentation class
$errorArr = array(); //Array of errors
$oldMedia = ""; $newMedia =""; $oldImage=""; $newImage =""; $presentationImageFil="";

if(!isset($_SESSION['ITCLoggedInAdmin']) || !isset($_SESSION["ITCadminEmail"])){ 
    $json = array("status" => 0, "msg" => "You are not logged in."); 
    echo json_encode($json);
}
else{
    if(filter_input(INPUT_POST, "fetchPresentations") != NULL){
        $requestData= $_REQUEST;
        $columns = array( 0 =>'id', 1 =>'id', 2 =>'id', 3 => 'name', 4 => 'organizer', 5 => 'location', 6 => 'date_presented', 7 => 'description', 8 => 'media', 9 => 'status', 10 => 'date_registered');

        // getting total number records without any search
        $query = $dbObj->query("SELECT * FROM presentation ");
        $totalData = mysqli_num_rows($query);
        $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

        $sql = "SELECT * FROM presentation WHERE 1=1 "; //id, name, short_name, category, start_date, code, description, media, amount, date_registered
        if(!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
                $sql.=" AND ( name LIKE '%".$requestData['search']['value']."%' ";    
                $sql.=" OR location LIKE '%".$requestData['search']['value']."%' ";
                $sql.=" OR description LIKE '%".$requestData['search']['value']."%' ";
                $sql.=" OR media LIKE '%".$requestData['search']['value']."%' ";
                $sql.=" OR date_presented LIKE '%".$requestData['search']['value']."%' ";
                $sql.=" OR date_registered LIKE '%".$requestData['search']['value']."%' ";
                $sql.=" OR organizer LIKE '%".$requestData['search']['value']."%' ) ";
        }
        $query = $dbObj->query($sql);
        $totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
        $sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
        /* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	

        echo $presentationObj->fetchForJQDT($requestData['draw'], $totalData, $totalFiltered, $sql);
    }
    
    if(filter_input(INPUT_POST, "deleteThisPresentation")!=NULL){
        $postVars = array('id', 'media', 'image'); // Form fields names
        $presentationMedia = "";
        //Validate the POST variables and add up to error message if empty
        foreach ($postVars as $postVar){
            switch($postVar){
                case 'media':   $presentationObj->$postVar = filter_input(INPUT_POST, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_POST, $postVar)) :  ''; 
                                $presentationMedia = $presentationObj->$postVar;
                                //if($presentationObj->$postVar === "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
                case 'image':   $presentationObj->$postVar = filter_input(INPUT_POST, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_POST, $postVar)) :  ''; 
                                $presentationImage = $presentationObj->$postVar;
                                if($presentationObj->$postVar === "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
                default     :   $presentationObj->$postVar = filter_input(INPUT_POST, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_POST, $postVar)) :  ''; 
                                if($presentationObj->$postVar === "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
            }
        }
        //If validated and not empty submit it to database
        if(count($errorArr) < 1)   {
            $fileDelParam = true; $imageDelParam = true;
            if($presentationMedia!='' && file_exists(MEDIA_FILES_PATH."presentation/".$presentationMedia)){
                if(unlink(MEDIA_FILES_PATH."presentation/".$presentationMedia)){ $fileDelParam = true;}
                else $fileDelParam = false;
            }
            if($presentationImage!='' && file_exists(MEDIA_FILES_PATH."presentation-image/".$presentationImage)){
                if(unlink(MEDIA_FILES_PATH."presentation-image/".$presentationImage)){ $imageDelParam = true;}
                else $imageDelParam = false;
            }
            if($fileDelParam == true && $imageDelParam ==true){ echo $presentationObj->delete(); }
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
    
    if(filter_input(INPUT_GET, "activePresentation")!=NULL){
        $postVars = array('id', 'status'); // Form fields names
        //Validate the POST variables and add up to error message if empty
        foreach ($postVars as $postVar){
            switch($postVar){
                case 'status':  $presentationObj->$postVar = filter_input(INPUT_GET, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_GET, $postVar, FILTER_VALIDATE_INT)) :  0; 
                                if($presentationObj->$postVar == 1) {$presentationObj->$postVar = 0;} 
                                elseif($presentationObj->$postVar == 0) {$presentationObj->$postVar = 1;}
//                                if($presentationObj->$postVar === "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
                default     :   $presentationObj->$postVar = filter_input(INPUT_GET, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_GET, $postVar)) :  ''; 
                                if($presentationObj->$postVar === "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
            }
        }
        //If validated and not empty submit it to database
        if(count($errorArr) < 1)   {
            echo Presentation::updateSingle($dbObj, ' status ',  $presentationObj->status, $presentationObj->id); 
        }
        //Else show error messages
        else{ 
            $json = array("status" => 0, "msg" => $errorArr); 
            $dbObj->close();//Close Database Connection
            header('Content-type: application/json');
            echo json_encode($json);
        }

    }
    
    if(filter_input(INPUT_POST, "updateThisPresentation") != NULL){
        $postVars = array('id','name','organizer','location','datePresented','description','media','image'); // Form fields names
        $oldMedia = $_REQUEST['oldFile']; $oldImage = $_REQUEST['oldImage'];
        //Validate the POST variables and add up to error message if empty
        foreach ($postVars as $postVar){
            switch($postVar){
                case 'media':   $newMedia = basename($_FILES["file"]["name"]) ? rand(100000, 1000000)."_".  strtolower(str_replace("-", "_", filter_input(INPUT_POST, 'datePresented'))).".".pathinfo(basename($_FILES["file"]["name"]),PATHINFO_EXTENSION): ""; 
                                $presentationObj->$postVar = $newMedia;
                                if($presentationObj->$postVar == ''){$presentationObj->$postVar = $oldMedia;}
                                $presentationMedFil = $newMedia;
                                break;
                case 'image':   $newImage = basename($_FILES["image"]["name"]) ? rand(100000, 1000000)."_".  strtolower(str_replace("-", "_", filter_input(INPUT_POST, 'datePresented'))).".".pathinfo(basename($_FILES["image"]["name"]),PATHINFO_EXTENSION): ""; 
                                $presentationObj->$postVar = $newImage;
                                if($presentationObj->$postVar == "") { $presentationObj->$postVar = $oldImage;}
                                $presentationImageFil = $newImage;
                                break;
                default     :   $presentationObj->$postVar = filter_input(INPUT_POST, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_POST, $postVar)) :  ''; 
                                if($presentationObj->$postVar == "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
                
            }
        }
        //If validated and not empty submit it to database
        if(count($errorArr) < 1)   {
            //$target_dir = "../project-files/";
            $target_file = MEDIA_FILES_PATH."presentation/". $presentationMedFil;
            $target_Image = MEDIA_FILES_PATH."presentation-image/". $presentationImageFil;
            $uploadOk = 1; $msg = '';
            $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
            
            if($newMedia !=""){
                if (move_uploaded_file($_FILES["file"]["tmp_name"], MEDIA_FILES_PATH."presentation/".$presentationMedFil)) {
                    $msg .= "The file ". basename( $_FILES["file"]["name"]). " has been uploaded.";
                    $status = 'ok'; if($oldMedia!='' && file_exists(MEDIA_FILES_PATH."presentation/".$oldMedia)) unlink(MEDIA_FILES_PATH."presentation/".$oldMedia);
                } else {
                    $uploadOk = 0;
                }
            }
            if($newImage !=""){
                if(Imaging::checkDimension($_FILES["image"]["tmp_name"], 400, 400, 'min', 'both') != 'true'){$uploadOk = 0; $msg = Imaging::checkDimension($_FILES["image"]["tmp_name"], 400, 400, 'min', 'both');}
                if ($uploadOk == 1 && move_uploaded_file($_FILES["image"]["tmp_name"], MEDIA_FILES_PATH."presentation-image/".$presentationImageFil)) {
                    $msg .= "The file ". basename( $_FILES["image"]["name"]). " has been uploaded.";
                    $status = 'ok'; if($oldImage!='' && file_exists(MEDIA_FILES_PATH."presentation-image/".$oldImage))unlink(MEDIA_FILES_PATH."presentation-image/".$oldImage);
                } else { $uploadOk = 0; }
            }
            
            if($uploadOk == 1){  echo $presentationObj->update();  }
            else {
                $msg = " Sorry, there was an error uploading your presentation media. ERROR: ".$msg;
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
    
    if(filter_input(INPUT_GET, "makeFeaturedPresentation")!=NULL){
        $postVars = array('id', 'featured'); // Form fields names
        //Validate the POST variables and add up to error message if empty
        foreach ($postVars as $postVar){
            switch($postVar){
                case 'featured':  $presentationObj->$postVar = filter_input(INPUT_GET, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_GET, $postVar, FILTER_VALIDATE_INT)) :  0; 
                                if($presentationObj->$postVar == 1) {$presentationObj->$postVar = 0;} 
                                elseif($presentationObj->$postVar == 0) {$presentationObj->$postVar = 1;}
                                break;
                default     :   $presentationObj->$postVar = filter_input(INPUT_GET, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_GET, $postVar)) :  ''; 
                                if($presentationObj->$postVar === "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
            }
        }
        //If validated and not empty submit it to database
        if(count($errorArr) < 1)   {
            echo Presentation::updateSingle($dbObj, ' featured ',  $presentationObj->featured, $presentationObj->id); 
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