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
        
        $keyIndicator->KeyIndicatorID = $keyIndicatorID;
        $keyIndicator->CategoryID = $categoryID;
        $keyIndicator->KeyIndicatorName = $keyIndicatorName;
        $keyIndicator->KeyIndicatorDescription = $keyIndicatorDescription;
        $keyIndicator->KeyIndicatorWeight = $keyIndicatorWeight;
        $keyIndicator->CreatedOn = $createdOn;
        $keyIndicator->IsActive = $isActive;
        
        $keyIndicator->DailyKeyIndicatorReports = array();
        
        return $keyIndicator;
    }
    
    public function addDailyKeyIndicatorReport($dailyKeyIndicatorReport)
    {
        $keyIndicator->DailyKeyIndicatorReports[$dailyKeyIndicatorReport->ReportID] = $dailyKeyIndicatorReport;
    }
}

?>