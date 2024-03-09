<?php
    //Validation
    $error_fields = array();
    if(!(isset($_POST["name"]) && !empty($_POST["name"]))){
        $error_fields[] = "name";
    }
    if(!(isset($_POST["email"]) && filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))){
        $error_fields[] = "email";
    }
    if(!(isset($_POST["password"]) && strlen($_POST["password"] > 5))){
        $error_fields[] = "password";
    }
    if($error_fields){
        header("location: form.php?error_fields=".implode(",",$error_fields));
        exit;
    }
    // Open the connection
    $conn = mysqli_connect("localhost", "root", "J43_/brrI*8gJHPb", "blog");
    if (! $conn) {
        echo mysqli_connect_error();
        exit;
    }
    // Escape any sepcial characters to avoid SQL Injection
    $name = mysqli_escape_string($conn, trim($_POST['name']));
    $email = mysqli_escape_string($conn, $_POST['email']);
    $password = mysqli_escape_string($conn, $_POST['password']);
    $hashedpassword = password_hash($password,PASSWORD_DEFAULT);
    // Insert data
    $query = "INSERT INTO `users` (`name`, `email`, `password`) 
    VALUES ('".$name."','".$email."','".$hashedpassword."')";// '".$name."' because it's a way to ensure that the variable is properly included in the string as a discrete element
    if (mysqli_query($conn,$query)) {
        echo "Thank you!, you information has been saved";
        header("location: login.php");
    }else{
        echo $query;
        echo mysqli_error($conn);
    }
    // Close connection
    mysqli_close($conn);

    
?>   