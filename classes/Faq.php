<?php
/**
 * Description of Faq
 *
 * @author Jamiu Babatunde Mojolagbe <mojolagbe@gmail.com>
 */
class Faq implements ContentManipulator{
    private $id;
    private $question;
    private $answer;
    private $dateAdded = ' CURRENT_DATE ';
    private $dbObj;
    
    
    //Class constructor
    public function Faq($dbObj) {
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
     * Method that adds a  faq into the database
     * @return JSON JSON encoded string/result
     */
    function add(){
        $sql = "INSERT INTO faq (question, answer, date_added) "
                ."VALUES ('{$this->question}','{$this->answer}',$this->dateAdded)";
        if($this->notEmpty($this->question,$this->answer)){
            $result = $this->dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done, faq successfully added!"); }
            else{ $json = array("status" => 2, "msg" => "Error adding  faq! ".  mysqli_error($this->dbObj->connection)); }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted. All fields must be filled."); }
        
        $this->dbObj->close();//Close Database Connection
        header('Content-type: application/json');
        return json_encode($json);
    }

    /** 
     * Method for deleting a  faq
     * @return JSON JSON encoded result
     */
    public function delete(){
        $sql = "DELETE FROM faq WHERE id = $this->id ";
        if($this->notEmpty($this->id)){
            $result = $this->dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done,  faq successfully deleted!"); }
            else{ $json = array("status" => 2, "msg" => "Error deleting  faq! ".  mysqli_error($this->dbObj->connection));  }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted."); }
        $this->dbObj->close();//Close Database Connection
        header('Content-type: application/json');
        return json_encode($json);
    }

    /** Method that fetches faqs from database for JQuery Data Table
     * @param string $column Column question of the data to be fetched
     * @param string $condition Additional condition e.g  faq_id > 9
     * @param string $sort column question to be used as sort parameter
     * @return JSON JSON encoded course faq details
     */
    public function fetchForJQDT($draw, $totalData, $totalFiltered, $customSql="", $column="*", $condition="", $sort="id"){
        $sql = "SELECT $column FROM faq ORDER BY $sort";
        if(!empty($condition)){$sql = "SELECT $column FROM faq WHERE $condition ORDER BY $sort";}
        if($customSql !=""){ $sql = $customSql; }
        $data = $this->dbObj->fetchAssoc($sql);
        $result =array(); 
        if(count($data)>0){
            foreach($data as $r){ 
                $multiActionBox = '<input type="checkbox" class="multi-action-box" data-id="'.$r['id'].'" data-question="'.$r['question'].'" />';
                $result[] = array(utf8_encode($multiActionBox), $r['id'], utf8_encode($r['question']), utf8_encode($r['answer']), utf8_encode($r['date_added']), utf8_encode(' <button data-date-added="'.$r['date_added'].'" data-id="'.$r['id'].'" class="btn btn-info btn-sm edit-faq"  title="Edit"><i class="btn-icon-only icon-pencil"> </i> <span id="JQDTquestionholder" class="hidden">'.$r['question'].'</span> <span id="JQDTanswerholder" class="hidden">'.$r['answer'].'</span> </button> <button data-id="'.$r['id'].'" class="btn btn-danger btn-sm delete-faq" title="Delete"><i class="btn-icon-only icon-trash"> </i> <span id="JQDTquestionholder2" class="hidden">'.$r['question'].'</span></button>'));
            }
            $json = array("status" => 1,"draw" => intval($draw), "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => $result);
        } 
        else{ $json = array("status" => 2, "msg" => "Empty result. ".mysqli_error($this->dbObj->connection), "draw" => intval($draw),  "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => false); }
        $this->dbObj->close();
        header('Content-type: application/json');
        return json_encode($json);
    }
    
    /** Method that fetches faqs from database
     * @param string $column Column question of the data to be fetched
     * @param string $condition Additional condition e.g  faq_id > 9
     * @param string $sort column question to be used as sort parameter
     * @return JSON JSON encoded course faq details
     */
    public function fetch($column="*", $condition="", $sort="id"){
        $sql = "SELECT $column FROM faq ORDER BY $sort";
        if(!empty($condition)){$sql = "SELECT $column FROM faq WHERE $condition ORDER BY $sort";}
        $data = $this->dbObj->fetchAssoc($sql);
        $result =array(); 
        if(count($data)>0){
            foreach($data as $r){
                $result[] = array("id" => $r['id'], "question" =>  utf8_encode($r['question']), "answer" =>  utf8_encode($r['answer']), "dateAdded" =>  utf8_encode($r['date_added']));
            }
            $json = array("status" => 1, "info" => $result);
        } 
        else{ $json = array("status" => 2, "msg" => "Empty result. ".mysqli_error($this->dbObj->connection)); }
        $this->dbObj->close();
        header('Content-type: application/json');
        return json_encode($json);
    }

    /** Method that fetches faqs from database
     * @param string $column Column name of the data to be fetched
     * @param string $condition Additional condition e.g category_id > 9
     * @param string $sort column name to be used as sort parameter
     * @return Array FAQ list
     */
    public function fetchRaw($column="*", $condition="", $sort="id"){
        $sql = "SELECT $column FROM faq ORDER BY $sort";
        if(!empty($condition)){$sql = "SELECT $column FROM faq WHERE $condition ORDER BY $sort";}
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
    
    /** Method that update single field detail of a  faq
     * @param string $field Column to be updated 
     * @param string $value New value of $field (Column to be updated)
     * @param int $id Id of the post to be updated
     * @return JSON JSON encoded success or failure message
     */
    public static function updateSingle($dbObj, $field, $value, $id){
        $sql = "UPDATE faq SET $field = '{$value}' WHERE id = $id ";
        if(!empty($id)){
            $result = $dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done,  faq successfully updated!"); }
            else{ $json = array("status" => 2, "msg" => "Error updating  faq! ".  mysqli_error($dbObj->connection));   }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted."); }
        $dbObj->close();
        header('Content-type: application/json');
        return json_encode($json);
    }

    /** Method that update details of a  faq
     * @return JSON JSON encoded success or failure message
     */
    public function update() {
        $sql = "UPDATE faq SET question = '{$this->question}', answer = '{$this->answer}' WHERE id = $this->id ";
        if(!empty($this->id)){
            $result = $this->dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done,  faq successfully updated!"); }
            else{ $json = array("status" => 2, "msg" => "Error updating  faq! ".  mysqli_error($this->dbObj->connection));   }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted."); }
        $this->dbObj->close();
        header('Content-type: application/json');
        return json_encode($json); 
    }

    /** getName() fetches the question of a faq using the faq $id
     * @param object $dbObj Database connectivity and manipulation object
     * @param int $id Category id of the  faq whose question is to be fetched
     * @return string Name of the  faq
     */
    public static function getName($dbObj, $id) {
        $thisFaqName = '';
        $thisFaqNames = $dbObj->fetchNum("SELECT question FROM faq WHERE id = '{$id}' LIMIT 1");
        foreach ($thisFaqNames as $thisFaqNames) { $thisFaqName = $thisFaqNames[0]; }
        return $thisFaqName;
    }
}
