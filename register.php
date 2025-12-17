<?php 
include "db.php"; 

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = $_POST['name']; 
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $sql = "INSERT INTO users(name,email,password,role)
            VALUES('$name','$email','$password','$role')";

    $result = mysqli_query($conn, $sql);

    if (!$result) { 
        echo "Error! : " . mysqli_error($conn);
    } else { 
        echo "You have registered successfully!";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ewu_Library_System</title>
    <link rel="stylesheet" type ="text/css" href="style.css">
</head>
<body class="register">

<form action="register.php" method="post">
    <input type="text" name="name" placeholder="Name"><br>
    <input type="email" name="email" placeholder="Email"><br>
    <input type="password" name="password" placeholder="Password"><br>
    <input type="hidden" name="role" value="user">
    <button type="submit">Sign up</button>
</form>

</body>
</html>
