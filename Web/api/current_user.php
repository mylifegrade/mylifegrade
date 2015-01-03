<?php

require 'api_base.php';

class CurrentUserApiWrapper extends ApiWrapper
{
    public function doGet()
    {
        return $this->userContext;
    }
    
    public function doPost($requestBody)
    {
        throw new Exception("Not Implemented");
    }
}

$userApi = new CurrentUserApiWrapper();
echo $userApi->getResponseJson();

?>