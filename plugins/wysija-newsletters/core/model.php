<?php
defined('WYSIJA') or die('Restricted access');
class WYSIJA_model extends WYSIJA_object{
    var $table_prefix='wysija';
    var $table_name='';
    var $pk='';
    var $values=array();
    var $conditions=array();
    var $orderby=array();
    var $groupby=false;
    var $noCheck =false;
    var $replaceQRY=false;
    var $limitON=false;
    var $dbg=false;
    var $colCheck=true;
    var $getFormat=ARRAY_A;
    var $getOne=false;
    var $fieldValid=true;
    var $specialUpdate=false;
    var $escapeFields=array();
    var $escapingOn=false;
    var $tableWP=false;
    var $columns=array();
    var $joins=array();
    var $ignore = false;
    var $comparisonKeys = array('equal', 'notequal', 'like', 'greater', 'less', 'greater_eq', 'less_eq');
    function WYSIJA_model($extensions=''){
        if(defined('WYSIJA_DBG') || defined('WYSIJA_DBG_ALL')) $this->dbg=true;
        global $wpdb;
        $this->wpprefix=$wpdb->prefix;
        if($extensions) $this->table_prefix=$extensions;


    }
    
    function reset(){
        $this->values=array();
        $this->conditions=array();
        $this->orderby=array();
        $this->groupby=false;
        $this->getFormat=ARRAY_A;
        $this->getOne=false;
        $this->limitON=false;
    }
    
    function get($columnsOrPKval=false,$conditions=array()){
        
        if(!$conditions){
            $conditions=array('equal'=>array($this->pk=>$columnsOrPKval));
            $columnsOrPKval=false;
            $this->noCheck=true;
        }
        
        if($conditions && !is_array($conditions)){
            $conditions=array('equal'=>array($this->pk=>$conditions));
        }
        if($this->setConditions($conditions)){
            if($this->getOne)   $results=$this->getRows($columnsOrPKval,0,1);
            else $results=$this->getRows($columnsOrPKval);

            if($this->getOne && count($results)==1){
                switch($this->getFormat){
                    case ARRAY_A:
                        foreach($results as $res)return $res;
                        break;
                    case OBJECT:
                        foreach($results as $res)return $res;
                        break;
                }
            }
            else return $results;
        }

        return false;
    }
    function getOne($columnsOrPKval=false,$conditions=array()){
        $this->getOne=true;
        $this->limitON=true;
        return $this->get($columnsOrPKval,$conditions);
    }
    
    function getRows($columns=false,$page=0,$limit=false){
        
        if($columns){
            if(is_array($columns)){
                $columns=implode(", ",$columns);
            }
        }else $columns="*";

        $query="SELECT ".$columns." FROM `".$this->getSelectTableName()."`";
        $query.=$this->makeJoins();
        $query.=$this->makeWhere();
        $query.=$this->makeGroupBY();
        $query.=$this->makeOrderBY();
        if($this->limitON) $query.=$this->setLimit($page,$limit);
        $results=$this->query("get_res",$query,$this->getFormat);

        return $results;
    }
    function escapeQuotesFromRes(&$results){
        if(!$this->escapingOn) return false;
        foreach($results as $k =>$r){
            if(in_array($this->getFormat,array(ARRAY_A,ARRAY_N))){
                foreach($r as $k1 =>$v1){
                    if(in_array($k1,$this->escapeFields)){
                        $results[$k][$k1]=  stripslashes($v1);
                    }
                }
            }
        }
    }
    function setLimit($page=0,$limit=false){
        
        if(!$this->getOne){
            if($page==0){
                if(isset($_REQUEST['pagi'])){
                    $page=(int)$_REQUEST['pagi'];
                    if($page!=0) $page=$page-1;
                }
            }else $page=$page-1;
        }
        if(!$limit){
            if(isset($this->limit_pp)) $limit=$this->limit_pp;
            else{
                $config=&WYSIJA::get("config","model");
                $limit=$config->getValue("limit_listing");
            }
        }
        $this->limit=(int)$limit;
        $this->page=$page;
        $this->limit_start=(int)($this->page*$this->limit);
        $this->limit_end=(int)($this->limit_start+$this->limit);
        return " LIMIT $this->limit_start , $this->limit";
    }
    
