<?php

class DB {
    protected static $instance = null; 

    public static function getConnection() {
        if (!self::$instance) { 
            self::$instance = new mysqli($GLOBALS['dbHost'], $GLOBALS['dbUser'], $GLOBALS['dbPass'], $GLOBALS['dbName'], $GLOBALS['dbPort']);
          
        }
        return self::$instance;

    }
    //stored procedures help keep queries and PHP code separate
    public static function callStoredProcedure($spName, $params) {
    $paramTypes = ''; 
    $paramValues = [];
    if(is_array($params)) {
        foreach ($params as $param) {
            $paramTypes = $paramTypes . $param['type'];
            $paramValues[] = $param['value'];
        }
    } else {
        $params = [];
    }
    
      $connection = self::getConnection();
      
    if ($connection->connect_errno > 0) {
        Logger::log("Failed to connect with reason: " .$connection->errno." while attempting to ".$spName." ".$connection->connect_error);
        throw new \Exception("Failed to connect with reason: " .$connection->errno." while attempting to ".$spName." ".$connection->connect_error);
    } else {
        $stmt = $connection->prepare($spName); //prepare() returns false if the SP has SELECT * in the statement
        $stmt->bind_param($paramTypes, ...$paramValues);
        $stmt->execute();

        if ($stmt->errno) {
            $errorMessage = $stmt->error;
            Logger::log("Failed to execute stored procedure: " . $spName . " with reason :" . $stmt->errno . " " . $errorMessage);
            $stmt->close();    
            throw new \Exception("Failed to execute stored procedure: " . $spName . " with reason :" . $stmt->errno . " " . $errorMessage);
                       
        }

        $stmtResult = $stmt->get_result();
        $stmt->free_result();
        $stmt->close();

        return $stmtResult;
    }
}

//very basic wrapper, just to have less CTRL+C, CTRL+V in the code
public static function query(string $string) {
    
       $connection = self::getConnection();
    
    if ($connection->connect_errno > 0) {
        Logger::log("Failed to connect with reason: " .$connection->errno." while attempting to execute ".$string." ".$connection->connect_error);
        throw new \Exception("Failed to connect with reason: " .$connection->errno." while attempting to execute ".$string." ".$connection->connect_error);
    } else {
        $result = null;
        $stmt = $connection->query($string);

        if ($connection->errno) {
            $errorMessage = $connection->error;
            Logger::log("Failed to execute: " . $string . " with reason :" . $connection->errno . " " . $errorMessage);  
            throw new \Exception("Failed to execute: " . $string . " with reason :" . $connection->errno . " " . $errorMessage);
        } else {
            $result = (preg_match('/^INSERT|^UPDATE/', $string) ? self::affectedRows($connection) : $stmt);
        } 
        return $result;
    
    }
}

	public static function affectedRows($conn) {
		return $conn->affected_rows;
	}
}?>
