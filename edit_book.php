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
$result = mysqli_query($conn, "SELECT * FROM books WHERE id=$id");

if(mysqli_num_rows($result) == 0){
    die("Book not found!");
}

$book = mysqli_fetch_assoc($result);

if($_SERVER['REQUEST_METHOD']=="POST"){
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $genre = mysqli_real_escape_string($conn, $_POST['genre']);
    $quantity = (int)$_POST['quantity'];

    mysqli_query($conn, "UPDATE books SET title='$title', author='$author', genre='$genre', quantity=$quantity WHERE id=$id");
    header("Location: manage_books.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h3>Edit Book</h3>
    <form method="post">
        <input type="text" name="title" value="<?= htmlspecialchars($book['title']); ?>" class="form-control mb-2" required>
        <input type="text" name="author" value="<?= htmlspecialchars($book['author']); ?>" class="form-control mb-2" required>
        <input type="text" name="genre" value="<?= htmlspecialchars($book['genre']); ?>" class="form-control mb-2">
        <input type="number" name="quantity" value="<?= $book['quantity']; ?>" class="form-control mb-2" min="1">
        <button type="submit" class="btn btn-primary">Update Book</button>
    </form>
    <p><a href="manage_books.php" class="btn btn-secondary mt-2">Back to Manage Books</a></p>
</div>
</body>
</html>
