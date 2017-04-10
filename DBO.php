<?php
class DBO
{
    protected $db;
    protected $field;
    protected $table;
    protected $rownums;

    public function __construct($table,$tmpconf=null)
    {
        Global $conf;
        if(!is_array($tmpconf)){
            $tmpconf = $conf;
        }
        // $this->db = \MysqliDb::getInstance();
        $this->db = new MysqliDb($tmpconf['host'],$tmpconf['user'],$tmpconf['password'],$tmpconf['dbname'],$tmpconf['port'],$tmpconf['charset']);
        $this->table = $table;
    }

    public function __call($method,$arg){
        $ret=$this;
        if(method_exists ($this->db, $method)){
            $ret=call_user_func_array(array($this->db,$method),$arg);
        }
        if(gettype($ret)==gettype($this->db) && $this->db = $ret){
            return $this;
        }else
        {
            return $ret;
        }
        //return gettype($ret)==gettype($this->db)? $this: $ret;
        //	return $ret==$this->db?$this:$ret;
    }
    public function __get($name){
        if(property_exists($this->db, $name)){
            return $this->db->$name;
        }else{
            return $this->$name;
        }
    }
    public function __set($name,$val){
        if(property_exists($this->db, $name)){
            $this->db->$name = $val;
        }else{
            $this->$name = $val;
        }
    }
    public function find(){
        return $this->db->getOne($this->table,$this->field);
    }
    public function findAll(){
        return $this->db->get($this->table,null,$this->field);
    }
    public function select(){
        return $this->db->get($this->table,null,$this->field);
    }
    public function count(){
        return $this->db->count;
    }
    public function table($table){
        $this->table=$table;
        return $this;
    }
    public function query($query, $bindParams = null)
    {
        return $this->db->rawQuery($query,$bindParams);
    }

    public function sql($query, $bindParams = null)
    {
        return $this->db->rawQuery($query,$bindParams);
    }

    public function queryOne($query, $bindParams = null){
        $res = $this->query($query, $bindParams);
        if (is_array($res) && isset($res[0])) {
            return $res[0];
        }
        return null;
    }

    public function limit($numRows = null){
        $this->rownums = $numRows;
        return $this;
    }
    public function get($columns = '*',$numRows=null)
    {
        if( !isset($numRows) && ( is_array($this->rownums) || $this->rownums!="" ) ){
            $numRows = $this->rownums ;
        }
        return $this->db->get($this->table,$numRows,$columns);
    }

    public function queryValue($query, $bindParams = null)
    {
        $res = $this->query($query, $bindParams);
        if (!$res) {
            return null;
        }

        $limit = preg_match('/limit\s+1;?$/i', $query);
        $key = key($res[0]);
        if (isset($res[0][$key]) && $limit == true) {
            return $res[0][$key];
        }

        $newRes = Array();
        for ($i = 0; $i < $this->count; $i++) {
            $newRes[] = $res[$i][$key];
        }
        return $newRes;
    }
    public function add($data){
        return $this->db->insert($this->table,$data);
    }
    public function update($data){
        return $this->db->update($this->table,$data);
    }
    public function save($data){
        return $this->update($data);
    }
    public function field($field){
        $this->field=$field;
        return $this;
    }
    public function delete($where){
        return $this->db->delete($this->table);
    }
    public function remove(){
        return $this->delete($this->table);
    }
    public function page($page,$pageLimit='10'){
        $this->db->pageLimit=$pageLimit;
        $info= $this->db->paginate($this->table,$page);
        return $info;
    }
}
