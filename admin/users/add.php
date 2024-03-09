<?php
    //Validation
    session_start();
    $error_fields = array();
    if(($_SERVER['REQUEST_METHOD'] == 'POST')){
        if(($_SESSION['admin'])==1)
        {
        if(!(isset($_POST["name"]) && !empty($_POST["name"]))){
            $error_fields[] = "name";
        }
        if(!(isset($_POST["email"]) && filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))){
            $error_fields[] = "email";
        }
        if(!(isset($_POST["password"]) && strlen($_POST["password"] > 5))){
            $error_fields[] = "password";
        }
    
    if(!$error_fields){
        // Open the connection
        $conn = mysqli_connect("localhost", "root", "12345", "blog");
        if (! $conn) {
            echo mysqli_connect_error();
            exit;
        }
        
        // Escape any sepcial characters to avoid SQL Injection
        $name = mysqli_escape_string($conn,trim($_POST['name']));
        $email = mysqli_escape_string($conn, $_POST['email']);
        $password = password_hash($_POST['password'],PASSWORD_DEFAULT);
        $admin = (isset($_POST['admin'])) ? 1 : 0 ;
        // Upload file
        $uploads_dir = $_SERVER['DOCUMENT_ROOT'].'/bloggingsys/uploads';
        $avatar = '';
        if($_FILES["avatar"]['error'] == UPLOAD_ERR_OK){
            $tmp_name = $_FILES["avatar"]["tmp_name"];
            $avatar = basename($_FILES["avatar"]["name"]);
            move_uploaded_file($tmp_name, "$uploads_dir/$avatar");
        }else{
            echo "File can't be uploaded";
            exit;
        }
        // Insert the data
        $query = "INSERT INTO `users` (`name`, `email`, `password`,`avatar`, `admin`) 
        VALUES ('".$name."','".$email."','".$password."', '".$avatar."', '".$admin."')";
        if(mysqli_query($conn, $query)){
            header("Location: list.php");
            exit;
        }else{
            echo mysqli_error($conn);
        }
        mysqli_close($conn);
        }
      }
    }

?>

<html>
<head>
    <link rel="stylesheet" href="styles.css">
    <title>Admin :: Add User</title>
</head>
    <body>
        <form method="post" enctype="multipart/form-data">
        <?php
            if ($_SESSION['admin'] == 0) {
                echo "<span class='error'>* You don't have permission</span>";
            } else {
                echo "<span class='hello'>Hello, admin</span>";
            }
            ?>
            <label for="name">Name</label>
            <input type="text" name="name" id="name" value="<?=(isset($_POST['name'])) ? $_POST['name'] : '' ?>" /><?php if(in_array("name", $error_fields)) 
            echo "<span class='error'> * Please enter your name</span>"; ?>
            <br />
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="<?=(isset($_POST['email'])) ? $_POST['email'] : '' ?>" /><?php if(in_array("email", $error_fields)) 
            echo "<span class='error'>* Please enter a valid email</span>"; ?>
            <br />
            <label for="password">Password</label>
            <input type="password" name="password" id="password" /><?php if(in_array("password", $error_fields)) 
            echo "<span class='error'>* Please enter a password not less than 6 characters</span>"; ?>
            <br />
            <input type="checkbox" name="admin" <?= (isset($_POST['admin'])) ? 'checked' : ''?> />Admin
            <br>
            <label for="avatar">Avatar</label>
            <input type="file" name="avatar" id="avatar">
            <br>
            <input type="submit" name="submit" value="Add User" />
        </form> 
    </body>
</html>
    
