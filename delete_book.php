<?php
session_start();
include "../db.php";

if(!isset($_SESSION['user_role']) || $_SESSION['user_role'] != "admin"){
    die("Access denied!");
}

if(!isset($_GET['id'])){
    die("Book ID not specified!");
}

$id = (int)$_GET['id'];
mysqli_query($conn, "DELETE FROM books WHERE id=$id");
header("Location: manage_books.php");
exit;
