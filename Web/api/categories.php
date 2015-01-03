<?php

require 'api_base.php';

class CategoriesApiWrapper extends ApiWrapper
{
    public function doGet()
    {
        return $this->userContext->Categories;
    }
    
    public function doPost($requestBody)
    {
        if (strlen($requestBody) == 0)
        {
            throw new ApiException(100, "No request body specified");
        }
        
        $category = json_decode($requestBody);
        if ($category == null)
        {
            throw new ApiException(123, "Bad JSON data");
        }
        
        $this->db->addCategory($this->userContext, $category);
        return $this->userContext;
    }
}

$categoriesApi = new CategoriesApiWrapper();
echo $categoriesApi->getResponseJson();

?>