<?php
/**
 * Description of Project
 *
 * @author Jamiu Babatunde Mojolagbe
 */
class Project implements ContentManipulator{
    private $id;
    private $name;
    private $isCompleted;
    private $startDate;
    private $endDate;
    private $description;
    private $media;
    private $image;
    private $sponsor;
    private $dateRegistered = " CURRENT_DATE ";
    private $status = 1;
    private $featured = 1;
    private $dbObj;
    private $tableName;
    
    //Class constructor
    public function Project($dbObj, $tableName='project') {
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
     * Method that adds a project into the database
     * @return JSON JSON encoded string/result
     */
    function add(){
        $sql = "INSERT INTO project (name, is_completed, sponsor, start_date, end_date, description, media, status, date_registered, image, featured) "
                ."VALUES ('{$this->name}','{$this->isCompleted}','{$this->sponsor}','{$this->startDate}','{$this->endDate}','{$this->description}','{$this->media}','{$this->status}',$this->dateRegistered,'{$this->image}','{$this->featured}')";
        if($this->notEmpty($this->name,$this->startDate,$this->description)){
            $result = $this->dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done, project successfully added!"); }
            else{ $json = array("status" => 2, "msg" => "Error adding project! ".  mysqli_error($this->dbObj->connection)); }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted. All fields must be filled."); }
        
        $this->dbObj->close();//Close Database Connection
        header('Content-type: application/json');
        return json_encode($json);
    }

    /** 
     * Method for deleting a project
     * @return JSON JSON encoded result
     */
    public function delete(){
        $sql = "DELETE FROM project WHERE id = $this->id ";
        if($this->notEmpty($this->id)){
            $result = $this->dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done, project successfully deleted!"); }
            else{ $json = array("status" => 2, "msg" => "Error deleting project! ".  mysqli_error($this->dbObj->connection));  }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted."); }
        $this->dbObj->close();//Close Database Connection
        header('Content-type: application/json');
        return json_encode($json);
    }

    /** Method that fetches projects from database for JQuery Data Table
     * @param string $column Column name of the data to be fetched
     * @param string $condition Additional condition e.g sponsor_id > 9
     * @param string $sort column name to be used as sort parameter
     * @return JSON JSON encoded project details
     */
    public function fetchForJQDT($draw, $totalData, $totalFiltered, $customSql="", $column="*", $condition="", $sort="id"){
        $sql = "SELECT $column FROM project ORDER BY $sort";
        if(!empty($condition)){$sql = "SELECT $column FROM project WHERE $condition ORDER BY $sort";}
        if($customSql !=""){ $sql = $customSql; }
        $data = $this->dbObj->fetchAssoc($sql);
        $result =array();  $fetProjectStat = 'icon-check-empty'; $fetProjectRolCol = 'btn-warning'; $fetProjectRolTit = "Activate Project";
        if(count($data)>0){
            foreach($data as $r){ 
                $projectMediaLink = '';
                $fetProjectStat = 'icon-check-empty'; $fetProjectRolCol = 'btn-warning'; $fetProjectRolTit = "Activate Project";
                $fetProjectFeat = 'icon-eye-close'; $fetProjectFeatCol = 'btn-warning'; $fetProjectFeatTit = "Make Featured Project Project";
                if($r['status'] == 1){  $fetProjectStat = 'icon-check'; $fetProjectRolCol = 'btn-success'; $fetProjectRolTit = "De-activate Project";}
                if($r['featured'] == 1){  $fetProjectFeat = 'icon-eye-open'; $fetProjectFeatCol = 'btn-success'; $fetProjectFeatTit = "Remove Featured Project Project";}
                if($r['media'] !=''){ $projectMediaLink = '<a href="'.SITE_URL.'media/project/'.$r['media'].'">View Media</a>'; }
                $multiActionBox = '<input type="checkbox" class="multi-action-box" data-id="'.$r['id'].'" data-name="'.$r['name'].'" data-status="'.$r['status'].'" data-image="'.$r['image'].'" data-media="'.$r['media'].'" data-featured="'.$r['featured'].'" />';
                $actionLink = ' <button data-id="'.$r['id'].'" data-name="'.$r['name'].'" data-is-completed="'.$r['is_completed'].'" data-sponsor="'.$r['sponsor'].'" data-start-date="'.$r['start_date'].'" data-end-date="'.$r['end_date'].'" data-description ="" data-media="'.$r['media'].'"  data-image="'.$r['image'].'" data-date-registered="'.$r['date_registered'].'" class="btn btn-info btn-sm edit-project"  title="Edit"><i class="btn-icon-only icon-pencil"> </i> <span class="hidden" id="JQDTdescriptionholder">'.$r['description'].'</span> </button> <button data-id="'.$r['id'].'" data-name="'.$r['name'].'" data-status="'.$r['status'].'"  class="btn '.$fetProjectRolCol.' btn-sm activate-project"  title="'.$fetProjectRolTit.'"><i class="btn-icon-only '.$fetProjectStat.'"> </i></button> <button data-id="'.$r['id'].'" data-media="'.$r['media'].'"  data-image="'.$r['image'].'" data-name="'.$r['name'].'" class="btn btn-danger btn-sm delete-project" title="Delete"><i class="btn-icon-only icon-trash"> </i></button> <button data-id="'.$r['id'].'" data-name="'.$r['name'].'" data-featured="'.$r['featured'].'"  class="btn '.$fetProjectFeatCol.' btn-sm make-featured-project"  title="'.$fetProjectFeatTit.'"><i class="btn-icon-only '.$fetProjectFeat.'"> </i></button>';
                $result[] = array(utf8_encode($multiActionBox), utf8_encode($actionLink), $r['id'], utf8_encode($r['name']), utf8_encode(($r['is_completed'])), Sponsor::getName($this->dbObj, $r['sponsor']), utf8_encode($r['start_date']), utf8_encode($r['end_date']), StringManipulator::trimStringToFullWord(60, utf8_encode(stripcslashes(strip_tags($r['description'])))), utf8_encode($projectMediaLink), utf8_encode('<img src="../media/project-image/'.utf8_encode($r['image']).'" width="60" height="50" style="width:60px; height:50px;" alt="Pix">'), utf8_encode($r['date_registered']));//
            }
            $json = array("status" => 1,"draw" => intval($draw), "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => $result);
        } 
        else{ $json = array("status" => 2, "msg" => "Necessary parameters not set. Or empty result. ".mysqli_error($this->dbObj->connection), "draw" => intval($draw),  "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => false); }
        $this->dbObj->close();
        //header('Content-type: application/json');
        return json_encode($json);
    }
    
    /** Method that fetches projects from database
     * @param string $column Column name of the data to be fetched
     * @param string $condition Additional condition e.g sponsor_id > 9
     * @param string $sort column name to be used as sort parameter
     * @return JSON JSON encoded project details
     */
    public function fetch($column="*", $condition="", $sort="id"){
        $sql = "SELECT $column FROM project ORDER BY $sort";
        if(!empty($condition)){$sql = "SELECT $column FROM project WHERE $condition ORDER BY $sort";}
        $data = $this->dbObj->fetchAssoc($sql);
        $result =array(); 
        if(count($data)>0){
            foreach($data as $r){
                $result[] = array("id" => $r['id'], "name" =>  utf8_encode($r['name']), "image" =>  utf8_encode($r['image']), 'isCompleted' =>  utf8_encode($r['is_completed']), 'sponsor' => utf8_encode($r['sponsor']), 'startDate' =>  utf8_encode($r['start_date']), 'endDate' =>  utf8_encode($r['end_date']), 'description' => utf8_encode(StringManipulator::trimStringToFullWord(200, stripcslashes(strip_tags($r['description'])))), 'media' =>  utf8_encode($r['media']), 'status' =>  utf8_encode($r['status']), 'dateRegistered' => utf8_encode($r['date_registered']), 'sponsorName' => utf8_encode(Sponsor::getName($this->dbObj, $r['sponsor'])));
            }
            $json = array("status" => 1, "info" => $result);
        } 
        else{ $json = array("status" => 2, "msg" => "Necessary parameters not set. Or empty result. ".mysqli_error($this->dbObj->connection)); }
        $this->dbObj->close();
        header('Content-type: application/json');
        return json_encode($json);
    }

    /** Method that fetches projects from database
     * @param string $column Column name of the data to be fetched
     * @param string $condition Additional condition e.g sponsor_id > 9
     * @param string $sort column name to be used as sort parameter
     * @return Array Projects list
     */
    public function fetchRaw($column="*", $condition="", $sort="id"){
        $sql = "SELECT $column FROM project ORDER BY $sort";
        if(!empty($condition)){$sql = "SELECT $column FROM project WHERE $condition ORDER BY $sort";}
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
    
    /** Method that update single field detail of a project
     * @param string $field Column to be updated 
     * @param string $value New value of $field (Column to be updated)
     * @param int $id Id of the post to be updated
     * @return JSON JSON encoded success or failure message
     */
    public static function updateSingle($dbObj, $field, $value, $id){
        $sql = "UPDATE project SET $field = '{$value}' WHERE id = $id ";
        if(!empty($id)){
            $result = $dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done, project successfully updated!"); }
            else{ $json = array("status" => 2, "msg" => "Error updating project! ".  mysqli_error($dbObj->connection));   }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted."); }
        $dbObj->close();
        header('Content-type: application/json');
        return json_encode($json);
    }

    /** Method that update details of a project
     * @return JSON JSON encoded success or failure message
     */
    public function update() {
        $sql = "UPDATE project SET name = '{$this->name}', image = '{$this->image}', is_completed = '{$this->isCompleted}', sponsor = '{$this->sponsor}', start_date = '{$this->startDate}', end_date = '{$this->endDate}', description = '{$this->description}', media = '{$this->media}' WHERE id = $this->id ";
        if(!empty($this->id)){
            $result = $this->dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done, project successfully updated!"); }
            else{ $json = array("status" => 2, "msg" => "Error updating project! ".  mysqli_error($this->dbObj->connection));   }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted."); }
        $this->dbObj->close();
        header('Content-type: application/json');
        return json_encode($json); 
    }
    
    /** getName() fetches the name of a project using the project $id
     * @param object $dbObj Database connectivity and manipulation object
     * @param int $id Sponsor id of the sponsor whose name is to be fetched
     * @return string Name of the sponsor
     */
    public static function getName($dbObj, $id) {
        $thisProjectName = '';
        $thisProjectNames = $dbObj->fetchNum("SELECT name FROM project WHERE id = '{$id}' LIMIT 1");
        foreach ($thisProjectNames as $thisProjectNames) { $thisProjectName = $thisProjectNames[0]; }
        return $thisProjectName;
    }

    
    /** getSingle() fetches the title of an project using the project $id
     * @param object $dbObj Database connectivity and manipulation object
     * @param string $column Table's required column in the datatbase
     * @param int $id Project id of the project whose name is to be fetched
     * @return string Name of the project
     */
    public static function getSingle($dbObj, $column, $id) {
        $thisAsstReqVal = '';
        $thisAsstReqVals = $dbObj->fetchNum("SELECT $column FROM project WHERE id = '{$id}' ");
        foreach ($thisAsstReqVals as $thisAsstReqVals) { $thisAsstReqVal = $thisAsstReqVals[0]; }
        return $thisAsstReqVal;
    }
    
    /**
     * Method that returns count/total number of a particular project
     * @param Object $dbObj Datatbase connectivity object
     * @param Object $condition Additional optional condition
     * @return int Number of projects
     */
    public static function getRawCount($dbObj, $condition=" 1=1 "){
        $sql = "SELECT * FROM project WHERE $condition ";
        $count = "";
        $result = $dbObj->query($sql);
        $totalData = mysqli_num_rows($result);
        if($result !== false){ $count = $totalData; }
        return $count;
    }
    
    /** fetchBySponsor fetches projects by a sponsor
     * @param int $sponsorId Sponsor id
     * @param string $sponsorTable Sponsor table name
     * @param string $condition Additional condition
     */
    public function fetchBySponsor($sponsorId, $sponsorTable, $condition=''){
        $projectArr = array();
        if($sponsorId !=0){
            $projectArr = array_merge($projectArr, $this->dbObj->fetchAssoc("SELECT * FROM $this->tableName WHERE sponsor = ".$sponsorId." $condition "));
            $catDetails = $this->dbObj->fetchAssoc("SELECT * FROM $sponsorTable WHERE parent = $sponsorId $condition ");
            foreach ($catDetails as $catDetail){
                $projectArr = array_merge($projectArr, $this->fetchBySponsor($catDetail['id'], $sponsorTable));
            }
            return $projectArr;
        }
    }
    
    /**
     * Method that returns count/total number of a particular lesson
     * @param object $dbObj Database connectivity and manipulation object
     * @param int $id Project id of the lessons whose titles are to be fetched
     * @param string $dbPrefix Database table prefix
     * @return int Number of projects
     */
    public static function getSingleSponsorCount($dbObj, $id, $dbPrefix=''){
        $tableName = $dbPrefix.'project';
        $sql = "SELECT * FROM $tableName WHERE sponsor = $id ";
        $count = "";
        $result = $dbObj->query($sql);
        $totalData = mysqli_num_rows($result);
        if($result !== false){ $count = $totalData; }
        return $count;
    }
}
