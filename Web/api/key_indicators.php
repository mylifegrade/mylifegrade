<?php

require 'api_base.php';

class KeyIndicatorsApiWrapper extends ApiWrapper
{
    public function doGet()
    {
        $category = self::getSpecifiedCategory();
        return $category->KeyIndicators;
    }
    
    public function doPost($requestBody)
    {
        $category = self::getSpecifiedCategory();
        if (strlen($requestBody) == 0)
        {
            throw new ApiException(100, "No request body specified");
        }
        
        $keyIndicator = json_decode($requestBody);
        if ($keyIndicator == null)
        {
            throw new ApiException(123, "Bad JSON data");
        }
        
        $this->db->addKeyIndicator($category, $keyIndicator);
        return $category;
    }
    
    private function getSpecifiedCategory()
    {
        // Get the category ID
        $categoryID = self::getFormValue("categoryID");
        if ($categoryID == null)
        {
            throw new ApiException(502, "A category ID must be specified");
        }
        $categoryID = (int)$categoryID;
        
        // Return the category associated with that ID
        if (!array_key_exists($categoryID, $this->userContext->Categories))
        {
            throw new ApiException(502, "The specified category ID doesn't exist");
        }
        
        return $this->userContext->Categories[$categoryID];
    }
}

$keyIndicatorsApi = new KeyIndicatorsApiWrapper();
echo $keyIndicatorsApi->getResponseJson();

?>