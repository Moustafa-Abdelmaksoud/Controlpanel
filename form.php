<?php
    //Check for errors
    $errors_arr = array();
    if(isset($_GET['error_fields'])){
        $errors_arr = explode(",", $_GET['error_fields']);
    }
?>




<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Register</title>
</head>
<body>
    <form method="post" action="process_db.php">
        <label for="name">Name</label>
        <input type="text" name="name" id="name"><?php if(in_array("name", $errors_arr)) 
        echo "<span class='error'>* Please enter your name</span>"; ?>
        <br>
        <label for="email">Email</label>
        <input type="email" name="email" id="email"><?php if(in_array("email", $errors_arr)) 
        echo "<span class='error'>* Please enter a valid email</span>"; ?>
        <br>
        <label for="password">Password</label>
        <input type="password" name="password" id="password"><?php if(in_array("password", $errors_arr)) 
        echo "<span class='error'>* Please enter a password not less than 6 characters</span>"; ?>
        <br>
        <label for="gender">Gender</label>
        <input type="radio" name="gender" value="male">Male
        <input type="radio" name="gender" value="female">Female
        <br>
        <input type="submit" name="submit" value="Register">
        <br>
        <b>have an account?<a href="login.php">LOGIN</a></b>
    </form>
</body>
</html>