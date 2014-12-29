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
    
    private function getUserContext($statement)
    {
        $selectUserResult = self::runQuery($statement, true);
        if (sizeof($selectUserResult) == 0)
        {
            // No user found
            return null;
        }
        
        // Create the business logic object
        $user = User::createFromDbObject($selectUserResult[0]);
        
        // Populate categories and key indicators
        $selectCategoriesResult = self::runQuery("SELECT * FROM Category WHERE UserID = " . $user->UserID . ";", true);
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
            $selectKeyIndicatorsResult = self::runQuery($selectKeyIndicatorsQueryText, true);
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
    public function runQueryJson($statement, $prettyPrint = false)
    {
        $obj = $this->runQuery($statement, true);
        if ($prettyPrint)
        {
            return json_encode($obj, JSON_PRETTY_PRINT);
        }
        else
        {
            return json_encode($obj);
        }
    }
    
    public function runQuery($statement, $fetchAsObjects = false, $className = null)
    {
        // echo "Running " . $statement . "<br />";
        
        // Create the MySQL connection
        $dbConn = null;
        try
        {
            $dbConn = new PDO("mysql:host=" . self::HOST_NAME . ";port=" . self::PORT . ";dbname=" . self::DB_NAME, self::USER_NAME, self::PASSWORD);
            if ($dbConn == null)
            {
                throw new Exception("The connection was initialized to null");
            }
        }
        catch (Exception $e)
        {
            die("Error while initializing the DB connection: " . $e->getMessage());
        }
        
        // Issue the query
        $result = null;
        try
        {
            $result = $dbConn->query($statement);
            if ($result == null)
            {
                throw new Exception("The query result was initialized to null");
            }
        }
        catch (Exception $e)
        {
            die("Error while executing the DB query: " . $e->getMessage());
        }
        
        // Determin the fetch method
        if ($fetchAsObjects)
        {
            if ($className != null)
            {
                $result->setFetchMode(PDO::FETCH_CLASS, $className);
            }
            else
            {
                $result->setFetchMode(PDO::FETCH_OBJ);
            }
        }
        
        // Build an array to return
        $returnArray = array();
        while ($returnValue = $result->fetch()) {
            array_push($returnArray, $returnValue);
        }
        
        return $returnArray;
    }
}

?>