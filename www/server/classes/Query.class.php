<?php

class Query {

    function insert_query($sql) {
        $r = mysql_query($sql);
		
		
        if (!$r) {
            $this->last_error = mysql_error();
            
            return false;
        }

        $id = mysql_insert_id();
        $msg = "ok";

        return ($id == 0) ? $msg : $id;
    }

    function select_query($sql) {
        $r = mysql_query($sql);

        if (!$r) {
            $this->last_error = mysql_error();
            return false;
        }

        $this->row_count = mysql_num_rows($r);
        return $this->row_count;
    }

    function delete_query($sql) {
        $r = mysql_query($sql);

        if (!$r) {
            $this->last_error = mysql_error();
            
            return false;
        }
        else {

            $msg = "ok";
            return $msg;
        }
    }

    function select_multiple($sql) {
        $r = mysql_query($sql);

        if (!$r) {
            
            return false;
        }

        if (mysql_num_rows($r) > 0) {
            while ($arr = mysql_fetch_assoc($r))
                $rows[] = $arr;
            return $rows;
        }
    }
}