<?php
session_start();
define("CONST_FILE_PATH", "../includes/constants.php");
include ('../classes/WebPage.php'); //Set up page as a web page
$thisPage = new WebPage(); //Create new instance of webPage class

$dbObj = new Database();//Instantiate database
$coursCatObj = new CourseCategory($dbObj); // Create an object of CourseCategory class
$errorArr = array(); //Array of errors
$oldMedia = ""; $newMedia =""; $coursCatMedFil ="";

if(!isset($_SESSION['ITCLoggedInAdmin']) || !isset($_SESSION["ITCadminEmail"])){ 
    $json = array("status" => 0, "msg" => "You are not logged in."); 
    echo json_encode($json);
}
else{
    if(filter_input(INPUT_POST, "addNewCategory") != NULL && filter_input(INPUT_POST, "addNewCategory")=="addNewCategory"){
        $postVars = array('name', 'description', 'image'); // Form fields names
        //Validate the POST variables and add up to error message if empty
        foreach ($postVars as $postVar){
            switch($postVar){
                case 'image':   $coursCatObj->$postVar = basename($_FILES["image"]["name"]) ? rand(100000, 1000000)."_".  strtolower(str_replace(" ", "_", filter_input(INPUT_POST, 'name'))).".".pathinfo(basename($_FILES["image"]["name"]),PATHINFO_EXTENSION): ""; 
                                $coursCatMedFil = $coursCatObj->$postVar;
                                if($coursCatObj->$postVar == "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
                default     :   $coursCatObj->$postVar = filter_input(INPUT_POST, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_POST, $postVar)) :  ''; 
                                if($coursCatObj->$postVar === "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
            }
        }
        //If validated and not empty submit it to database
        if(count($errorArr) < 1)   {
            
            $target_file = MEDIA_FILES_PATH."category/". basename($_FILES["image"]["name"]);
            $uploadOk = 1; $msg = '';
            $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
            // Check if file already exists
            if (file_exists($target_file)) { $msg .= " Course category image already exists."; $uploadOk = 0; }
            // Check file size
            if ($_FILES["image"]["size"] > 800000000) { $msg .= " Course category image is too large."; $uploadOk = 0; }
            if ($uploadOk == 0) {
                $msg = "Sorry, your course category image was not uploaded. ERROR: ".$msg;
                $json = array("status" => 0, "msg" => $msg); 
                $dbObj->close();//Close Database Connection
                header('Content-type: application/json');
                echo json_encode($json);
            } 
            else {
                if (move_uploaded_file($_FILES["image"]["tmp_name"], MEDIA_FILES_PATH."category/".$coursCatMedFil)) {
                    $msg .= "The file ". basename( $_FILES["image"]["name"]). " has been uploaded.";
                    $status = 'ok';
                    echo $coursCatObj->add();
                } else {
                    $msg = " Sorry, there was an error uploading your course category image. ERROR: ".$msg;
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
    
    if(filter_input(INPUT_POST, "fetchCategories") != NULL){
        $requestData= $_REQUEST;
        $columns = array( 0 =>'id', 1 =>'id', 2 => 'name', 3 => 'description', 4 => 'image');

        // getting total number records without any search
        $query = $dbObj->query("SELECT * FROM course_category ");
        $totalData = mysqli_num_rows($query);
        $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

        $sql = "SELECT id, name, description,image FROM course_category WHERE 1=1 ";
        if(!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
                $sql.=" AND ( name LIKE '%".$requestData['search']['value']."%' ";    
                $sql.=" OR description LIKE '".$requestData['search']['value']."%' ";
                $sql.=" OR image LIKE '".$requestData['search']['value']."%' ) ";
        }
        $query = $dbObj->query($sql);
        $totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
        $sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
        /* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	

        echo $coursCatObj->fetchForJQDT($requestData['draw'], $totalData, $totalFiltered, $sql);
    }
    
    if(filter_input(INPUT_POST, "deleteThisCategory")!=NULL){
        $postVars = array('id','image'); // Form fields names
        //Validate the POST variables and add up to error message if empty
        foreach ($postVars as $postVar){
            switch($postVar){
                case 'image':   $coursCatObj->$postVar = filter_input(INPUT_POST, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_POST, $postVar)) :  ''; 
                                $coursCatMedFil = $coursCatObj->$postVar;
                                if($coursCatObj->$postVar === "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
                default     :   $coursCatObj->$postVar = filter_input(INPUT_POST, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_POST, $postVar)) :  ''; 
                                if($coursCatObj->$postVar === "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
            }
        }
        //If validated and not empty submit it to database
        if(count($errorArr) < 1)   {
            if(unlink(MEDIA_FILES_PATH."category/".$coursCatMedFil)){ echo $coursCatObj->delete(); }
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
    
    if(filter_input(INPUT_POST, "addNewCategory") != NULL && filter_input(INPUT_POST, "addNewCategory")=="editCourseCategory"){
        $postVars = array('id', 'name', 'description', 'image'); // Form fields names
        $oldMedia = $_REQUEST['oldFile'];
        //Validate the POST variables and add up to error message if empty
        foreach ($postVars as $postVar){
            switch($postVar){
                case 'image':   $newMedia = basename($_FILES["image"]["name"]) ? rand(100000, 1000000)."_".  strtolower(str_replace(" ", "_", filter_input(INPUT_POST, 'name'))).".".pathinfo(basename($_FILES["image"]["name"]),PATHINFO_EXTENSION): ""; 
                                $coursCatObj->$postVar = $newMedia;
                                if($coursCatObj->$postVar == "") { $coursCatObj->$postVar = $oldMedia;}
                                $coursCatMedFil = $newMedia;
                                break;
                default     :   $coursCatObj->$postVar = filter_input(INPUT_POST, $postVar) ? mysqli_real_escape_string($dbObj->connection, filter_input(INPUT_POST, $postVar)) :  ''; 
                                if($coursCatObj->$postVar === "") {array_push ($errorArr, "Please enter $postVar ");}
                                break;
            }
        }
        //If validated and not empty submit it to database
        if(count($errorArr) < 1)   {
            //$target_dir = "../project-files/";
            $target_file = MEDIA_FILES_PATH."category/". $coursCatMedFil;
            $uploadOk = 1; $msg = '';
            $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
           
            if($newMedia !=""){
                if (move_uploaded_file($_FILES["image"]["tmp_name"], MEDIA_FILES_PATH."category/".$coursCatMedFil)) {
                    $msg .= "The file ". basename( $_FILES["image"]["name"]). " has been uploaded.";
                    $status = 'ok';
                    unlink(MEDIA_FILES_PATH."category/".$oldMedia);
                    echo $coursCatObj->update();
                } else {
                    $msg = " Sorry, there was an error uploading your course media. ERROR: ".$msg;
                    $json = array("status" => 0, "msg" => $msg); 
                    $dbObj->close();//Close Database Connection
                    header('Content-type: application/json');
                    echo json_encode($json);
                }
            } 
            else{
                echo $coursCatObj->update();
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