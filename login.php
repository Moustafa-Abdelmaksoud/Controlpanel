<?php

    // Storing the signed in user data
    session_start();
    if($_SERVER['REQUEST_METHOD']=='POST')
    {
        // Connect to DB
        $conn = mysqli_connect("localhost", "root", "J43_/brrI*8gJHPb", "blog");
        if (! $conn) {
            echo mysqli_connect_error();
            exit;
        }
        $email = mysqli_escape_string($conn,$_POST['email']);
        $password = $_POST['password'];
        // Select 
        // $query = "SELECT * FROM `users` WHERE `email` = '".$email."' AND `password` = '".$password."' LIMIT 1";
        $query = "SELECT * FROM `users` WHERE `email` = '".$email."' ";
        $result = mysqli_query($conn,$query);
        if($row = mysqli_fetch_assoc($result)){
            $hashedpassword = $row['password'];
            if(!password_verify($password,$hashedpassword)){
                $error = 'Invalid email or password';
            }else{
            $_SESSION['id'] = $row['id'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['admin'] = $row['admin'];
            header("location: admin/users/list.php");
            exit;
            }
        }
        // Close the connection 
        mysqli_free_result($result);
        mysqli_close($conn);

    }
?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
</head>

<body>
    <form method="post">
        <?php if(isset($error)) echo "<span class='error'>$error</span>"; ?>
        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="<?= (isset($_POST['email'])) ? $_POST['email'] : '' ?>">
        <br>
        <label for="password">Password</label>
        <input type="password" name="password" id="password">
        <br>
        <input type="submit" name="submit" value="Login">
        <br>
        <b>Don't have account?<a href="form.php">SIGNUP</a></b>


    </form>
</body>

</html>