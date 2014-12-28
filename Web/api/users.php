<?php

require 'api_base.php';

class UsersApiWrapper extends ApiWrapper
{
    public function doWork($method)
    {
        switch ($method)
        {
            case "GET":
                return $this->user;
            default:
                throw new ApiException(001, "Unrecognized HTTP method: " . $method);
        }
    }
}

$userApi = new UsersApiWrapper();
echo $userApi->getResponseJson();

?>