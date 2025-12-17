<?php
session_start();
include "db.php";

if($_SERVER['REQUEST_METHOD']=="POST"){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn,$sql);

    if(mysqli_num_rows($result)==1){
        $row = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['user_email'] = $row['email'];
        $_SESSION['user_role'] = $row['role'];

        header("Location: dashboard.php");
        exit;
    } else {
        echo "<p style='color:red'>Invalid email or password!</p>";
    }
}
?>

<h3>Login</h3>
<form method="post">
<input type="email" name="email" placeholder="Email" required><br><br>
<input type="password" name="password" placeholder="Password" required><br><br>
<button type="submit">Login</button>
</form>
