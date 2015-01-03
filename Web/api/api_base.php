<?php

require '../../DB/DbWrapper.php';
require 'Models/Exceptions/ApiException.php';

abstract class ApiWrapper
{
    protected $db;
    protected $userContext;
    
    abstract protected function doGet();
    abstract protected function doPost($requestBody);
    
    public function getResponseJson($requireApiKey = true)
    {
        $result = null;
        try
        {
            // Process the request
            $method = $_SERVER['REQUEST_METHOD'];
            try
            {
                $this->db = new DbWrapper();
                
                // Authenticate
                if ($requireApiKey)
                {
                    $apiKey = self::getFormValue("apiKey");
                    if ($apiKey == null)
                    {
                        throw new ApiException(403, "No API key provided");
                    }
                    
                    $this->userContext = $this->db->getUserContextByApiKey($apiKey);
                    if ($this->userContext == null)
                    {
                        throw new ApiException(403, "Not authenticated");
                    }
                }
                
                // Do whatever work we need to do
                switch ($method)
                {
                    case "GET":
                        $result = static::doGet();
                        break;
                    case "POST":
                        $result = static::doPost(file_get_contents('php://input'));
                        break;
                    default:
                        throw new Exception("No other HTTP methods implemented yet");
                }
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
        $prettyPrint = self::getFormValue("prettyprint");
        if ($prettyPrint == null)
        {
            $prettyPrint = false;
        }
        else
        {
            $prettyPrint = (bool)$prettyPrint;
        }
        
        // Print out the results
        if ($prettyPrint)
        {
            return json_encode($result, JSON_PRETTY_PRINT);
        }
        else
        {
            return json_encode($result);
        }
    }
    
    protected function getFormValue($keyName)
    {
        $methodDictionary = self::getMethodDictionary();
        return isset($methodDictionary[$keyName]) ? $methodDictionary[$keyName] : null;
    }
    
    protected function getMethodDictionary()
    {
        return $_GET;
    }
}

?>