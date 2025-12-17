<?php
$server = "localhost";
$user   = "root";
$pass   = "";
$dbname = "ewu_library_system";

$conn = mysqli_connect($server, $user, $pass, $dbname);

if(!$conn){
    die("Database connection failed: " . mysqli_connect_error());
}
?>
