<?php
/* 
 * Class Tutor describes individual tutors
 * @author Kaiste
 */
class Tutor implements ContentManipulator{
    //class properties/data
    private $id;
    private $name;
    private $qualification;
    private $field;
    private $bio;
    private $email;
    private $website;
    private $picture;
    private $visible = '1' ;
    private $dbObj;
    private $tableName;

    //class constructor
    public function Tutor($dbObj, $tableName='tutor') {
        $this->dbObj =  $dbObj;        $this->tableName = $tableName;
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
     * Method that submits a tutor into the database
     * @return JSON JSON encoded string/result
     */
    public function add(){
        $sql = "INSERT INTO $this->tableName (name, qualification, field, bio, email, website, picture, visible) "
                ."VALUES ('{$this->name}','{$this->qualification}','{$this->field}','{$this->bio}','{$this->email}','{$this->website}','{$this->picture}','{$this->visible}')";
        if($this->notEmpty($this->name,$this->qualification,$this->bio)){
            $result = $this->dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done, tutor successfully added!"); }
            else{ $json = array("status" => 2, "msg" => "Error adding tutor! ".  mysqli_error($this->dbObj->connection)); }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted. All fields must be filled."); }
        
        $this->dbObj->close();//Close Database Connection
        header('Content-type: application/json');
        return json_encode($json);
    }

    /** 
     * Method for deleting a tutor
     * @return JSON JSON encoded string/result
     */
    public function delete(){
        $sql = "DELETE FROM $this->tableName WHERE id = $this->id ";
        if($this->notEmpty($this->id)){
            $result = $this->dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done, tutor successfully deleted!"); }
            else{ $json = array("status" => 2, "msg" => "Error deleting tutor! ".  mysqli_error($this->dbObj->connection));  }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted."); }
        $this->dbObj->close();//Close Database Connection
        header('Content-type: application/json');
        return json_encode($json);
    }

    /** Method that fetches tutors from database
     * @param string $column Column name of the data to be fetched
     * @param string $condition Additional condition e.g tutor_id > 9
     * @param string $sort column name to be used as sort parameter
     * @return JSON JSON encoded string/result
     */
    public function fetch($column="*", $condition="", $sort="id"){
        $sql = "SELECT $column FROM $this->tableName ORDER BY $sort";
        if(!empty($condition)){$sql = "SELECT $column FROM $this->tableName WHERE $condition ORDER BY $sort";}
        $data = $this->dbObj->fetchAssoc($sql);
        $result =array(); 
        if(count($data)>0){
            foreach($data as $r){
                $result[] = array("id" => $r['id'], "name" =>  utf8_encode($r['name']), 'qualification' =>  utf8_encode($r['qualification']), "field" =>  utf8_encode($r['field']), "bio" =>  utf8_encode(stripslashes(strip_tags($r['bio']))), "email" =>  utf8_encode($r['email']), "website" =>  utf8_encode($r['website']), "picture" =>  utf8_encode($r['picture']), "visible" =>  utf8_encode($r['visible']));
            }
            $json = array("status" => 1, "info" => $result);
        } else{ $json = array("status" => 2, "msg" => "Necessary parameters not set. Or empty result. ".mysqli_error($this->dbObj->connection)); }
        
        $this->dbObj->close();
        header('Content-type: application/json');
        return json_encode($json);
    }
    
    /** Method that fetches tutors from database
     * @param string $column Column name of the data to be fetched
     * @param string $condition Additional condition e.g category_id > 9
     * @param string $sort column name to be used as sort parameter
     * @return Array tutor list
     */
    public function fetchRaw($column="*", $condition="", $sort="id"){
        $sql = "SELECT $column FROM $this->tableName ORDER BY $sort";
        if(!empty($condition)){$sql = "SELECT $column FROM $this->tableName WHERE $condition ORDER BY $sort";}
        $result = $this->dbObj->fetchAssoc($sql);
        return $result;
    }
    
    /** Method that fetches tutors from database for JQuery Data Table
     * @param string $column Column name of the data to be fetched
     * @param string $condition Additional condition e.g tutor_id > 9
     * @param string $sort column name to be used as sort parameter
     * @return JSON JSON encoded tutor details
     */
    public function fetchForJQDT($draw, $totalData, $totalFiltered, $customSql="", $column="*", $condition="", $sort="id"){
        $sql = "SELECT $column FROM $this->tableName ORDER BY $sort";
        if(!empty($condition)){$sql = "SELECT $column FROM $this->tableName WHERE $condition ORDER BY $sort";}
        if($customSql !=""){ $sql = $customSql; }
        $data = $this->dbObj->fetchAssoc($sql);
        $result =array(); $fetTutorStat = 'icon-check-empty'; $fetTutorRolCol = 'btn-warning'; $fetTutorRolTit = "Activate Tutor";
        if(count($data)>0){
            foreach($data as $r){ 
                $fetTutorStat = 'icon-check-empty'; $fetTutorRolCol = 'btn-warning'; $fetTutorRolTit = "Activate Tutor";
                if($r['visible'] == 1){  $fetTutorStat = 'icon-check'; $fetTutorRolCol = 'btn-success'; $fetTutorRolTit = "De-activate Tutor";}
                $multiActionBox = '<input type="checkbox" class="multi-action-box" data-id="'.$r['id'].'" data-name="'.$r['name'].'" data-picture="'.$r['picture'].'"  data-visible="'.$r['visible'].'" />';
                $result[] = array(utf8_encode($multiActionBox), $r['id'], utf8_encode(' <button data-id="'.$r['id'].'" data-picture="'.$r['picture'].'" data-name="'.$r['name'].'" class="btn btn-danger btn-sm delete-tutor" title="Delete"><i class="btn-icon-only icon-trash"> </i></button> <button data-id="'.$r['id'].'" data-name="'.$r['name'].'" data-qualification="'.$r['qualification'].'" data-field="'.$r['field'].'" data-email="'.$r['email'].'" data-website="'.$r['website'].'" data-picture="'.$r['picture'].'" class="btn btn-info btn-sm edit-tutor"  title="Edit"><i class="btn-icon-only icon-pencil"> </i> <span id="JQDTbioholder" data-bio ="" class="hidden">'.$r['bio'].'</span> </button> <button data-id="'.$r['id'].'" data-name="'.$r['name'].'" data-visible="'.$r['visible'].'"  class="btn '.$fetTutorRolCol.' btn-sm activate-tutor"  title="'.$fetTutorRolTit.'"><i class="btn-icon-only '.$fetTutorStat.'"> </i></button>'), utf8_encode('<img src="../media/tutor/'.utf8_encode($r['picture']).'" style="width:60px; height:50px;" alt="Pix">'), utf8_encode($r['name']), StringManipulator::trimStringToFullWord(40, utf8_encode(stripslashes(strip_tags($r['qualification'])))), StringManipulator::trimStringToFullWord(40, utf8_encode(stripslashes(strip_tags($r['field'])))), StringManipulator::trimStringToFullWord(62, utf8_encode(stripslashes(strip_tags($r['bio'])))), utf8_encode($r['email']), utf8_encode($r['website']));//
            }
            $json = array("status" => 1,"draw" => intval($draw), "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => $result);
        } 
        else{ $json = array("status" => 2, "msg" => "Necessary parameters not set. Or empty result. ".mysqli_error($this->dbObj->connection), "draw" => intval($draw),  "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => false); }
        $this->dbObj->close();
        header('Content-type: application/json');
        return json_encode($json);
    }
    
    /** Method that update details of a tutor
     * @return JSON JSON encoded success or failure message
     */
    public function update() {
        $sql = "UPDATE $this->tableName SET name = '{$this->name}', qualification = '{$this->qualification}', field = '{$this->field}', bio = '{$this->bio}', email = '{$this->email}', website = '{$this->website}', picture = '{$this->picture}' WHERE id = $this->id ";
        if(!empty($this->id)){
            $result = $this->dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done, tutor successfully update!"); }
            else{ $json = array("status" => 2, "msg" => "Error updating tutor! ".  mysqli_error($this->dbObj->connection));   }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted."); }
        $this->dbObj->close();
        header('Content-type: application/json');
        return json_encode($json); 
    }
    
    /** Method that update single field detail of a tutor
     * @param string $field Column to be updated 
     * @param string $value New value of $field (Column to be updated)
     * @param int $id Id of the post to be updated
     * @return JSON JSON encoded success or failure message
     */
    public static function updateSingle($dbObj, $field, $value, $id){
        $sql = "UPDATE tutor SET $field = '{$value}' WHERE id = $id ";
        if(!empty($id)){
            $result = $dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done, tutor successfully update!"); }
            else{ $json = array("status" => 2, "msg" => "Error updating tutor! ".  mysqli_error($dbObj->connection));   }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted."); }
        $dbObj->close();
        header('Content-type: application/json');
        echo json_encode($json);
    }
    
    /** Empty string checker  */
    public function notEmpty() {
        foreach (func_get_args() as $arg) {
            if (empty($arg)) { return false; } 
            else {continue; }
        }
        return true;
    }
    
    /** getSingle() fetches the name of a tutor using the tutor $id
     * @param object $dbObj Database connectivity and manipulation object
     * @param int $column Requested column from the database
     * @param int $id Tutor id of the tutor whose name is to be fetched
     * @return string Name of the tutor
     */
    public static function getSingle($dbObj, $column, $id) {
        $thisTutorName = '';
        $thisTutorNames = $dbObj->fetchNum("SELECT $column FROM tutor WHERE id = '{$id}' LIMIT 1");
        foreach ($thisTutorNames as $thisTutorNames) { $thisTutorName = $thisTutorNames[0]; }
        return $thisTutorName;
    }
}