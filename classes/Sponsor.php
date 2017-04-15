<?php
/**
 * Description of Sponsor
 *
 * @author Jamiu Babatunde Mojolagbe <mojolagbe@gmail.com>
 */
class Sponsor implements ContentManipulator{
    private $id;
    private $name;
    private $logo;
    private $website;
    private $product;
    private $description;
    private $image;
    private $status = 1;
    private $dateAdded = ' CURRENT_DATE ';
    private $dbObj;
    
    
    //Class constructor
    public function Sponsor($dbObj) {
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
     * Method that adds a  sponsor into the database
     * @return JSON JSON encoded string/result
     */
    function add(){
        $sql = "INSERT INTO sponsor (name, logo, website, status, date_added, product, description, image) "
                ."VALUES ('{$this->name}','{$this->logo}','{$this->website}','{$this->status}',$this->dateAdded,'{$this->product}','{$this->description}','{$this->image}')";
        if($this->notEmpty($this->name,$this->logo,$this->website)){
            $result = $this->dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done, sponsor successfully added!"); }
            else{ $json = array("status" => 2, "msg" => "Error adding  sponsor! ".  mysqli_error($this->dbObj->connection)); }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted. All fields must be filled."); }
        
        $this->dbObj->close();//Close Database Connection
        header('Content-type: application/json');
        return json_encode($json);
    }

    /** 
     * Method for deleting a  sponsor
     * @return JSON JSON encoded result
     */
    public function delete(){
        $sql = "DELETE FROM sponsor WHERE id = $this->id ";
        if($this->notEmpty($this->id)){
            $result = $this->dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done,  sponsor successfully deleted!"); }
            else{ $json = array("status" => 2, "msg" => "Error deleting  sponsor! ".  mysqli_error($this->dbObj->connection));  }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted."); }
        $this->dbObj->close();//Close Database Connection
        header('Content-type: application/json');
        return json_encode($json);
    }

    /** Method that fetches sponsors from database for JQuery Data Table
     * @param string $column Column name of the data to be fetched
     * @param string $condition Additional condition e.g  sponsor_id > 9
     * @param string $sort column name to be used as sort parameter
     * @return JSON JSON encoded course sponsor details
     */
    public function fetchForJQDT($draw, $totalData, $totalFiltered, $customSql="", $column="*", $condition="", $sort="id"){
        $sql = "SELECT $column FROM sponsor ORDER BY $sort";
        if(!empty($condition)){$sql = "SELECT $column FROM sponsor WHERE $condition ORDER BY $sort";}
        if($customSql !=""){ $sql = $customSql; }
        $data = $this->dbObj->fetchAssoc($sql);
        $result =array(); $fetSponsorStat = 'icon-check-empty'; $fetSponsorRolCol = 'btn-warning'; $fetSponsorRolTit = "Activate Sponsor";
        if(count($data)>0){
            foreach($data as $r){ 
                $fetSponsorStat = 'icon-check-empty'; $fetSponsorRolCol = 'btn-warning'; $fetSponsorRolTit = "Activate Sponsor";
                $multiActionBox = '<input type="checkbox" class="multi-action-box" data-id="'.$r['id'].'" data-image="'.$r['image'].'" data-logo="'.$r['logo'].'" data-name="'.$r['name'].'" data-status="'.$r['status'].'"/>';
                if($r['status'] == 1){  $fetSponsorStat = 'icon-check'; $fetSponsorRolCol = 'btn-success'; $fetSponsorRolTit = "De-activate Sponsor";}
                $result[] = array(utf8_encode($multiActionBox), $r['id'], utf8_encode(' <button data-id="'.$r['id'].'" data-logo="'.$r['logo'].'" data-image="'.$r['image'].'" data-name="'.$r['name'].'"  data-website="'.$r['website'].'" class="btn btn-danger btn-sm delete-sponsor" title="Delete"><i class="btn-icon-only icon-trash"> </i></button> <button  data-id="'.$r['id'].'"  data-image="'.$r['image'].'" data-logo="'.$r['logo'].'" data-name="'.$r['name'].'" data-product="'.$r['product'].'"  data-website="'.$r['website'].'" class="btn btn-info btn-sm edit-sponsor"  title="Edit"><i class="btn-icon-only icon-pencil"> </i> <span class="hidden" id="JQDTdescriptionholder">'.utf8_encode($r['description']).'</span> </button> <button data-id="'.$r['id'].'" data-name="'.$r['name'].'" data-status="'.$r['status'].'"  class="btn '.$fetSponsorRolCol.' btn-sm activate-sponsor"  title="'.$fetSponsorRolTit.'"><i class="btn-icon-only '.$fetSponsorStat.'"> </i></button>'), utf8_encode($r['name']), utf8_encode('<img src="../media/sponsor/'.utf8_encode($r['logo']).'" width="40" height="30" alt="Pix">'), utf8_encode('<a href="'.$r['website'].'" target="_blank">Visit Website</a>'), utf8_encode($r['date_added']), utf8_encode($r['product']), StringManipulator::trimStringToFullWord(60, strip_tags(utf8_encode($r['description']))), utf8_encode('<img src="../media/sponsor-image/'.utf8_encode($r['image']).'" width="40" height="30" alt="Pix">'));//
            }
            $json = array("status" => 1,"draw" => intval($draw), "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => $result);
        } 
        else{ $json = array("status" => 2, "msg" => "Necessary parameters not set. Or empty result. ".mysqli_error($this->dbObj->connection), "draw" => intval($draw),  "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => false); }
        $this->dbObj->close();
        //header('Content-type: application/json');
        return json_encode($json);
    }
    
    /** Method that fetches sponsors from database
     * @param string $column Column name of the data to be fetched
     * @param string $condition Additional condition e.g  sponsor_id > 9
     * @param string $sort column name to be used as sort parameter
     * @return JSON JSON encoded course sponsor details
     */
    public function fetch($column="*", $condition="", $sort="id"){
        $sql = "SELECT $column FROM sponsor ORDER BY $sort";
        if(!empty($condition)){$sql = "SELECT $column FROM sponsor WHERE $condition ORDER BY $sort";}
        $data = $this->dbObj->fetchAssoc($sql);
        $result =array(); 
        if(count($data)>0){
            foreach($data as $r){
                $result[] = array("id" => $r['id'], "name" =>  utf8_encode($r['name']), "logo" =>  utf8_encode($r['logo']), "website" =>  utf8_encode($r['website']), "status" =>  utf8_encode($r['status']), "dateAdded" =>  utf8_encode($r['date_added']), "product" =>  utf8_encode($r['product']), "description" =>  utf8_encode($r['description']), "image" =>  utf8_encode($r['image']));
            }
            $json = array("status" => 1, "info" => $result);
        } 
        else{ $json = array("status" => 2, "msg" => "Empty result. ".mysqli_error($this->dbObj->connection)); }
        $this->dbObj->close();
        header('Content-type: application/json');
        return json_encode($json);
    }

    /** Method that fetches sponsors from database
     * @param string $column Column name of the data to be fetched
     * @param string $condition Additional condition e.g category_id > 9
     * @param string $sort column name to be used as sort parameter
     * @return Array sponsors list
     */
    public function fetchRaw($column="*", $condition="", $sort="id"){
        $sql = "SELECT $column FROM sponsor ORDER BY $sort";
        if(!empty($condition)){$sql = "SELECT $column FROM sponsor WHERE $condition ORDER BY $sort";}
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
    
    /** Method that update single field detail of a  sponsor
     * @param string $field Column to be updated 
     * @param string $value New value of $field (Column to be updated)
     * @param int $id Id of the post to be updated
     * @return JSON JSON encoded success or failure message
     */
    public static function updateSingle($dbObj, $field, $value, $id){
        $sql = "UPDATE sponsor SET $field = '{$value}' WHERE id = $id ";
        if(!empty($id)){
            $result = $dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done,  sponsor successfully update!"); }
            else{ $json = array("status" => 2, "msg" => "Error updating  sponsor! ".  mysqli_error($dbObj->connection));   }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted."); }
        $dbObj->close();
        header('Content-type: application/json');
        return json_encode($json);
    }

    /** Method that update details of a  sponsor
     * @return JSON JSON encoded success or failure message
     */
    public function update() {
        $sql = "UPDATE sponsor SET name = '{$this->name}', logo = '{$this->logo}', website = '{$this->website}', product = '{$this->product}', description = '{$this->description}', image = '{$this->image}' WHERE id = $this->id ";
        if($this->notEmpty($this->id, $this->logo, $this->name)){
            $result = $this->dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done,  sponsor successfully update!"); }
            else{ $json = array("status" => 2, "msg" => "Error updating  sponsor! ".  mysqli_error($this->dbObj->connection));   }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted."); }
        $this->dbObj->close();
        header('Content-type: application/json');
        return json_encode($json); 
    }

    /** getName() fetches the name of a sponsor using the sponsor $id
     * @param object $dbObj Database connectivity and manipulation object
     * @param int $id Category id of the  sponsor whose name is to be fetched
     * @return string Name of the  sponsor
     */
    public static function getName($dbObj, $id) {
        $thisSponsorName = '';
        $thisSponsorNames = $dbObj->fetchNum("SELECT name FROM sponsor WHERE id = '{$id}' LIMIT 1");
        foreach ($thisSponsorNames as $thisSponsorNames) { $thisSponsorName = $thisSponsorNames[0]; }
        return $thisSponsorName;
    }
}
