<?php
/**
 * Description of Course
 *
 * @author Kaiste
 */
class Course implements ContentManipulator{
    private $id;
    private $name;
    private $shortName;
    private $category;
    private $startDate;
    private $endDate;
    private $code;
    private $description;
    private $media;
    private $dateRegistered = " CURRENT_DATE ";
    private $status = 1;
    private $amount = 0;
    private $image;
    private $featured = 1;
    private $currency;
    private $dbObj;
    private $tableName;
    
    //Class constructor
    public function Course($dbObj, $tableName='course') {
        $this->dbObj = $dbObj;        $this->tableName = $tableName;
    }
    
    //Using Magic__set and __get
    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }
    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }
    
    /**  
     * Method that adds a course into the database
     * @return JSON JSON encoded string/result
     */
    function add(){
        $sql = "INSERT INTO course (name, short_name, category, start_date, end_date, code, description, media, amount, status, date_registered, image, featured, currency) "
                ."VALUES ('{$this->name}','{$this->shortName}','{$this->category}','{$this->startDate}','{$this->endDate}','{$this->code}','{$this->description}','{$this->media}','{$this->amount}','{$this->status}',$this->dateRegistered,'{$this->image}','{$this->featured}','{$this->currency}')";
        if($this->notEmpty($this->name,$this->startDate,$this->description,$this->image)){
            $result = $this->dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done, course successfully added!"); }
            else{ $json = array("status" => 2, "msg" => "Error adding course! ".  mysqli_error($this->dbObj->connection)); }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted. All fields must be filled."); }
        
        $this->dbObj->close();//Close Database Connection
        header('Content-type: application/json');
        return json_encode($json);
    }

    /** 
     * Method for deleting a course
     * @return JSON JSON encoded result
     */
    public function delete(){
        $sql = "DELETE FROM course WHERE id = $this->id ";
        if($this->notEmpty($this->id)){
            $result = $this->dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done, course successfully deleted!"); }
            else{ $json = array("status" => 2, "msg" => "Error deleting course! ".  mysqli_error($this->dbObj->connection));  }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted."); }
        $this->dbObj->close();//Close Database Connection
        header('Content-type: application/json');
        return json_encode($json);
    }

    /** Method that fetches courses from database for JQuery Data Table
     * @param string $column Column name of the data to be fetched
     * @param string $condition Additional condition e.g category_id > 9
     * @param string $sort column name to be used as sort parameter
     * @return JSON JSON encoded course details
     */
    public function fetchForJQDT($draw, $totalData, $totalFiltered, $customSql="", $column="*", $condition="", $sort="id"){
        $sql = "SELECT $column FROM course ORDER BY $sort";
        if(!empty($condition)){$sql = "SELECT $column FROM course WHERE $condition ORDER BY $sort";}
        if($customSql !=""){ $sql = $customSql; }
        $data = $this->dbObj->fetchAssoc($sql);
        $result =array();  $fetCourseStat = 'icon-check-empty'; $fetCourseRolCol = 'btn-warning'; $fetCourseRolTit = "Activate Course";
        if(count($data)>0){
            foreach($data as $r){ 
                $courseMediaLink = '';
                $fetCourseStat = 'icon-check-empty'; $fetCourseRolCol = 'btn-warning'; $fetCourseRolTit = "Activate Course";
                $fetCourseFeat = 'icon-eye-close'; $fetCourseFeatCol = 'btn-warning'; $fetCourseFeatTit = "Make Private Sector Course";
                if($r['status'] == 1){  $fetCourseStat = 'icon-check'; $fetCourseRolCol = 'btn-success'; $fetCourseRolTit = "De-activate Course";}
                if($r['featured'] == 1){  $fetCourseFeat = 'icon-eye-open'; $fetCourseFeatCol = 'btn-success'; $fetCourseFeatTit = "Make Public Sector Course";}
                if($r['media'] !=''){ $courseMediaLink = '<a href="'.SITE_URL.'media/course/'.$r['media'].'">View Media</a>'; }
                $multiActionBox = '<input type="checkbox" class="multi-action-box" data-id="'.$r['id'].'" data-name="'.$r['name'].'" data-status="'.$r['status'].'" data-image="'.$r['image'].'" data-media="'.$r['media'].'" data-featured="'.$r['featured'].'" />';
                $actionLink = ' <button data-id="'.$r['id'].'" data-name="'.$r['name'].'" data-short-name="'.$r['short_name'].'" data-category="'.$r['category'].'" data-currency="'.$r['currency'].'" data-start-date="'.$r['start_date'].'" data-end-date="'.$r['end_date'].'" data-code="'.$r['code'].'" data-description ="" data-media="'.$r['media'].'"  data-image="'.$r['image'].'" data-amount="'.$r['amount'].'" data-date-registered="'.$r['date_registered'].'" class="btn btn-info btn-sm edit-course"  title="Edit"><i class="btn-icon-only icon-pencil"> </i> <span class="hidden" id="JQDTdescriptionholder">'.$r['description'].'</span> </button> <button data-id="'.$r['id'].'" data-name="'.$r['name'].'" data-status="'.$r['status'].'"  class="btn '.$fetCourseRolCol.' btn-sm activate-course"  title="'.$fetCourseRolTit.'"><i class="btn-icon-only '.$fetCourseStat.'"> </i></button> <button data-id="'.$r['id'].'" data-media="'.$r['media'].'"  data-image="'.$r['image'].'" data-name="'.$r['name'].'" class="btn btn-danger btn-sm delete-course" title="Delete"><i class="btn-icon-only icon-trash"> </i></button> <button data-id="'.$r['id'].'" data-name="'.$r['name'].'" data-featured="'.$r['featured'].'"  class="btn '.$fetCourseFeatCol.' btn-sm make-featured-course"  title="'.$fetCourseFeatTit.'"><i class="btn-icon-only '.$fetCourseFeat.'"> </i></button>';
                $result[] = array(utf8_encode($multiActionBox), utf8_encode($actionLink), $r['id'], utf8_encode($r['name']), utf8_encode($r['short_name']), CourseCategory::getName($this->dbObj, $r['category']), utf8_encode($r['start_date']), utf8_encode($r['end_date']), utf8_encode($r['code']), StringManipulator::trimStringToFullWord(60, utf8_encode(stripcslashes(strip_tags($r['description'])))), utf8_encode($courseMediaLink), utf8_encode($r['currency'].' '.number_format($r['amount'])), utf8_encode('<img src="../media/course-image/'.utf8_encode($r['image']).'" width="60" height="50" style="width:60px; height:50px;" alt="Pix">'), utf8_encode($r['date_registered']));//
            }
            $json = array("status" => 1,"draw" => intval($draw), "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => $result);
        } 
        else{ $json = array("status" => 2, "msg" => "Necessary parameters not set. Or empty result. ".mysqli_error($this->dbObj->connection), "draw" => intval($draw),  "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => false); }
        $this->dbObj->close();
        //header('Content-type: application/json');
        return json_encode($json);
    }
    
    /** Method that fetches courses from database
     * @param string $column Column name of the data to be fetched
     * @param string $condition Additional condition e.g category_id > 9
     * @param string $sort column name to be used as sort parameter
     * @return JSON JSON encoded course details
     */
    public function fetch($column="*", $condition="", $sort="id"){
        $sql = "SELECT $column FROM course ORDER BY $sort";
        if(!empty($condition)){$sql = "SELECT $column FROM course WHERE $condition ORDER BY $sort";}
        $data = $this->dbObj->fetchAssoc($sql);
        $result =array(); 
        if(count($data)>0){
            foreach($data as $r){
                $result[] = array("id" => $r['id'], "name" =>  utf8_encode($r['name']), "image" =>  utf8_encode($r['image']), 'shortName' =>  utf8_encode($r['short_name']), 'category' => utf8_encode($r['category']), 'startDate' =>  utf8_encode($r['start_date']), 'endDate' =>  utf8_encode($r['end_date']), 'code' =>  utf8_encode($r['code']), 'description' => utf8_encode(StringManipulator::trimStringToFullWord(200, stripcslashes(strip_tags($r['description'])))), 'media' =>  utf8_encode($r['media']), 'currency' =>  utf8_encode($r['currency']), 'amount' =>  utf8_encode($r['amount']), 'cost' =>  utf8_encode($r['currency'].number_format($r['amount'], 2)), 'status' =>  utf8_encode($r['status']), 'dateRegistered' => utf8_encode($r['date_registered']), 'categoryName' => utf8_encode(CourseCategory::getName($this->dbObj, $r['category'])));
            }
            $json = array("status" => 1, "info" => $result);
        } 
        else{ $json = array("status" => 2, "msg" => "Necessary parameters not set. Or empty result. ".mysqli_error($this->dbObj->connection)); }
        $this->dbObj->close();
        header('Content-type: application/json');
        return json_encode($json);
    }

    /** Method that fetches courses from database
     * @param string $column Column name of the data to be fetched
     * @param string $condition Additional condition e.g category_id > 9
     * @param string $sort column name to be used as sort parameter
     * @return Array Courses list
     */
    public function fetchRaw($column="*", $condition="", $sort="id"){
        $sql = "SELECT $column FROM course ORDER BY $sort";
        if(!empty($condition)){$sql = "SELECT $column FROM course WHERE $condition ORDER BY $sort";}
        $result = $this->dbObj->fetchAssoc($sql);
        return $result;
    }
    
    /** Empty string checker  
     * @return Booloean True|False
     */
    public function notEmpty() {
        foreach (func_get_args() as $arg) {
            if (empty($arg)) { return false; } 
            else {continue; }
        }
        return true;
    }
    
    /** Method that update single field detail of a course
     * @param string $field Column to be updated 
     * @param string $value New value of $field (Column to be updated)
     * @param int $id Id of the post to be updated
     * @return JSON JSON encoded success or failure message
     */
    public static function updateSingle($dbObj, $field, $value, $id){
        $sql = "UPDATE course SET $field = '{$value}' WHERE id = $id ";
        if(!empty($id)){
            $result = $dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done, course successfully updated!"); }
            else{ $json = array("status" => 2, "msg" => "Error updating course! ".  mysqli_error($dbObj->connection));   }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted."); }
        $dbObj->close();
        header('Content-type: application/json');
        return json_encode($json);
    }

    /** Method that update details of a course
     * @return JSON JSON encoded success or failure message
     */
    public function update() {
        $sql = "UPDATE course SET name = '{$this->name}', image = '{$this->image}', short_name = '{$this->shortName}', category = '{$this->category}', start_date = '{$this->startDate}', end_date = '{$this->endDate}', code = '{$this->code}', description = '{$this->description}', media = '{$this->media}', amount = '{$this->amount}', currency = '{$this->currency}' WHERE id = $this->id ";
        if(!empty($this->id)){
            $result = $this->dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done, course successfully updated!"); }
            else{ $json = array("status" => 2, "msg" => "Error updating course! ".  mysqli_error($this->dbObj->connection));   }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted."); }
        $this->dbObj->close();
        header('Content-type: application/json');
        return json_encode($json); 
    }
    
    /** getName() fetches the name of a course using the course $id
     * @param object $dbObj Database connectivity and manipulation object
     * @param int $id Category id of the category whose name is to be fetched
     * @return string Name of the category
     */
    public static function getName($dbObj, $id) {
        $thisCourseName = '';
        $thisCourseNames = $dbObj->fetchNum("SELECT name FROM course WHERE id = '{$id}' LIMIT 1");
        foreach ($thisCourseNames as $thisCourseNames) { $thisCourseName = $thisCourseNames[0]; }
        return $thisCourseName;
    }

    
    /** getSingle() fetches the title of an course using the course $id
     * @param object $dbObj Database connectivity and manipulation object
     * @param string $column Table's required column in the datatbase
     * @param int $id Course id of the course whose name is to be fetched
     * @return string Name of the course
     */
    public static function getSingle($dbObj, $column, $id) {
        $thisAsstReqVal = '';
        $thisAsstReqVals = $dbObj->fetchNum("SELECT $column FROM course WHERE id = '{$id}' ");
        foreach ($thisAsstReqVals as $thisAsstReqVals) { $thisAsstReqVal = $thisAsstReqVals[0]; }
        return $thisAsstReqVal;
    }
    
    /**
     * Method that returns count/total number of a particular course
     * @param Object $dbObj Datatbase connectivity object
     * @param Object $condition Additional optional condition
     * @return int Number of courses
     */
    public static function getRawCount($dbObj, $condition=" 1=1 "){
        $sql = "SELECT * FROM course WHERE $condition ";
        $count = "";
        $result = $dbObj->query($sql);
        $totalData = mysqli_num_rows($result);
        if($result !== false){ $count = $totalData; }
        return $count;
    }
    
    /** fetchByCategory fetches courses in a category and sub-categories
     * @param int $categoryId Category id
     * @param string $categoryTable Category table name
     * @param string $condition Additional condition
     */
    public function fetchByCategory($categoryId, $categoryTable, $condition=''){
        $courseArr = array();
        if($categoryId !=0){
            $courseArr = array_merge($courseArr, $this->dbObj->fetchAssoc("SELECT * FROM $this->tableName WHERE category = ".$categoryId." $condition "));
            $catDetails = $this->dbObj->fetchAssoc("SELECT * FROM $categoryTable WHERE parent = $categoryId $condition ");
            foreach ($catDetails as $catDetail){
                $courseArr = array_merge($courseArr, $this->fetchByCategory($catDetail['id'], $categoryTable));
            }
            return $courseArr;
        }
    }
    
    /**
     * Method that returns count/total number of a particular lesson
     * @param object $dbObj Database connectivity and manipulation object
     * @param int $id Course id of the lessons whose titles are to be fetched
     * @param string $dbPrefix Database table prefix
     * @return int Number of courses
     */
    public static function getSingleCategoryCount($dbObj, $id, $dbPrefix=''){
        $tableName = $dbPrefix.'course';
        $sql = "SELECT * FROM $tableName WHERE category = $id ";
        $count = "";
        $result = $dbObj->query($sql);
        $totalData = mysqli_num_rows($result);
        if($result !== false){ $count = $totalData; }
        return $count;
    }
}
