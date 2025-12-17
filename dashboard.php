<?php
session_start();
include "db.php"; // include database connection

// Login check
if(!isset($_SESSION['user_email'])){
    header("Location: login.php");
    exit;
}

$role = $_SESSION['user_role'];
$email = $_SESSION['user_email'];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>EWU Library System Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>EWU Library System Dashboard</h2>
    <p>Email: <?= $email; ?> | Role: <?= $role; ?></p>

    <?php if($role == "admin"){ ?>
        <h3>Admin Panel</h3>
        <ul class="list-group mb-3">
            <li class="list-group-item"><a href="admin/add_book.php">Add Book</a></li>
            <li class="list-group-item"><a href="admin/view_books.php">Manage Books</a></li>
            <li class="list-group-item"><a href="search_book.php">Search Books</a></li>
            <li class="list-group-item"><a href="admin/borrow_book.php">Borrow Book</a></li>
            <li class="list-group-item"><a href="admin/return_book.php">Return Book</a></li>
            <li class="list-group-item"><a href="admin/view_transactions.php">View Transactions</a></li>
            <li class="list-group-item"><a href="logout.php">Logout</a></li>
        </ul>
    <?php } else { ?>
        <h3>User Panel</h3>
        <ul class="list-group mb-3">
            <li class="list-group-item"><a href="search_book.php">Search Books</a></li>
            <li class="list-group-item"><a href="borrow_book.php">Borrow Book</a></li>
            <li class="list-group-item"><a href="return_book.php">Return Book</a></li>
            
            <li class="list-group-item"><a href="logout.php">Logout</a></li>
        </ul>
    <?php } ?>
</div>
</body>
</html>
