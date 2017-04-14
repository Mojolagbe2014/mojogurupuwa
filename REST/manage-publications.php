<?php
session_start();
define("CONST_FILE_PATH", "../includes/constants.php");
include ('../classes/WebPage.php'); //Set up page as a web page
$thisPage = new WebPage(); //Create new instance of webPage class

$dbObj = new Database();//Instantiate database
$publicationObj = new Publication($dbObj); // Create an object of PublicationCategory class
$errorArr = array(); //Array of errors
$oldMedia = ""; $newMedia =""; $oldImage=""; $newImage =""; $publicationImageFil="";

if(!isset($_SESSION['ITCLoggedInAdmin']) || !isset($_SESSION["ITCadminEmail"])){ 
    $json = array("status" => 0, "msg" => "You are not logged in."); 
    echo json_encode($json);
}
else{
    if(filter_input(INPUT_POST, "fetchPublications") != NULL){
        $requestData= $_REQUEST;
        $columns = array( 0 =>'id', 1 =>'id', 2 =>'id', 3 => 'name', 4 => 'category', 5 => 'date_published', 6 => 'description', 7 => 'media', 8 => 'status', 9 => 'date_registered');

        // getting total number records without any search
        $query = $dbObj->query("SELECT * FROM publication ");
        $totalData = mysqli_num_rows($query);
        $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

        $sql = "SELECT * FROM publication WHERE 1=1 "; //id, name, short_name, category, start_date, code, description, media, amount, date_registered
        if(!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
                $sql.=" AND ( name LIKE '%".$requestData['search']['value']."%' ";   
                $sql.=" OR description LIKE '%".$requestData['search']['value']."%' ";
                $sql.=" OR media LIKE '%".$requestData['search']['value']."%' ";
                $sql.=" OR date_published LIKE '%".$requestData['search']['value']."%' ";
                $sql.=" OR date_registered LIKE '%".$requestData['search']['value']."%' ) ";
        }
        $query = $dbObj->query($sql);
        $totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
        $sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
        /* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	

        echo $publicationObj->fetchForJQDT($requestData['draw'], $totalData, $totalFiltered, $sql);
    }
    
    if(filter_input(INPUT_POST, "deleteThisPublication")!=NULL){
        $postVars = array('id', 'media', 'image'); // Form fields names
        $publicationMedia = "";
        //Validate the POST variables and add up to error message if empty
        foreach ($postVars as $postVar){
            switch($postVar){
                case 'media':   $publicationObj->$postVar = filter_input(INPUT_POST, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_POST, $postVar)) :  ''; 
                                $publicationMedia = $publicationObj->$postVar;
                                //if($publicationObj->$postVar === "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
                case 'image':   $publicationObj->$postVar = filter_input(INPUT_POST, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_POST, $postVar)) :  ''; 
                                $publicationImage = $publicationObj->$postVar;
                                if($publicationObj->$postVar === "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
                default     :   $publicationObj->$postVar = filter_input(INPUT_POST, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_POST, $postVar)) :  ''; 
                                if($publicationObj->$postVar === "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
            }
        }
        //If validated and not empty submit it to database
        if(count($errorArr) < 1)   {
            $fileDelParam = true; $imageDelParam = true;
            if($publicationMedia!='' && file_exists(MEDIA_FILES_PATH."publication/".$publicationMedia)){
                if(unlink(MEDIA_FILES_PATH."publication/".$publicationMedia)){ $fileDelParam = true;}
                else $fileDelParam = false;
            }
            if($publicationImage!='' && file_exists(MEDIA_FILES_PATH."publication-image/".$publicationImage)){
                if(unlink(MEDIA_FILES_PATH."publication-image/".$publicationImage)){ $imageDelParam = true;}
                else $imageDelParam = false;
            }
            if($fileDelParam == true && $imageDelParam ==true){ echo $publicationObj->delete(); }
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
    
    if(filter_input(INPUT_GET, "activePublication")!=NULL){
        $postVars = array('id', 'status'); // Form fields names
        //Validate the POST variables and add up to error message if empty
        foreach ($postVars as $postVar){
            switch($postVar){
                case 'status':  $publicationObj->$postVar = filter_input(INPUT_GET, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_GET, $postVar, FILTER_VALIDATE_INT)) :  0; 
                                if($publicationObj->$postVar == 1) {$publicationObj->$postVar = 0;} 
                                elseif($publicationObj->$postVar == 0) {$publicationObj->$postVar = 1;}
//                                if($publicationObj->$postVar === "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
                default     :   $publicationObj->$postVar = filter_input(INPUT_GET, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_GET, $postVar)) :  ''; 
                                if($publicationObj->$postVar === "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
            }
        }
        //If validated and not empty submit it to database
        if(count($errorArr) < 1)   {
            echo Publication::updateSingle($dbObj, ' status ',  $publicationObj->status, $publicationObj->id); 
        }
        //Else show error messages
        else{ 
            $json = array("status" => 0, "msg" => $errorArr); 
            $dbObj->close();//Close Database Connection
            header('Content-type: application/json');
            echo json_encode($json);
        }

    }
    
    if(filter_input(INPUT_POST, "updateThisPublication") != NULL){
        $postVars = array('id','name','category','datePublished','description','media','image'); // Form fields names
        $oldMedia = $_REQUEST['oldFile']; $oldImage = $_REQUEST['oldImage'];
        //Validate the POST variables and add up to error message if empty
        foreach ($postVars as $postVar){
            switch($postVar){
                case 'media':   $newMedia = basename($_FILES["file"]["name"]) ? rand(100000, 1000000)."_".  strtolower(str_replace("-", "_", filter_input(INPUT_POST, 'datePublished'))).".".pathinfo(basename($_FILES["file"]["name"]),PATHINFO_EXTENSION): ""; 
                                $publicationObj->$postVar = $newMedia;
                                if($publicationObj->$postVar == ''){$publicationObj->$postVar = $oldMedia;}
                                $publicationMedFil = $newMedia;
                                break;
                case 'image':   $newImage = basename($_FILES["image"]["name"]) ? rand(100000, 1000000)."_".  strtolower(str_replace("-", "_", filter_input(INPUT_POST, 'datePublished'))).".".pathinfo(basename($_FILES["image"]["name"]),PATHINFO_EXTENSION): ""; 
                                $publicationObj->$postVar = $newImage;
                                if($publicationObj->$postVar == "") { $publicationObj->$postVar = $oldImage;}
                                $publicationImageFil = $newImage;
                                break;
                default     :   $publicationObj->$postVar = filter_input(INPUT_POST, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_POST, $postVar)) :  ''; 
                                if($publicationObj->$postVar == "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
                
            }
        }
        //If validated and not empty submit it to database
        if(count($errorArr) < 1)   {
            //$target_dir = "../project-files/";
            $target_file = MEDIA_FILES_PATH."publication/". $publicationMedFil;
            $target_Image = MEDIA_FILES_PATH."publication-image/". $publicationImageFil;
            $uploadOk = 1; $msg = '';
            $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
            
            if($newMedia !=""){
                if (move_uploaded_file($_FILES["file"]["tmp_name"], MEDIA_FILES_PATH."publication/".$publicationMedFil)) {
                    $msg .= "The file ". basename( $_FILES["file"]["name"]). " has been uploaded.";
                    $status = 'ok'; if($oldMedia!='' && file_exists(MEDIA_FILES_PATH."publication/".$oldMedia)) unlink(MEDIA_FILES_PATH."publication/".$oldMedia);
                } else {
                    $uploadOk = 0;
                }
            }
            if($newImage !=""){
                if(Imaging::checkDimension($_FILES["image"]["tmp_name"], 400, 400, 'min', 'both') != 'true'){$uploadOk = 0; $msg = Imaging::checkDimension($_FILES["image"]["tmp_name"], 400, 400, 'min', 'both');}
                if ($uploadOk == 1 && move_uploaded_file($_FILES["image"]["tmp_name"], MEDIA_FILES_PATH."publication-image/".$publicationImageFil)) {
                    $msg .= "The file ". basename( $_FILES["image"]["name"]). " has been uploaded.";
                    $status = 'ok'; if($oldImage!='' && file_exists(MEDIA_FILES_PATH."publication-image/".$oldImage))unlink(MEDIA_FILES_PATH."publication-image/".$oldImage);
                } else { $uploadOk = 0; }
            }
            
            if($uploadOk == 1){  echo $publicationObj->update();  }
            else {
                $msg = " Sorry, there was an error uploading your publication media. ERROR: ".$msg;
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
    
    if(filter_input(INPUT_GET, "makeFeaturedPublication")!=NULL){
        $postVars = array('id', 'featured'); // Form fields names
        //Validate the POST variables and add up to error message if empty
        foreach ($postVars as $postVar){
            switch($postVar){
                case 'featured':  $publicationObj->$postVar = filter_input(INPUT_GET, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_GET, $postVar, FILTER_VALIDATE_INT)) :  0; 
                                if($publicationObj->$postVar == 1) {$publicationObj->$postVar = 0;} 
                                elseif($publicationObj->$postVar == 0) {$publicationObj->$postVar = 1;}
                                break;
                default     :   $publicationObj->$postVar = filter_input(INPUT_GET, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_GET, $postVar)) :  ''; 
                                if($publicationObj->$postVar === "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
            }
        }
        //If validated and not empty submit it to database
        if(count($errorArr) < 1)   {
            echo Publication::updateSingle($dbObj, ' featured ',  $publicationObj->featured, $publicationObj->id); 
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