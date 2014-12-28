<?php

require 'DbModels.php';
require '../../Web/api/Models/User.php';

class DbWrapper
{
    const HOST_NAME = "mylifegrade-mylifegrade-1180782";
    const USER_NAME = "mylifegrade"; 
    const PASSWORD = "";
    const DB_NAME = "myLifeGrade";
    const PORT = 3306;
    
    public function __construct() 
    {
        
    }
    
    /*
     *  USER FUNCTIONS
     */
    public function getUserContextByID($userID)
    {
        $queryText = "SELECT * FROM Users WHERE UserID = " . $userID . " LIMIT 1;";
        $result = self::runQuery($queryText);
        
        if (sizeof($result) == 0)
        {
            return null;
        }
        else
        {
            return DbUser::parse($result[0]);
        }
    }
    
    public function getUserContextByApiKey($apiKey)
    {
        $queryText = "SELECT * FROM Users WHERE ApiKey = '" . $apiKey . "' LIMIT 1;";
        $result = self::runQuery($queryText);
        if (sizeof($result) == 0)
        {
            return null;
        }
        
        $dbUser = DbUser::parse($result[0]);
        return User::createFromDbObject($dbUser);
    }
    
    
    
    
    
    /*
     * GENERIC QUERY METHODS
     */
    public function runQueryJson($queryText, $prettyPrint = false)
    {
        $rows = $this->runQuery($queryText);
        if ($prettyPrint)
        {
            return json_encode($rows, JSON_PRETTY_PRINT);
        }
        else
        {
            return json_encode($rows);
        }
    }
    
    public function runQuery($queryText)
    {
        // Create the MySQL connection
        $dbConn = new mysqli(self::HOST_NAME, self::USER_NAME, self::PASSWORD, self::DB_NAME, self::PORT) or die(mysql_error());
        if($dbConn->connect_errno > 0) {
            die('Unable to connect to database [' . $dbConn->connect_error . ']');
        }
        
        // Issue the query
        $result = $dbConn->query($queryText);
        if(!$result) {
            die('There was an error running the query [' . $dbConn->error . ']');
        }
        
        // Get a collection of the rows to return
        $rows = array();
        while($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        
        // Return the row collection
        return $rows;
    }
}

?>