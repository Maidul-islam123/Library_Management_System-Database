<?php
session_start();
include "../db.php";

if(!isset($_SESSION['user_role']) || $_SESSION['user_role'] != "admin"){
    die("Access denied!");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Admin Dashboard</h2>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></p>

    <ul>
        <li><a href="add_book.php">Add Book</a></li>
        <li><a href="manage_books.php">Manage Books</a></li>
        <li><a href="borrow.php">Borrow Book</a></li>
        <li><a href="return.php">Return Book</a></li>
        <li><a href="view_transactions.php">View Transactions</a></li>
        <li><a href="../logout.php">Logout</a></li>
    </ul>
</div>
</body>
</html>
