<?php

require '../../DB/DbWrapper.php';
require 'Models/ApiException.php';

$result = null;
$prettyPrint = false;

try
{
    $method = $_SERVER['REQUEST_METHOD'];
    try
    {
        // Do whatever work we need to do
        $result = doWork($method);
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
if ($_GET["prettyprint"] && $_GET["prettyprint"] > 0 && $_GET["prettyprint"] < 500)
{
    $prettyPrint = (bool)($_GET["prettyprint"]);
}

// Print out the results
if ($prettyPrint)
{
    echo json_encode($result);
}
else
{
    echo json_encode($result, JSON_PRETTY_PRINT);
}






/*
 *  HELPER FUNCTIONS
 */
function doWork($method)
{
    $db = new DbWrapper();
    switch ($method)
    {
        case "GET":
            if (!$_GET["userID"] || $_GET["userID"] <= 0)
            {
                throw new ApiException(100, "No UserID specified");
            }
            $user = $db->getUserByID($_GET["userID"]);
            if ($user == null)
            {
                throw new ApiException(101, "There is no user with ID " . $_GET["userID"]);
            }
            return $user;
        default:
            throw new ApiException(001, "Unrecognized HTTP method: " . $method);
    }
}

?>