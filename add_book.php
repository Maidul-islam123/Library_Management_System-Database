<?php
session_start();
include "../db.php";

if(!isset($_SESSION['user_role']) || $_SESSION['user_role'] != "admin"){
    die("Access denied!");
}

if($_SERVER['REQUEST_METHOD']=="POST"){
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $genre = mysqli_real_escape_string($conn, $_POST['genre']);
    $quantity = (int)$_POST['quantity'];

    $sql = "INSERT INTO books (title, author, genre, quantity) VALUES ('$title', '$author', '$genre', $quantity)";
    if(mysqli_query($conn, $sql)){
        $msg = "<p style='color:green'>Book added successfully!</p>";
    } else {
        $msg = "<p style='color:red'>Error: ".mysqli_error($conn)."</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h3>Add Book</h3>
    <?php if(isset($msg)) echo $msg; ?>

    <form method="post">
        <input type="text" name="title" placeholder="Book Title" required class="form-control mb-2">
        <input type="text" name="author" placeholder="Author" required class="form-control mb-2">
        <input type="text" name="genre" placeholder="Genre" class="form-control mb-2">
        <input type="number" name="quantity" placeholder="Quantity" value="1" min="1" class="form-control mb-2">
        <button type="submit" class="btn btn-primary">Add Book</button>
    </form>

    <p><a href="dashboard.php" class="btn btn-secondary mt-2">Back to Dashboard</a></p>
</div>
</body>
</html>
