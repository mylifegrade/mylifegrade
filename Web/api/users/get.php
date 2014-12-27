<?php
 
//Connect to the database
$host = "mylifegrade-mylifegrade-1180782";   //See Step 3 about how to get host name
$user = "mylifegrade";                     //Your Cloud 9 username
$pass = "";                                 //Remember, there is NO password!
$db = "myLifeGrade";                          //Your database name you want to connect to
$port = 3306;                               //The port #. It is always 3306

$connection = mysqli_connect($host, $user, $pass, $db, $port)or die(mysql_error());

$query = "SELECT * FROM Users;";
$result = mysqli_query($connection, $query);

$rows = array();
while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
    // echo "The ID is: " . $row['UserID'] . " and the Username is: " . $row['UserName'];
}

echo json_encode($rows, JSON_PRETTY_PRINT);

?>