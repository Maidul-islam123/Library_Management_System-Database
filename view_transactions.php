<?php
session_start();
include "../db.php";

if(!isset($_SESSION['user_role']) || $_SESSION['user_role'] != "admin"){
    die("Access denied!");
}

$transactions = $conn->query("SELECT t.id, u.name as user_name, b.title as book_title, t.issue_date, t.return_date, t.status
    FROM transactions t
    JOIN users u ON t.user_id = u.id
    JOIN books b ON t.book_id = b.id
    ORDER BY t.id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Transactions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h3>All Transactions</h3>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Book</th>
                <th>Issue Date</th>
                <th>Return Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while($t = $transactions->fetch_assoc()): ?>
            <tr>
                <td><?= $t['id']; ?></td>
                <td><?= htmlspecialchars($t['user_name']); ?></td>
                <td><?= htmlspecialchars($t['book_title']); ?></td>
                <td><?= $t['issue_date']; ?></td>
                <td><?= $t['return_date']; ?></td>
                <td><?= $t['status']; ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <p><a href="dashboard.php" class="btn btn-secondary mt-2">Back to Dashboard</a></p>
</div>
</body>
</html>
