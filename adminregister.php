<?php

session_start();

require_once 'db.php';
require_once 'functions.php';

// Kontrollera behörighet
if (!harRoll('Huvudadmin')) {
    die("Du har inte behörighet att registrera personal.");
}

$meddelande = "";

if (isset($_POST['Submit'])) {

    $namn = trim($_POST['namn'] ?? '');
    $pwd = $_POST['pwd'] ?? '';
    $grupp = $_POST['grupp'] ?? '';

    if (registreraPersonal($pdo, $namn, $pwd, $grupp)) {
        $meddelande = "Personalen har registrerats!";
    } else {
        $meddelande = "Registreringen misslyckades. Kontrollera uppgifterna.";
    }
}

?>

<!DOCTYPE html>
<html lang="sv">

<head>
    <meta charset="UTF-8">
    <title>Registrera personal</title>
</head>

<body>

    <h2>Registrera ny personal</h2>

    <p>
        <?= htmlspecialchars($meddelande) ?>
    </p>

    <form method="POST">

        <label for="namn">Namn:</label>
        <input type="text" name="namn" id="namn" required>

        <br><br>

        <label for="pwd">Lösenord:</label>
        <input type="password" name="pwd" id="pwd" required>

        <br><br>

        <label for="grupp">Välj roll:</label>

        <select name="grupp" id="grupp" required>

            <option value="Personal">Personal</option>
            <option value="Admin">Admin</option>
            <option value="Ekonom">Ekonom</option>

        </select>

        <br><br>

        <button type="submit" name="Submit">Registrera personal</button>

    </form>

</body>

</html>