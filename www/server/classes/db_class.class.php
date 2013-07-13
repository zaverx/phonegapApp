<?php
/**
 * @author apostolis haitalis
 */

class db_class
{
    private static function formatFieldList($fields)
    {
        if (is_array($fields)) {
            $fields = implode(',', $fields);
        }
        
        return $fields;
    }
    
    private static function getWhereString($where)
    {
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
    
    public function mergeWhereArrays($where1, $where2)
    {
		
        $where1[0] .= ' ' . $where2[0];
        
        $where2 = array_splice($where2, 1);
        
        foreach ($where2 as $arg) {
            array_push($where1, $arg);
        }
        
        return $where1;
    }
    
    // INSERT DATA  
    function insert($table, $fieldList, $vals = null)
    {
        global $query;
        
        $lock_table = "LOCK TABLES " . $table . " WRITE";
        $query->db_insert_query($lock_table);
        
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
        $res = $query->db_insert_query($sql_query);
        
        $unlock_table = "UNLOCK TABLES";
        $query->db_insert_query($unlock_table);
        
        return $res;
    }
    
    // UPDATE DATA  
    function update($table, $fields, $where)
    {
        global $query;
        
        $fields = self::getWhereString($fields);
        $where = self::getWhereString($where);
        
        $sql_query = " UPDATE " . $table . " SET " . $fields . " WHERE " . $where . "";
        $res = $query->db_insert_query($sql_query);
        
        return $res;
    }
    
    // SELECT DATA  
    function select($table, $fields, $where = '', $extra='')
    {
        global $query;
        
        $where = self::getWhereString($where);
        
        if ($where != '') {
            $where = 'WHERE ' . $where;
        }
		else{
			$where = self::getWhereString($extra);
			
		}
        
        $sql_query = "SELECT " . self::formatFieldList($fields) . " FROM " . $table . " " . $where . "";
        
        $res = $query->db_select_multiple($sql_query);
        
        return $res;
    }
}

?>