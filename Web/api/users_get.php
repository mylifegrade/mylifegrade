<?php

require 'DataWrapper.php';

$api = new DataWrapper();
echo $api->runQueryJson("SELECT * FROM Users;", $_GET["prettyprint"]);

?>