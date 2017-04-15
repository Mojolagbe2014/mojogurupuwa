<?php
/**
 * Description of Presentation
 *
 * @author Jamiu Babatunde Mojolagbe <mojolagbe@gmail.com>
 */
class Presentation implements ContentManipulator{
    private $id;
    private $name;
    private $organizer;
    private $location;
    private $datePresented;
    private $description;
    private $media;
    private $image;
    private $dateRegistered = " CURRENT_DATE ";
    private $status = 1;
    private $featured = 1;
    private $dbObj;
    private $tableName;
    
    //Class constructor
    public function Presentation($dbObj, $tableName='presentation') {
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
     * Method that adds a presentation into the database
     * @return JSON JSON encoded string/result
     */
    function add(){
        $sql = "INSERT INTO presentation (name, organizer, location, date_presented, description, media, status, date_registered, image, featured) "
                ."VALUES ('{$this->name}','{$this->organizer}','{$this->location}','{$this->datePresented}','{$this->description}','{$this->media}','{$this->status}',$this->dateRegistered,'{$this->image}','{$this->featured}')";
        if($this->notEmpty($this->name,$this->datePresented,$this->description,$this->image)){
            $result = $this->dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done, presentation successfully added!"); }
            else{ $json = array("status" => 2, "msg" => "Error adding presentation! ".  mysqli_error($this->dbObj->connection)); }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted. All fields must be filled."); }
        
        $this->dbObj->close();//Close Database Connection
        header('Content-type: application/json');
        return json_encode($json);
    }

    /** 
     * Method for deleting a presentation
     * @return JSON JSON encoded result
     */
    public function delete(){
        $sql = "DELETE FROM presentation WHERE id = $this->id ";
        if($this->notEmpty($this->id)){
            $result = $this->dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done, presentation successfully deleted!"); }
            else{ $json = array("status" => 2, "msg" => "Error deleting presentation! ".  mysqli_error($this->dbObj->connection));  }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted."); }
        $this->dbObj->close();//Close Database Connection
        header('Content-type: application/json');
        return json_encode($json);
    }

    /** Method that fetches presentations from database for JQuery Data Table
     * @param string $column Column name of the data to be fetched
     * @param string $condition Additional condition e.g location_id > 9
     * @param string $sort column name to be used as sort parameter
     * @return JSON JSON encoded presentation details
     */
    public function fetchForJQDT($draw, $totalData, $totalFiltered, $customSql="", $column="*", $condition="", $sort="id"){
        $sql = "SELECT $column FROM presentation ORDER BY $sort";
        if(!empty($condition)){$sql = "SELECT $column FROM presentation WHERE $condition ORDER BY $sort";}
        if($customSql !=""){ $sql = $customSql; }
        $data = $this->dbObj->fetchAssoc($sql);
        $result =array();  $fetPresentationStat = 'icon-check-empty'; $fetPresentationRolCol = 'btn-warning'; $fetPresentationRolTit = "Activate Presentation";
        if(count($data)>0){
            foreach($data as $r){ 
                $presentationMediaLink = '';
                $fetPresentationStat = 'icon-check-empty'; $fetPresentationRolCol = 'btn-warning'; $fetPresentationRolTit = "Activate Presentation";
                $fetPresentationFeat = 'icon-eye-close'; $fetPresentationFeatCol = 'btn-warning'; $fetPresentationFeatTit = "Make Featured Presentation";
                if($r['status'] == 1){  $fetPresentationStat = 'icon-check'; $fetPresentationRolCol = 'btn-success'; $fetPresentationRolTit = "De-activate Presentation";}
                if($r['featured'] == 1){  $fetPresentationFeat = 'icon-eye-open'; $fetPresentationFeatCol = 'btn-success'; $fetPresentationFeatTit = "Remove as Featured Presentation";}
                if($r['media'] !=''){ $presentationMediaLink = '<a href="'.SITE_URL.'media/presentation/'.$r['media'].'">View Media</a>'; }
                $multiActionBox = '<input type="checkbox" class="multi-action-box" data-id="'.$r['id'].'" data-name="'.$r['name'].'" data-status="'.$r['status'].'" data-image="'.$r['image'].'" data-media="'.$r['media'].'" data-featured="'.$r['featured'].'" />';
                $actionLink = ' <button data-id="'.$r['id'].'" data-name="'.$r['name'].'" data-organizer="'.$r['organizer'].'" data-location="'.$r['location'].'" data-date-presented="'.$r['date_presented'].'" data-description ="" data-media="'.$r['media'].'"  data-image="'.$r['image'].'" data-date-registered="'.$r['date_registered'].'" class="btn btn-info btn-sm edit-presentation"  title="Edit"><i class="btn-icon-only icon-pencil"> </i> <span class="hidden" id="JQDTdescriptionholder">'.$r['description'].'</span> </button> <button data-id="'.$r['id'].'" data-name="'.$r['name'].'" data-status="'.$r['status'].'"  class="btn '.$fetPresentationRolCol.' btn-sm activate-presentation"  title="'.$fetPresentationRolTit.'"><i class="btn-icon-only '.$fetPresentationStat.'"> </i></button> <button data-id="'.$r['id'].'" data-media="'.$r['media'].'"  data-image="'.$r['image'].'" data-name="'.$r['name'].'" class="btn btn-danger btn-sm delete-presentation" title="Delete"><i class="btn-icon-only icon-trash"> </i></button> <button data-id="'.$r['id'].'" data-name="'.$r['name'].'" data-featured="'.$r['featured'].'"  class="btn '.$fetPresentationFeatCol.' btn-sm make-featured-presentation"  title="'.$fetPresentationFeatTit.'"><i class="btn-icon-only '.$fetPresentationFeat.'"> </i></button>';
                $result[] = array(utf8_encode($multiActionBox), utf8_encode($actionLink), $r['id'], utf8_encode($r['name']), utf8_encode($r['organizer']), utf8_encode($r['location']), utf8_encode($r['date_presented']), StringManipulator::trimStringToFullWord(60, utf8_encode(stripcslashes(strip_tags($r['description'])))), utf8_encode($presentationMediaLink), utf8_encode('<img src="../media/presentation-image/'.utf8_encode($r['image']).'" width="60" height="50" style="width:60px; height:50px;" alt="Pix">'), utf8_encode($r['date_registered']));//
            }
            $json = array("status" => 1,"draw" => intval($draw), "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => $result);
        } 
        else{ $json = array("status" => 2, "msg" => "Necessary parameters not set. Or empty result. ".mysqli_error($this->dbObj->connection), "draw" => intval($draw),  "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => false); }
        $this->dbObj->close();
        //header('Content-type: application/json');
        return json_encode($json);
    }
    
    /** Method that fetches presentations from database
     * @param string $column Column name of the data to be fetched
     * @param string $condition Additional condition e.g location_id > 9
     * @param string $sort column name to be used as sort parameter
     * @return JSON JSON encoded presentation details
     */
    public function fetch($column="*", $condition="", $sort="id"){
        $sql = "SELECT $column FROM presentation ORDER BY $sort";
        if(!empty($condition)){$sql = "SELECT $column FROM presentation WHERE $condition ORDER BY $sort";}
        $data = $this->dbObj->fetchAssoc($sql);
        $result =array(); 
        if(count($data)>0){
            foreach($data as $r){
                $result[] = array("id" => $r['id'], "name" =>  utf8_encode($r['name']), "image" =>  utf8_encode($r['image']), 'organizer' =>  utf8_encode($r['organizer']), 'location' => utf8_encode($r['location']), 'datePresented' =>  utf8_encode($r['date_presented']), 'description' => utf8_encode(StringManipulator::trimStringToFullWord(200, stripcslashes(strip_tags($r['description'])))), 'media' =>  utf8_encode($r['media']), 'status' =>  utf8_encode($r['status']), 'dateRegistered' => utf8_encode($r['date_registered']));
            }
            $json = array("status" => 1, "info" => $result);
        } 
        else{ $json = array("status" => 2, "msg" => "Necessary parameters not set. Or empty result. ".mysqli_error($this->dbObj->connection)); }
        $this->dbObj->close();
        header('Content-type: application/json');
        return json_encode($json);
    }

    /** Method that fetches presentations from database
     * @param string $column Column name of the data to be fetched
     * @param string $condition Additional condition e.g location_id > 9
     * @param string $sort column name to be used as sort parameter
     * @return Array Presentations list
     */
    public function fetchRaw($column="*", $condition="", $sort="id"){
        $sql = "SELECT $column FROM presentation ORDER BY $sort";
        if(!empty($condition)){$sql = "SELECT $column FROM presentation WHERE $condition ORDER BY $sort";}
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
    
    /** Method that update single field detail of a presentation
     * @param string $field Column to be updated 
     * @param string $value New value of $field (Column to be updated)
     * @param int $id Id of the post to be updated
     * @return JSON JSON encoded success or failure message
     */
    public static function updateSingle($dbObj, $field, $value, $id){
        $sql = "UPDATE presentation SET $field = '{$value}' WHERE id = $id ";
        if(!empty($id)){
            $result = $dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done, presentation successfully updated!"); }
            else{ $json = array("status" => 2, "msg" => "Error updating presentation! ".  mysqli_error($dbObj->connection));   }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted."); }
        $dbObj->close();
        header('Content-type: application/json');
        return json_encode($json);
    }

    /** Method that update details of a presentation
     * @return JSON JSON encoded success or failure message
     */
    public function update() {
        $sql = "UPDATE presentation SET name = '{$this->name}', image = '{$this->image}', organizer = '{$this->organizer}', location = '{$this->location}', date_presented = '{$this->datePresented}', description = '{$this->description}', media = '{$this->media}' WHERE id = $this->id ";
        if(!empty($this->id)){
            $result = $this->dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done, presentation successfully updated!"); }
            else{ $json = array("status" => 2, "msg" => "Error updating presentation! ".  mysqli_error($this->dbObj->connection));   }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted."); }
        $this->dbObj->close();
        header('Content-type: application/json');
        return json_encode($json); 
    }
    
    /** getName() fetches the name of a presentation using the presentation $id
     * @param object $dbObj Database connectivity and manipulation object
     * @param int $id Category id of the location whose name is to be fetched
     * @return string Name of the location
     */
    public static function getName($dbObj, $id) {
        $thisPresentationName = '';
        $thisPresentationNames = $dbObj->fetchNum("SELECT name FROM presentation WHERE id = '{$id}' LIMIT 1");
        foreach ($thisPresentationNames as $thisPresentationNames) { $thisPresentationName = $thisPresentationNames[0]; }
        return $thisPresentationName;
    }

    
    /** getSingle() fetches the title of an presentation using the presentation $id
     * @param object $dbObj Database connectivity and manipulation object
     * @param string $column Required column in the database
     * @param int $id Presentation id of the presentation whose name is to be fetched
     * @return string Name of the presentation
     */
    public static function getSingle($dbObj, $column, $id) {
        $thisAsstReqVal = '';
        $thisAsstReqVals = $dbObj->fetchNum("SELECT $column FROM presentation WHERE id = '{$id}' ");
        foreach ($thisAsstReqVals as $thisAsstReqVals) { $thisAsstReqVal = $thisAsstReqVals[0]; }
        return $thisAsstReqVal;
    }
    
    /**
     * Method that returns count/total number of a particular presentation
     * @param Object $dbObj Database connectivity object
     * @param Object $condition Additional optional condition
     * @return int Number of presentations
     */
    public static function getRawCount($dbObj, $condition=" 1=1 "){
        $sql = "SELECT * FROM presentation WHERE $condition ";
        $count = "";
        $result = $dbObj->query($sql);
        $totalData = mysqli_num_rows($result);
        if($result !== false){ $count = $totalData; }
        return $count;
    }
}
