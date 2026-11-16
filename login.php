<?php
require_once 'assets/functions.php';
session_start();

if (isset($_POST['submit'])){

    $username = $_POST['username'];
    $password = $_POST['password'];

    if(login($username, $password)){
        $user = getInfo($username);
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
        $_SESSION['email'] = $user['email'];
        $_SESSION['name'] = $user['name'];
        header('Location: index.php');
        exit();
    }
    else{
        echo "Invalid username or password";
    }   
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>D&H Strength</title>
</head>
<body>
    <form action="" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
        <br>
        <input type="submit" name="submit" value="Login">
    </form>
    <p><a href="signup.php">Skapa en konto</a></p>
    <p><a href="adminlogin.php">Administration</a></p>
</body>
</html>