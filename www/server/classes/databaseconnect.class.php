<?php

class DatabaseConnect
{
    private $db_connection;
    private $db_host;
    private $db_username;
    private $db_password;
    private $db_database_name;
    
    function __construct($params = null, $master_db_name)
    {
        if (!isset($params['db_host'], $params['db_username'], $params['db_password'], $params['db_database_name'])) {
            throw new DatabaseConnectException(DatabaseConnectException::$ERROR_CODE_INVALID_PARAMETERS);
        }
        else {
            $db_connection = mysql_connect($params['db_host'], $params['db_username'], $params['db_password'], true);
            mysql_select_db($master_db_name);
        }
    }
}

class DatabaseConnectException extends Exception
{
    public static $ERROR_CODE_INVALID_PARAMETERS = 0;
    
    function __construct($error_code)
    {
        switch ($error_code) {
            case self::$ERROR_CODE_INVALID_PARAMETERS:
                $msg = "Invalid Parameters Passed";
                break;
            default:
                $msg = "Unknown Error";
                break;
        }
        
        parent::__construct($msg, 0);
    }
}
?>