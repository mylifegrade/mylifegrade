<?php

require 'api.php';

class UsersApiWrapper extends ApiWrapper
{
    public function doWork($method, $db)
    {
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
}

$userApi = new UsersApiWrapper();
echo $userApi->getResponse();

?>