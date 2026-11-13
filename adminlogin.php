<?php
session_start();

require_once 'assets/config/db.php';
require_once 'assets/functions.php';

if (isset($_POST['submit'])) {

    $namn = $_POST['namn'];
    $pwd = $_POST['pwd'];

    if (loggaInPersonal($pdo, $namn, $pwd)) {

        header('Location: admin.php');
        exit();

    } else {
        echo "Felaktigt användarnamn eller lösenord. Kontakta administratören om du inte har ett konto.";
    }
}
?>

<!DOCTYPE html>

<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personalinloggning</title>
</head>

<body>

<h1>Logga in</h1>

<form action="" method="post">

    <label for="namn">Användarnamn:</label>
    <input type="text" id="namn" name="namn" required>

    <br>

    <label for="pwd">Lösenord:</label>
    <input type="password" id="pwd" name="pwd" required>

    <br>

    <input type="submit" name="submit" value="Logga in">

</form>

</body>
</html>