    function getResults($query,$type=ARRAY_A){
        return $this->query("get_res",$query, $type);
    }

    function getSelectTableName(){
        if($this->joins && isset($this->joins['tablestart'])){
            if(isset($this->joins['prefstart']))   return $this->wpprefix.$this->joins['prefstart'].'_'.$this->joins['tablestart'];
            else return $this->getPrefix().$this->joins['tablestart'];
        }else return $this->getPrefix().$this->table_name;
    }
    
    function count($query=false,$keygetcount=false){
        if(!$query){
            $groupBy=$this->makeGroupBY();
            $columnMore='';
            if($groupBy) $columnMore=','.$this->groupby;
            $query="SELECT COUNT(".$this->getPk().") as count ".$columnMore." FROM `".$this->getSelectTableName()."`";
            $query.=$this->makeJoins();
            $query.=$this->makeWhere();
            $query.=$groupBy;
        }
        if($this->dbg) $this->keepQry($query,"count");
        $results=$this->query("get_res",$query,$this->getFormat);
        if(!$results || count($results)>1) return $results;
        else {
            if($keygetcount) return $results[0][$keygetcount];
            else{
                foreach($results[0] as $key => $count) return $count;
            }
        }

        return $results;
    }
    
    function makeWhere(){
        $query="";
        if($this->conditions){
            
            $conditions=array();
            foreach($this->conditions as $type=>$values){
                if(!in_array($type, $this->comparisonKeys)){
                    $conditionsss=$this->conditions;
                    $this->conditions=array();
                    $this->conditions["equal"]=$conditionsss;
                    break;
                }
            }
            foreach($this->conditions as $type=>$values){
                if($type=='like' && count($values)>1){
                    if(is_array($values)){
                        $total=count($values);
                        $i=1;
                        $likeCond="";
                        foreach($values as $qfield => $qval){
                            $likeCond.=$qfield." LIKE '%".mysql_real_escape_string(addcslashes($qval, '%_' ))."%'";
                            if($i<$total){
                                $likeCond.=" OR ";
                            }
                            $i++;
                        }
                        $conditions[]="(".$likeCond.")";
                    }
                    continue;
                }
                foreach($values as $condK => $condVal){
                    
                    $this->_secureFieldVal($condK, $condVal);
                    switch($type){
                        case "equal":
                            if(is_array($condVal)){
                                $conditions[]=$condK.' IN ("'.implode('","', $condVal).'")';
                            }else{
                                if(is_null($condVal)) {
                                    $conditions[] = $condK.' IS NULL';
                                } else {
                                    if(is_numeric($condVal) === false) $condVal = '"'.$condVal.'"';
                                    $conditions[] = $condK.'='.$condVal;
                                }
                            }
                            break;
                        case "notequal":
                            if(is_array($condVal)){
                                $conditions[]=$condK.' NOT IN ("'.implode('","', $condVal).'")';
                            }else{
                                if(is_null($condVal)) {
                                    $conditions[] = $condK.' IS NOT NULL';
                                } else {
                                    if(is_numeric($condVal) === false) $condVal = '"'.$condVal.'"';
                                    $conditions[] = $condK.' != '.$condVal;
                                }
                            }
                            break;
                        case "like":
                                $conditions[]=$condK." LIKE '%".mysql_real_escape_string(addcslashes($condVal, '%_' ))."%'";
                            break;
                        case "greater":
                            if(is_numeric($condVal) === false) $condVal = '"'.$condVal.'"';
                            $conditions[]=$condK.' > '.$condVal;
                            break;
                        case "less":
                            if(is_numeric($condVal) === false) $condVal = '"'.$condVal.'"';
                            $conditions[]=$condK.' < '.$condVal;
                            break;
                        case "greater_eq":
                            if(is_numeric($condVal) === false) $condVal = '"'.$condVal.'"';
                            $conditions[]=$condK.' >= '.$condVal;
                            break;
                        case "less_eq":
                            if(is_numeric($condVal) === false) $condVal = '"'.$condVal.'"';
                            $conditions[]=$condK.' <= '.$condVal;
                            break;
                    }
                }
            }
            $query.=" WHERE ".implode(" AND ",$conditions);
        }
        return $query;
    }
    
