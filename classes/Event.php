<?php
/**
 * Description of Event
 *
 * @author Kaiste
 */
class Event implements ContentManipulator{
    private $id;
    private $name;
    private $description;
    private $location;
    private $image;
    private $dateTime;
    private $dateAdded = ' NOW() ';
    private $status = 0;
    private $dbObj;
    
    
    //Class constructor
    public function Event($dbObj) {
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
     * Method that adds a  event into the database
     * @return JSON JSON encoded string/result
     */
    function add(){
        $sql = "INSERT INTO event (name, description, location, image, date_time, status, date_added) "
                ."VALUES ('{$this->name}','{$this->description}','{$this->location}','{$this->image}','{$this->dateTime}','{$this->status}',$this->dateAdded)";
        if($this->notEmpty($this->name,$this->description,$this->location)){
            $result = $this->dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done, event successfully added!"); }
            else{ $json = array("status" => 2, "msg" => "Error adding  event! ".  mysqli_error($this->dbObj->connection)); }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted. All fields must be filled."); }
        
        $this->dbObj->close();//Close Database Connection
        header('Content-type: application/json');
        return json_encode($json);
    }

    /** 
     * Method for deleting a  event
     * @return JSON JSON encoded result
     */
    public function delete(){
        $sql = "DELETE FROM event WHERE id = $this->id ";
        if($this->notEmpty($this->id)){
            $result = $this->dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done,  event successfully deleted!"); }
            else{ $json = array("status" => 2, "msg" => "Error deleting  event! ".  mysqli_error($this->dbObj->connection));  }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted."); }
        $this->dbObj->close();//Close Database Connection
        header('Content-type: application/json');
        return json_encode($json);
    }

    /** Method that fetches events from database for JQuery Data Table
     * @param string $column Column name of the data to be fetched
     * @param string $condition Additional condition e.g  event_id > 9
     * @param string $sort column name to be used as sort parameter
     * @return JSON JSON encoded event details
     */
    public function fetchForJQDT($draw, $totalData, $totalFiltered, $customSql="", $column="*", $condition="", $sort="id"){
        $sql = "SELECT $column FROM event ORDER BY $sort";
        if(!empty($condition)){$sql = "SELECT $column FROM event WHERE $condition ORDER BY $sort";}
        if($customSql !=""){ $sql = $customSql; }
        $data = $this->dbObj->fetchAssoc($sql);
        $result =array(); $fetEventStat = 'icon-check-empty'; $fetEventRolCol = 'btn-warning'; $fetEventRolTit = "Activate Event";
        if(count($data)>0){
            foreach($data as $r){ 
                $fetEventStat = 'icon-check-empty'; $fetEventRolCol = 'btn-warning'; $fetEventRolTit = "Activate Event";
                $multiActionBox = '<input type="checkbox" class="multi-action-box" data-id="'.$r['id'].'" data-image="'.$r['image'].'" data-name="'.$r['name'].'" data-status="'.$r['status'].'"/>';
                if($r['status'] == 1){  $fetEventStat = 'icon-check'; $fetEventRolCol = 'btn-success'; $fetEventRolTit = "De-activate Event";}
                $result[] = array(utf8_encode($multiActionBox), $r['id'], utf8_encode('<div style="white-space:nowrap"><button data-id="'.$r['id'].'" data-image="'.$r['image'].'" data-name="'.$r['name'].'"  data-location="'.$r['location'].'" class="btn btn-danger btn-sm delete-event" title="Delete"><i class="btn-icon-only icon-trash"> </i></button> <button  data-id="'.$r['id'].'"  data-image="'.$r['image'].'" data-name="'.$r['name'].'" data-location="'.$r['location'].'"  data-date-time="'.$r['date_time'].'" class="btn btn-info btn-sm edit-event"  title="Edit"><i class="btn-icon-only icon-pencil"> </i> <span class="hidden" id="JQDTdescriptionholder">'.utf8_encode($r['description']).'</span> </button> <button data-id="'.$r['id'].'" data-name="'.$r['name'].'" data-status="'.$r['status'].'"  class="btn '.$fetEventRolCol.' btn-sm activate-event"  title="'.$fetEventRolTit.'"><i class="btn-icon-only '.$fetEventStat.'"> </i></button></div>'), utf8_encode($r['name']), utf8_encode('<img src="../media/event/'.utf8_encode($r['image']).'" width="40" height="30" alt="Pix">'), StringManipulator::trimStringToFullWord(90, strip_tags(utf8_encode($r['description']))), utf8_encode($r['date_time']), utf8_encode($r['location']), utf8_encode($r['date_added']));//
            }
            $json = array("status" => 1,"draw" => intval($draw), "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => $result);
        } 
        else{ $json = array("status" => 2, "msg" => "Necessary parameters not set. Or empty result. ".mysqli_error($this->dbObj->connection), "draw" => intval($draw),  "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => false); }
        $this->dbObj->close();
        //header('Content-type: application/json');
        return json_encode($json);
    }
    
    /** Method that fetches events from database
     * @param string $column Column name of the data to be fetched
     * @param string $condition Additional condition e.g  event_id > 9
     * @param string $sort column name to be used as sort parameter
     * @return JSON JSON encoded  event details
     */
    public function fetch($column="*", $condition="", $sort="id"){
        $sql = "SELECT $column FROM event ORDER BY $sort";
        if(!empty($condition)){$sql = "SELECT $column FROM event WHERE $condition ORDER BY $sort";}
        $data = $this->dbObj->fetchAssoc($sql);
        $result =array(); 
        if(count($data)>0){
            foreach($data as $r){
                $result[] = array("id" => $r['id'], "name" =>  utf8_encode($r['name']), "image" =>  utf8_encode($r['image']), "description" =>  utf8_encode(stripcslashes(strip_tags($r['description']))), "location" =>  utf8_encode($r['location']), "dateAdded" =>  utf8_encode($r['date_added']), "dateTime" =>  utf8_encode($r['date_time']), "status" =>  utf8_encode($r['status']));
            }
            $json = array("status" => 1, "info" => $result);
        } 
        else{ $json = array("status" => 2, "msg" => "Empty result. ".mysqli_error($this->dbObj->connection)); }
        $this->dbObj->close();
        header('Content-type: application/json');
        return json_encode($json);
    }

    /** Method that fetches events from database
     * @param string $column Column name of the data to be fetched
     * @param string $condition Additional condition e.g category_id > 9
     * @param string $sort column name to be used as sort parameter
     * @return Array events list
     */
    public function fetchRaw($column="*", $condition="", $sort="id"){
        $sql = "SELECT $column FROM event ORDER BY $sort";
        if(!empty($condition)){$sql = "SELECT $column FROM event WHERE $condition ORDER BY $sort";}
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
    
    /** Method that update single field detail of a  event
     * @param string $field Column to be updated 
     * @param string $value New value of $field (Column to be updated)
     * @param int $id Id of the post to be updated
     * @return JSON JSON encoded success or failure message
     */
    public static function updateSingle($dbObj, $field, $value, $id){
        $sql = "UPDATE event SET $field = '{$value}' WHERE id = $id ";
        if(!empty($id)){
            $result = $dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done,  event successfully update!"); }
            else{ $json = array("status" => 2, "msg" => "Error updating  event! ".  mysqli_error($dbObj->connection));   }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted."); }
        $dbObj->close();
        header('Content-type: application/json');
        return json_encode($json);
    }

    /** Method that update details of a  event
     * @return JSON JSON encoded success or failure message
     */
    public function update() {
        $sql = "UPDATE event SET name = '{$this->name}', image = '{$this->image}', description = '{$this->description}', location = '{$this->location}', date_time = '{$this->dateTime}' WHERE id = $this->id ";
        if($this->notEmpty($this->id, $this->description, $this->name)){
            $result = $this->dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done,  event successfully update!"); }
            else{ $json = array("status" => 2, "msg" => "Error updating  event! ".  mysqli_error($this->dbObj->connection));   }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted."); }
        $this->dbObj->close();
        header('Content-type: application/json');
        return json_encode($json); 
    }

    /** getName() fetches the name of a event using the event $id
     * @param object $dbObj Database connectivity and manipulation object
     * @param int $id Category id of the  event whose name is to be fetched
     * @return string Name of the  event
     */
    public static function getName($dbObj, $id) {
        $thisEventName = '';
        $thisEventNames = $dbObj->fetchNum("SELECT name FROM event WHERE id = '{$id}' LIMIT 1");
        foreach ($thisEventNames as $thisEventNames) { $thisEventName = $thisEventNames[0]; }
        return $thisEventName;
    }
    
    /** getSingle() fetches the column of an event using the event $id
     * @param object $dbObj Database connectivity and manipulation object
     * @param string $column Table's required column in the datatbase
     * @param int $id Course id of the event whose name is to be fetched
     * @return string Name of the event
     */
    public static function getSingle($dbObj, $column, $id) {
        $thisReqColVal = '';
        $thisReqColVals = $dbObj->fetchNum("SELECT $column FROM event WHERE id = '{$id}' ");
        foreach ($thisReqColVals as $thisReqColVals) { $thisReqColVal = $thisReqColVals[0]; }
        return $thisReqColVal;
    }
}
