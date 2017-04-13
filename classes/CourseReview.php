<?php
/**
 * Description of CourseReview
 *
 * @author Kaiste
 */
class CourseReview implements ContentManipulator{
    private $id;
    private $course;
    private $name;
    private $email;
    private $review;
    private $status = 1;
    private $dateAdded = ' CURRENT_DATE ';
    private $dbObj;
    
    
    //Class constructor
    public function CourseReview($dbObj) {
        $this->dbObj = $dbObj;
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
     * Method that adds a  course review into the database
     * @return JSON JSON encoded string/result
     */
    function add(){
        $sql = "INSERT INTO course_review (course, name, email, review, date_added, status) "
                ."VALUES ('{$this->course}','{$this->name}','{$this->email}','{$this->review}',$this->dateAdded,'{$this->status}')";
        if($this->notEmpty($this->course,$this->name,$this->email)){
            $result = $this->dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done, course review successfully added!"); }
            else{ $json = array("status" => 2, "msg" => "Error adding  course review! ".  mysqli_error($this->dbObj->connection)); }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted. All fields must be filled."); }
        
        $this->dbObj->close();//Close Database Connection
        header('Content-type: application/json');
        return json_encode($json);
    }

    /** 
     * Method for deleting a  course review
     * @return JSON JSON encoded result
     */
    public function delete(){
        $sql = "DELETE FROM course_review WHERE id = $this->id ";
        if($this->notEmpty($this->id)){
            $result = $this->dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done,  course review successfully deleted!"); }
            else{ $json = array("status" => 2, "msg" => "Error deleting  course review! ".  mysqli_error($this->dbObj->connection));  }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted."); }
        $this->dbObj->close();//Close Database Connection
        header('Content-type: application/json');
        return json_encode($json);
    }

    /** Method that fetches course reviews from database for JQuery Data Table
     * @param string $column Column name of the data to be fetched
     * @param string $condition Additional condition e.g  course_review_id > 9
     * @param string $sort column name to be used as sort parameter
     * @return JSON JSON encoded course review details
     */
    public function fetchForJQDT($draw, $totalData, $totalFiltered, $customSql="", $column="*", $condition="", $sort="id"){
        $sql = "SELECT $column FROM course_review ORDER BY $sort";
        if(!empty($condition)){$sql = "SELECT $column FROM course_review WHERE $condition ORDER BY $sort";}
        if($customSql !=""){ $sql = $customSql; }
        $data = $this->dbObj->fetchAssoc($sql);
        $result =array(); $fetCourseReviewStat = 'icon-check-empty'; $fetCourseReviewRolCol = 'btn-warning'; $fetCourseReviewRolTit = "Activate CourseReview";
        if(count($data)>0){
            foreach($data as $r){ 
                $fetCourseReviewStat = 'icon-check-empty'; $fetCourseReviewRolCol = 'btn-warning'; $fetCourseReviewRolTit = "Activate CourseReview";
                if($r['status'] == 1){  $fetCourseReviewStat = 'icon-check'; $fetCourseReviewRolCol = 'btn-success'; $fetCourseReviewRolTit = "De-activate CourseReview";}
                $result[] = array($r['id'], utf8_encode(' <button data-id="'.$r['id'].'" data-course="'.$r['course'].'" data-name="'.$r['name'].'"  data-email="'.$r['email'].'" class="btn btn-danger btn-small delete-course_review" title="Delete"><i class="btn-icon-only icon-trash"> </i></button> <button  data-id="'.$r['id'].'" data-course="'.$r['course'].'" data-name="'.$r['name'].'"  data-email="'.$r['email'].'" class="btn btn-info btn-small edit-course_review"  title="Edit"><i class="btn-icon-only icon-pencil"> </i> <span class="hidden" id="JQDTreviewholder">'.$r['review'].'</span> </button> <button data-id="'.$r['id'].'" data-course="'.$r['course'].'" data-status="'.$r['status'].'"  class="btn '.$fetCourseReviewRolCol.' btn-small activate-course_review"  title="'.$fetCourseReviewRolTit.'"><i class="btn-icon-only '.$fetCourseReviewStat.'"> </i></button>'), utf8_encode($r['course']), utf8_encode($r['name']), utf8_encode($r['review']), utf8_encode($r['email']), utf8_encode($r['date_added']));//
            }
            $json = array("status" => 1,"draw" => intval($draw), "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => $result);
        } 
        else{ $json = array("status" => 2, "msg" => "Necessary parameters not set. Or empty result. ".mysqli_error($this->dbObj->connection), "draw" => intval($draw),  "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => false); }
        $this->dbObj->close();
        //header('Content-type: application/json');
        return json_encode($json);
    }
    
    /** Method that fetches course reviews from database
     * @param string $column Column name of the data to be fetched
     * @param string $condition Additional condition e.g  course_review_id > 9
     * @param string $sort column name to be used as sort parameter
     * @return JSON JSON encoded course course_review details
     */
    public function fetch($column="*", $condition="", $sort="id"){
        $sql = "SELECT $column FROM course_review ORDER BY $sort";
        if(!empty($condition)){$sql = "SELECT $column FROM course_review WHERE $condition ORDER BY $sort";}
        $data = $this->dbObj->fetchAssoc($sql);
        $result =array(); 
        if(count($data)>0){
            foreach($data as $r){
                $result[] = array("id" => $r['id'], "course" =>  utf8_encode($r['course']), "name" =>  utf8_encode($r['name']), "email" =>  utf8_encode($r['email']), "review" =>  utf8_encode($r['review']), "status" =>  utf8_encode($r['status']), "dateAdded" =>  utf8_encode($r['date_added']));
            }
            $json = array("status" => 1, "info" => $result);
        } 
        else{ $json = array("status" => 2, "msg" => "Empty result. ".mysqli_error($this->dbObj->connection)); }
        $this->dbObj->close();
        header('Content-type: application/json');
        return json_encode($json);
    }

    /** Method that fetches course reviews from database
     * @param string $column Column name of the data to be fetched
     * @param string $condition Additional condition e.g category_id > 9
     * @param string $sort column name to be used as sort parameter
     * @return Array course review list
     */
    public function fetchRaw($column="*", $condition="", $sort="id"){
        $sql = "SELECT $column FROM course_review ORDER BY $sort";
        if(!empty($condition)){$sql = "SELECT $column FROM course_review WHERE $condition ORDER BY $sort";}
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
    
    /** Method that update single field detail of a  course_review
     * @param string $field Column to be updated 
     * @param string $value New value of $field (Column to be updated)
     * @param int $id Id of the post to be updated
     * @return JSON JSON encoded success or failure message
     */
    public static function updateSingle($dbObj, $field, $value, $id){
        $sql = "UPDATE course_review SET $field = '{$value}' WHERE id = $id ";
        if(!empty($id)){
            $result = $dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done,  course review successfully update!"); }
            else{ $json = array("status" => 2, "msg" => "Error updating  course_review! ".  mysqli_error($dbObj->connection));   }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted."); }
        $dbObj->close();
        header('Content-type: application/json');
        return json_encode($json);
    }

    /** Method that update details of a  course_review
     * @return JSON JSON encoded success or failure message
     */
    public function update() {
        $sql = "UPDATE course_review SET name = '{$this->name}', email = '{$this->email}', review = '{$this->review}' WHERE id = $this->id ";
        if($this->notEmpty($this->id, $this->review, $this->name)){
            $result = $this->dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done,  course review successfully update!"); }
            else{ $json = array("status" => 2, "msg" => "Error updating  course_review! ".  mysqli_error($this->dbObj->connection));   }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted."); }
        $this->dbObj->close();
        header('Content-type: application/json');
        return json_encode($json); 
    }

    /** getName() fetches the name of a course_review using the course_review $id
     * @param object $dbObj Database connectivity and manipulation object
     * @param int $id Category id of the  course_review whose name is to be fetched
     * @return string Name of the  course_review
     */
    public static function getName($dbObj, $id) {
        $thisCourseReviewName = '';
        $thisCourseReviewNames = $dbObj->fetchNum("SELECT name FROM course_review WHERE id = '{$id}' LIMIT 1");
        foreach ($thisCourseReviewNames as $thisCourseReviewNames) { $thisCourseReviewName = $thisCourseReviewNames[0]; }
        return $thisCourseReviewName;
    }
    
    /**
     * Method that returns count/total number of reviews
     * @param Object $dbObj Datatbase connectivity object
     * @param int $courseId Course Id 
     * @return int Number of reviews
     */
    public static function getRawCount($dbObj, $courseId=0){
        $sql = "SELECT * FROM course_review ";
        if($courseId !==0){ $sql = "SELECT * FROM course_review WHERE course = $courseId "; }
        $count = "";
        $result = $dbObj->query($sql);
        $totalData = mysqli_num_rows($result);
        if($result !== false){ $count = $totalData; }
        return $count;
    }
}
