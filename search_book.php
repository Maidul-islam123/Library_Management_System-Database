<?php
include "db.php";
?>

<h3>Search Books</h3>
<form method="post">
<input type="text" name="search" placeholder="Search by Title or Author">
<button>Search</button>
</form>

<?php
if(isset($_POST['search'])){
    $search = $_POST['search'];
    $result = mysqli_query($conn,"SELECT * FROM books WHERE title LIKE '%$search%' OR author LIKE '%$search%'");
    while($row=mysqli_fetch_assoc($result)){
        echo $row['id']." | ".$row['title']." | ".$row['author']." | ".$row['genre']." | Qty: ".$row['quantity']."<br>";
    }
}
?>
