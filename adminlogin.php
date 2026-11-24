<?php
session_start();

require_once 'assets/config/db.php';
require_once 'assets/functions.php';

$felmeddelande = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $namn = trim($_POST['namn'] ?? '');
    $pwd = $_POST['pwd'] ?? '';

    if (loggaInPersonal($pdo, $namn, $pwd)) {

        header('Location: admin.php');
        exit();

    } else {

        $felmeddelande = "Felaktigt användarnamn eller lösenord. Kontakta administratören om du inte har ett konto.";
    }
}
?>

<!DOCTYPE html>

<html lang="sv">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admininloggning</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <div class="login-container">

        <?php if (!empty($felmeddelande)): ?>

            <div class="error-message">
                <?php echo htmlspecialchars($felmeddelande); ?>
            </div>

        <?php endif; ?>

        <h2>Adminlogin</h2>
        <p>Logga in som administratör</p>

        <form action="adminlogin.php" method="POST">

            <div class="form-group">
                <label for="namn">Användarnamn</label>
                <input type="text" id="namn" name="namn" required>
            </div>

            <div class="form-group">
                <label for="pwd">Lösenord</label>
                <input type="password" id="pwd" name="pwd" required>
            </div>

            <button type="submit" class="login-btn">Logga in</button>

        </form>

        <div class="login-link">
            <a href="login.php">Kund inloggning</a>
        </div>

    </div>

</body>

</html>