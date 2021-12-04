<?php
$db_host = "std-mysql.ist.mospolytech.ru:3306";
$db_user = "std_1515_shooting_gallery";
$db_password = "shootinggallery";
$database = "std_1515_shooting_gallery";
$conn = mysqli_connect($db_host, $db_user, $db_password, $database);
if (!$conn) {
    die("Connection failed: ".mysqli_connect_error());
}
echo "";
?>
