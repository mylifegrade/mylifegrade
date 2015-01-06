<?php

require 'api_base.php';

class DailyKeyIndicatorReportApiWrapper extends ApiWrapper
{
    public function doGet()
    {
        throw new Exception("Not Implemented");
    }
    
    public function doPost($requestBody)
    {
        throw new Exception("Not Implemented");
    }
}

$reportsApi = new DailyKeyIndicatorReportApiWrapper();
echo $reportsApi->getResponseJson();

?>