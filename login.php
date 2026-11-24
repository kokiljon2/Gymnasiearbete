<?php
session_start();

require_once 'assets/config/db.php';
require_once 'assets/functions.php';

$felmeddelande = "";

if (isset($_POST['submit'])) {

    $email = trim($_POST['email']);
    $pwd = $_POST['pwd'];

    if (loggaInKund($pdo, $email, $pwd)) {

        header('Location: index.php');
        exit();

    } else {

        $felmeddelande = "Felaktig e-post eller lösenord. Kontrollera dina uppgifter eller skapa ett konto.";
    }
}
?>

<!DOCTYPE html>
<html lang="sv">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kundlogin</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <div class="login-container">

        <h2>Kundlogin</h2>
        <p>Logga in på ditt konto</p>

        <?php if (!empty($felmeddelande)): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($felmeddelande); ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">

            <div class="form-group">
                <label for="email">E-post</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="pwd">Lösenord</label>
                <input type="password" id="pwd" name="pwd" required>
            </div>

            <button type="submit" name="submit" class="login-btn">
                Logga in
            </button>

        </form>

        <div class="login-link">
            <a href="register.php">Skapa konto</a>
            <br>
            <a href="adminlogin.php">Administration</a>
        </div>

    </div>

</body>

</html>