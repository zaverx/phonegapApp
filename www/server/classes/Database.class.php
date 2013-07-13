<?php

class Database extends Query {

    private $connection;

	public function __construct(){
        
	}
	
    public function connect() {
    	if(!$this->connection){
        	$this->connection = @mysql_connect(constant('DB_HOST'), constant('DB_USER'), constant('DB_PASSWORD'), true);
        	mysql_select_db(constant('DB_NAME'));
		}	
    }

    private static function formatFieldList($fields) {
        if (is_array($fields)) {
            $fields = implode(',', $fields);
        }

        return $fields;
    }
    
    private static function getWhereString($where) {
        if (is_array($where)) {
            $format = $where[0];
            $args = array_splice($where, 1);

            for ($i = 0; $i < count($args); $i++) {
                $args[$i] = mysql_real_escape_string($args[$i]);
            }
            
            $where = vsprintf($format, $args);
        }
        
        return $where;
    }
    
    public function mergeWhereArrays($where1, $where2) {
        $where1[0] .= ' ' . $where2[0];
        
        $where2 = array_splice($where2, 1);
        
        foreach ($where2 as $arg) {
            array_push($where1, $arg);
        }
        
        return $where1;
    }

    function insert($table, $fieldList, $vals = null) {
        $lock_table = "LOCK TABLES " . $table . " WRITE";
        $this->insert_query($lock_table);
        
        if ($vals == null) {
            $fields = array_keys($fieldList);
            $vals = array_values($fieldList);
        }
        else {
            $fields = $fieldList;
        }
        
        if (is_array($vals)) {
            $tmpVals = array();
            
            foreach ($vals as $val) {
                if ($val == 'CURRENT_TIMESTAMP') {
                    $tmpVals[] = $val;
                }
                else {
                    $tmpVals[] = "'" . mysql_real_escape_string($val) . "'";
                }
            }
            
            $vals = implode(',', $tmpVals);
        }
        
        $sql_query = " INSERT INTO " . $table . "(" . self::formatFieldList($fields) . ") VALUES(" . $vals . ")";
        $res = $this->insert_query($sql_query);
        
        $unlock_table = "UNLOCK TABLES";
        $this->insert_query($unlock_table);
	
     
        return $res;
    }

    function update($table, $fields, $where) {
        
        $fields = self::getWhereString($fields);
        $where = self::getWhereString($where);
        
        $sql_query = " UPDATE " . $table . " SET " . $fields . " WHERE " . $where . "";
        $res = $this->insert_query($sql_query);
        
        return $res;
    }

    function select($table, $fields, $where = '') {

        $where = self::getWhereString($where);
        
        if ($where != '') {
            $where = 'WHERE ' . $where;
        }
        
        $sql_query = "SELECT " . self::formatFieldList($fields) . " FROM " . $table . " " . $where . "";
        
        $res = $this->select_multiple($sql_query);
        
        return $res;
    }
}

?>