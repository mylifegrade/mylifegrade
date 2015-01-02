<?php

require 'api_base.php';

class CategoriesApiWrapper extends ApiWrapper
{
    public function doWork($method, $requestBody)
    {
        switch ($method)
        {
            case "POST":
                return "Well, hi!";
            default:
                throw new ApiException(001, "Unrecognized HTTP method: " . $method);
        }
    }
}

$categoriesApi = new CategoriesApiWrapper();
echo $categoriesApi->getResponseJson();

?>