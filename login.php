<?php
require_once 'assets/functions.php';
session_start();

if (isset($_POST['submit'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    if (login($username, $password)) {
        $user = getInfo($username);
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
        $_SESSION['email'] = $user['email'];
        $_SESSION['name'] = $user['name'];
        header('Location: index.php');
        exit();
    } else {
        echo "Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html lang="sv">

<head>
    <meta charset="UTF-8">
    <title>Kundlogin</title>
    <link rel="stylesheet" href="login.css">
</head>

<body>

    <div class="login-container">

        <h2>Kundlogin</h2>
        <p>Logga in på ditt konto</p>

        <form action="kundlogin.php" method="POST">

            <div class="form-group">
                <label for="email">E-post</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Lösenord</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="login-btn">Logga in</button>

        </form>

        <div class="login-link">
            <a href="registrera.php">Skapa konto</a>
        </div>

    </div>

</body>

</html>