    function makeOrderBY(){
        $query=" ORDER BY ";
        if($this->orderby){
            
            $query.=$this->orderby." ".$this->orderbyt;
        }else{
           
            if(is_array($this->pk)) return "";
            $query.=$this->pk." DESC";
        }
        return $query;
    }

    function makeJoins(){
        if($this->joins){
            $join=" as A";
            $arrayLetters=array("B","C","D","E");
            foreach($this->joins['tablejoins'] as $table => $fk){
                $letter=array_shift($arrayLetters);
                $join.=' JOIN `'.$this->getPrefix().$table.'` AS '.$letter." on $letter.$fk=A.".$this->joins['keystart']." ";
            }
            
            return $join;
        }else return '';
    }
    
    function makeGroupBY(){
        if($this->groupby){
            
            return " GROUP BY ".$this->groupby;
        }else return '';
    }
    function groupBy($name){
        if (!is_string($name) OR preg_match('|[^a-z0-9#_.-]|i',$name) !== 0 ){
            $this->groupby=false;
        }else $this->groupby=$name;
    }
    function orderBy($name,$type = 'ASC'){
        if(is_array($name) and count($name) > 0) {

            $this->orderby = '';
            $this->ordert = '';

            $count = count($name);

            for($i = 0; $i < $count; $i++) {
                $value = current($name);

                if(!is_string(key($name)) OR preg_match('|[^a-z0-9#_.-]|i',key($name)) !== 0 ){
                    $orderByCol="";
                }else $orderByCol=key($name);

                if(!is_string($value) OR preg_match('|[^a-z0-9#_.-]|i',$value) !== 0 ){
                    $orderByVal="";
                }else $orderByVal=$value;
                if($i === ($count - 1)) {
                    $this->orderby .= $orderByCol;
                    $this->ordert = $orderByVal;
                } else {
                    $this->orderby .=$orderByCol.' '.$orderByVal;
                    $this->orderby .= ', ';
                    next($name);
                }
            }
        } else if(!is_string($name) OR preg_match('|[^a-z0-9#_.-]|i',$name) !== 0 ){
            $this->orderby="";
        }else {
            $this->orderby=$name;
        }
        if(!in_array($type,array('DESC','ASC'))) $type = 'DESC';
        $this->orderbyt=$type;
    }

    
    function insert($values,$ignore=false){
        if($ignore)$this->ignore=true;
        if($this->setValues($values)){
            return $this->save();
        }else{
            $this->error(sprintf('missing values in model insert : %1$s.', get_class($this)));
        }
    }

    function replace($values=array()){
        $this->replaceQRY=true;
        $this->insert($values);
        $this->replaceQRY=false;
    }
    
    function update($values=array(),$conditions=array()){
        if($this->setValues($values)){
            
            if(!$conditions){
                if(!$this->conditions){
                   if(isset($values[$this->pk]) && $values[$this->pk]){
                        $this->setConditions(array($this->pk =>$values[$this->pk]),true);
                        unset($values[$this->pk]);

                        return $this->save(true);
                    }else{
                        $this->error(sprintf('missing pk value in model update : %1$s.', get_class($this)));
                    }
                }
            }else{
               if($this->setConditions($conditions,true)){
                    return $this->save(true);
                }else{
                    $this->error(sprintf('missing conditions in model update : %1$s.', get_class($this)));
                }
            }
        }else{
            $this->error(sprintf('missing values in model update : %1$s.', get_class($this)));
        }
    }
    
