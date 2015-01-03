<?php

require '../../Web/api/Models/User.php';

class DbWrapper
{
    const HOST_NAME = "mylifegrade-mylifegrade-1180782";
    const USER_NAME = "mylifegrade"; 
    const PASSWORD = "";
    const DB_NAME = "myLifeGrade";
    const PORT = 3306;
    
    private $dbConn;
    
    public function __construct() 
    {
        // Create the connection to use
        $this->dbConn = null;
        try
        {
            $this->dbConn = new PDO("mysql:host=" . self::HOST_NAME . ";port=" . self::PORT . ";dbname=" . self::DB_NAME, self::USER_NAME, self::PASSWORD);
            if ($this->dbConn == null)
            {
                throw new Exception("The connection was initialized to null");
            }
        }
        catch (Exception $e)
        {
            die("Error while initializing the DB connection: " . $e->getMessage());
        }
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
        // Prep the object for injection
        $category->UserID = $user->UserID;
        $category->CreatedOn = self::now();
        $category->IsActive = true;
        
        // Prepare the statement
        $queryText = "insert into Category (UserID, CategoryName, CategoryDescription, CategoryWeight, IsActive, CreatedOn)";
        $queryText .= " values (:userID, :categoryName, :categoryDescription, :categoryWeight, :isActive, :createdOn);";
        $parameters = array(
            ":userID" => $category->UserID,
            ":categoryName" => $category->CategoryName,
            ":categoryDescription" => $category->CategoryDescription,
            ":categoryWeight" => $category->CategoryWeight,
            ":isActive" => $category->IsActive,
            ":createdOn" => $category->CreatedOn
        );
        
        // Insert the category and populate the ID
        $category->CategoryID = self::runStatementGetLastInsertedID($queryText, $parameters);
        
        // Handle KeyIndicators
        if ($category->KeyIndicators == null)
        {
            $category->KeyIndicators = array();
        }
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
        return $this->dbConn->lastInsertId();
    }
    
    public function runStatementGetAffectedRows($queryText, $parameters = null)
    {
        $statement = self::createAndExecuteStatement($queryText, $parameters);
        return $statement->rowCount();
    }
    
    private function createAndExecuteStatement($queryText, $parameters = null)
    {
        // echo "Preparing to run " . $queryText;
        
        // Create the statement
        $statement = $this->dbConn->prepare($queryText);
        
        // Logging
        // echo "Running " . $statement->queryString;

        // Execute the statement
        $statement->execute($parameters);
        
        // Return the statement
        return $statement;
    }
    
    private function now()
    {
        return date("c");
    }
}

?>