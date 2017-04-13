<?php
/**
 * Description of Quote
 *
 * @author Kaiste
 */
class Quote implements ContentManipulator{
    private $id;
    private $content;
    private $author;
    private $image;
    private  static $dbObj;
    private static $tableName;
    
    
    //Class constructor
    public function Quote($dbObj, $tableName = 'quote') {
        self::$dbObj = $dbObj;        self::$tableName = $tableName;
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
     * Method that adds a quote into the database
     * @return JSON JSON encoded string/result
     */
    public function add(){
        $sql = "INSERT INTO ".self::$tableName." (content, author, image) "
                ."VALUES ('{$this->content}','{$this->author}','{$this->image}')";
        if($this->notEmpty($this->content,$this->author,$this->image)){
            $result = self::$dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done, quote successfully added!"); }
            else{ $json = array("status" => 2, "msg" => "Error adding quote! ".  mysqli_error(self::$dbObj->connection)); }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted. All fields must be filled."); }
        
        self::$dbObj->close();//Close Database Connection
        header('Content-type: application/json');
        return json_encode($json);
    }

    /** 
     * Method for deleting a quote
     * @return JSON JSON encoded result
     */
    public function delete(){
        $sql = "DELETE FROM ".self::$tableName." WHERE id = $this->id ";
        if($this->notEmpty($this->id)){
            $result = self::$dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done, quote successfully deleted!"); }
            else{ $json = array("status" => 2, "msg" => "Error deleting quote! ".  mysqli_error(self::$dbObj->connection));  }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted."); }
        self::$dbObj->close();//Close Database Connection
        header('Content-type: application/json');
        return json_encode($json);
    }

    /** Method that fetches quote from database for JQuery Data Table
     * @param string $column Column content of the data to be fetched
     * @param string $condition Additional condition e.g quote_id > 9
     * @param string $sort column content to be used as sort parameter
     * @return JSON JSON encoded quote details
     */
    public function fetchForJQDT($draw, $totalData, $totalFiltered, $customSql="", $column="*", $condition="", $sort="id"){
        $sql = "SELECT $column FROM ".self::$tableName." ORDER BY $sort";
        if(!empty($condition)){$sql = "SELECT $column FROM ".self::$tableName." WHERE $condition ORDER BY $sort";}
        if($customSql !=""){ $sql = $customSql; }
        $data = self::$dbObj->fetchAssoc($sql);
        $result =array(); 
        if(count($data)>0){
            foreach($data as $r){ 
                $multiActionBox = '<input type="checkbox" class="multi-action-box" data-id="'.$r['id'].'" data-image="'.$r['image'].'" />';
                $result[] = array(utf8_encode($multiActionBox), $r['id'], utf8_encode($r['content']), utf8_encode($r['author']), utf8_encode('<img src="../media/quote/'.utf8_encode($r['image']).'" width="60" height="50" alt="Pix">'), utf8_encode(' <div style="white-space:nowrap"><button data-id="'.$r['id'].'"  data-author="'.$r['author'].'"  data-image="'.$r['image'].'" class="btn btn-info btn-sm edit-quote"  title="Edit"><i class="btn-icon-only icon-pencil"> </i> <span class="hidden">'.$r['content'].'</span> </button> <button data-image="'.$r['image'].'" data-id="'.$r['id'].'" class="btn btn-danger btn-sm delete-quote" title="Delete"><i class="btn-icon-only icon-trash"> </i><span class="hidden">'.$r['content'].'</span></button></div>'));
            }
            $json = array("status" => 1,"draw" => intval($draw), "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => $result);
        } 
        else{ $json = array("status" => 2, "msg" => "Necessary parameters not set. Or empty result. ".mysqli_error(self::$dbObj->connection), "draw" => intval($draw),  "recordsTotal"    => intval($totalData), "recordsFiltered" => intval($totalFiltered), "data" => false); }
        self::$dbObj->close();
        header('Content-type: application/json');
        return json_encode($json);
    }
    
    /** Method that fetches quote from database
     * @param string $column Column content of the data to be fetched
     * @param string $condition Additional condition e.g quote_id > 9
     * @param string $sort column content to be used as sort parameter
     * @return JSON JSON encoded quote details
     */
    public function fetch($column="*", $condition="", $sort="id"){
        $sql = "SELECT $column FROM ".self::$tableName." ORDER BY $sort";
        if(!empty($condition)){$sql = "SELECT $column FROM ".self::$tableName." WHERE $condition ORDER BY $sort";}
        $data = self::$dbObj->fetchAssoc($sql);
        $result =array(); 
        if(count($data)>0){
            foreach($data as $r){
                $result[] = array("id" => $r['id'], "content" =>  utf8_encode($r['content']), "author" =>  utf8_encode($r['author']), "image" =>  utf8_encode($r['image']));
            }
            $json = array("status" => 1, "info" => $result);
        } 
        else{ $json = array("status" => 2, "msg" => "Necessary parameters not set. Or empty result. ".mysqli_error(self::$dbObj->connection)); }
        self::$dbObj->close();
        header('Content-type: application/json');
        return json_encode($json);
    }

    /** Method that fetches quote from database
     * @param string $column Column name of the data to be fetched
     * @param string $condition Additional condition e.g category_id > 9
     * @param string $sort column name to be used as sort parameter
     * @return Array quote list
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
    
    /** Method that update single field detail of a quote
     * @param string $field Column to be updated 
     * @param string $value New value of $field (Column to be updated)
     * @param int $id Id of the post to be updated
     * @return JSON JSON encoded success or failure message
     */
    public static function updateSingle($dbObj, $field, $value, $id){
        $sql = "UPDATE ".self::$tableName." SET $field = '{$value}' WHERE id = $id ";
        if(!empty($id)){
            $result = $dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done, quote successfully update!"); }
            else{ $json = array("status" => 2, "msg" => "Error updating quote! ".  mysqli_error($dbObj->connection));   }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted."); }
        $dbObj->close();
        header('Content-type: application/json');
        return json_encode($json);
    }

    /** Method that update details of a quote
     * @return JSON JSON encoded success or failure message
     */
    public function update() {
        $sql = "UPDATE ".self::$tableName." SET content = '{$this->content}', author = '{$this->author}', image = '{$this->image}' WHERE id = $this->id ";
        if(!empty($this->id)){
            $result = self::$dbObj->query($sql);
            if($result !== false){ $json = array("status" => 1, "msg" => "Done, quote successfully update!"); }
            else{ $json = array("status" => 2, "msg" => "Error updating quote! ".  mysqli_error(self::$dbObj->connection));   }
        }
        else{ $json = array("status" => 3, "msg" => "Request method not accepted."); }
        self::$dbObj->close();
        header('Content-type: application/json');
        return json_encode($json); 
    }

    /** getName() fetches the content of a quote using the quote $id
     * @param object $dbObj Database connectivity and manipulation object
     * @param int $id Quote id of the quote whose content is to be fetched
     * @return string Name of the quote
     */
    public static function getContent($dbObj, $id) {
        $thisQuoteCont = '';
        $thisQuoteConts = $dbObj->fetchNum("SELECT content FROM ".self::$tableName." WHERE id = '{$id}' LIMIT 1");
        foreach ($thisQuoteConts as $thisQuoteConts) { $thisQuoteCont = $thisQuoteConts[0]; }
        return $thisQuoteCont;
    }
    
    /**
     * Method that returns count/total number of a particular course
     * @param Object $dbObj Datatbase connectivity object
     * @return int Number of quotes
     */
    public static function getRawCount($dbObj, $dbPrefix){
        $tableName = $dbPrefix.self::$tableName;
        $sql = "SELECT * FROM $tableName ";
        $count = "";
        $result = $dbObj->query($sql);
        $totalData = mysqli_num_rows($result);
        if($result !== false){ $count = $totalData; }
        return $count;
    }
    
    /** getSingle() fetches the title of an quote using the course $id
     * @param object $dbObj Database connectivity and manipulation object
     * @param string $column Table's required column in the datatbase
     * @param int $id quote id of the course whose name is to be fetched
     * @return string requested value
     */
    public static function getSingle($dbObj, $column, $id) {
        $thisReqContent = '';
        $thisReqContents = $dbObj->fetchNum("SELECT $column FROM ".self::$tableName." WHERE id = '{$id}' ");
        foreach ($thisReqContents as $thisReqContents) { $thisReqContent = $thisReqContents[0]; }
        return $thisReqContent;
    }
}
