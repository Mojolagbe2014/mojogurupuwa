<?php
/**
 * Description of Resume
 *
 * @author Jamiu Babatunde Mojolagbe <mojolagbe@gmail.com>
 */
class Resume implements ContentManipulator{
    private $id;
    private $name;
    private $document;
    private  static $dbObj;
    private static $tableName = 'resume';
    
    
    //Class constructor
    public function Resume($dbObj, $tableName='resume') {
        self::$dbObj = $dbObj;  self::$tableName = $tableName;
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
     * Method that adds a resume into the database
     * @return JSON JSON encoded string/result
     */
    function add(){
        $sql = "INSERT INTO ".self::$tableName." (name, document) "
                ."VALUES ('{$this->name}','{$this->document}')";
        if($this->notEmpty($this->name,$this->document)){
            $result = self::$dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done, resume successfully added!"); }
            else{ $json = array("status" => 2, "msg" => "Error adding resume! ".  mysqli_error(self::$dbObj->connection)); }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted. All fields must be filled."); }
        
        self::$dbObj->close();//Close Database Connection
        header('Content-type: application/json');
        return json_encode($json);
    }

    /** 
     * Method for deleting a resume
     * @return JSON JSON encoded result
     */
    public function delete(){
        $sql = "DELETE FROM ".self::$tableName." WHERE id = $this->id ";
        if($this->notEmpty($this->id)){
            $result = self::$dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done, resume successfully deleted!"); }
            else{ $json = array("status" => 2, "msg" => "Error deleting resume! ".  mysqli_error(self::$dbObj->connection));  }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted."); }
        self::$dbObj->close();//Close Database Connection
        header('Content-type: application/json');
        return json_encode($json);
    }

    /** Method that fetches  categories from database for JQuery Data Table
     * @param string $column Column name of the data to be fetched
     * @param string $condition Additional condition e.g resume_id > 9
     * @param string $sort column name to be used as sort parameter
     * @return JSON JSON encoded resume details
     */
    public function fetchForJQDT($draw, $totalData, $totalFiltered, $customSql="", $column="*", $condition="", $sort="id"){
        $sql = "SELECT $column FROM ".self::$tableName." ORDER BY $sort";
        if(!empty($condition)){$sql = "SELECT $column FROM ".self::$tableName." WHERE $condition ORDER BY $sort";}
        if($customSql !=""){ $sql = $customSql; }
        $data = self::$dbObj->fetchAssoc($sql);
        $result =array(); 
        if(count($data)>0){
            foreach($data as $r){ 
                $multiActionBox = '<input type="checkbox" class="multi-action-box" data-id="'.$r['id'].'" data-name="'.$r['name'].'" data-document="'.$r['document'].'" />';
                $actionLink = ' <button data-name="'.$r['name'].'" data-id="'.$r['id'].'"  data-document="'.$r['document'].'" class="btn btn-info btn-small edit-resume"  title="Edit"><i class="btn-icon-only icon-pencil"> </i></button> <button data-name="'.$r['name'].'" data-document="'.$r['document'].'" data-id="'.$r['id'].'" class="btn btn-danger btn-small delete-resume" title="Delete"><i class="btn-icon-only icon-trash"> </i></button>';
                $result[] = array(utf8_encode($multiActionBox), $r['id'], utf8_encode($r['name']), utf8_encode('<a href="../media/resume/'.utf8_encode($r['document']).'">View/Download</a>'), utf8_encode($actionLink));
            }
            $json = array("status" => 1,"draw" => intval($draw), "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => $result);
        } 
        else{ $json = array("status" => 2, "msg" => "Necessary parameters not set. Or empty result. ".mysqli_error(self::$dbObj->connection), "draw" => intval($draw),  "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => false); }
        self::$dbObj->close();
        header('Content-type: application/json');
        return json_encode($json);
    }
    
    /** Method that fetches  categories from database
     * @param string $column Column name of the data to be fetched
     * @param string $condition Additional condition e.g resume_id > 9
     * @param string $sort column name to be used as sort parameter
     * @return JSON JSON encoded resume details
     */
    public function fetch($column="*", $condition="", $sort="id"){
        $sql = "SELECT $column FROM ".self::$tableName." ORDER BY $sort";
        if(!empty($condition)){$sql = "SELECT $column FROM ".self::$tableName." WHERE $condition ORDER BY $sort";}
        $data = self::$dbObj->fetchAssoc($sql);
        $result =array(); 
        if(count($data)>0){
            foreach($data as $r){
                $result[] = array("id" => $r['id'], "name" =>  utf8_encode($r['name']), "document" =>  utf8_encode($r['document']));
            }
            $json = array("status" => 1, "info" => $result);
        } 
        else{ $json = array("status" => 2, "msg" => "Necessary parameters not set. Or empty result. ".mysqli_error(self::$dbObj->connection)); }
        self::$dbObj->close();
        header('Content-type: application/json');
        return json_encode($json);
    }
    
    /** Method that fetches  categories from database
     * @param string $column Column name of the data to be fetched
     * @param string $condition Additional condition e.g resume_id > 9
     * @param string $sort column name to be used as sort parameter
     * @return Array Brochure list
     */
    public function fetchRaw($column="*", $condition="", $sort="id"){
        $sql = "SELECT $column FROM ".self::$tableName." ORDER BY $sort";
        if(!empty($condition)){$sql = "SELECT $column FROM ".self::$tableName." WHERE $condition ORDER BY $sort";}
        $result = self::$dbObj->fetchAssoc($sql);
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
    
    /** Method that update single field detail of a resume
     * @param string $field Column to be updated 
     * @param string $value New value of $field (Column to be updated)
     * @param int $id Id of the post to be updated
     * @return JSON JSON encoded success or failure message
     */
    public static function updateSingle($dbObj, $field, $value, $id){
        $sql = "UPDATE ".self::$tableName." SET $field = '{$value}' WHERE id = $id ";
        if(!empty($id)){
            $result = $dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done, resume successfully update!"); }
            else{ $json = array("status" => 2, "msg" => "Error updating resume! ".  mysqli_error($dbObj->connection));   }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted."); }
        $dbObj->close();
        header('Content-type: application/json');
        return json_encode($json);
    }

    /** Method that update details of a resume
     * @return JSON JSON encoded success or failure message
     */
    public function update() {
        $sql = "UPDATE ".self::$tableName." SET name = '{$this->name}', document = '{$this->document}' WHERE id = $this->id ";
        if(!empty($this->id)){
            $result = self::$dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done, resume successfully update!"); }
            else{ $json = array("status" => 2, "msg" => "Error updating resume! ".  mysqli_error(self::$dbObj->connection));   }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted."); }
        self::$dbObj->close();
        header('Content-type: application/json');
        return json_encode($json); 
    }

    /** getName() fetches the name of a resume using the resume $id
     * @param object $dbObj Database connectivity and manipulation object
     * @param int $id Brochure id of the resume whose name is to be fetched
     * @return string Name of the resume
     */
    public static function getName($dbObj, $id=0) {
        $thisCatName = '';
        $sql = $id == 0 ? "SELECT name, MAX(id) AS maxId FROM ".self::$tableName." GROUP BY id " : "SELECT name FROM ".self::$tableName." WHERE id = '{$id}' LIMIT 1";
        $thisCatNames = $dbObj->fetchNum($sql);
        foreach ($thisCatNames as $thisCatNames) { $thisCatName = $thisCatNames[0]; }
        return $thisCatName;
    }
    
    /** getCurrent() fetches the current resume 
     * @param object $dbObj Database connectivity and manipulation object
     * @return string Document of the resume
     */
    public static function getCurrent($dbObj) {
        $thisBrochName = '';
        $thisBrochNames = $dbObj->fetchNum("SELECT document, MAX(id) AS maxId FROM ".self::$tableName." GROUP BY id  ");
        foreach ($thisBrochNames as $thisBrochNames) { $thisBrochName = $thisBrochNames[0]; }
        return $thisBrochName;
    }
    
    /**
     * Method that returns count/total number of a particular 
     * @param Object $dbObj Database connectivity object
     * @return int Number of s
     */
    public static function getRawCount($dbObj, $dbPrefix=''){
        $tableName = $dbPrefix.self::$tableName;
        $sql = "SELECT * FROM $tableName ";
        $count = "";
        $result = $dbObj->query($sql);
        $totalData = mysqli_num_rows($result);
        if($result !== false){ $count = $totalData; }
        return $count;
    }
}
