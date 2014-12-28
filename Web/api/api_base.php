<?php

require '../../DB/DbWrapper.php';
require 'Models/ApiException.php';

abstract class ApiWrapper
{
    protected $db;
    protected $user;
    
    abstract protected function doWork($method);
    
    public function getResponseJson($requireApiKey = true)
    {
        $result = null;
        try
        {
            // Process the request
            $method = $_SERVER['REQUEST_METHOD'];
            try
            {
                // Authenticate
                if ($requireApiKey)
                {
                    if (!isset($_GET["apiKey"]))
                    {
                        throw new ApiException(403, "No API key provided");
                    }
                    
                    $this->user = $this->db->getUserByApiKey();
                    if ($this->user == null)
                    {
                        throw new ApiException(403, "Not authenticated");
                    }
                }
                
                // Do whatever work we need to do
                $result = static::doWork($method);
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
    
    private function getUser()
    {
        
    }
}

?>