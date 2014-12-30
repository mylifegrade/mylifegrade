<?php

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
        return self::getUserContext("SELECT * FROM User WHERE UserID = :userID LIMIT 1;", array(":userID" => $userID));
    }
    
    public function getUserContextByApiKey($apiKey)
    {
        return self::getUserContext("SELECT * FROM User WHERE ApiKey = :apiKey LIMIT 1;", array(":apiKey" => $apiKey));
    }
    
    private function getUserContext($queryText, $parameters)
    {
        $selectUserResult = self::runQuery($queryText, $parameters, true);
        if (sizeof($selectUserResult) == 0)
        {
            // No user found
            return null;
        }
        
        // Create the business logic object
        $user = User::createFromDbObject($selectUserResult[0]);
        
        // Populate categories and key indicators
        $selectCategoriesResult = self::runQuery("SELECT * FROM Category WHERE UserID = :userID;", array(":userID" => $user->UserID), true);
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
     * CATEGORY FUNCTIONS
     */
    public function addCategory($user, $category)
    {
        // Insert the category
        $queryText = "insert into Category (UserID, CategoryName, CategoryDescription, CategoryWeight, IsActive)";
        $queryText .= " values (:userID, :categoryName, :categoryDescription, :categoryWeight, true);";
        $parameters = array(
            ":userID" => $user->UserID,
            ":categoryName" => $category->CategoryID,
            ":categoryName" => $category->CategoryName,
            ":categoryDescription" => $category->Description,
            ":categoryWeight" => $category->CategoryWeight
        );
        $category->CategoryID = self::runStatementGetLastInsertedID($queryText, $parameters);
        
        if (sizeof($category->KeyIndicators) > 0)
        {
            foreach ($category->KeyIndicators as $keyIndicator)
            {
                self::addKeyIndicator($category, $keyIndicator);
            }
        }
        
        // Update the object passed to us
        $user->addCategory($category);
        
        // Return the user context
        return $user;
    }
    
    
    /*
     * KEY INDICATOR 
     */
    public function addKeyIndicator($category, $keyIndicator)
    {
        $queryText = "insert into KeyIndicator (CategoryID, KeyIndicatorName, KeyIndicatorDescription, KeyIndicatorWeight, IsActive)";
        $queryText .= " values (:categoryID, :keyIndicatorName, :keyIndicatorDescription, :keyIndicatorWeight, :isActive);";
        $parameters = array(
            ":categoryID" => $category->CategoryID,
            ":keyIndicatorName" => $keyIndicator->KeyIndicatorName,
            ":keyIndicatorDescription" => $keyIndicator->KeyIndicatorDescription,
            ":keyIndicatorWeight" => $keyIndicator->KeyIndicatorWeight,
            ":isActive" => $keyIndicator->KeyIndicatorWeight
        );
        $keyIndicator->KeyIndicatorID = self::runStatementGetLastInsertedID($queryText, $parameters);
        $category->addKeyIndicator($keyIndicator);
        return $category;
    }
    
    /*
     * GENERIC QUERY METHODS
     */
    public function runQuery($queryText, $parameters = null, $fetchAsObjects = false, $className = null)
    {
        $statement = self::createAndExecuteStatement($queryText, $parameters);
        
        // Return the query results
        if ($fetchAsObjects)
        {
            if ($className != null)
            {
                // Return as a known types
                return $statement->fetchAll(PDO::FETCH_CLASS, $className);
            }
            
            // Return as anonymous objects
            return $statement->fetchAll(PDO::FETCH_OBJ);
        }
        
        // Return as an associative array
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function runStatementGetLastInsertedID($queryText, $parameters = null)
    {
        $statement = self::createAndExecuteStatement($queryText, $parameters);
        // TODO: Get the last inserted ID
    }
    
    public function runStatementGetAffectedRows($queryText, $parameters = null)
    {
        $statement = self::createAndExecuteStatement($queryText, $parameters);
        // TODO: Get the count of affected rows
    }
    
    private function createAndExecuteStatement($queryText, $parameters = null)
    {
        // echo "Preparing to run " . $queryText;
        
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
        
        // Create the statement
        $statement = $dbConn->prepare($queryText);
        
        // Logging
        // echo "Running " . $statement->queryString;

        // Execute the statement
        $statement->execute($parameters);
        
        // Return the statement
        return $statement;
    }
}

?>