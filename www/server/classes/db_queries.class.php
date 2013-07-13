<?php

class queries
{
    var $db_info;
    var $db_flag;
    var $db_type;
    var $last_error;
    var $last_query;
    
    //insert query returns last inserted id
    function db_insert_query($sql) {
        $this->last_query = $sql;

        $r = mysql_query($sql);
        if (!$r) {
            $this->last_error = mysql_error();
            $msg = "mysql_error1";
            return $msg;
        }
        
        $id = mysql_insert_id();
        $msg = "ok";
        
        return ($id == 0) ? $msg : $id;
    }
    
    //select query: returns numrows
    function db_select_query($sql) {
        $this->last_query = $sql;
        
        $r = mysql_query($sql);
        
        if (!$r) {
            $this->last_error = mysql_error();
            return false;
        }
        
        $this->row_count = mysql_num_rows($r);
        return $this->row_count;
    }
    
    //delete query
    function db_delete_query($sql) {
        $this->last_query = $sql;
        $r = mysql_query($sql);
        
        if (!$r) {
            $this->last_error = mysql_error();
            $msg = "mysql_error1";
            return $msg;
        }
        else {
            
            $msg = "ok";
            return $msg;
        }
    }

    //select query: returns multiple
    function db_select_multiple($sql)
    {
        $this->last_query = $sql;
        
        $r = mysql_query($sql);
        
        if (!$r) {
            //$this->last_error = mysql_error();
            $msg = "mysql_error1";
            return $msg;
        }
        
        if (mysql_num_rows($r) > 0) {
            while ($arr = mysql_fetch_assoc($r))
                $rows[] = $arr;
            return $rows;
        }
    }
}

global $query;

$query = new queries();

?>