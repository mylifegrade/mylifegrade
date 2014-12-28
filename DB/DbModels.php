<?php

function parseObject($row, $obj)
{
    foreach (array_keys($row) as $columnName)
    {
        $obj->$columnName = $row[$columnName];
    }
    return $obj;
}

class DbUser
{
    public $UserID;
    public $UserName;
    public $PasswordHash;
    public $PasswordSalt;
    public $Email;
    public $CreatedOn;
    public $LastActivityDate;
    public $UserType;
    public $CurrentPoints;
    public $ApiKey;
    
    public static function parse($row)
    {
        return parseObject($row, new DbUser());
    }
}

class DbCategory
{
    public $categoryID;
    public $userID;
    public $categoryName;
    public $categoryDescription;
    public $categoryWeight;
    public $createdOn;
    public $isActive;
}

class DbKeyIndicator
{
    public $keyIndicatorID;
    public $categoryID;
    public $keyIndicatorName;
    public $keyIndicatorDescription;
    public $keyIndicatorWeight;
    public $createdOn;
    public $isActive;
}

class DbDailyKeyIndicatorReport
{
    public $reportID;
    public $keyIndicatorID;
    public $createdOn;
    public $achievedPercentage;
    public $notes;
}

?>