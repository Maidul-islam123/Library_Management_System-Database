<?php
session_start();
include "../db.php";

if(!isset($_SESSION['user_role']) || $_SESSION['user_role'] != "admin"){
    die("Access denied!");
}

if($_SERVER['REQUEST_METHOD']=="POST"){
    $transaction_id = (int)$_POST['transaction_id'];

    $stmt = $conn->prepare("SELECT book_id, status FROM transactions WHERE id=?");
    $stmt->bind_param("i", $transaction_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $trans = $res->fetch_assoc();
    $stmt->close();

    if(!$trans){
        $msg = "<p style='color:red'>Transaction not found!</p>";
    } elseif($trans['status'] != 'borrowed'){
        $msg = "<p style='color:red'>Book already returned!</p>";
    } else {
        $conn->begin_transaction();
        try{
            $stmt = $conn->prepare("UPDATE transactions SET status='returned', return_date=CURDATE() WHERE id=?");
            $stmt->bind_param("i", $transaction_id);
            $stmt->execute();
            $stmt->close();

            $stmt = $conn->prepare("UPDATE books SET quantity = quantity + 1 WHERE id=?");
            $stmt->bind_param("i", $trans['book_id']);
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

$transactions = $conn->query("SELECT t.id, u.name as user_name, b.title as book_title, t.issue_date 
    FROM transactions t
    JOIN users u ON t.user_id = u.id
    JOIN books b ON t.book_id = b.id
    WHERE t.status='borrowed'");
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
    <?php if(isset($msg)) echo $msg; ?>
    <form method="post">
        <select name="transaction_id" class="form-control mb-2" required>
            <option value="">Select Borrowed Book</option>
            <?php while($t = $transactions->fetch_assoc()): ?>
                <option value="<?= $t['id']; ?>"><?= $t['book_title']." borrowed by ".$t['user_name']." on ".$t['issue_date']; ?></option>
            <?php endwhile; ?>
        </select>
        <button type="submit" class="btn btn-success">Return Book</button>
    </form>
    <p><a href="dashboard.php" class="btn btn-secondary mt-2">Back to Dashboard</a></p>
</div>
</body>
</html>
