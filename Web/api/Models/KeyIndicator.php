<?php

class KeyIndicator
{
    public $KeyIndicatorID;
    public $CategoryID;
    public $KeyIndicatorName;
    public $KeyIndicatorDescription;
    public $KeyIndicatorWeight;
    public $CreatedOn;
    public $IsActive;
    
    public $DailyKeyIndicatorReports;
    
    public static function createFromDbObject($dbKeyIndicator)
    {
        return static::create($dbKeyIndicator->KeyIndicatorID, $dbKeyIndicator->CategoryID, $dbKeyIndicator->KeyIndicatorName, $dbKeyIndicator->KeyIndicatorDescription, $dbKeyIndicator->KeyIndicatorWeight, $dbKeyIndicator->CreatedOn, $dbKeyIndicator->IsActive);
    }
    
    public static function create($keyIndicatorID, $categoryID, $keyIndicatorName, $keyIndicatorDescription, $keyIndicatorWeight, $createdOn, $isActive)
    {
        $keyIndicator = new KeyIndicator();
        
        $keyIndicator->KeyIndicatorID;
        $keyIndicator->CategoryID;
        $keyIndicator->KeyIndicatorName;
        $keyIndicator->KeyIndicatorDescription;
        $keyIndicator->KeyIndicatorWeight;
        $keyIndicator->CreatedOn;
        $keyIndicator->IsActive;
        
        $keyIndicator->DailyKeyIndicatorReports = array();
        
        return $keyIndicator;
    }
    
    public function addDailyKeyIndicatorReport($dailyKeyIndicatorReport)
    {
        $keyIndicator->DailyKeyIndicatorReports[$dailyKeyIndicatorReport->ReportID] = $dailyKeyIndicatorReport;
    }
}

?>