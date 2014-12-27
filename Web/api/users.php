<?php

require 'DataWrapper.php';

$method = $_SERVER['REQUEST_METHOD'];
$api = new DataWrapper();

$queryText = "";
switch ($method)
{
    case "GET":
        $queryText = buildGetQuery();
        break;
    case "POST":
        $queryText = buildPostQuery();
        break;
    case "PUT":
        $queryText = buildPutQuery();
        break;
    case "DELETE":
        $queryText = buildDeleteQuery();
        break;
    default:
        die("What kind of method is this? " . $method);
        break;
}

// Pretty-print
$prettyPrint = false;
if ($_GET["prettyprint"] && $_GET["prettyprint"] > 0 && $_GET["prettyprint"] < 500)
{
    $topLimit = (bool)($_GET["prettyprint"]);
}

// Issue the DB call and write it out
echo $api->runQueryJson($queryText, $prettyPrint);



/*
 *  HELPER FUNCTIONS
 */

function buildGetQuery()
{
    // Select users
    $query = "SELECT * FROM Users";
    
    // UserID
    if ($_GET["userid"])
    {
        $query .= " WHERE UserID = " . (int)($_GET["userid"]);
    }
    
    // Limit
    if ($_GET["limit"] && $_GET["limit"] > 0 && $_GET["limit"] < 500)
    {
        $query .= " LIMIT " . (int)($_GET["limit"]);
    }
    
    $query .= ";";
    
    return $query;
}

function buildPostQuery()
{
    // Update user
    $query = "SELECT * FROM Users";
    
    // TODO: Write this method!
    
    return $query;
}

function buildPutQuery()
{
    // Create user
    $query = "SELECT * FROM Users";
    
    // TODO: Write this method!
    
    return $query;
}

function buildDeleteQuery()
{
    // Delete user
    $query = "SELECT * FROM Users";
    
    // TODO: Write this method!
    
    return $query;
}

?>