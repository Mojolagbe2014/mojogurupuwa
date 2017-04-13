<?php
/**
 * Description of UserCourse
 *
 * @author Kaiste
 */
class UserCourse implements ContentManipulator{
    private $id;
    private $user;
    private $speciality;
    private $topic;
    private $attendanceDate;
    private $point;
    private $location;
    private $comment;
    private $certificate;
    private $dbObj;
    
    
    //Class constructor
    public function UserCourse($dbObj) {
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
     * Method that adds a user's course into the database
     * @return JSON JSON encoded string/result
     */
    function add(){
        $sql = "INSERT INTO logged_cpd (user, speciality, topic, attendance_date, point, location, comment, certificate) "
                ."VALUES ('{$this->user}','{$this->speciality}','{$this->topic}','{$this->attendanceDate}','{$this->point}','{$this->location}','{$this->comment}','{$this->certificate}')";
        if($this->notEmpty($this->user)){
            $result = $this->dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done, user's  course successfully added!"); }
            else{ $json = array("status" => 2, "msg" => "Error adding user's  course! ".  mysqli_error($this->dbObj->connection)); }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted. All fields must be filled."); }
        
        $this->dbObj->close();//Close Database Connection
        header('Content-type: application/json');
        return json_encode($json);
    }

    /** 
     * Method for deleting a user's  course
     * @return JSON JSON encoded result
     */
    public function delete(){
        $sql = "DELETE FROM logged_cpd WHERE id = $this->id ";
        if($this->notEmpty($this->id)){
            $result = $this->dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done, user's  course successfully deleted!"); }
            else{ $json = array("status" => 2, "msg" => "Error deleting user's  course! ".  mysqli_error($this->dbObj->connection));  }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted."); }
        $this->dbObj->close();//Close Database Connection
        header('Content-type: application/json');
        return json_encode($json);
    }

    
    /** Method that fetches user- courses from database
     * @param string $column Column name of the data to be fetched
     * @param string $condition Additional condition e.g category_id > 9
     * @param string $sort column name to be used as sort parameter
     * @return JSON JSON encoded course details
     */
    public function fetch($column="*", $condition="", $sort="id"){
        $sql = "SELECT $column FROM logged_cpd ORDER BY $sort";
        if(!empty($condition)){$sql = "SELECT $column FROM logged_cpd WHERE $condition ORDER BY $sort";}
        $data = $this->dbObj->fetchAssoc($sql);
        $result =array(); 
        if(count($data)>0){
            foreach($data as $r){
                $result[] = array("id" => $r['id'], "user" =>  utf8_encode($r['user']), "topic" =>  utf8_encode($r['topic']), 'speciality' =>  utf8_encode($r['speciality']), 'attendanceDate' => utf8_encode($r['attendance_date']), 'point' =>  utf8_encode($r['point']), 'location' =>  utf8_encode($r['location']), 'comment' =>  utf8_encode($r['comment']), 'certificate' =>  utf8_encode($r['certificate']));
            }
            $json = array("status" => 1, "info" => $result);
        } 
        else{ $json = array("status" => 2, "msg" => "Necessary parameters not set. Or empty result. ".mysqli_error($this->dbObj->connection)); }
        $this->dbObj->close();
        header('Content-type: application/json');
        return json_encode($json);
    }
    
    /** Method that fetches user course from database
     * @param string $column Column name of the data to be fetched
     * @param string $condition Additional condition e.g category_id > 9
     * @param string $sort column name to be used as sort parameter
     * @return Array User logged courses list
     */
    public function fetchRaw($column="*", $condition="", $sort="id"){
        $sql = "SELECT $column FROM logged_cpd ORDER BY $sort";
        if(!empty($condition)){$sql = "SELECT $column FROM logged_cpd WHERE $condition ORDER BY $sort";}
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
        $sql = "UPDATE logged_cpd SET $field = '{$value}' WHERE id = $id ";
        if(!empty($id)){
            $result = $dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done, course successfully update!"); }
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
        $sql = "UPDATE logged_cpd SET topic = '{$this->topic}', speciality = '{$this->speciality}', attendance_date = '{$this->attendanceDate}', point = '{$this->point}', location = '{$this->location}', comment = '{$this->comment}', certificate = '{$this->certificate}' WHERE id = $this->id ";
        if(!empty($this->id)){
            $result = $this->dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done, course successfully update!"); }
            else{ $json = array("status" => 2, "msg" => "Error updating course! ".  mysqli_error($this->dbObj->connection));   }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted."); }
        $this->dbObj->close();
        header('Content-type: application/json');
        return json_encode($json); 
    }
    
    /**
     * Method that returns count/total number of a particular user
     * @return JSON JSON object of suceess|failure message and count of the courses
     */
    public function getUserCourseCount(){
        $sql = "SELECT * FROM logged_cpd WHERE user = $this->user";
        if(!empty($this->user)){
            $result = $this->dbObj->query($sql);
            $totalData = mysqli_num_rows($result);
            if($result !== false){ $json = array("status" => 1, "count" => $totalData); }
            else{ $json = array("status" => 2, "msg" => "Error fetching course count! ".  mysqli_error($this->dbObj->connection));   }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted."); }
        $this->dbObj->close();
        header('Content-type: application/json');
        return json_encode($json);
    }
    
    
}
