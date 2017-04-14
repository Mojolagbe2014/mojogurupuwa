<?php
/**
 * Description of Publication
 *
 * @author Jamiu Babatunde Mojolagbe
 */
class Publication implements ContentManipulator{
    private $id;
    private $name;
    private $category;
    private $datePublished;
    private $description;
    private $media;
    private $image;
    private $dateRegistered = " CURRENT_DATE ";
    private $status = 1;
    private $featured = 1;
    private $dbObj;
    private $tableName;
    
    //Class constructor
    public function Publication($dbObj, $tableName='publication') {
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
     * Method that adds a publication into the database
     * @return JSON JSON encoded string/result
     */
    function add(){
        $sql = "INSERT INTO publication (name, category, date_published, description, media, status, date_registered, image, featured) "
                ."VALUES ('{$this->name}','{$this->category}','{$this->datePublished}','{$this->description}','{$this->media}','{$this->status}',$this->dateRegistered,'{$this->image}','{$this->featured}')";
        if($this->notEmpty($this->name,$this->datePublished,$this->description,$this->image)){
            $result = $this->dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done, publication successfully added!"); }
            else{ $json = array("status" => 2, "msg" => "Error adding publication! ".  mysqli_error($this->dbObj->connection)); }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted. All fields must be filled."); }
        
        $this->dbObj->close();//Close Database Connection
        header('Content-type: application/json');
        return json_encode($json);
    }

    /** 
     * Method for deleting a publication
     * @return JSON JSON encoded result
     */
    public function delete(){
        $sql = "DELETE FROM publication WHERE id = $this->id ";
        if($this->notEmpty($this->id)){
            $result = $this->dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done, publication successfully deleted!"); }
            else{ $json = array("status" => 2, "msg" => "Error deleting publication! ".  mysqli_error($this->dbObj->connection));  }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted."); }
        $this->dbObj->close();//Close Database Connection
        header('Content-type: application/json');
        return json_encode($json);
    }

    /** Method that fetches publications from database for JQuery Data Table
     * @param string $column Column name of the data to be fetched
     * @param string $condition Additional condition e.g category_id > 9
     * @param string $sort column name to be used as sort parameter
     * @return JSON JSON encoded publication details
     */
    public function fetchForJQDT($draw, $totalData, $totalFiltered, $customSql="", $column="*", $condition="", $sort="id"){
        $sql = "SELECT $column FROM publication ORDER BY $sort";
        if(!empty($condition)){$sql = "SELECT $column FROM publication WHERE $condition ORDER BY $sort";}
        if($customSql !=""){ $sql = $customSql; }
        $data = $this->dbObj->fetchAssoc($sql);
        $result =array();  $fetPublicationStat = 'icon-check-empty'; $fetPublicationRolCol = 'btn-warning'; $fetPublicationRolTit = "Activate Publication";
        if(count($data)>0){
            foreach($data as $r){ 
                $publicationMediaLink = '';
                $fetPublicationStat = 'icon-check-empty'; $fetPublicationRolCol = 'btn-warning'; $fetPublicationRolTit = "Activate Publication";
                $fetPublicationFeat = 'icon-eye-close'; $fetPublicationFeatCol = 'btn-warning'; $fetPublicationFeatTit = "Make Featured Publication";
                if($r['status'] == 1){  $fetPublicationStat = 'icon-check'; $fetPublicationRolCol = 'btn-success'; $fetPublicationRolTit = "De-activate Publication";}
                if($r['featured'] == 1){  $fetPublicationFeat = 'icon-eye-open'; $fetPublicationFeatCol = 'btn-success'; $fetPublicationFeatTit = "Remove Featured Publication";}
                if($r['media'] !=''){ $publicationMediaLink = '<a href="'.SITE_URL.'media/publication/'.$r['media'].'">View Media</a>'; }
                $multiActionBox = '<input type="checkbox" class="multi-action-box" data-id="'.$r['id'].'" data-name="'.$r['name'].'" data-status="'.$r['status'].'" data-image="'.$r['image'].'" data-media="'.$r['media'].'" data-featured="'.$r['featured'].'" />';
                $actionLink = ' <button data-id="'.$r['id'].'" data-name="'.$r['name'].'" data-category="'.$r['category'].'" data-date-published="'.$r['date_published'].'" data-description ="" data-media="'.$r['media'].'"  data-image="'.$r['image'].'" data-date-registered="'.$r['date_registered'].'" class="btn btn-info btn-sm edit-publication"  title="Edit"><i class="btn-icon-only icon-pencil"> </i> <span class="hidden" id="JQDTdescriptionholder">'.$r['description'].'</span> </button> <button data-id="'.$r['id'].'" data-name="'.$r['name'].'" data-status="'.$r['status'].'"  class="btn '.$fetPublicationRolCol.' btn-sm activate-publication"  title="'.$fetPublicationRolTit.'"><i class="btn-icon-only '.$fetPublicationStat.'"> </i></button> <button data-id="'.$r['id'].'" data-media="'.$r['media'].'"  data-image="'.$r['image'].'" data-name="'.$r['name'].'" class="btn btn-danger btn-sm delete-publication" title="Delete"><i class="btn-icon-only icon-trash"> </i></button> <button data-id="'.$r['id'].'" data-name="'.$r['name'].'" data-featured="'.$r['featured'].'"  class="btn '.$fetPublicationFeatCol.' btn-sm make-featured-publication"  title="'.$fetPublicationFeatTit.'"><i class="btn-icon-only '.$fetPublicationFeat.'"> </i></button>';
                $result[] = array(utf8_encode($multiActionBox), utf8_encode($actionLink), $r['id'], utf8_encode($r['name']), PublicationCategory::getName($this->dbObj, $r['category']), utf8_encode($r['date_published']), StringManipulator::trimStringToFullWord(60, utf8_encode(stripcslashes(strip_tags($r['description'])))), utf8_encode($publicationMediaLink), utf8_encode('<img src="../media/publication-image/'.utf8_encode($r['image']).'" width="60" height="50" style="width:60px; height:50px;" alt="Pix">'), utf8_encode($r['date_registered']));//
            }
            $json = array("status" => 1,"draw" => intval($draw), "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => $result);
        } 
        else{ $json = array("status" => 2, "msg" => "Necessary parameters not set. Or empty result. ".mysqli_error($this->dbObj->connection), "draw" => intval($draw),  "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => false); }
        $this->dbObj->close();
        //header('Content-type: application/json');
        return json_encode($json);
    }
    
    /** Method that fetches publications from database
     * @param string $column Column name of the data to be fetched
     * @param string $condition Additional condition e.g category_id > 9
     * @param string $sort column name to be used as sort parameter
     * @return JSON JSON encoded publication details
     */
    public function fetch($column="*", $condition="", $sort="id"){
        $sql = "SELECT $column FROM publication ORDER BY $sort";
        if(!empty($condition)){$sql = "SELECT $column FROM publication WHERE $condition ORDER BY $sort";}
        $data = $this->dbObj->fetchAssoc($sql);
        $result =array(); 
        if(count($data)>0){
            foreach($data as $r){
                $result[] = array("id" => $r['id'], "name" =>  utf8_encode($r['name']), "image" =>  utf8_encode($r['image']), 'category' => utf8_encode($r['category']), 'datePublished' =>  utf8_encode($r['date_published']), 'description' => utf8_encode(StringManipulator::trimStringToFullWord(200, stripcslashes(strip_tags($r['description'])))), 'media' =>  utf8_encode($r['media']), 'status' =>  utf8_encode($r['status']), 'dateRegistered' => utf8_encode($r['date_registered']), 'categoryName' => utf8_encode(PublicationCategory::getName($this->dbObj, $r['category'])));
            }
            $json = array("status" => 1, "info" => $result);
        } 
        else{ $json = array("status" => 2, "msg" => "Necessary parameters not set. Or empty result. ".mysqli_error($this->dbObj->connection)); }
        $this->dbObj->close();
        header('Content-type: application/json');
        return json_encode($json);
    }

    /** Method that fetches publications from database
     * @param string $column Column name of the data to be fetched
     * @param string $condition Additional condition e.g category_id > 9
     * @param string $sort column name to be used as sort parameter
     * @return Array Publications list
     */
    public function fetchRaw($column="*", $condition="", $sort="id"){
        $sql = "SELECT $column FROM publication ORDER BY $sort";
        if(!empty($condition)){$sql = "SELECT $column FROM publication WHERE $condition ORDER BY $sort";}
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
    
    /** Method that update single field detail of a publication
     * @param string $field Column to be updated 
     * @param string $value New value of $field (Column to be updated)
     * @param int $id Id of the post to be updated
     * @return JSON JSON encoded success or failure message
     */
    public static function updateSingle($dbObj, $field, $value, $id){
        $sql = "UPDATE publication SET $field = '{$value}' WHERE id = $id ";
        if(!empty($id)){
            $result = $dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done, publication successfully updated!"); }
            else{ $json = array("status" => 2, "msg" => "Error updating publication! ".  mysqli_error($dbObj->connection));   }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted."); }
        $dbObj->close();
        header('Content-type: application/json');
        return json_encode($json);
    }

    /** Method that update details of a publication
     * @return JSON JSON encoded success or failure message
     */
    public function update() {
        $sql = "UPDATE publication SET name = '{$this->name}', image = '{$this->image}', category = '{$this->category}', date_published = '{$this->datePublished}', description = '{$this->description}', media = '{$this->media}' WHERE id = $this->id ";
        if(!empty($this->id)){
            $result = $this->dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done, publication successfully updated!"); }
            else{ $json = array("status" => 2, "msg" => "Error updating publication! ".  mysqli_error($this->dbObj->connection));   }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted."); }
        $this->dbObj->close();
        header('Content-type: application/json');
        return json_encode($json); 
    }
    
    /** getName() fetches the name of a publication using the publication $id
     * @param object $dbObj Database connectivity and manipulation object
     * @param int $id Category id of the category whose name is to be fetched
     * @return string Name of the category
     */
    public static function getName($dbObj, $id) {
        $thisPublicationName = '';
        $thisPublicationNames = $dbObj->fetchNum("SELECT name FROM publication WHERE id = '{$id}' LIMIT 1");
        foreach ($thisPublicationNames as $thisPublicationNames) { $thisPublicationName = $thisPublicationNames[0]; }
        return $thisPublicationName;
    }

    
    /** getSingle() fetches the title of an publication using the publication $id
     * @param object $dbObj Database connectivity and manipulation object
     * @param string $column Table required column in the database
     * @param int $id Publication id of the publication whose name is to be fetched
     * @return string Name of the publication
     */
    public static function getSingle($dbObj, $column, $id) {
        $thisAsstReqVal = '';
        $thisAsstReqVals = $dbObj->fetchNum("SELECT $column FROM publication WHERE id = '{$id}' ");
        foreach ($thisAsstReqVals as $thisAsstReqVals) { $thisAsstReqVal = $thisAsstReqVals[0]; }
        return $thisAsstReqVal;
    }
    
    /**
     * Method that returns count/total number of a particular publication
     * @param Object $dbObj Database connectivity object
     * @param Object $condition Additional optional condition
     * @return int Number of publications
     */
    public static function getRawCount($dbObj, $condition=" 1=1 "){
        $sql = "SELECT * FROM publication WHERE $condition ";
        $count = "";
        $result = $dbObj->query($sql);
        $totalData = mysqli_num_rows($result);
        if($result !== false){ $count = $totalData; }
        return $count;
    }
    
    /** fetchByCategory fetches publications in a category and sub-categories
     * @param int $categoryId Category id
     * @param string $categoryTable Category table name
     * @param string $condition Additional condition
     */
    public function fetchByCategory($categoryId, $categoryTable, $condition=''){
        $publicationArr = array();
        if($categoryId !=0){
            $publicationArr = array_merge($publicationArr, $this->dbObj->fetchAssoc("SELECT * FROM $this->tableName WHERE category = ".$categoryId." $condition "));
            $catDetails = $this->dbObj->fetchAssoc("SELECT * FROM $categoryTable WHERE parent = $categoryId $condition ");
            foreach ($catDetails as $catDetail){
                $publicationArr = array_merge($publicationArr, $this->fetchByCategory($catDetail['id'], $categoryTable));
            }
            return $publicationArr;
        }
    }
    
    /**
     * Method that returns count/total number of a particular lesson
     * @param object $dbObj Database connectivity and manipulation object
     * @param int $id Publication id of the lessons whose titles are to be fetched
     * @param string $dbPrefix Database table prefix
     * @return int Number of publications
     */
    public static function getSingleCategoryCount($dbObj, $id, $dbPrefix=''){
        $tableName = $dbPrefix.'publication';
        $sql = "SELECT * FROM $tableName WHERE category = $id ";
        $count = "";
        $result = $dbObj->query($sql);
        $totalData = mysqli_num_rows($result);
        if($result !== false){ $count = $totalData; }
        return $count;
    }
}