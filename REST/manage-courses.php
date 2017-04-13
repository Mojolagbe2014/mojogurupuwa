<?php
session_start();
define("CONST_FILE_PATH", "../includes/constants.php");
include ('../classes/WebPage.php'); //Set up page as a web page
$thisPage = new WebPage(); //Create new instance of webPage class

$dbObj = new Database();//Instantiate database
$courseObj = new Course($dbObj); // Create an object of CourseCategory class
$errorArr = array(); //Array of errors
$oldMedia = ""; $newMedia =""; $oldImage=""; $newImage =""; $courseImageFil="";

if(!isset($_SESSION['ITCLoggedInAdmin']) || !isset($_SESSION["ITCadminEmail"])){ 
    $json = array("status" => 0, "msg" => "You are not logged in."); 
    echo json_encode($json);
}
else{
    if(filter_input(INPUT_POST, "fetchCourses") != NULL){
        $requestData= $_REQUEST;
        $columns = array( 0 =>'id', 1 =>'id', 2 =>'id', 3 => 'name', 4 => 'short_name', 5 => 'category', 6 => 'start_date', 7 => 'end_date', 8 => 'code', 9 => 'description', 10 => 'media', 11 => 'amount', 12 => 'status', 13 => 'date_registered');

        // getting total number records without any search
        $query = $dbObj->query("SELECT * FROM course ");
        $totalData = mysqli_num_rows($query);
        $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

        $sql = "SELECT * FROM course WHERE 1=1 "; //id, name, short_name, category, start_date, code, description, media, amount, date_registered
        if(!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
                $sql.=" AND ( name LIKE '%".$requestData['search']['value']."%' ";    
                $sql.=" OR code LIKE '%".$requestData['search']['value']."%' ";
                $sql.=" OR description LIKE '%".$requestData['search']['value']."%' ";
                $sql.=" OR media LIKE '%".$requestData['search']['value']."%' ";
                $sql.=" OR start_date LIKE '%".$requestData['search']['value']."%' ";
                $sql.=" OR date_registered LIKE '%".$requestData['search']['value']."%' ";
                $sql.=" OR amount LIKE '%".$requestData['search']['value']."%' ";
                $sql.=" OR short_name LIKE '%".$requestData['search']['value']."%' ) ";
        }
        $query = $dbObj->query($sql);
        $totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
        $sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
        /* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	

        echo $courseObj->fetchForJQDT($requestData['draw'], $totalData, $totalFiltered, $sql);
    }
    
    if(filter_input(INPUT_POST, "deleteThisCourse")!=NULL){
        $postVars = array('id', 'media', 'image'); // Form fields names
        $courseMedia = "";
        //Validate the POST variables and add up to error message if empty
        foreach ($postVars as $postVar){
            switch($postVar){
                case 'media':   $courseObj->$postVar = filter_input(INPUT_POST, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_POST, $postVar)) :  ''; 
                                $courseMedia = $courseObj->$postVar;
                                //if($courseObj->$postVar === "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
                case 'image':   $courseObj->$postVar = filter_input(INPUT_POST, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_POST, $postVar)) :  ''; 
                                $courseImage = $courseObj->$postVar;
                                if($courseObj->$postVar === "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
                default     :   $courseObj->$postVar = filter_input(INPUT_POST, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_POST, $postVar)) :  ''; 
                                if($courseObj->$postVar === "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
            }
        }
        //If validated and not empty submit it to database
        if(count($errorArr) < 1)   {
            $fileDelParam = true; $imageDelParam = true;
            if($courseMedia!='' && file_exists(MEDIA_FILES_PATH."course/".$courseMedia)){
                if(unlink(MEDIA_FILES_PATH."course/".$courseMedia)){ $fileDelParam = true;}
                else $fileDelParam = false;
            }
            if($courseImage!='' && file_exists(MEDIA_FILES_PATH."course-image/".$courseImage)){
                if(unlink(MEDIA_FILES_PATH."course-image/".$courseImage)){ $imageDelParam = true;}
                else $imageDelParam = false;
            }
            if($fileDelParam == true && $imageDelParam ==true){ echo $courseObj->delete(); }
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
    
    if(filter_input(INPUT_GET, "activeCourse")!=NULL){
        $postVars = array('id', 'status'); // Form fields names
        //Validate the POST variables and add up to error message if empty
        foreach ($postVars as $postVar){
            switch($postVar){
                case 'status':  $courseObj->$postVar = filter_input(INPUT_GET, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_GET, $postVar, FILTER_VALIDATE_INT)) :  0; 
                                if($courseObj->$postVar == 1) {$courseObj->$postVar = 0;} 
                                elseif($courseObj->$postVar == 0) {$courseObj->$postVar = 1;}
//                                if($courseObj->$postVar === "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
                default     :   $courseObj->$postVar = filter_input(INPUT_GET, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_GET, $postVar)) :  ''; 
                                if($courseObj->$postVar === "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
            }
        }
        //If validated and not empty submit it to database
        if(count($errorArr) < 1)   {
            echo Course::updateSingle($dbObj, ' status ',  $courseObj->status, $courseObj->id); 
        }
        //Else show error messages
        else{ 
            $json = array("status" => 0, "msg" => $errorArr); 
            $dbObj->close();//Close Database Connection
            header('Content-type: application/json');
            echo json_encode($json);
        }

    }
    
    if(filter_input(INPUT_POST, "updateThisCourse") != NULL){
        $postVars = array('id','name','shortName','category','startDate','endDate','code','description','media','amount','image', 'currency'); // Form fields names
        $oldMedia = $_REQUEST['oldFile']; $oldImage = $_REQUEST['oldImage'];
        //Validate the POST variables and add up to error message if empty
        foreach ($postVars as $postVar){
            switch($postVar){
                case 'media':   $newMedia = basename($_FILES["file"]["name"]) ? rand(100000, 1000000)."_".  strtolower(str_replace(" ", "_", filter_input(INPUT_POST, 'code'))).".".pathinfo(basename($_FILES["file"]["name"]),PATHINFO_EXTENSION): ""; 
                                $courseObj->$postVar = $newMedia;
                                if($courseObj->$postVar == ''){$courseObj->$postVar = $oldMedia;}
                                $courseMedFil = $newMedia;
                                break;
                case 'image':   $newImage = basename($_FILES["image"]["name"]) ? rand(100000, 1000000)."_".  strtolower(str_replace(" ", "_", filter_input(INPUT_POST, 'code'))).".".pathinfo(basename($_FILES["image"]["name"]),PATHINFO_EXTENSION): ""; 
                                $courseObj->$postVar = $newImage;
                                if($courseObj->$postVar == "") { $courseObj->$postVar = $oldImage;}
                                $courseImageFil = $newImage;
                                break;
                case 'code':   $courseObj->$postVar = filter_input(INPUT_POST, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_POST, $postVar)) :  ''; 
                                break;
                default     :   $courseObj->$postVar = filter_input(INPUT_POST, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_POST, $postVar)) :  ''; 
                                if($courseObj->$postVar == "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
                
            }
        }
        //If validated and not empty submit it to database
        if(count($errorArr) < 1)   {
            //$target_dir = "../project-files/";
            $target_file = MEDIA_FILES_PATH."course/". $courseMedFil;
            $target_Image = MEDIA_FILES_PATH."course-image/". $courseImageFil;
            $uploadOk = 1; $msg = '';
            $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
            
            if($newMedia !=""){
                if (move_uploaded_file($_FILES["file"]["tmp_name"], MEDIA_FILES_PATH."course/".$courseMedFil)) {
                    $msg .= "The file ". basename( $_FILES["file"]["name"]). " has been uploaded.";
                    $status = 'ok'; if($oldMedia!='' && file_exists(MEDIA_FILES_PATH."course/".$oldMedia)) unlink(MEDIA_FILES_PATH."course/".$oldMedia);
                } else {
                    $uploadOk = 0;
                }
            }
            if($newImage !=""){
                if(Imaging::checkDimension($_FILES["image"]["tmp_name"], 400, 400, 'equ', 'both') != 'true'){$uploadOk = 0; $msg = Imaging::checkDimension($_FILES["image"]["tmp_name"], 400, 400, 'equ', 'both');}
                if ($uploadOk == 1 && move_uploaded_file($_FILES["image"]["tmp_name"], MEDIA_FILES_PATH."course-image/".$courseImageFil)) {
                    $msg .= "The file ". basename( $_FILES["image"]["name"]). " has been uploaded.";
                    $status = 'ok'; if($oldImage!='' && file_exists(MEDIA_FILES_PATH."course-image/".$oldImage))unlink(MEDIA_FILES_PATH."course-image/".$oldImage);
                } else { $uploadOk = 0; }
            }
            
            if($uploadOk == 1){  echo $courseObj->update();  }
            else {
                $msg = " Sorry, there was an error uploading your course media. ERROR: ".$msg;
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
    
    if(filter_input(INPUT_GET, "makeFeaturedCourse")!=NULL){
        $postVars = array('id', 'featured'); // Form fields names
        //Validate the POST variables and add up to error message if empty
        foreach ($postVars as $postVar){
            switch($postVar){
                case 'featured':  $courseObj->$postVar = filter_input(INPUT_GET, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_GET, $postVar, FILTER_VALIDATE_INT)) :  0; 
                                if($courseObj->$postVar == 1) {$courseObj->$postVar = 0;} 
                                elseif($courseObj->$postVar == 0) {$courseObj->$postVar = 1;}
                                break;
                default     :   $courseObj->$postVar = filter_input(INPUT_GET, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_GET, $postVar)) :  ''; 
                                if($courseObj->$postVar === "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
            }
        }
        //If validated and not empty submit it to database
        if(count($errorArr) < 1)   {
            echo Course::updateSingle($dbObj, ' featured ',  $courseObj->featured, $courseObj->id); 
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