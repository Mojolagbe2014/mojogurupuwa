<?php
/* 
 * Class Member describes individual members
 * @author Jamiu Babatunde Mojolagbe <mojolagbe@gmail.com>
 */
class Member implements ContentManipulator{
    //class properties/data
    private $id;
    private $name;
    private $program;
    private $field;
    private $bio;
    private $email;
    private $website;
    private $picture;
    private $visible = '1' ;
    private $graduated = 0;
    private $twitter;
    private $facebook;
    private $linkedin;
    private $dbObj;
    private $tableName;

    //class constructor
    public function Member($dbObj, $tableName='member') {
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
     * Method that submits a member into the database
     * @return JSON JSON encoded string/result
     */
    public function add(){
        $sql = "INSERT INTO $this->tableName (name, program, field, bio, email, website, picture, visible, graduated, twitter, facebook, linkedin) "
                ."VALUES ('{$this->name}','{$this->program}','{$this->field}','{$this->bio}','{$this->email}','{$this->website}','{$this->picture}','{$this->visible}','{$this->graduated}','{$this->twitter}','{$this->facebook}','{$this->linkedin}')";
        if($this->notEmpty($this->name,$this->program,$this->bio)){
            $result = $this->dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done, member successfully added!"); }
            else{ $json = array("status" => 2, "msg" => "Error adding member! ".  mysqli_error($this->dbObj->connection)); }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted. All fields must be filled."); }
        
        $this->dbObj->close();//Close Database Connection
        header('Content-type: application/json');
        return json_encode($json);
    }

    /** 
     * Method for deleting a member
     * @return JSON JSON encoded string/result
     */
    public function delete(){
        $sql = "DELETE FROM $this->tableName WHERE id = $this->id ";
        if($this->notEmpty($this->id)){
            $result = $this->dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done, member successfully deleted!"); }
            else{ $json = array("status" => 2, "msg" => "Error deleting member! ".  mysqli_error($this->dbObj->connection));  }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted."); }
        $this->dbObj->close();//Close Database Connection
        header('Content-type: application/json');
        return json_encode($json);
    }

    /** Method that fetches members from database
     * @param string $column Column name of the data to be fetched
     * @param string $condition Additional condition e.g member_id > 9
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
                $result[] = array("id" => $r['id'], "name" =>  utf8_encode($r['name']), 'program' =>  utf8_encode($r['program']), "field" =>  utf8_encode($r['field']), "bio" =>  utf8_encode(stripslashes(strip_tags($r['bio']))), "email" =>  utf8_encode($r['email']), "website" =>  utf8_encode($r['website']), "picture" =>  utf8_encode($r['picture']), "visible" =>  utf8_encode($r['visible']), "linkedin" =>  utf8_encode($r['linkedin']), "twitter" =>  utf8_encode($r['twitter']), "facebook" =>  utf8_encode($r['facebook']));
            }
            $json = array("status" => 1, "info" => $result);
        } else{ $json = array("status" => 2, "msg" => "Necessary parameters not set. Or empty result. ".mysqli_error($this->dbObj->connection)); }
        
        $this->dbObj->close();
        header('Content-type: application/json');
        return json_encode($json);
    }
    
    /** Method that fetches members from database
     * @param string $column Column name of the data to be fetched
     * @param string $condition Additional condition e.g category_id > 9
     * @param string $sort column name to be used as sort parameter
     * @return Array member list
     */
    public function fetchRaw($column="*", $condition="", $sort="id"){
        $sql = "SELECT $column FROM $this->tableName ORDER BY $sort";
        if(!empty($condition)){$sql = "SELECT $column FROM $this->tableName WHERE $condition ORDER BY $sort";}
        $result = $this->dbObj->fetchAssoc($sql);
        return $result;
    }
    
    /** Method that fetches members from database for JQuery Data Table
     * @param string $column Column name of the data to be fetched
     * @param string $condition Additional condition e.g member_id > 9
     * @param string $sort column name to be used as sort parameter
     * @return JSON JSON encoded member details
     */
    public function fetchForJQDT($draw, $totalData, $totalFiltered, $customSql="", $column="*", $condition="", $sort="id"){
        $sql = "SELECT $column FROM $this->tableName ORDER BY $sort";
        if(!empty($condition)){$sql = "SELECT $column FROM $this->tableName WHERE $condition ORDER BY $sort";}
        if($customSql !=""){ $sql = $customSql; }
        $data = $this->dbObj->fetchAssoc($sql);
        $result =array(); $fetMemberStat = 'icon-check-empty'; $fetMemberRolCol = 'btn-warning'; $fetMemberRolTit = "Activate Member";
        if(count($data)>0){
            foreach($data as $r){ 
                $fetMemberStat = 'icon-check-empty'; $fetMemberRolCol = 'btn-warning'; $fetMemberRolTit = "Activate Member";
                $graduatedSt = 'icon-eye-close'; $graduatedStCol = 'btn-warning'; $graduatedStTit = "Activate Graduated";
                if($r['graduated'] == 1){  $graduatedSt = 'icon-eye-open'; $graduatedStCol = 'btn-success'; $graduatedStTit = "Activate Current Student";}
                if($r['visible'] == 1){  $fetMemberStat = 'icon-check'; $fetMemberRolCol = 'btn-success'; $fetMemberRolTit = "De-activate Member";}
                $multiActionBox = '<input type="checkbox" class="multi-action-box" data-id="'.$r['id'].'" data-name="'.$r['name'].'" data-picture="'.$r['picture'].'"  data-visible="'.$r['visible'].'" data-graduated="'.$r['graduated'].'" />';
                $actionLink = ' <button data-id="'.$r['id'].'" data-picture="'.$r['picture'].'" data-name="'.$r['name'].'" class="btn btn-danger btn-sm delete-member" title="Delete"><i class="btn-icon-only icon-trash"> </i></button> <button data-id="'.$r['id'].'" data-name="'.$r['name'].'" data-program="'.$r['program'].'" data-field="'.$r['field'].'" data-email="'.$r['email'].'" data-website="'.$r['website'].'" data-picture="'.$r['picture'].'" class="btn btn-info btn-sm edit-member"  title="Edit"><i class="btn-icon-only icon-pencil"> </i> <span id="JQDTbioholder" data-bio =""  data-linkedin="'.$r['linkedin'].'"  data-twitter="'.$r['twitter'].'"  data-facebook="'.$r['facebook'].'" class="hidden">'.$r['bio'].'</span> </button> <button data-id="'.$r['id'].'" data-name="'.$r['name'].'" data-visible="'.$r['visible'].'"  data-linkedin="'.$r['linkedin'].'"  data-twitter="'.$r['twitter'].'"  data-facebook="'.$r['facebook'].'"  class="btn '.$fetMemberRolCol.' btn-sm activate-member"  title="'.$fetMemberRolTit.'"><i class="btn-icon-only '.$fetMemberStat.'"> </i></button> <button data-id="'.$r['id'].'" data-name="'.$r['name'].'" data-graduated="'.$r['graduated'].'" data-linkedin="'.$r['linkedin'].'"  data-twitter="'.$r['twitter'].'"  data-facebook="'.$r['facebook'].'" class="btn '.$graduatedStCol.' btn-sm set-graduation"  title="'.$graduatedStTit.'"><i class="btn-icon-only '.$graduatedSt.'"> </i></button>';
                $result[] = array(utf8_encode($multiActionBox), $r['id'], utf8_encode($actionLink), utf8_encode('<img src="../media/member/'.utf8_encode($r['picture']).'" style="width:60px; height:50px;" alt="Pix">'), utf8_encode($r['name']), StringManipulator::trimStringToFullWord(40, utf8_encode(stripslashes(strip_tags($r['program'])))), StringManipulator::trimStringToFullWord(40, utf8_encode(stripslashes(strip_tags($r['field'])))), StringManipulator::trimStringToFullWord(62, utf8_encode(stripslashes(strip_tags($r['bio'])))), utf8_encode($r['email']), utf8_encode($r['website']), utf8_encode($r['twitter']), utf8_encode($r['facebook']), utf8_encode($r['linkedin']));//
            }
            $json = array("status" => 1,"draw" => intval($draw), "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => $result);
        } 
        else{ $json = array("status" => 2, "msg" => "Necessary parameters not set. Or empty result. ".mysqli_error($this->dbObj->connection), "draw" => intval($draw),  "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => false); }
        $this->dbObj->close();
        header('Content-type: application/json');
        return json_encode($json);
    }
    
    /** Method that update details of a member
     * @return JSON JSON encoded success or failure message
     */
    public function update() {
        $sql = "UPDATE $this->tableName SET name = '{$this->name}', program = '{$this->program}', field = '{$this->field}', bio = '{$this->bio}', email = '{$this->email}', twitter = '{$this->twitter}', facebook = '{$this->facebook}', linkedin = '{$this->linkedin}', website = '{$this->website}', picture = '{$this->picture}' WHERE id = $this->id ";
        if(!empty($this->id)){
            $result = $this->dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done, member successfully update!"); }
            else{ $json = array("status" => 2, "msg" => "Error updating member! ".  mysqli_error($this->dbObj->connection));   }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted."); }
        $this->dbObj->close();
        header('Content-type: application/json');
        return json_encode($json); 
    }
    
    /** Method that update single field detail of a member
     * @param string $field Column to be updated 
     * @param string $value New value of $field (Column to be updated)
     * @param int $id Id of the post to be updated
     * @return JSON JSON encoded success or failure message
     */
    public static function updateSingle($dbObj, $field, $value, $id){
        $sql = "UPDATE member SET $field = '{$value}' WHERE id = $id ";
        if(!empty($id)){
            $result = $dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done, member successfully update!"); }
            else{ $json = array("status" => 2, "msg" => "Error updating member! ".  mysqli_error($dbObj->connection));   }
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
    
    /** getSingle() fetches the name of a member using the member $id
     * @param object $dbObj Database connectivity and manipulation object
     * @param int $column Requested column from the database
     * @param int $id Member id of the member whose name is to be fetched
     * @return string Name of the member
     */
    public static function getSingle($dbObj, $column, $id) {
        $thisMemberName = '';
        $thisMemberNames = $dbObj->fetchNum("SELECT $column FROM member WHERE id = '{$id}' LIMIT 1");
        foreach ($thisMemberNames as $thisMemberNames) { $thisMemberName = $thisMemberNames[0]; }
        return $thisMemberName;
    }
}