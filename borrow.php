<?php
session_start();
include "../db.php";

if(!isset($_SESSION['user_role']) || $_SESSION['user_role'] != "admin"){
    die("Access denied!");
}

if($_SERVER['REQUEST_METHOD']=="POST"){
    $user_id = (int)$_POST['user_id'];
    $book_id = (int)$_POST['book_id'];
    $issue_date = $_POST['issue_date'];
    $return_date = $_POST['return_date'];

    $stmt = $conn->prepare("INSERT INTO transactions (user_id, book_id, issue_date, return_date, status) VALUES (?, ?, ?, ?, 'borrowed')");
    $stmt->bind_param("iiss", $user_id, $book_id, $issue_date, $return_date);
    if($stmt->execute()){
        $msg = "<p style='color:green'>Book borrowed successfully!</p>";
    } else {
        $msg = "<p style='color:red'>Error: ".$stmt->error."</p>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Borrow Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h3>Borrow Book</h3>
    <?php if(isset($msg)) echo $msg; ?>

    <form method="post">
        <input type="number" name="user_id" placeholder="User ID" class="form-control mb-2" required>
        <input type="number" name="book_id" placeholder="Book ID" class="form-control mb-2" required>
        <label>Issue Date:</label>
        <input type="date" name="issue_date" class="form-control mb-2" required>
        <label>Return Date (optional):</label>
        <input type="date" name="return_date" class="form-control mb-2">
        <button type="submit" class="btn btn-primary">Borrow Book</button>
    </form>
    <p><a href="dashboard.php" class="btn btn-secondary mt-2">Back to Dashboard</a></p>
</div>
</body>
</html>
