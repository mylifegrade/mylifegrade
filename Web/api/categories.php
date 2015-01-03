<?php

require 'api_base.php';

class CategoriesApiWrapper extends ApiWrapper
{
    public function doWork($method, $requestBody)
    {
        switch ($method)
        {
            case "POST":
                $categoryPostDataString = file_get_contents('php://input');
                if (strlen($categoryPostDataString) > 0)
                {
                    $category = json_decode($categoryPostDataString);
                    if ($category == null)
                    {
                        throw new ApiException(123, "Bad JSon data");
                    }
                    
                    $this->db->addCategory($this->userContext, $category);
                    return $this->userContext;
                }
            default:
                throw new ApiException(001, "Unrecognized HTTP method: " . $method);
        }
    }
}

$categoriesApi = new CategoriesApiWrapper();
echo $categoriesApi->getResponseJson();

?>