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
        return self::getUserContext("SELECT * FROM User WHERE UserID = " . $userID . " LIMIT 1;");
    }
    
    public function getUserContextByApiKey($apiKey)
    {
        return self::getUserContext("SELECT * FROM User WHERE ApiKey = '" . $apiKey . "' LIMIT 1;");
    }
    
    private function getUserContext($queryText)
    {
        $selectUserResult = self::runQueryFetchAsObjects($queryText);
        if (sizeof($selectUserResult) == 0)
        {
            // No user found
            return null;
        }
        
        // Create the business logic object
        $user = User::createFromDbObject($selectUserResult[0]);
        
        // Populate categories and key indicators
        $selectCategoriesResult = self::runQueryFetchAsObjects("SELECT * FROM Category WHERE UserID = " . $user->UserID . ";");
        if (sizeof($selectCategoriesResult) > 0)
        {
            // Populate the categories and store the category IDs
            $categoryIDs = array();
            foreach ($selectCategoriesResult as $selectCategoryResult)
            {
                $category = Category::createFromDbObject($selectCategoryResult);
                array_push($categoryIDs, $category->CategoryID);
                $user->addCategory($category);
            }
            
            // Build a query for the key indicators given the category IDs
            $selectKeyIndicatorsQueryText = "SELECT * FROM KeyIndicators WHERE  CategoryID in (";
            $first = true;
            foreach ($categoryIDs as $categoryID)
            {
                if (!$first)
                {
                    $selectKeyIndicatorsQueryText .= ", ";
                }
                $selectKeyIndicatorsQueryText .= $categoryID;
            }
            $selectKeyIndicatorsQueryText .= ");";
            
            // Populate the key indicators
            $selectKeyIndicatorsResult = self::runQueryFetchAsObjects($selectKeyIndicatorsQueryText);
            foreach ($selectKeyIndicatorsResult as $selectKeyIndicatorResult)
            {
                $keyIndicator = KeyIndicator::createFromDbObject($selectKeyIndicatorResult);
                $user->addKeyIndicator($keyIndicator);
            }
        }
        
        // Return the complete user context
        return $user;
    }
    
    
    
    /*
     * GENERIC QUERY METHODS
     */
    public function runQueryJson($queryText, $prettyPrint = false)
    {
        $obj = $this->runQueryFetchAsObjects($queryText);
        if ($prettyPrint)
        {
            return json_encode($obj, JSON_PRETTY_PRINT);
        }
        else
        {
            return json_encode($obj);
        }
    }
    
    public function runQueryFetchAsRows($queryText)
    {
        // Run the query
        $result = self::runQuery($queryText);
        
        // Get a collection of the rows to return
        $rows = array();
        while($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        
        // Return the row collection
        $result->close();
        return $rows;
    }
    
    public function runQueryFetchAsObjects($queryText, $className = null)
    {
        // Run the query
        $result = self::runQuery($queryText);
        
        // Get a collection of the rows to return
        $objects = array();
        while($obj = self::fetch_next_object($result, $className)) {
            $objects[] = $obj;
        }
        
        // Return the object collection
        $result->close();
        return $objects;
    }
    
    private function runQuery($queryText)
    {
        // echo "Running " . $queryText . "<br />";
        
        // Create the MySQL connection
        $dbConn = new mysqli(self::HOST_NAME, self::USER_NAME, self::PASSWORD, self::DB_NAME, self::PORT) or die(mysql_error());
        if($dbConn->connect_errno > 0) {
            die('Unable to connect to database [' . $dbConn->connect_error . ']');
        }
        
        // Issue the query
        $result = $dbConn->query($queryText);
        if(!$result) {
            die("There was an error running the query [' . $dbConn->error . ']. The query text was: " . $queryText);
        }
        
        // Return the result
        return $result;
    }
    
    private function fetch_next_object($result, $className)
    {
        if ($className != null)
        {
            return $result->fetch_object($className);
        }
        return $result->fetch_object();
    }
}

?>