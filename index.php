<?php

function get_info(){
    require ('DB.php');
    $query = 'SELECT * FROM ';
    return mysqli_query($conn, $query);
}

require ('DB.php');
$content = '';
require ('template.php');