    function specialUpdate( $table, $data, $where, $format = null, $where_format = null ) {
            if ( ! is_array( $data ) || ! is_array( $where ) )
                    return false;
            $formats = $format = (array) $format;
            $bits = $wheres = array();
            $i=0;
            foreach ( $data as $field => $val) {
                $this->_secureFieldVal($field,$val);
                switch($format[$i]){
                    case "%d":
                        $bits[] = "`$field` = ".(int)$val;
                        break;
                    case "[increment]":
                        $bits[] = "`$field` = ".$field."+1";
                        break;
                    case "[decrement]":
                        $bits[] = "`$field` = ".$field."-1";
                        break;
                    default :
                        $bits[] = "`$field` = '".$val."'";
                }
                $i++;
            }
            $sql = "UPDATE `$table` SET " . implode( ', ', $bits ) . ' ' . $this->makeWhere();
            return $this->query( $sql );
    }

    function _secureFieldVal(&$field,&$val){
        if (!is_string($field) OR preg_match('|[^a-z0-9#_.-]|i',$field) !== 0 ){
            die('field "'.$field .'" not secured');
        }
        global $wpdb;
        if(is_string($val)) $val=mysql_real_escape_string($val,$wpdb->dbh);
        else{
            if(is_array($val)){
                foreach($val as $k=>$v){
                    if(is_string($v)) $val[$k]=mysql_real_escape_string($v,$wpdb->dbh);
                }
            }elseif(is_object($val)){
                foreach($val as $k=>$v){
                    if(is_string($v)) $val->$k=mysql_real_escape_string($v,$wpdb->dbh);
                }
            }
        }
    }
    
    function save($update=false){
        if($update)$updateStr="Update";
        else $updateStr="Insert";
        $beforeSave='before'.$updateStr;
        $afterSave='after'.$updateStr;

            if(!$update && isset($this->columns['created_at']))$this->values['created_at']=time();
            foreach($this->columns as $key => $params){
                
                if((isset($params['autoup']) && $update) || (!$update && $key!='sent_at')){
                    if(isset($params['type']) && !isset($this->values[$key])){
                        switch($params['type']){
                            case 'date':
                                $this->values[$key]=time();
                                break;
                            case 'ip':
                                $userHelper=&WYSIJA::get("user","helper");
                                
                                $this->values[$key]=$userHelper->getIP();
                                break;
                            case 'referer':
                                
                                $this->values[$key]=$_SERVER['HTTP_REFERER'];
                                break;
                        }
                    }
                }
            }
        if(method_exists($this,$beforeSave)){
            if(!$this->$beforeSave()){

                return false;
            }
        }
        
        $fieldsFormats=array();
        if(!is_array($this->pk) && isset($this->values[$this->pk])) unset($this->values[$this->pk]);
        foreach($this->values as $key =>$val){
            if(!isset($this->columns[$key]['html']))    $this->values[$key]=strip_tags($val);
            
            if(in_array($val, array("[increment]","[decrement]"))){
                $fieldsFormats[]=$val;
                $this->specialUpdate=true;
            }else{

                if(!isset($this->columns[$key]['type'])){
                    $this->columns[$key]['type']="default";
                }
                switch($this->columns[$key]['type']){
                    case "integer":
                    case "boolean":
                        $fieldsFormats[]="%d";
                        break;
                    default:
                        $fieldsFormats[]="%s";
                }
            }
        }
        if($this->fieldValid && !$this->validateFields()) {
            $this->error(__('Error Validating the fields',WYSIJA),true);
            $this->stay=true;
            return false;
        }
        global $wpdb;
        if($update){
            if( $this->specialUpdate || isset($this->conditions['equal']) || isset($this->conditions['notequal']) || isset($this->conditions['like'])){
                $resultSave=$this->specialUpdate($this->getPrefix().$this->table_name,$this->values,$this->conditions,$fieldsFormats);
                $this->logError();
            }else{
                $wpdb->update($this->getPrefix().$this->table_name,$this->values,$this->conditions,$fieldsFormats);
                $this->logError();
                $resultSave=$wpdb->result;
            }
        }else{
            if($this->replaceQRY){
                $resultSave=$wpdb->replace($this->getPrefix().$this->table_name,$this->values,$fieldsFormats);
                $this->logError();
            }else{
                if($this->ignore)   $resultSave=$wpdb->insert($this->getPrefix().$this->table_name,$this->values,$fieldsFormats);
                else $resultSave=$wpdb->insert($this->getPrefix().$this->table_name,$this->values,$fieldsFormats);
                $this->logError();

            }
        }
        if($this->dbg){
            $this->keepQry();
        }
        if(!$resultSave){
            $wpdb->show_errors();
            return false;
        }else{
            if($update){
                if(isset($this->conditions[$this->getPk()])){
                    $resultSave=$this->conditions[$this->getPk()];
                }else{
                    if(isset($this->conditions[$this->getPk(1)]))   $resultSave=$this->conditions[$this->getPk(1)];
                }
            }else{
                $resultSave=$wpdb->insert_id;
            }
        }
        $wpdb->flush();
        if(method_exists($this,$afterSave)){
            if(!$this->$afterSave($resultSave)){

                return false;
            }
        }
        return $resultSave;
    }

