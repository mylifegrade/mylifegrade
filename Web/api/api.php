<?php

require '../../DB/DbWrapper.php';
require 'Models/ApiException.php';

abstract class ApiWrapper
{
    abstract protected function doWork($method, $db);
    
    public function getResponse()
    {
        $result = null;
        try
        {
            $method = $_SERVER['REQUEST_METHOD'];
            try
            {
                // Do whatever work we need to do
                $result = static::doWork($method, new DbWrapper());
            }
            catch (ApiException $e)
            {
                // Handle known API Exception
                $result = $e;
            }
        }
        catch (Exception $e)
        {
            // Handle unknown system error
            $apiEx = new ApiException($e->getMessage());
            $apiEx->ErrorCode = 500;
            $result = $apiEx;
        }
        
        // Determine whether to pretty print
        $prettyPrint = false;
        if ($_GET["prettyprint"] && $_GET["prettyprint"] > 0 && $_GET["prettyprint"] < 500)
        {
            $prettyPrint = (bool)($_GET["prettyprint"]);
        }
        
        // Print out the results
        if ($prettyPrint)
        {
            return json_encode($result);
        }
        else
        {
            return json_encode($result, JSON_PRETTY_PRINT);
        }
    }
}

?>