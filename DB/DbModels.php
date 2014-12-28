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
    public $CategoryID;
    public $UserID;
    public $CategoryName;
    public $CategoryDescription;
    public $CategoryWeight;
    public $CreatedOn;
    public $IsActive;
    
    public static function parse($row)
    {
        return parseObject($row, new DbCategory());
    }
}

class DbKeyIndicator
{
    public $KeyIndicatorID;
    public $CategoryID;
    public $KeyIndicatorName;
    public $KeyIndicatorDescription;
    public $KeyIndicatorWeight;
    public $CreatedOn;
    public $IsActive;
    
    public static function parse($row)
    {
        return parseObject($row, new DbKeyIndicator());
    }
}

class DbDailyKeyIndicatorReport
{
    public $ReportID;
    public $KeyIndicatorID;
    public $CreatedOn;
    public $AchievedPercentage;
    public $Notes;
    
    public static function parse($row)
    {
        return parseObject($row, new DbDailyKeyIndicatorReport());
    }
}

?>