    function insertMany($values){
        $fields=array_keys($values[0]);
        $query="INSERT INTO `".$this->getPrefix().$this->table_name."` (`" . implode( '`,`', $fields ) . "`) VALUES ";
        $total=count($values);
        $i=1;
        foreach($values as &$vals){
            foreach($vals as &$v) $v=mysql_real_escape_string($v);
           $query.= "('" . implode( "','", $vals )."')";
           if($i<$total)    $query.=",";
           $i++;
        }
        $this->query($query.$myvalues);
    }
    
    function validateFields(){
        $error=false;
        foreach($this->values as $key =>$val){
            if(isset($this->columns[$key]['req']) && !$val && $this->columns[$key]['type']!="boolean"){
                $this->error(sprintf(__('Field "%1$s" is required in table "%2$s".',WYSIJA), $key,$this->table_name),true);
                $error=true;
            }
            
            switch($this->columns[$key]['type']){
                case "email":
                    $userHelper = &WYSIJA::get("user","helper");
                    if(!$userHelper->validEmail($val)){
                        $this->error(sprintf(__('Field "%1$s" needs to be a valid Email.',WYSIJA), $key),true);
                        $error=true;
                    }
                    break;
            }
        }
        if($error) return false;
        return true;
    }
    
    function delete($conditions){
        $query="DELETE FROM `".$this->getPrefix().$this->table_name."`";
        if($this->setConditions($conditions)){
            $whereQuery=$this->makeWhere();
            if(!$whereQuery){
                $this->error("Cannot delete element without conditions in model : ".get_class($this));
            }
        }else{
            $this->error("Cannot delete element without conditions in model : ".get_class($this));
            return false;
        }
        $result=$this->beforeDelete($conditions);
        if($result) $result=$this->query($query.$whereQuery);
        else return false;
        $this->afterDelete();
        return true;
    }
    function exists($conditions){
        $query="SELECT ".$this->getPk()." FROM `".$this->getSelectTableName()."`";
        $query.=$this->makeJoins();
        if($this->setConditions($conditions)){
            $whereQuery=$this->makeWhere();
            if(!$whereQuery){
                $this->error("Cannot test element without conditions in model : ".get_class($this));
            }
        }else{
            $this->error("Cannot test element without conditions in model : ".get_class($this));
            return false;
        }
        $res=$this->query("get_res",$query.$whereQuery, ARRAY_A);
        if($res) return $res;
        else return false;
    }
    function getPk($numb=0){
        $pk=$this->pk;
        if(is_array($pk)) $pk=$pk[$numb];
        return $pk;
    }
    
    function setValues($values){
        if($this->colCheck && !$this->checkAreColumns($values)) return false;
        $this->values=array();
        $this->values=$values;
        return true;
    }
    
