<?php

class DbUser
{
    public $UserID;
    public $UserName;
    public $PasswordHash;
    public $PasswordSalt;
    public $Email;
    public $CreatedOn;
    public $LastLoginDate;
    public $UserType;
    public $CurrentPoints;
    public $ApiKey;
    
    public static function parse($row)
    {
        $dbUser = new DbUser();
        foreach (array_keys($row) as $columnName)
        {
            $dbUser->$columnName = $row[$columnName];
        }
        return $dbUser;
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