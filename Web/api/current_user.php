<?php

require 'api_base.php';

class CurrentUserApiWrapper extends ApiWrapper
{
    public function doWork($method, $requestBody)
    {
        switch ($method)
        {
            case "GET":
                $this->userContext->UserName = 'New name';
                return $this->userContext;
            default:
                throw new ApiException(001, "Unrecognized HTTP method: " . $method);
        }
    }
}

$userApi = new CurrentUserApiWrapper();
echo $userApi->getResponseJson();

?>