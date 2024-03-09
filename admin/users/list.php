<?php
require '../models/User.php';
session_start();
if (isset($_SESSION['id'])) {
    echo '<p> Welcome ' . $_SESSION['name'] . ' <a href="logout.php">Logout</a></p>';
} else {
    header("location: ../../login.php");
    exit;
}
$user = new User();
$users = $user->getUsers();
/*
// Connect to mysql
$conn = mysqli_connect("localhost", "root", "12345", "blog");
if (!$conn) {
    echo mysqli_connect_error();
    exit;
}
*/
// Select all users
// $query = "SELECT * FROM `users`";
// Search by name or the email

if (isset($_GET['search'])) {
    $users = $user->searchUsers($_GET['search']);
    // $search = mysqli_escape_string($conn, $_GET['search']);
    // $query .= " WHERE `users`.`name` LIKE '%" . $search . "%' OR `users`.`email` LIKE '%" . $search . "%' ";
}
// $result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Admin :: List Users</title>
</head>

<body>
    <h1>List users</h1>
    <!-- Searcher -->
    <form id="search" method="GET">
        <input type="text" name="search" placeholder="Enter {Name} or {Email} to search">
        <input type="submit" value="Search">
    </form>
    <!-- Display a table containg all users -->
    <table>
        <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Email</th>
                <th>avatar</th>
                <th>Admin</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // loop on the rowset
            foreach($users as $row) {
            ?>
                <tr>
                    <td><?php echo $row["id"] ?></td>
                    <td><?php echo $row["name"] ?></td>
                    <td><?php echo $row["email"] ?></td>
                    <td><?php if ($row['avatar']) { ?><img src="../../uploads/<?= $row['avatar'] ?>
                        " style="width: 100px; height: 100px" /><?php } else { ?><img src="../../uploads/noimage.png" style="width: 100px; height: 100px" /><?php } ?></td>
                    <td><?php echo ($row["admin"]) ? 'Yes' : 'No' ?></td>
                    <td><a href="edit.php?id=<?= $row['id'] ?>">Edit</a> | <a href="delete.php?id=<?= $row['id'] ?>">Delete</a></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" style="text-align: center;"><?= count($users) ?>users</td>
                <td colspan="3" style="text-align: center;"><a href="add.php"> Add User</a></td>
            </tr>
        </tfoot>
    </table>



</body>

</html>
<?php
// // Close connection
// mysqli_free_result($result);
// mysqli_close($conn);
?>
