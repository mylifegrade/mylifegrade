<?php

require 'KeyIndicator.php';

class Category
{
    public $CategoryID;
    public $UserID;
    public $CategoryName;
    public $CategoryDescription;
    public $CategoryWeight;
    public $CreatedOn;
    public $IsActive;
    
    public $KeyIndicators;
    
    public static function createFromDbObject($dbCategory)
    {
        return static::create($dbCategory->CategoryID, $dbCategory->UserID, $dbCategory->CategoryName, $dbCategory->CategoryDescription, $dbCategory->CategoryWeight, $dbCategory->CreatedOn, $dbCategory->IsActive);
    }
    
    public static function create($categoryID, $userID, $categoryName, $categoryDescription, $categoryWeight, $createdOn, $isActive)
    {
        $category = new Category();
        
        $category->CategoryID = $categoryID;
        $category->UserID = $userID;
        $category->CategoryName = $categoryName;
        $category->CategoryDescription = $categoryDescription;
        $category->CategoryWeight = $categoryWeight;
        $category->CreatedOn = $createdOn;
        $category->IsActive = $isActive;
        
        $category->KeyIndicators = array();
        
        return $category;
    }
    
    public function addKeyIndicator($keyIndicator)
    {
        $category->KeyIndicators[$keyIndicator->KeyIndicatorID] = $keyIndicator;
    }
}

?>