    function setJoin($joins){
        $this->joins=$joins;
        return true;
    }
    
    function setConditions($conditions,$update=false){
        if($conditions && is_array($conditions)){
            $this->conditions=array();
            if($update){
                foreach($conditions as $key =>$cond){
                    if($this->colCheck && !$this->checkAreColumns($conditions)) return false;
                    if(is_array($cond)){
                        $this->specialUpdate=true;
                        $this->conditions=$conditions;
                        return true;
                    }else   $this->conditions[$key]=$cond;
                }
            } else {
                foreach($conditions as $key => $cond) {
                    if(!in_array($key, array('like','equal','notequal','greater','less','greater_eq','less_eq'))){
                        if($this->colCheck && !$this->checkAreColumns($conditions)) return false;
                        if(array_key_exists('equal', $this->conditions) === false) $this->conditions['equal'] = array();
                        $this->conditions['equal'][$key] = $cond;
                    }else{
                        if($this->colCheck && !$this->checkAreColumns($cond)) return false;
                        $this->conditions[$key]=$cond;
                    }
                }
            }
            return true;
        }else return false;
    }
    
    function checkAreColumns($columns){
        if($this->noCheck) return true;
        foreach($columns as $column => $values) {

            if(in_array($column, $this->comparisonKeys)) continue;
            $columnName = $column;
            if(!isset($this->columns[$columnName])){
                $this->error(sprintf('Column %1$s does not exist in model : %2$s', $columnName, get_class($this)));
                return false;
            }
        }
        return true;
    }
    function query($query,$arg2="",$arg3=ARRAY_A){
        global $wpdb;
       if(!$arg2) $query=str_replace(array('[wysija]','[wp]'),array($this->getPrefix(),$wpdb->prefix),$query);
       else $arg2=str_replace(array('[wysija]','[wp]'),array($this->getPrefix(),$wpdb->prefix),$arg2);
        switch($query){
            case "get_row":
                if($this->dbg) $this->keepQry($arg2,"query");
                $resultss=$wpdb->get_row($arg2,$arg3);
                $this->logError();
                return $resultss;
                break;
            case "get_res":
                if($this->dbg) $this->keepQry($arg2,"query");
                $results=$wpdb->get_results($arg2,$arg3);

                $this->logError();
                return $results;
                break;
            default:
                if($this->dbg) $this->keepQry($query,"query");
                $result=$wpdb->query($query);
                $this->logError();
                if(substr($query, 0, 6)=="INSERT") return $wpdb->insert_id;
                else return $result;
        }
    }
    function logError(){
        if(defined('WYSIJA_DBG') && WYSIJA_DBG>1){
            global $wysija_queries_errors, $wpdb;
            if(!$wysija_queries_errors) $wysija_queries_errors=array();
            $mysqlerror=mysql_error($wpdb->dbh);
           
            if($mysqlerror) {
                $wysija_queries_errors[]=$mysqlerror;
                WYSIJA::log('queries_errors',$mysqlerror,'query_errors');
            }
        }
    }
    function keepQry($qry=false,$from="wpdb"){
        global $wpdb,$wysija_queries;
        if($qry)    $wysija_queries[]="[FROM ".$from."]".$qry;
        else    $wysija_queries[$from][]="[FROM ".$from."]".$wpdb->last_query;
    }
    function getAffectedRows(){
        global $wpdb;
        return $wpdb->rows_affected;

    }
    function getErrorMsg(){
        global $wpdb;
        return $wpdb->show_errors();
    }
    
    function getPrefix(){
        if($this->tableWP) return $this->wpprefix.$this->table_prefix;
        else return $this->wpprefix.$this->table_prefix."_";
    }
    function beforeInsert(){
        return true;
    }
    function afterInsert($resultSaveID){
        return true;
    }
    function beforeDelete(){
        return true;
    }
    function afterDelete(){
        return true;
    }
    function beforeUpdate(){
        return true;
    }
    function afterUpdate($resultSaveID){
        return true;
    }
}