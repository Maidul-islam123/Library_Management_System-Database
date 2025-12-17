<?php
session_start();

// Make sure the user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != "user") {
    die("Access denied! You must be logged in as a user.");
}

// Include the database connection
include "db.php"; // assuming db.php is in the same folder

// Get user_id from session
$user_id = $_SESSION['user_id'];

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (!$conn) {
        die("Database connection failed!");
    }

    // Get book ID and dates from form
    $book_id = mysqli_real_escape_string($conn, $_POST['book_id']);
    $issue_date = mysqli_real_escape_string($conn, $_POST['issue_date']);
    $return_date = mysqli_real_escape_string($conn, $_POST['return_date']);

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO transactions (user_id, book_id, issue_date, return_date, status) VALUES (?, ?, ?, ?, 'borrowed')");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("iiss", $user_id, $book_id, $issue_date, $return_date);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Book borrowed successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!-- HTML form to borrow a book -->
<!DOCTYPE html>
<html>
<head>
    <title>Borrow Book</title>
</head>
<body>
<h2>Borrow Book</h2>
<p>Logged in as: <?php echo $_SESSION['user_role']; ?></p>
<form method="POST" action="">
    <label>Book ID:</label><br>
    <input type="number" name="book_id" required><br><br>

    <label>Issue Date:</label><br>
    <input type="date" name="issue_date" required><br><br>

    <label>Return Date:</label><br>
    <input type="date" name="return_date"><br><br>

    <input type="submit" value="Borrow Book">
</form>
</body>
</html>

