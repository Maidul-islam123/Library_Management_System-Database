<?php
session_start();
include "../db.php";

// Only allow logged-in users
if(!isset($_SESSION['user_role']) || $_SESSION['user_role'] != "user"){
    die("Access denied! Only users can return books.");
}

$user_id = $_SESSION['user_id'];
$msg = "";

// Handle form submission
if($_SERVER['REQUEST_METHOD']=="POST"){
    $book_id = (int)$_POST['book_id'];

    // Fetch latest borrowed transaction for this user and book
    $stmt = $conn->prepare("
        SELECT id, status 
        FROM transactions 
        WHERE user_id=? AND book_id=? AND status='borrowed' 
        ORDER BY issue_date DESC LIMIT 1
    ");
    $stmt->bind_param("ii", $user_id, $book_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $trans = $res->fetch_assoc();
    $stmt->close();

    if(!$trans){
        $msg = "<p style='color:red'>No borrowed record found for this book!</p>";
    } else {
        $transaction_id = $trans['id'];
        $conn->begin_transaction();
        try{
            // Mark as returned
            $stmt = $conn->prepare("UPDATE transactions SET status='returned', return_date=CURDATE() WHERE id=?");
            $stmt->bind_param("i", $transaction_id);
            $stmt->execute();
            $stmt->close();

            // Increase book quantity
            $stmt = $conn->prepare("UPDATE books SET quantity = quantity + 1 WHERE id=?");
            $stmt->bind_param("i", $book_id);
            $stmt->execute();
            $stmt->close();

            $conn->commit();
            $msg = "<p style='color:green'>Book returned successfully!</p>";
        } catch(Exception $e){
            $conn->rollback();
            $msg = "<p style='color:red'>Error: ".$e->getMessage()."</p>";
        }
    }
}

// Fetch all borrowed books for the user
$books = $conn->query("
    SELECT b.id AS book_id, b.title, t.issue_date
    FROM transactions t
    JOIN books b ON t.book_id = b.id
    WHERE t.user_id=$user_id AND t.status='borrowed'
    ORDER BY t.issue_date DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Return Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h3>Return Book</h3>

    <?php if($msg) echo $msg; ?>

    <?php if($books->num_rows > 0): ?>
        <form method="post">
            <select name="book_id" class="form-control mb-2" required>
                <option value="">Select Book to Return</option>
                <?php while($b = $books->fetch_assoc()): ?>
                    <option value="<?= $b['book_id']; ?>">
                        <?= $b['title']." borrowed on ".$b['issue_date']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <button type="submit" class="btn btn-success">Return Book</button>
        </form>
    <?php else: ?>
        <div class="alert alert-info">You have no borrowed books to return.</div>
    <?php endif; ?>

    <p><a href="../dashboard.php" class="btn btn-secondary mt-2">Back to Dashboard</a></p>
</div>
</body>
</html>
